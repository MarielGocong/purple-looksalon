<?php

namespace App\Http\Livewire;

use App\Models\Concern;
use Livewire\Component;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|email|max:255',
        'message' => 'required|string|max:500',
    ];

    public function submitForm()
    {
        $this->validate();

        // Save to the database
        Concern::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        // Clear the form fields
        $this->reset(['name', 'email','subject', 'message']);

        // Optionally show a success message
        session()->flash('message', 'Your message has been sent successfully.');
    }

    public function render()
    {
        return view('web.contact');
    }
}
