<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200  mt-2">
        <div class="flex justify-between mx-7">
            <h2 class="text-2xl font-bold">

                @if ($selectFilter == 'upcoming')
                        Upcoming
                @elseif ($selectFilter == 'previous')
                        Previous
                @elseif ($selectFilter == 'cancelled')
                        Cancelled
                @elseif ($selectFilter == 'completed')
                        Completed
                @endif


                Appointments</h2>


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
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only ">Search</label>
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

                <select class="border text-gray-900  border-gray-300 rounded-lg" wire:model="selectFilter" >
                    <option value="completed">Completed</option>
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

                    @if (auth()->user()->role->name  == 'Admin' || auth()->user()->role->name  == 'Employee')

                    <th scope="col" class="px-4 py-4 font-bold text-gray-900">Customer</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-900">Contact No</th>
                        <th scope="col" class="px-4 py-4 font-bold text-gray-900">Email</th>
                    @endif
                    <th scope="col" class="px-4 py-4 font-bold text-gray-900">Reason</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                @if($appointments->count() == 0)
                    <tr class="hover:bg-gray-50 text-center">
                        <td class="pl-6 py-4  max-w-0
                        " colspan="9">No Appointments Found</td>
                    </tr>
                @else
                @foreach ($appointments as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="pl-6 py-4  max-w-0">{{ $appointment->appointment_code }}</td>

                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->service->name}}</td>
                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->date}}</td>
                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->time }}</td>
                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->employee->first_name}}</td>

                        @if (auth()->user()->role->name == 'Admin' || auth()->user()->role->name == 'Employee')
                            <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->user->name}}</td>
                            <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->user->phone_number}}</td>
                            <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->user->email}}</td>
                            <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $appointment->cancellation_reason}}</td>
                        @endif


                        <td>
                            <div class="flex gap-1 mt-5">

                                @if ($selectFilter == 'upcoming')

                                <x-button wire:click="completeAppointment({{ $appointment->id }} )" class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                                    <svg class="w-5 h-5 text dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                      </svg>


                                    Completed

                                </x-button>

                                    <x-danger-button wire:click="confirmAppointmentCancellation({{ $appointment->id }})" wire:loading.attr="disabled">
                                        {{ __('Cancel') }}
                                    </x-danger-button>

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
                    {{ __('Cancel Appointment') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to cancel the appointment?') }}

                </x-slot>

                <x-slot name="footer">
                    <div class="flex gap-3">
                        <x-secondary-button wire:click="$set('confirmingAppointmentCancellation', false)" wire:loading.attr="disabled">
                            {{ __('Back') }}
                        </x-secondary-button>

                        <x-danger-button wire:click="cancelAppointment({{ $confirmingAppointmentCancellation }})" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-danger-button>
                    </div>

                </x-slot>
            </x-dialog-modal>

        </div>
    </div>
</div>

