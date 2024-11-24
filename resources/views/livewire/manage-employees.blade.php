<div class="p-4 sm:ml-64">
    <div class="flex justify-between mx-7">
        <h2 class="text-2xl font-bold">MANAGE  EMPLOYEE</h2>



        <x-button wire:click="showAddEmployeeModal"  class="px-5 py-2 text-white bg-purple-500 rounded-md hover:bg--600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>

            Create
        </x-button>
    </div>
    <div class="mt-4">
        @if (session()->has('message'))
            <div class="px-4 py-2 text-white bg-green-500 rounded-md">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="mt-4">
        @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">{{session('error')}}</span>
          </div>
        @endif
    </div>

    <div class="overflow-auto rounded-lg border border-gray-200 shadow-md m-5">
        <div class="w-1/3 float-right m-4">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only ">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" wire:model="search" id="default-search" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Employee...">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
            </div>
        </div>

        <select wire:model="statusFilter" class="form-select">
            <option value="all">All Employees</option>
            <option value="active">Active Employees</option>
            <option value="archived">Archived Employees</option>
        </select>

        <table class="min-w-full border-collapse bg-white text-sm text-left text-gray-500">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-gray-900">Id</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Image</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">First Name</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Last Name</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Email</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Phone Number</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Position</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">On Duty Days</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Status</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Visibility</th>
                    <th scope="col" class="px-6 py-3 text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($employees as $employee)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $employee->id }}</td>
                    <td class="px-6 py-4">
                        <img src="{{ $employee->image ? asset('storage/' . $employee->image) : asset('default-avatar.png') }}"
                             alt="Employee Image"
                             class="w-20 h-20 object-cover rounded-full">
                    </td>
                    <td class="px-6 py-4">{{ $employee->first_name }}</td>
                    <td class="px-6 py-4">{{ $employee->last_name }}</td>
                    <td class="px-6 py-4">{{ $employee->email }}</td>
                    <td class="px-6 py-4">{{ $employee->phone_number }}</td>
                    <td class="px-6 py-4">{{ $employee->jobCategory?->name }}</td>
                    <td class="px-6 py-4">{{ implode(', ', $employee->working_days ?? []) }}</td>
                    <td class="px-6 py-4">
                        {{ $employee->status ? 'Active' : 'Archived' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="{{ $employee->is_hidden ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} px-2 py-1 text-xs font-medium rounded-full">
                            {{ $employee->is_hidden ? 'Hidden' : 'Visible' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 gap-2">

                        <button wire:click="viewEmployee({{ $employee->id }})" class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                              </svg>

                            View
                        </button>
                        <button wire:click="showEditEmployeeModal({{ $employee->id }})" wire:loading.attr="disabled" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                            Edit
                        </button>
                        @if($employee->status == 1)  <!-- Active employee -->
                        <button wire:click="archiveEmployee({{ $employee->id }})" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                              </svg>

                            Archive
                        </button>
                    @else  <!-- Archived employee -->
                        <button wire:click="unarchiveEmployee({{ $employee->id }})" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                              </svg>

                            Unarchive
                        </button>
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


        <div class="p-5">
          {{ $employees->links() }}
        </div>



        <x-dialog-modal wire:model="confirmingEmployeeView">
            <x-slot name="title">
                {{ __('Employee Details') }}
            </x-slot>
            <x-slot name="content">
                @if($employee)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-4xl w-full p-8 transition-all duration-300 animate-fade-in">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/3 text-center mb-8 md:mb-0">
                            <img src="{{ asset('storage/' . $employee->image) }}" alt="Profile Picture" class="rounded-full w-48 h-48 mx-auto mb-4 border-4 border-purple-800 dark:border-blue-900 transition-transform duration-300 hover:scale-105">
                            <h1 class="text-2xl font-bold text-purple-800 dark:text-white mb-2">{{ $employee->first_name }} {{ $employee->last_name }}</h1>
                            <p class="text-gray-600 dark:text-gray-300">{{ $employee->jobCategory->name ?? 'N/A' }}</p>
                        </div>
                        <div class="md:w-2/3 md:pl-8">
                            <h2 class="text-xl font-semibold text-purple-800 dark:text-white mb-4">Details</h2>
                            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <p><strong>Age:</strong> {{ $employee->age }}</p>
                                </li>
                                <li class="flex items-center">
                                    <p><strong>Birthday:</strong> {{ $employee->birthday }}</p>
                                </li>
                                <li class="flex items-center">
                                    <p><strong>Date Started:</strong> {{ $employee->birthday }}</p>
                                </li>
                            </ul>
                            <h2 class="text-xl font-semibold text-purple-800 dark:text-white mb-4">Contact Information</h2>
                            <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-800 dark:text-blue-900" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                   {{$employee->email}}
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-800 dark:text-blue-900" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                    </svg>
                                    {{$employee->phone_number}}
                                </li>
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-800 dark:text-blue-900" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    {{$employee->address}}
                                </li>
                            </ul>
                        </div>
                    </div>
                  </div>
                @else
                    <p>{{ __('Employee details not available.') }}</p>
                @endif
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmingEmployeeView', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>

        <x-dialog-modal wire:model="showEditEmployeeModal">
            <x-slot name="title">{{ __('Edit Employee') }}</x-slot>
            <x-slot name="content">
                @include('components.employee-form', ['isEditing' => true])
            </x-slot>
            <x-slot name="footer">
                <div class="flex gap-3">
                <x-secondary-button wire:click="$set('showEditEmployeeModal', false)">Cancel</x-secondary-button>
                <x-button wire:click="saveEmployee">Save Changes</x-button>
                </div>
            </x-slot>
        </x-dialog-modal>


        <x-dialog-modal wire:model="showAddEmployeeModal">
            <x-slot name="title">{{ __('Add Employee') }}</x-slot>
            <x-slot name="content">
                @include('components.employee-form', ['isEditing' => false])
            </x-slot>
            <x-slot name="footer">
                <div class="flex gap-3">
                <x-secondary-button wire:click="$set('showAddEmployeeModal', false)">Cancel</x-secondary-button>
                <x-button wire:click="saveEmployee">Save</x-button>
                </div>
            </x-slot>
        </x-dialog-modal>



</div>
</div>



