<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment
    )
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject( 'Appointment Confirmation - Purple Look Hair Salon and Spa 🎉' . $this->appointment->service->name)
            ->from('noreply@purplelooksalonandspa.com')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment for ' . $this->appointment->service->name . ' has been confirmed!')
            ->line('Your payment of PHP ' . $this->appointment->total . ' has been processed.')
            ->line('🧾 Appointment Code: ' . $this->appointment->appointment_code)
            ->line('📅 Date: ' . $this->appointment->date)
            ->line('⏰ Time: ' . $this->appointment->start_time . ' - ' . $this->appointment->end_time)
            ->line('📞 Staff Assigned: ' . $this->appointment->employee->first_name)

            ->action(
                'View Your Appointment',
                route('customerview', ['customer' => $this->appointment->user->id]) . '?search=' . $this->appointment->appointment_code
            )
            ->line('Thank you for using Purple Look Hair Salon and Spa! We hope to see you again soon.');

    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
