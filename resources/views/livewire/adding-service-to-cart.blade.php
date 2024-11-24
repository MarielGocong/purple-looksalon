<section class="mt-10">
    <h3 class="text-xl font-medium my-2">Book Your Appointment</h3>

    <form wire:submit.prevent="addToCart">

        <!-- Date Picker -->
       <!-- Date Picker -->
<div class="mt-5">
    <h4 class="text-lg font-semibold text-gray-900">Select Date</h4>
    <fieldset>
        <input type="date"
               class="rounded py-2 px-4 border border-gray-300"
               wire:model.debounce="selectedDate"
               min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
               max="{{ \Carbon\Carbon::now()->addDays(30)->format('Y-m-d') }}"
               required>
    </fieldset>
</div>

<!-- Time Slot Selection -->
<!-- Time Slot Selection -->
<div>
    <h4 class="text-lg font-medium text-gray-900 mb-4">Select Time</h4>
    <div class="grid grid-cols-3 gap-4">
        @php
            $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
            $currentTime = \Carbon\Carbon::now();
        @endphp

        @for($hour = 8; $hour <= 20; $hour++)
            @php
                $time = \Carbon\Carbon::today()->setHour($hour)->setMinute(0)->setSecond(0);
                $isDisabled = ($selectedDate === $currentDate && $time->isPast());
                $isSelected = ($selectedTime === $time->format('H:i'));
            @endphp

            <label class="flex items-center">
                <input type="radio"
                       wire:model="selectedTime"
                       value="{{ $time->format('H:i') }}"
                       class="hidden"
                       {{ $isDisabled ? 'disabled' : '' }}>

                <span class="py-2 px-4 text-center border rounded-lg w-full
                                {{ $isDisabled ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-900 hover:bg-purple-100' }}
                             {{ $isSelected ? 'bg-purple-500 text-salonPurple border-purple-600' : '' }}">
                    {{ $time->format('g:i a') }}
                </span>
            </label>
        @endfor
    </div>
</div>



        <!-- Employee Selection -->
        <div class="mt-5">
            <h4 class="text-lg font-semibold text-gray-900">Select Employee</h4>
            <fieldset class="mt-2">
                <legend class="sr-only">Select an Employee</legend>
                <div class="grid grid-cols-3 gap-4" x-data="{ selectedEmployee : @entangle('selectedEmployee').defer }">
                    @foreach($employees as $employee)
                        <div wire:key="employee-{{ $employee->id }}-element">
                            @if($employee->available)
                                <label wire:key="employee-{{ $employee->id }}-available"
                                       class="group relative flex items-center text-gray-800 justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase focus:outline-none sm:flex-1 cursor-pointer shadow-sm"
                                       x-bind:class="{
                                           'bg-purple-500 text-white': selectedEmployee === {{ $employee->id }},
                                           'bg-gray-50 hover:bg-purple-100': selectedEmployee !== {{ $employee->id }}
                                       }">
                                    <input type="radio" name="employee-choice"
                                           value="{{ $employee->id }}"
                                           class="sr-only"
                                           x-on:change="selectedEmployee = {{ $employee->id }}"
                                           aria-labelledby="employee-choice-{{ $employee->id }}-label">
                                    <span id="employee-choice-{{ $employee->id }}-label">
                                        {{ $employee->first_name }}
                                        <span>
                                            {{ $employee->job_category?->name }}
                                            </span>
                                    </span>
                                    <span class="pointer-events-none absolute -inset-px rounded-md" aria-hidden="true"></span>
                                </label>
                            @else
                                <label wire:key="employee-{{ $employee->id }}-unavailable"
                                       class="group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase hover:bg-gray-50 focus:outline-none sm:flex-1 cursor-not-allowed bg-gray-50 text-gray-200">
                                    <input type="radio" name="employee-choice"
                                           value="{{ $employee->id }}" disabled class="sr-only"
                                           aria-labelledby="employee-choice-{{ $employee->id }}-label">
                                    <span id="employee-choice-{{ $employee->id }}-label">
                                        {{ $employee->first_name }}
                                        <span>
                                        {{ $employee->job_category?->name }}
                                        </span>
                                    </span>
                                    <span aria-hidden="true"
                                          class="pointer-events-none absolute -inset-px rounded-md border-2 border-gray-200">
                                        <svg class="absolute inset-0 h-full w-full stroke-2 text-gray-200"
                                             viewBox="0 0 100 100" preserveAspectRatio="none" stroke="currentColor">
                                            <line x1="0" y1="100" x2="100" y2="0" vector-effect="non-scaling-stroke"/>
                                        </svg>
                                    </span>
                                </label>
                            @endif
                        </div>
                    @endforeach
                    
                </div>
            </fieldset>
        </div>

        <!-- Message -->
        <div class="py-5">
            <label for="message" class="block mb-2 font-sm font-bold text-gray-900 dark:text-white">Your message: <span class="font-m text-gray-500">Optional</span></label>
            <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your notes here..."></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="mt-6 flex w-full items-center justify-center rounded-md border border-transparent bg-purple-600 px-8 py-3 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                :disabled="!selectedTimeSlot || !selectedEmployee">
            Add to cart
        </button>
    </form>
</section>
