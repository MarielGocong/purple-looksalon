<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class AddingServiceToCart extends Component
{
    public $service;
    public $selectedEmployee;
    public $selectedTime;
    public $selectedDate;
    public $employees;
    public $errorMessage;


    public function mount(Service $service)
    {
        $this->service = $service;

        // Fetch employees assigned to this service
        $this->employees = $this->service->employees;

        // Check if there are no employees assigned to the service
        if ($this->employees->isEmpty()) {
            // Set the error message to display if no employees are assigned
            $this->errorMessage = "No Staff Assigned. Please select a different service.";
        } else {
            // Reset the error message if there are employees
            $this->errorMessage = null;
        }
    }

    public function render()
    {
        return view('livewire.adding-service-to-cart');
    }

    // When date or time slot is selected, check availability for employees
    public function updatedSelectedDate($selectedDate)
    {
        // Refresh employee availability after date is selected
        $this->displayUnavailableEmployees();
    }

    public function updatedSelectedTime($selectedTime)
    {
        // Refresh employee availability after time slot is selected
        $this->displayUnavailableEmployees();
    }

    // This method will check employee availability based on selected date and time slot
    private function displayUnavailableEmployees()
{
    if (!$this->selectedDate) {
        return;
    }

    $selectedDayOfWeek = Carbon::parse($this->selectedDate)->format('l'); // Get the day name

    // Fetch unavailable employees due to existing appointments
    $unavailableDueToAppointments = Appointment::where('date', $this->selectedDate)
        ->where('time', $this->selectedTime)
        ->pluck('employee_id')
        ->toArray();

    foreach ($this->employees as $employee) {
        // Check if the employee works on the selected day
        $isWorkingDay = in_array($selectedDayOfWeek, $employee->working_days ?? []);

        // Check if the employee is unavailable due to an appointment
        $isUnavailableByAppointment = in_array($employee->id, $unavailableDueToAppointments);

        // Determine availability: the employee must be working and not booked
        $isAvailable = $isWorkingDay && !$isUnavailableByAppointment;

        $employee->available = $isAvailable;

        // Reset selected employee if they become unavailable
        if ($this->selectedEmployee == $employee->id && !$isAvailable) {
            $this->selectedEmployee = null;
        }
    }
}




    // Add the service to the cart
    public function addToCart()
{
    if ($this->service->is_hidden) {
        return redirect()->back();
    }

    // Check if the user is logged in
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Validate that the selected employee is assigned to this service
    $employee = Employee::find($this->selectedEmployee);

    if (!$employee || !$this->service->employees->contains($employee)) {
        session()->flash('error', 'The selected employee is not assigned to this service.');
        return redirect()->route('service.book', ['service' => $this->service->id]);
    }

    // Check if the user has a cart
    $cart = auth()->user()->cart?->where('is_paid', false)->first();

    // If no cart exists, create one
    if (!$cart) {
        $cart = auth()->user()->cart()->create();
    }

    // Check if the user has a cart item with the same time slot and employee
    $cartItem = $cart->services()
        ->where('date', $this->selectedDate)
        ->where('time', $this->selectedTime)
        ->where('employee_id', $this->selectedEmployee)
        ->first();

    // If the cart already contains the service with the same time, return an error
    if ($cartItem) {
        session()->flash('error', 'You already have a service in your cart with the same time slot and employee.');
        return redirect()->route('cart');
    }

    // Check if there is already an appointment with the same time slot and employee
    $appointment = Appointment::where('date', $this->selectedDate)
        ->where('time', $this->selectedTime)
        ->where('employee_id', $this->selectedEmployee)
        ->first();

    // If there is an appointment, return an error
    if ($appointment) {
        session()->flash('error', 'There is already an appointment with the same time slot and employee.');
        return redirect()->route('cart');
    }

    // Add the service to the cart if everything is available
    $cart->services()->attach($this->service->id, [
        'time' => $this->selectedTime,
        'date' => $this->selectedDate,
        'employee_id' => $this->selectedEmployee,
        'first_name' => $employee->first_name,
        'price' => $this->service->price,
    ]);

    // Update the cart's total price
    $cart->total = $cart->services()->sum(DB::raw('cart_service.price'));
    $cart->save();

    return redirect()->route('cart');
}
}
