<?php

namespace App\Http\Livewire;

use App\Models\Holiday;
use Livewire\Component;

class ManageHolidays extends Component
{
    public $holidays;
    public $holidayName;
    public $holidayDate;

    public function mount()
    {
        $this->holidays = Holiday::all();
    }

    public function addHoliday()
    {
        $this->validate([
            'holidayName' => 'required|string|max:255',
            'holidayDate' => 'required|date',
        ]);

        Holiday::create([
            'name' => $this->holidayName,
            'date' => $this->holidayDate,
        ]);

        $this->resetInput();
        $this->holidays = Holiday::all(); // Refresh list
    }

    public function deleteHoliday($id)
    {
        Holiday::find($id)->delete();
        $this->holidays = Holiday::all(); // Refresh list
    }

    private function resetInput()
    {
        $this->holidayName = null;
        $this->holidayDate = null;
    }

    public function render()
    {
        return view('livewire.manage-holidays');
    }
}
