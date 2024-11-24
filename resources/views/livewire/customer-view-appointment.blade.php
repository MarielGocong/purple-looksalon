    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 mt-2">
            <div class="flex justify-between mx-7">
                <h2 class="text-2xl font-bold">
                    @if ($selectFilter == 'upcoming')
                        Upcoming
                    @elseif ($selectFilter == 'previous')
                        Previous
                    @elseif ($selectFilter == 'cancelled')
                        Cancelled
                    @endif
                    Appointments
                </h2>
                <span class="text-m text-red-500">Note*: Cancellation will be disabled after 12 hours</span>
            </div>
            <div class="mt-4">
                @if (session()->has('message'))
                    <div class="px-4 py-2 text-white bg-green-500 rounded-md">
                        {{ session('message') }}
                    </div>
                @endif
            </div>

            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md m-5">
                <div class="w-full m-4 flex">
                    <div class="w-1/2 mx-2">
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="search" wire:model="search" id="default-search" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Appointments...">
                            <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                        </div>
                    </div>

                    <select class="border text-gray-900 border-gray-300 rounded-lg" wire:model="selectFilter">
                        <option value="upcoming">Upcoming</option>
                        <option value="previous">Previous</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 overflow-x-scroll min-w-screen">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="pl-6 py-4 font-bold text-gray-900">Code</th>
                            <th scope="col" class="px-4 py-4 font-bold text-gray-900">Service</th>
                            <th scope="col" class="px-4 py-4 font-bold text-gray-900">Date</th>
                            <th scope="col" class="px-4 py-4 font-bold text-gray-900">Time</th>
                            <th scope="col" class="px-4 py-4 font-bold text-gray-900">Staff Assigned</th>
                            <th scope="col" class="px-4 py-4 font-bold text-gray-900">Reason</th>

                            <th scope="col" class="px-4 py-4 font-bold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        @if($appointments->count() == 0)
                            <tr class="hover:bg-gray-50 text-center">
                                <td class="pl-6 py-4 max-w-0" colspan="7">No Appointments Found</td>
                            </tr>
                        @else
                            @foreach ($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="pl-6 py-4 max-w-0">{{ $appointment->appointment_code }}</td>
                                    <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->service->name }}</td>
                                    <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->date }}</td>
                                    <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->time}}</td>
                                    <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->employee->first_name }}</td>
                                    <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->cancellation_reason }}</td>

                                    <td>
                                        <div class="flex gap-1 mt-5">
                                            @if ($selectFilter == 'upcoming')
                                                @php
                                                    $appointmentTime = Carbon\Carbon::parse($appointment->date . ' ' . $appointment->time);
                                                    $timeDifference = $appointmentTime->diffInHours(now());
                                                @endphp

                                                @if ($timeDifference > 12) <!-- Adjust to 24 if needed -->
                                                    <x-danger-button wire:click="setAppointmentIdToCancel({{ $appointment->id }})" wire:loading.attr="disabled">
                                                        {{ __('Cancel') }}
                                                    </x-danger-button>
                                                @else
                                                    <button disabled class="text-gray-500 bg-gray-300 rounded-md px-4 py-2 cursor-not-allowed">
                                                        {{ __('Cannot Cancel') }}
                                                    </button>
                                                @endif
                                                <x-button wire:click="editAppointment({{ $appointment->id }})">
                                                    Reschedule
                                                </x-button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="p-5">
                    {{ $appointments->links() }}
                </div>

                <x-dialog-modal wire:model="confirmingAppointmentCancellation">
                    <x-slot name="title">
                        Cancel Appointment
                    </x-slot>

                    <x-slot name="content">
                        <p>Are you sure you want to cancel this appointment?</p>

                        <!-- Reason selection dropdown -->
                        <div class="mt-4">
                            <label for="cancellationReason" class="block text-sm font-medium text-gray-700">Reason for cancellation</label>
                            <select id="cancellationReason" wire:model="cancellationReason" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select a reason</option>
                                <option value="Not needed anymore">Not needed anymore</option>
                                <option value="Scheduling conflict">Scheduling conflict</option>
                                <option value="Found a better provider">Found a better provider</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('cancellationReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <div class="flex gap-3">
                            <x-secondary-button wire:click="$set('confirmingAppointmentCancellation', false)" wire:loading.attr="disabled">
                                Back
                            </x-secondary-button>

                            <x-danger-button wire:click="cancelAppointment" wire:loading.attr="disabled">
                                Confirm Cancellation
                            </x-danger-button>
                        </div>
                    </x-slot>
                </x-dialog-modal>



                <x-dialog-modal wire:model="editingAppointment">
                    <x-slot name="title">
                        Reschedule Appointment
                    </x-slot>

                    <x-slot name="content">
                        <!-- New Date Selection -->
                        <div class="mt-4">
                            <label for="newDate" class="block text-sm font-medium text-gray-700">Select a new date</label>
                            <input type="date" wire:model="newDate" min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Timeslot Selection -->
                        <div class="mt-4">
                            <label for="newTimeSlot" class="block text-sm font-medium text-gray-700">Select a new timeslot</label>
                            <select wire:model="newTimeSlot" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($availableTimeSlots as $slot)
                                    <option value="{{ $slot->id }}" @if(!$slot->available) disabled @endif>
                                        {{ $slot->start_time }} - {{ $slot->end_time }}
                                        @if(!$slot->available) (Unavailable) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <div class="flex gap-3">
                            <x-secondary-button wire:click="$set('editingAppointment', false)" wire:loading.attr="disabled">
                                Cancel
                            </x-secondary-button>

                            <x-button wire:click="updateAppointment" wire:loading.attr="disabled">
                                Save Changes
                            </x-button>
                        </div>
                    </x-slot>
                </x-dialog-modal>



            </div>
        </div>
    </div>

