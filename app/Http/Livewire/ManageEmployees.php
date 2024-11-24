<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageEmployees extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $employee;
    public $search;
    public $confirmingEmployeeView = false;
    public $showAddEmployeeModal = false; // For Add Modal
    public $showEditEmployeeModal = false; // For Edit Modal
    public $selectedEmployeeId = null; // Tracks the employee being edited
    public $workingDays = [];
    public $statusFilter = ''; // For filtering active and archived employees
    public $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    public $newEmployee = [];
    public $image;

    public $birthday;
    public $age;

    public function updatedNewEmployeeBirthday($value)
    {
        $this->newEmployee['age'] = $this->calculateAge($value); // Store age in newEmployee
    }

    public function calculateAge($birthday)
    {
        return Carbon::parse($birthday)->age;
    }


    public function showAddEmployeeModal()
    {
        $this->resetNewEmployee(); // Clear form fields
        $this->showAddEmployeeModal = true;
    }

    public function showEditEmployeeModal($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $this->newEmployee = $employee->toArray();
        $this->workingDays = $employee->working_days ?? [];
        $this->selectedEmployeeId = $employee->id;
        $this->age = Carbon::parse($this->newEmployee['birthday'])->age;
        $this->newEmployee['job_category_id'] = $employee->job_category_id;
        $this->showEditEmployeeModal = true;
    }


    public function archiveEmployee($employeeId)
{
    $employee = Employee::findOrFail($employeeId);
    $employee->status = 0;  // Set to archived
    $employee->save();

    session()->flash('message', 'Employee archived successfully!');
}

// Unarchive employee
public function unarchiveEmployee($employeeId)
{
    $employee = Employee::findOrFail($employeeId);
    $employee->status = 1;  // Set to active
    $employee->save();

    session()->flash('message', 'Employee unarchived successfully!');
}

    public function resetNewEmployee()
    {
        $this->newEmployee = [
            'first_name' => '',
            'last_name' => '',
            'age' => null,
            'phone_number' => '',
            'job_category_id' => '',
            'birthday' => null,
            'date_started' => '',
            'address' => '',
            'email' => '',
            'is_hidden' => false,
        ];

        $this->image = null;
        $this->selectedEmployeeId = null;
    }





    public function saveEmployee()
{

    $this->validate([
        'newEmployee.first_name' => 'required|string|max:255',
        'newEmployee.last_name' => 'required|string|max:255',
        'newEmployee.email' => 'required|email|unique:employees,email,' . $this->selectedEmployeeId,
        'newEmployee.phone_number' => 'required|string|max:15',
        'newEmployee.job_category_id' => 'nullable|exists:job_categories,id',
        'newEmployee.birthday' => 'required|date',
        'newEmployee.date_started' => 'required|date',
        'newEmployee.address' => 'nullable|string',
        'newEmployee.is_hidden' => 'boolean',
        'image' => 'nullable|image|max:5120',
        'workingDays' => 'array', // Validate working days
        'workingDays.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
    ]);

    if ($this->image) {
        $path = $this->image->store('images', 'public');
        $this->newEmployee['image'] = $path;
    }

    $this->newEmployee['working_days'] = $this->workingDays; // Add working days to newEmployee

    if ($this->selectedEmployeeId) {
        Employee::findOrFail($this->selectedEmployeeId)->update($this->newEmployee);
    } else {
        Employee::create($this->newEmployee);
    }

    session()->flash('message', $this->selectedEmployeeId ? 'Employee updated successfully!' : 'Employee added successfully!');
    $this->reset('newEmployee', 'image', 'workingDays');
    $this->showAddEmployeeModal = false;
    $this->showEditEmployeeModal = false;
}

    public function viewEmployee($employeeId)
    {
        $this->employee = Employee::find($employeeId);
        if (!$this->employee) {
            $this->dispatchBrowserEvent('notification', ['message' => 'Employee not found']);
        }
        $this->confirmingEmployeeView = true;
    }

    public function render()
{
    $job_categories = \App\Models\JobCategory::all();

    $employees = Employee::when($this->statusFilter == 'active', function($query) {
                    return $query->where('status', 1);
                })
                ->when($this->statusFilter == 'archived', function($query) {
                    return $query->where('status', 0);
                })
                ->orderBy('created_at')
                ->paginate(10);

    return view('livewire.manage-employees', [
        'employees' => $employees,
        'job_categories' => $job_categories,
    ]);
}
}
