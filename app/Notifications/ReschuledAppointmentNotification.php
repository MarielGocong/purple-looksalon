<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReschuledAppointmentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Appointment $appointment
    )
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Rescheduled - Purple Look Hair Salon and Spa 📅')
            ->from('noreply@purplelooksalonandspa.com')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your appointment for ' . $this->appointment->service->name . ' has been successfully rescheduled.')
            ->line('Here are the updated details of your appointment:')
            ->line('🧾 Appointment Code: ' . $this->appointment->appointment_code)
            ->line('📅 New Date: ' . $this->appointment->date)
            ->line('⏰ New Time: ' . $this->appointment->start_time . ' - ' . $this->appointment->end_time)
            ->line('📞 Staff Assigned: ' . $this->appointment->employee->first_name)
            ->action('View Your Appointment', route('customerview') . '?search=' . $this->appointment->appointment_code)
            ->line('Thank you for choosing Purple Look Hair Salon and Spa! We look forward to seeing you soon.');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
