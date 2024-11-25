<?php

namespace App\Http\Controllers;

use App\Jobs\SendAppointmentConfirmationMailJob;
use App\Notifications\NewAppointmentNotification;
use App\Models\Role;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index()
    {
        // Get the cart of the user that is not paid
        $cart = auth()->user()->cart()->where('is_paid', false)->first();
        return view('web.cart', compact('cart'));
    }
    public function removeItem($cart_service_id)
    {
        // Get the cart of the user that is not paid
        $cart = auth()->user()->cart()->where('is_paid', false)->first();

        // If the cart is not found, redirect back
        if (!$cart) {
            return redirect()->back();
        }

        // Get the cart_service with id = cart_service_id
        $cart_service = DB::table('cart_service')->where('id', $cart_service_id)->where('cart_id', $cart->id)->first();

        // If the cart service is not found, redirect back
        if (!$cart_service) {
            return redirect()->back();
        }

        // Delete the cart service
        DB::table('cart_service')->where('id', $cart_service_id)->where('cart_id', $cart->id)->delete();

        // Update the total
        $cart->total = $cart->services()->sum('cart_service.price');
        $cart->save();

        return redirect()->back();
    }

    public function checkout(Request $request)
{
    // Validate inputs
    $request->validate([
        'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for proof of payment (if provided)
        'reference_number' => 'nullable|digits:4', // Validation for reference number (if provided)
        'pay_with_cash' => 'required|boolean', // Ensure that the cash payment option is selected
    ]);

    // Get the user's cart that is not paid
    $cart = auth()->user()->cart()->where('is_paid', false)->first();

    // If no cart exists or the cart is already paid, redirect back
    if (!$cart) {
        return redirect()->back()->with('error', 'No unpaid cart found or the cart is already paid.');
    }

    // Handle file upload for proof of payment if available
    $proofPath = null;
    if ($request->hasFile('proof_of_payment')) {
        $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');
    }

    // Check employee availability for each service in the cart
    $is_employees_available = true;
    $unavailable_employees = collect();

    foreach ($cart->services as $service) {
        $is_available = DB::table('appointments')
            ->where('date', $service->pivot->date)
            ->where('time', $service->pivot->time)
            ->where('employee_id', $service->pivot->employee_id)
            ->doesntExist();

        if (!$is_available) {
            $is_employees_available = false;

            $first_name = DB::table('employees')->where('id', $service->pivot->employee_id)->value('first_name');
            $service_name = $service->name;

            $unavailable_employees->push([
                'service_name' => $service_name,
                'date' => $service->pivot->date,
                'time' => $service->pivot->time,
                'first_name' => $first_name,
            ]);
        }
    }

    // If there are unavailable employees, return an error message
    if (!$is_employees_available) {
        return redirect()->back()->with('unavailable_employees', $unavailable_employees);
    }

    // Create appointments for each service in the cart
    foreach ($cart->services as $service) {
        Appointment::create([
            'cart_id' => $cart->id,
            'user_id' => $cart->user_id,
            'service_id' => $service->id,
            'time' => $service->pivot->time,
            'date' => $service->pivot->date,
            'employee_id' => $service->pivot->employee_id,
            'total' => $service->pivot->price,
            'proof_of_payment' => $proofPath, // Store the path to the proof of payment if provided
            'reference_number' => $request->reference_number, // Store the reference number
            'pay_with_cash' => $request->pay_with_cash, // Store payment method (cash or GCash)
        ]);
    }

    // Mark the cart as paid
    $cart->is_paid = true;
    $cart->save();

    // Dispatch confirmation emails to the user
    foreach ($cart->appointments as $appointment) {
        SendAppointmentConfirmationMailJob::dispatch(auth()->user(), $appointment);
    }

    // Notify admins (those with the role 'Admin' or 'Employee')
    $admins = User::whereHas('role', function ($query) {
        $query->whereIn('name', ['Admin', 'Employee']);
    })->get();

    foreach ($admins as $admin) {
        // Send a notification to each admin about the new appointment
        $admin->notify(new NewAppointmentNotification($appointment));
    }

    // Redirect the user with a success message
    return redirect()->route('customerview')->with('success', 'Your appointment has been booked successfully.');
}
}
