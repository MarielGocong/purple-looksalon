<?php

namespace App\Http\Controllers;

use App\Models\Concern;
use Illuminate\Http\Request;

class DisplayContact extends Controller
{
    // Display the contact form and contacts table
    public function index()
   {
        return view('web.contact');
   }

    public function contact()
    {
        $concerns = Concern::orderBy('created_at', 'desc')->paginate(10);
        return view('dashboard.manage-contact.index', compact('concerns'));
    }

    // Handle form submission and save data to the database
    public function storeContact(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:500',
        ]);

        // Create a new contact record
        Concern::create($request->only('name', 'email', 'subject','message'));

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Your message has been sent successfully.');
    }

    public function showMessages()
    {
        $messages = Concern::orderBy('created_at', 'desc')->get();
        return view('dashboard.manage-contact.index', compact('messages'));
    }
}
