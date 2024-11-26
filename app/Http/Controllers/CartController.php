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
        // Validate payment
        $request->validate([
            'pay_method' => 'required|in:cash,gcash',
            'proof_of_payment' => 'required_if:pay_method,gcash|file|mimes:jpeg,png,jpg,gif|max:2048',
            'reference_number' => 'required_if:pay_method,gcash|digits:4',
        ]);

        // Retrieve cart with items for confirmation
        $cart = auth()->user()->cart()->with(['forConfirmationServices'])->where('is_paid', false)->first();

        if (!$cart || $cart->forConfirmationServices->isEmpty()) {
            return redirect()->back()->with('error', 'No items marked for confirmation in your cart.');
        }

        // Handle GCash payment proof
        $proofPath = null;
        if ($request->pay_method === 'gcash') {
            $proofPath = $request->file('proof_of_payment')->store('proofs', 'public');
        }

        // Start transaction
        DB::beginTransaction();

        try {
            // Confirm services
            foreach ($cart->forConfirmationServices as $service) {
                Appointment::create([
                    'cart_id' => $cart->id,
                    'user_id' => $cart->user_id,
                    'service_id' => $service->id,
                    'time' => $service->pivot->time,
                    'date' => $service->pivot->date,
                    'first_name' => $service->pivot->first_name,
                    'employee_id' => $service->pivot->employee_id,
                    'total' => $service->pivot->price,
                    'pay_method' => $request->pay_method,
                    'proof_of_payment' => $proofPath,
                    'reference_number' => $request->reference_number,
                ]);
            }

            // Unmark or remove confirmed services
            $cart->services()->updateExistingPivot(
                $cart->forConfirmationServices->pluck('id')->toArray(),
                ['is_for_confirmation' => false]
            );

            // If no more items are for confirmation, mark cart as paid
            if ($cart->services()->wherePivot('is_for_confirmation', true)->count() === 0) {
                $cart->update(['is_paid' => true]);
            }

            // Commit transaction
            DB::commit();

            return redirect()->route('customerview')->with('success', 'Selected services have been confirmed.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while confirming your services.');
        }
    }

}
