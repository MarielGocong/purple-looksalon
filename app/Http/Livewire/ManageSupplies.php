<?php

    namespace App\Http\Livewire;

    use App\Models\Category;
    use App\Models\OnlineSupplier;
    use App\Models\Supply;
    use Livewire\Component;
    use Livewire\WithFileUploads;
    use Livewire\WithPagination;
    use Barryvdh\DomPDF\Facade\Pdf;
    use Storage;

    class ManageSupplies extends Component
    {
        use WithFileUploads;
        use WithPagination;

        public $supply;
        public $search;
        public $confirmingSuppliesDeletion = false;
        public $confirmingSuppliesView = false;
        public $showAddSuppliesModal = false;
        public $showEditSuppliesModal = false;
        public $selectedSupplyId;
        public $newSupplies = [];
        public $image;
        public $categories;
        public $online_suppliers;
        public $categoryFilter = null;
        public $selectFilter = 'all';  // Default to 'all' filter
        private $userId;
        public $paginate = 10;

        public function resetFilters()
        {
            $this->categoryFilter = null;
            $this->search = '';
            $this->resetPage(); // Reset pagination to the first page
        }

        public function mount($userId = null, $selectFilter = 'all')
        {
            if (auth()->user()->role->name == 'Customer') {
                $this->userId = auth()->user()->id;
            } elseif (in_array(auth()->user()->role->name, ['Employee', 'Admin'])) {
                $this->userId = $userId;
            }

            // Set the select filter based on the passed parameter or default value
            $this->selectFilter = $selectFilter ?: 'all';

            // Fetch categories and online suppliers
            $this->categories = Category::all();
            $this->online_suppliers = OnlineSupplier::all();

            $this->resetNewSupplies();
        }

        public function resetNewSupplies()
        {
            $this->newSupplies = [
                'name' => '',
                'description' => '',
                'quantity' => '',
                'category_id' => '',
                'color_code' => '',
                'color_shade' => '',
                'size' => '',
                'expiration_date' => '',
                'online_supplier_id' => '',
            ];
        }

        public function showAddSuppliesModal()
        {
            $this->resetNewSupplies(); // Clear form fields
            $this->confirmingSuppliesView = false;  // Close view modal
            $this->showAddSuppliesModal = true;
        }

        public function showEditSuppliesModal($supplyId)
        {
            $this->newSupplies = Supply::findOrFail($supplyId)->toArray();
            $this->selectedSupplyId = $supplyId; // Track editing
            $this->confirmingSuppliesView = false;  // Close view modal
            $this->showEditSuppliesModal = true;
        }

        public function viewSupplies($supplyId)
        {
            // Close other modals first
            $this->showAddSuppliesModal = false;
            $this->showEditSuppliesModal = false;

            $this->supply = Supply::find($supplyId);

            if (!$this->supply) {
                $this->dispatchBrowserEvent('notification', ['message' => 'Supply not found']);
            }

            // Show the view modal
            $this->confirmingSuppliesView = true;
        }

        public function closeModals()
        {
            // Close all modals when the close button is clicked
            $this->showAddSuppliesModal = false;
            $this->showEditSuppliesModal = false;
            $this->confirmingSuppliesView = false;
            $this->resetNewSupplies(); // Reset data when closing modals
        }

        public function confirmSuppliesDeletion($supplyId)
        {
            $this->confirmingSuppliesDeletion = $supplyId;
        }

        public function deleteSupplies($supplyId)
        {
            Supply::find($supplyId)->delete();
            $this->confirmingSuppliesDeletion = false;
            session()->flash('message', 'Supply Deleted Successfully.');
        }

        public function saveSupplies()
        {
        $this->validate([
            'newSupplies.name' => 'required|string|max:255',
            'newSupplies.description' => 'required|string|max:255',
            'newSupplies.quantity' => 'required|integer|min:0',
            'newSupplies.category_id' => 'required|exists:categories,id',
            'newSupplies.online_supplier_id' => 'nullable|exists:online_suppliers,id',
            'newSupplies.color_code' => 'nullable|string|unique:supplies,color_code,' . $this->selectedSupplyId,
            'newSupplies.color_shade' => 'required|string|max:255',
            'newSupplies.size' => 'required|string|max:255',
            'newSupplies.expiration_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($this->image) {
            $path = $this->image->store('images', 'public');
            $this->newSupplies['image'] = $path;
        }

        // Save or update the supply record
        Supply::updateOrCreate(
            ['id' => $this->selectedSupplyId], // Ensure ID is passed for update
            $this->newSupplies
        );

        session()->flash('message', $this->selectedSupplyId ? 'Supply Updated Successfully!' : 'Supply Added Successfully');
        $this->closeModals();
        }

        public function render()
        {
            $query = Supply::with(['category', 'online_supplier'])
                ->when($this->categoryFilter, function ($query) {
                    $query->where('category_id', $this->categoryFilter);
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('description', 'like', '%' . $this->search . '%');
                    });
                });

            // Apply filters based on the selected filter
            if ($this->selectFilter === 'expired') {
                $query->whereDate('expiration_date', '<', now());
            } elseif ($this->selectFilter === 'low_quantity') {
                $query->where('quantity', '<', 10);  // Low quantity threshold, adjust as needed
            }

            // Get paginated supplies
            $supplies = $query->paginate($this->paginate ?: 10);

            return view('livewire.manage-supplies', [
                'supplies' => $supplies,
                'categories' => $this->categories,
                'online_suppliers' => $this->online_suppliers,
            ]);
        }

        public function exportToPdf()
        {
            $supplies = Supply::with(['category'])
                ->when($this->categoryFilter, function ($query) {
                    // Add filtering by category
                    $query->where('category_id', $this->categoryFilter);
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('brand_name', 'like', '%' . $this->search . '%');
                    });
                })
                ->get();

            $pdf = Pdf::loadView('supplies-report', ['supplies' => $supplies]);

            $pdfPath = 'pdf/supplies-report-' . time() . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdf->output());

            $this->dispatchBrowserEvent('downloadFile', ['url' => Storage::url($pdfPath)]);
        }
    }
