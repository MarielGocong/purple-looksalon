<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\User;
use App\Notifications\EquipmentNotification;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ManageEquipments extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $equipment;

    public $search;
    public $confirmingEquipmentDeletion = false;
    public $confirmingEquipmentView = false;
    public $showAddEquipmentModal = false;
    public $showEditEquipmentModal = false;
    protected $notifiedEquipments = [];
    public $selectedEquipmentId;
    public $newEquipment = [];
    public $image;
    public $categories;
    public $categoryFilter = null;
    public $employees;
    public $paginate = 10;

    public function resetFilters()
    {
        $this->categoryFilter = null;
        $this->search = '';
        $this->resetPage(); // Reset pagination to the first page
    }

    public function mount()
    {
        $this->categories = Category::all();
        $this->employees = Employee::all();

        $this->resetNewEquipment();
    }

    public function resetNewEquipment()
    {
        $this->newEquipment = [
            'name' => '',
            'category_id' => '',
            'quantity' => '',
            'last_maintenance' => '',
            'next_maintenance' => '',
            'purchased_date' => '',
            'employee_id' => '',
            'brand_name' => '',
        ];
    }

    public function showAddEquipmentModal()
    {
        $this->resetNewEquipment(); // Clear form fields
        $this->showAddEquipmentModal = true;
    }


    public function showEditEquipmentModal($equipmentId)
    {
        $this->newEquipment = Equipment::findOrFail($equipmentId)->toArray();
        $this->selectedEquipmentId = $equipmentId; // Track editing
        $this->showEditEquipmentModal = true;
    }

    public function closeModals()
    {
        $this->showAddEquipmentModal = false;
        $this->showEditEquipmentModal = false;
        $this->resetNewEquipment(); // Reset data
    }



    public function confirmEquipmentDeletion($equipmentId)
    {
        $this->confirmingEquipmentDeletion = $equipmentId;
    }

    public function deleteEquipment($equipmentId)
    {
        Equipment::find($equipmentId)->delete();
        $this->confirmingEquipmentDeletion = false;
        session()->flash('message', 'Equipment Deleted Successfully');
    }

    public function saveEquipment()
    {
    $this->validate([
        'newEquipment.name' => 'required|string|max:255',
        'newEquipment.brand_name' => 'required|string|max:255',
        'newEquipment.category_id' => 'required|exists:categories,id',
        'newEquipment.employee_id' => 'required|integer|exists:employees,id',
        'newEquipment.quantity' => 'required|integer|min:0',
        'newEquipment.last_maintenance' => 'required|date',
        'newEquipment.next_maintenance' => 'required|date|after:last_maintenance',
        'newEquipment.purchased_date' => 'required|date',
        'image' => 'nullable|image|max:2048',
    ]);

    if ($this->image) {
        $path = $this->image->store('images', 'public');
        $this->newEquipment['image'] = $path;
    }

    Equipment::updateOrCreate(
        ['id' => $this->selectedEquipmentId],
        $this->newEquipment
    );

    session()->flash('message', $this->selectedEquipmentId ? 'Equipment updated successfully!' : 'Equipment added successfully!');
    $this->closeModals();
    }



    public function viewEquipment($equipmentId)
    {
        $this->equipment = Equipment::find($equipmentId);

        if(!$this->equipment){
            $this->dispatchBrowserEvent('notification',['message' => 'Equipment not found']);
        }

        $this->confirmingEquipmentView = true;
    }

    public function render()
    {
        $query = Equipment::with(['employee', 'category'])
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('brand_name', 'like', '%' . $this->search . '%');
                });
            });

        $equipments = $query->paginate($this->paginate);

        foreach ($equipments as $equipment) {
            $maintenanceDate = Carbon::parse($equipment->next_maintenance);
            $lowQuantity = $equipment->quantity < 5;

            if ($lowQuantity && !in_array($equipment->id, $this->notifiedEquipments)) {
                $this->notifyAdminAndEmployee($equipment, 'low_quantity');
                $this->notifiedEquipments[] = $equipment->id;
            }

            if (($maintenanceDate->isToday() || $maintenanceDate->isTomorrow()) && !in_array($equipment->id . '_maintenance', $this->notifiedEquipments)) {
                $this->notifyAdminAndEmployee($equipment, 'maintenance_due');
                $this->notifiedEquipments[] = $equipment->id . '_maintenance';
            }
        }

        return view('livewire.manage-equipments', [
            'equipments' => $equipments,
            'categories' => $this->categories,
        ]);
    }

    public function notifyAdminAndEmployee($equipment, $type)
{
    // Retrieve admins with 'admin' role
    $admins = User::whereHas('role', function ($query) {
        $query->where('name', 'admin'); // Adjust if role is identified by name or role_id
    })->get();

    // Check if there are any admins
    if ($admins->isEmpty()) {
        \Log::warning('No admins found for notification.');
    }

    // Find the specific employee assigned to this equipment
    $employee = null;
    if ($equipment->employee_id) {
        $employee = User::find($equipment->employee_id);
    }

    // Notify admins
    foreach ($admins as $admin) {
        $admin->notify(new EquipmentNotification($equipment, $type));
    }

    // Notify employee if exists
    if ($employee) {
        $employee->notify(new EquipmentNotification($equipment, $type));
    } else {
        \Log::warning('No employee assigned to the equipment with ID: ' . $equipment->id);
    }
}

    public function exportToPdf()
    {
        $equipments = Equipment::with(['employee', 'category'])
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('brand_name', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        if ($equipments->isEmpty()) {
            session()->flash('message', 'No equipment found for export.');
            return;
        }

        $pdf = Pdf::loadView('equipment-report', ['equipments' => $equipments]);

        $pdfPath = 'pdf/equipment-report-' . time() . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $this->dispatchBrowserEvent('downloadFile', ['url' => Storage::url($pdfPath)]);
    }
}
