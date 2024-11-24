<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Notifications\ReschuledAppointmentNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;


class SendReschedAppointmentMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $customer,
        public Appointment $appointment
    )
    {
    }

    public function handle(): void
    {

        $notification = new ReschuledAppointmentNotification(
            $this->appointment
        );

        Notification::send($this->customer, $notification);
    }
}
