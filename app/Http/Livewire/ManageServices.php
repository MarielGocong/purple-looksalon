<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Service;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ManageServices extends Component

{

    use withPagination;
    use withFileUploads;

    public $confirmingServiceDeletion = false;
    public $confirmingServiceAdd = false;
    public $confirmingServiceEdit = false;

    public $search;
    public $statusFilter = '';
    public $jobCategoryFilter = '';
    public $categoryFilter = ''; // Default: show all categories


    public function archiveService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->status = 0; // Archived
        $service->save();

        session()->flash('message', 'Service archived successfully.');
    }

    public function unarchiveService($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->status = 1; // Active
        $service->save();

        session()->flash('message', 'Service unarchived successfully.');
    }


    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public $newService, $name, $description, $price, $is_hidden, $image = false;

    protected function rules()
{
    $rules = [
        'newService.name' => 'required|string|min:1|max:255',
        'newService.slug' => 'unique:services,slug,' . ($this->newService['id'] ?? ''),
        'newService.description' => 'required|string|min:1|max:255',
        'newService.price' => 'required|numeric|min:0',
        'newService.is_hidden' => 'boolean',
        'newService.category_id' => 'required|integer|min:1|exists:categories,id',
        'newService.allergens' => 'nullable|string|min:1|max:255',
        'newService.cautions' => 'nullable|string|min:1|max:255',
        'newService.benefits' => 'nullable|string|min:1|max:255',
        'newService.aftercare_tips' => 'nullable|string|min:1|max:255',
        'newService.job_category_id' => 'nullable|exists:job_categories,id',
        'newService.notes' => 'nullable|string|min:1|max:255',

    ];
    // check if image is an instance of UploadedFile
    if ($this->image instanceof \Illuminate\Http\UploadedFile) {

        $rules['image'] = 'required|image|mimes:jpg,jpeg,png,svg,gif,webp|max:204800';
    } else {
        $rules['image'] = 'required|string|min:1|max:255';
    }
    return $rules;
}


public $employeeIds = [];

public function render()
{
    $services = Service::when($this->search, function ($query) {
        $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('description', 'like', '%' . $this->search . '%')
            ->orWhereHas('category', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
    })
    ->when($this->statusFilter == 'active', function ($query) {
        $query->where('status', 1); // Filter by active services
    })
    ->when($this->statusFilter == 'archived', function ($query) {
        $query->where('status', 0); // Filter by archived services
    })
    ->when($this->categoryFilter, function ($query) {
        $query->where('category_id', $this->categoryFilter); // Filter by category
    })
    ->when($this->jobCategoryFilter, function ($query) {
        $query->where('job_category_id', $this->jobCategoryFilter);
    })
    ->orderBy('price', 'asc') // Order by price
    ->paginate(10); // Paginate results

    $categories = \App\Models\Category::all();
    $jobCategories = \App\Models\JobCategory::all();
    $employees = \App\Models\Employee::all(); // Fetch all employees

    return view('livewire.manage-services', compact('services', 'categories', 'jobCategories', 'employees'));
}



    public function confirmServiceDeletion($id)
    {
        $this->confirmingServiceDeletion = $id;


    }

    public function deleteService(Service $service)
    {
        if ($service->appointments()->exists()) {
            session()->flash('error', 'Cannot delete this service because it has associated appointments.');
            return;
        }

        $service->delete();

        session()->flash('message', 'Service successfully deleted.');
        $this->confirmingServiceDeletion = false;

    }


    public function confirmServiceAdd() {

        $this->reset(['newService']);
        $this->reset(['image']);
        $this->confirmingServiceAdd = true;


    }

    public function confirmServiceEdit( Service $newService ) {
        $this->newService = $newService;

        $this->image = $newService->image;

        $this->confirmingServiceAdd = true;
    }



    public function saveService()
{
    try {
        $this->validateOnly('newService.name');
        $this->validateOnly('newService.description');
        $this->validateOnly('newService.price');
        $this->validateOnly('newService.is_hidden');
        $this->validateOnly('newService.category_id');
        $this->validateOnly('newService.job_category_id');
        $this->validateOnly('newService.allergens');
        $this->validateOnly('newService.cautions');
        $this->validateOnly('newService.benefits');
        $this->validateOnly('newService.aftercare_tips');
        $this->validateOnly('newService.notes');

        if (isset($this->newService['id'])) {
            // If a new image is uploaded, delete the old one
            if ($this->image instanceof \Illuminate\Http\UploadedFile) {
                $this->validateOnly('image');
                $originalImage = Service::find($this->newService['id'])->image;
                $originalImage = str_replace('storage', 'public', $originalImage);
                Storage::delete($originalImage);

                // Save the image and get the path
                $this->image = $this->image->store('images', 'public');
            }

            // Save the service
            $this->newService['image'] = $this->image;

            if ($this->newService->isDirty('name')) {
                $this->newService->slug = \Str::slug($this->newService->name);
                $this->validate(['newService.slug' => 'unique:services,slug,' . $this->newService->id]);
            }

            $this->newService->save();

            // Sync the employees with the service
            $this->newService->employees()->sync($this->employeeIds);

        } else {
            // Create a new slug and save the service
            $this->newService['slug'] = \Str::slug($this->newService['name']);
            $service = Service::create($this->newService);

            // Sync the employees with the service
            $service->employees()->sync($this->employeeIds);
        }

        session()->flash('message', 'Service successfully saved.');
        $this->confirmingServiceAdd = false;

    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->errorInfo[1] == 1062) {
            session()->flash('error', 'The service name is already taken. Please choose a different name.');
        } else {
            session()->flash('error', 'An error occurred while saving the service.');
        }
    }
}

}



