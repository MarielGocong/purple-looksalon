<?php

namespace App\View\Components;

use App\Models\Appointment;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

/**
 *
 */
class DaySchedule extends Component
{
    public  $daySchedule = null;
    public  $time  = null;
    public function __construct(
        public readonly Carbon $date,

    )
    {
        $this->daySchedule = $this->getDaySchedule();
        
    }

    public function render(): View
    {

        return view('components.day-schedule');
    }

    private function getDaySchedule()
    {

        return (
            Appointment::orderBy('time', 'asc')
            ->where('date', $this->date->toDateString())
            ->where('status', '!=', 0)
            ->orderBy('time', 'asc')
            ->where('status', '!=', 0)
            ->with('service', 'user')
            ->get());
    }


}
