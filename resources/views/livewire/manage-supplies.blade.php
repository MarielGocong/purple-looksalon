<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200">
    <div class="flex justify-between mx-7">
    <h2 class="text-2xl font-bold text-salonPurple">MANAGE SUPPLIES AND CONSUMABLES</h2>



        <x-button wire:click="showAddSuppliesModal" class="px-5 py-2 text-white bg-purple-500 rounded-md hover:bg--600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            Add
        </x-button>


        <x-button wire:click="exportToPdf"  class="px-5 py-2 text-white bg-purple-500 rounded-md hover:bg--600">
            <svg class="w-6 h-6 me-2 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
              </svg>
            Print to PDF
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

        <div class="w-full m-4 flex">

            <div class="w-1/2 mx-2">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only ">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" wire:model="search" id="default-search" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Supplies and Consumbles...">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
            </div>

            <select id="categoryFilter" wire:model="categoryFilter" class="border text-gray-900  border-gray-300 rounded-lg">
                <option selected>All Category</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
              </select>


             <select class="border text-gray-900  border-gray-300 rounded-lg" wire:model="selectFilter" >
                <option value="all">All</option>
                <option value="expired">Expired</option>
                <option value="lowquantity">Low Quantity</option>
          </select>

            <select wire:model="statusFilter" class="border text-gray-900  border-gray-300 rounded-lg">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="archived">Archived</option>
            </select>
            </div>


        </div>

        <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 overflow-x-scroll min-w-screen">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="pl-6 py-4 font-large text-gray-900">Id</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Image</th>
              <th scope="col" class="px-4 py-4 font-large text-gray-900">Name</th>
              <th scope="col" class="px-4 py-4 font-large text-gray-900">Description</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Color Code</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Color Shade</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Category</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Quantity</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Size</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Expiration Date</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Status</th>
              <th scope="col" class="px-6 py-4 font-large text-gray-900">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 border-t border-gray-100">

            @foreach ($supplies as $supply)
            <tr class="hover:bg-gray-50">
                <td class="pl-6 py-4  max-w-0">{{ $supply->id }}</td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-700">
                        <img src="{{ asset('storage/' . $supply->image) }}" alt="" class="w-20 h-20 object-cover">
                    </div>
                </td>


                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-900">{{ $supply->name}}</div>
                </td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-900">{{ $supply->description}}</div>
                </td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-900">{{ $supply->color_code}}</div>
                </td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-900">{{ $supply->color_shade}}</div>
                </td>

                <td class="px-6 py-4 max-w-0">
                    <div class="'font-medium text-gray-900" >{{ $supply->category?->name }}</div>
                </td>



                <td class="px-6 py-4 max-w-0">
                    <div class="font-medium {{ $supply->quantity <= 5 ? 'text-red-600' : 'text-gray-700' }}">
                        {{ $supply->quantity }}
                    </div>
                </td>



                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-700">{{ $supply->size}}</div>
                </td>

                <td class="px-6 py-4 {{ \Carbon\Carbon::parse($supply->expiration_date)->diffInDays(now()) <= 2 ? 'text-red-600' : 'text-gray-700' }}">
                    {{ $supply->expiration_date }}
                </td>

                <td>{{ $supply->status ? 'Active' : 'Archived' }}</td>

                <td>
                    <div class="my-4 gap-0 ">
                        <button wire:click="viewSupplies({{ $supply->id }})" class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 shadow-lg shadow-purple-500/50 dark:shadow-lg dark:shadow-purple-800/80 rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                              </svg>
                            View
                        </button>


                        <x-button wire:click="showEditSuppliesModal({{ $supply->id }})" wire:loading.attr="disabled" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                           Edit
                        </x-button>

                        @if($supply->status == 1)  <!-- Active employee -->
                        <button wire:click="archiveSupplies({{ $supply->id }})" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                              </svg>

                            Archive
                        </button>
                    @else  <!-- Archived employee -->
                        <button wire:click="unarchiveSupplies({{ $supply->id }})" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
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
          {{ $supplies->links() }}
        </div>



        <x-dialog-modal wire:model="confirmingSuppliesDeletion">
            <x-slot name="title">
                {{ __('Delete Supply') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to Delete the Supplies?') }}

            </x-slot>

            <x-slot name="footer">
                <div class="flex gap-3">
                <x-secondary-button wire:click="$set('confirmingSuppliesDeletion', false)" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>

                    <x-danger-button wire:click="deleteSupplies({{ $confirmingSuppliesDeletion }})" wire:loading.attr="disabled">
                        {{ __('Delete') }}
                    </x-danger-button>
                </div>

            </x-slot>
        </x-dialog-modal>

        <x-dialog-modal wire:model="confirmingSuppliesView">
            <x-slot name="title">
                {{ __('Supplies Details') }}
            </x-slot>
            <x-slot name="content">
                <!-- Display supply details -->
                @if($supply)
                <div class="w-full md:w-1/3 bg-white grid place-items-center">
                    <img src="{{ asset('storage/' . $supply->image) }}" alt="Supply Image" class="w-20 h-20 object-cover lazyload" loading="lazy">
                    <div class="w-full md:w-2/3 bg-white flex flex-col space-y-2 p-3">
                        <h3 class="font-black text-gray-800">{{ $supply->name }}</h3>
                        <p class="text-xl font-black text-gray-800">{{ $supply->category->name ?? 'N/A' }}</p>
                        <p class="text-xl font-black text-gray-800">{{ $supply->description }}</p>
                        <p class="text-xl font-black text-gray-800">{{ $supply->quantity }}</p>
                        <p class="text-xl font-black text-gray-800">{{ $supply->size }}</p>
                        <p class="text-xl font-black text-gray-800">{{ $supply->color_code }}</p>
                        <p class="text-xl font-black text-gray-800">{{ $supply->color_shade }}</p>
                    </div>
                </div>
                @endif
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="$set('confirmingSuppliesView', false)" wire:loading.attr="disabled">
                    {{ __('Close') }}
                </x-secondary-button>
            </x-slot>
        </x-dialog-modal>


        <x-dialog-modal wire:model="showAddSuppliesModal">
            <x-slot name="title">{{ __('Add Supply') }}</x-slot>
            <x-slot name="content">
            @include('components.supplies-form', ['isEditing' => true])   
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModals">Cancel</x-secondary-button>
                <x-button wire:click="saveSupplies">Save</x-button>
            </x-slot>
        </x-dialog-modal>

        <x-dialog-modal wire:model="showEditSuppliesModal">
            <x-slot name="title">{{ __('Edit Supply') }}</x-slot>
            <x-slot name="content">
            @include('components.supplies-form', ['isEditing' => true])
                    </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModals">Cancel</x-secondary-button>
                <x-button wire:click="saveSupplies">Save Changes</x-button>
            </x-slot>
        </x-dialog-modal>


        <script>
            window.addEventListener('downloadFile', event => {
                const link = document.createElement('a');
                link.href = event.detail.url;
                link.download = 'supplies-report.pdf';
                link.click();
            });
        </script>


    </div>
</div>
