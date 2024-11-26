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

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->employees = Employee::where('status', true)->get(); // All available employees
        $this->employees->map(function ($employee) {
            // Initialize all employees as available initially
            $employee->available = true;
        });
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
        // Step 1: Collect unavailable employees based on existing appointments
        $unavailableEmployees = Appointment::where('date', $this->selectedDate)
            ->where('time', $this->selectedTime)
            ->pluck('employee_id')
            ->toArray();

        // Step 2: Check the user's cart for services with the same date and time, and the same employee
        $cart = auth()->user()?->cart?->where('is_paid', false)->first();

        if ($cart) {
            // Get the selected date and time slot for comparison
            $selectedDate = $this->selectedDate;
            $selectedTime = $this->selectedTime;

            // Check if the selected date and time slot already exist in the cart
            $inCartSameEmployee = $cart->services()
                ->where('date', $selectedDate)
                ->where('time', $selectedTime)
                ->pluck('employee_id')  // We need to check which employees are already assigned in the cart
                ->toArray();

            // Merge unavailable employees from appointments and cart
            $unavailableEmployees = array_merge($unavailableEmployees, $inCartSameEmployee);
        }

        // Step 3: Mark employees as available or unavailable based on the merged list
        foreach ($this->employees as $employee) {
            // If employee is not in the unavailable list, mark as available
            if (!in_array($employee->id, $unavailableEmployees)) {
                $employee->available = true;
            } else {
                $employee->available = false;
            }

            // If the user has selected a specific employee and that employee is unavailable, reset selection
            if ($this->selectedEmployee != null && in_array($this->selectedEmployee, $unavailableEmployees)) {
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
        $employee = Employee::find($this->selectedEmployee);

        if (!$employee) {
            session()->flash('error', 'The selected time slot is not available for the chosen employee.');
            return redirect()->route('cart');
        }

        // Add the service to the cart with necessary details
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
