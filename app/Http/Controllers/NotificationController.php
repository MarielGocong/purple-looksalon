<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function redirectToAppointment($id)
    {
        $notification = auth()->user()->unreadNotifications->find($id);
        if ($notification) {
            $notification->markAsRead();
            $appointmentId = $notification->data['appointment_id'];
            return redirect()->route('manageappointments', ['appointment' => $appointmentId]);
        }

        return back()->with('error', 'Notification not found.');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->unreadNotifications->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }
}
