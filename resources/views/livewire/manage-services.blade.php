<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200  mt-2">
        <div class="flex justify-between mx-7">
        <h2 class="text-2xl font-bold text-salonPurple">MANAGE SERVICES</h2>

        <x-button wire:click="confirmServiceAdd"  class="px-5 py-2 text-white bg-purple-500 rounded-md hover:bg--600">
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




    <div class="overflow-auto rounded-lg border border-gray-200 shadow-md m-5">
        <div class="w-1/3 float-right m-4">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only ">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" wire:model="search" id="default-search" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Services...">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
            </div>
        </div>

        <select wire:model="statusFilter" class="form-select">
            <option value="all">All Employees</option>
            <option value="active">Active Employees</option>
            <option value="archived">Archived Employees</option>
        </select>

        <select wire:model="categoryFilter" class="form-select me-3">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>


        <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 overflow-x-scroll min-w-screen">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="pl-6 py-4 font-medium text-gray-900">Id</th>
              <th scope="col" class="px-4 py-4 font-medium text-gray-900">Service</th>
              <th scope="col" class="px-8 py-4 font-medium text-gray-900">Photo</th>
              <th scope="col" class="px-6 py-4 font-medium text-gray-900">Description</th>
              <th scope="col" class="px-6 py-4 font-medium text-gray-900">Price</th>
              <th scope="col" class="px-6 py-4 font-medium text-gray-900">Category</th>
              <th scope="col" class="px-6 py-4 font-medium text-gray-900">Status</th>
              <th scope="col" class="px-6 py-4 font-medium text-gray-900">Visibility</th>
              <th scope="col" class="px-6 py-4 font-medium text-gray-900">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 border-t border-gray-100">

            @foreach ($services as $service)
            <tr class="hover:bg-gray-50">
                <td class="pl-6 py-4  max-w-0">{{ $service->id }}</td>

                <th class="flex gap-3 px-6 py-4 font-normal text-gray-900  max-w-0">

                    <div class="font-medium text-gray-700">{{ $service->name}}</div>

                </th>
                <td class="px-8 py-4  max-w-0">
                    <div class="font-medium text-gray-700">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="" class="w-20 h-20 object-cover">
                    </div>
                </td>

                <td class="px-6 py-4 max-w-0">{{ $service->description }}</td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-700">{{ $service->price}}</div>
                </td>
                <td class="px-6 py-4  max-w-0">
{{--                    @dd($service->category->name)--}}
                    <div class="font-medium text-gray-700">{{ $service->category?->name}}</div>
                </td>
                <td>{{ $service->status ? 'Active' : 'Archived' }}</td>

                <td class="px-6 py-4 ">
                    <div>

                    @if($service->is_hidden == true)
                        <span
                        class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-600"
                      >
                        <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                        Hidden
                      </span>
                    @else
                        <span
                        class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-600"
                        >
                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                        Visible
                        </span>
                    @endif

                    </div>
                </td>
                <td>
                    <div class="mt-5 ">
                        <a href="{{ route('view-service', ['slug' => $service->slug ])  }}" >
                            <button class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                                <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                  </svg>

                                View
                            </button>
                        </a>
                        <button wire:click="confirmServiceEdit({{ $service->id }})" wire:loading.attr="disabled" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                            Edit
                        </button>



                        @if($service->status == 1)  <!-- Active employee -->
                        <button wire:click="archiveService({{ $service->id }})" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                              </svg>

                            Archive
                        </button>
                    @else  <!-- Archived employee -->
                        <button wire:click="unarchiveService({{ $service->id }})" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                              </svg>

                            Unarchive
                        </button>
                    @endif




                    </div>
                </td>
            </tr>
            @endforeach

          </tbody>
        </table>
        <div class="p-5">
          {{ $services->links() }}
        </div>





        <x-dialog-modal wire:model="confirmingServiceDeletion">

            <x-slot name="title">
                {{ __('Delete Service') }}
            </x-slot>

            <x-slot name="content">
                <div class="mt-4">
                    @if (session()->has('error'))
                        <div class="px-4 py-2 text-white bg-red-500 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                {{ __('Are you sure you want to delete the service?') }}

            </x-slot>

            <x-slot name="footer">
                <div class="flex gap-3">
                <x-secondary-button wire:click="$set('confirmingServiceDeletion', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                    <x-danger-button wire:click="deleteService({{ $confirmingServiceDeletion }})" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </x-danger-button>
                </div>

            </x-slot>
        </x-dialog-modal>





        <x-dialog-modal wire:model="confirmingServiceAdd">
            <x-slot name="title">
                {{-- {{ __('Add a new service') }} --}}
                {{ isset($this->newService->id) ? 'Edit Service' : 'Add Service' }}
            </x-slot>

            <x-slot name="content">
                @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400">
                    <span>
                    {{ session('error') }}
                    </span>
                </div>
                @endif

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model="newService.name" id="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('newService.name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" wire:model="newService.description"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            @error('newService.description') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-3">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="text" wire:model="newService.price" id="price" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                            @error('newService.price') <span class="text-red-500">{{ $message }}</span>@enderror

                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>

                            <select wire:model="newService.category_id" id="category_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option disabled selected value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name}}</option>
                                @endforeach
                                @error('newService.category_id') <span class="text-red-500">{{ $message }}</span>@enderror
                            </select>
                        </div>
                    </div>
                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                    <div>
                        <label for="allergens" class="block text-sm font-medium text-gray-700">Allergens</label>
                        <textarea id="allergens" wire:model="newService.allergens"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error('newService.allergens') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label for="cautions" class="block text-sm font-medium text-gray-700">Cautions</label>
                        <textarea id="cautions" wire:model="newService.benefits"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error('newService.cautions') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>

                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div>
                            <label for="benefits" class="block text-sm font-medium text-gray-700">Benefits</label>
                            <textarea id="benefits" wire:model="newService.benefits"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            @error('newService.benefits') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label for="aftercare_tips" class="block text-sm font-medium text-gray-700">Aftercare Tips</label>
                            <textarea id="aftercare_tips" wire:model="newService.aftercare_tips"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            @error('newService.aftercare_tips') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" wire:model="newService.notes"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        @error('newService.notes') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                   
                    <div class="mt-4">
                        <label for="employees" class="block text-sm font-medium text-gray-700">Assign Employees</label>
                        <div id="employees" class="mt-2">
                            @foreach($employees as $employee)
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        wire:model="employeeIds"
                                        value="{{ $employee->id }}"
                                        id="employee_{{ $employee->id }}"
                                        class="mr-2"
                                    />
                                    <label for="employee_{{ $employee->id }}" class="text-gray-700">{{ $employee->first_name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="is_hidden" class="block text-sm font-medium text-gray-700">Is Hidden</label>

                        <input type="checkbox" wire:model="newService.is_hidden" id="is_hidden">
                        @error('newService.is_hidden') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>


                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div class="col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" wire:model.defer="image" id="image" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('image') <span class="text-red-500">{{ $message }}</span>@enderror

                            {{-- If the image is already saved is system show img --}}
                             @if (isset($image) && is_string($image))
                                <img alt="image" src="{{ '/storage/' . $image }}" class="mt-4" width="200">
                            {{-- When the image is uploaded show img --}}
                             @elseif (isset($image) && is_object($image))
                                <img alt="image" src="{{ $image->temporaryUrl() }}" class="mt-4" width="200">
                             @else

                            @endif

                        </div>
                    </div>




            </x-slot>

            <x-slot name="footer">


                <x-secondary-button wire:click="$set('confirmingServiceAdd', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-button wire:click="saveService">Save</x-button>
            </x-slot>
        </x-dialog-modal>





</div>
</div>
