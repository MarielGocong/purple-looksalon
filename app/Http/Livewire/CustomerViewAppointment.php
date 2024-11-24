<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use App\Jobs\SendReschedAppointmentMailJob;
use App\Notifications\ReschuledAppointmentNotification;

class CustomerViewAppointment extends Component
{
    public $search;
    public $selectFilter = 'upcoming';
    public $confirmingAppointmentCancellation = false;
    public $confirmingAppointmentEdit = false;
    public $cancellationReason;
    public $appointmentIdToCancel;
    public $editingAppointment = false;
    public $selectedAppointment;
    public $newDate;
    public $newTimeSlot;
    public $appointment;
    public $availableTimeSlots = []; // Declare the property here
    private $timeNow;

    protected $rules = [
        "appointment.service_id" => "required|integer",
        "appointment.date" => "required|date",
        "appointment.time" => "required|integer",
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->timeNow = Carbon::now();
    }


    public function render()
    {
        $query = Appointment::with( 'user', 'service')
            ->where('user_id', auth()->user()->id);

        if ($this->search) {
            $query->where(function ($subQuery) {
                $subQuery
                    ->where('date', 'like', '%' . $this->search . '%')
                    ->orWhere('appointment_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('service', function ($serviceQuery) {
                        $serviceQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->selectFilter === 'previous') {
            $query->whereDate('date', '<', Carbon::today())->where('status', 1);
        } elseif ($this->selectFilter === 'upcoming') {
            $query->whereDate('date', '>=', Carbon::today())->where('status', 1);
        } elseif ($this->selectFilter === 'cancelled') {
            $query->where('status', 0);
        }

        $appointments = $query->orderBy('date')->orderBy('employee_id')->paginate(10);

        return view('livewire.customer-view-appointment', [
            'appointments' => $appointments,
            'availableTimeSlots' => $this->availableTimeSlots,
        ]);
    }






    public function updateAppointment()
    {
        // Validate new date and timeslot
        $this->validate([
            'newDate' => 'required|date|after:today',
            'newTimeSlot' => 'required|exists:time_slots,id',
        ]);

        // Update the appointment with the new date and timeslot
        $this->selectedAppointment->update([
            'date' => $this->newDate,
            'time_slot_id' => $this->newTimeSlot,
        ]);

        // Notify the customer about the rescheduled appointment
        if ($this->selectedAppointment->role) {
            $this->selectedAppointment->role->notify(new SendReschedAppointmentMailJob($this->selectedAppointment));
        }

        // Notify admins and employees about the rescheduled appointment
        $admins = User::whereHas('role', function($query) {
            $query->where('name', 'Admin')->orWhere('name', 'Employee');
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new ReschuledAppointmentNotification($this->selectedAppointment));
        }

        // Display success message
        session()->flash('message', 'Appointment Rescheduled Successfully!');

        // Close modal and reset fields
        $this->reset(['editingAppointment', 'newDate', 'newTimeSlot']);
    }

    public function setAppointmentIdToCancel($id)
    {
        $this->appointmentIdToCancel = $id;
        $this->confirmingAppointmentCancellation = true;
    }
    public function cancelAppointment()
    {
        $this->validate([
            'cancellationReason' => 'required|string|max:255',
        ]);

        // Retrieve the appointment using the ID
        $appointment = Appointment::find($this->appointmentIdToCancel);

        // Check if appointment exists and belongs to the authenticated user
        if (!$appointment || auth()->user()->id !== $appointment->user_id) {
            session()->flash('error', 'Unauthorized or Appointment not found.');
            return;
        }

        // Update appointment status and cancellation reason
        $appointment->status = 0; // Assuming 0 means canceled
        $appointment->cancellation_reason = $this->cancellationReason;

        if ($appointment->save()) {
            // Successfully canceled, reset component state
            $this->reset(['confirmingAppointmentCancellation', 'cancellationReason', 'appointmentIdToCancel']);
            session()->flash('message', 'Appointment canceled successfully with reason: ' . $this->cancellationReason);
        } else {
            session()->flash('error', 'An error occurred while canceling the appointment.');
        }
    }
}
