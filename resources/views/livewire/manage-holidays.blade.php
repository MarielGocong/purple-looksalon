<div>
    <h2>Manage Holidays and Special Events</h2>

    <form wire:submit.prevent="addHoliday">
        <input type="text" wire:model="holidayName" placeholder="Holiday/Event Name">
        <input type="date" wire:model="holidayDate" placeholder="Date">
        <button type="submit">Add Holiday</button>
    </form>

    <ul>
        @foreach ($holidays as $holiday)
            <li>{{ $holiday->name }} - {{ $holiday->date }}
                <button wire:click="deleteHoliday({{ $holiday->id }})">Delete</button>
            </li>
        @endforeach
    </ul>
</div>
