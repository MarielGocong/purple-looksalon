<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200  mt-2">
        <div class="flex justify-between mx-7">
        <h2 class="text-2xl font-bold text-salonPurple">SUPPLIERS</h2>

            <x-button wire:click="confirmSupplierAdd"  class="px-5 py-2 text-white bg-purple-500 rounded-md hover:bg--600">
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
                    <input type="search" wire:model="search" id="default-search" name="search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Suppliers...">
                    <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                </div>
            </div>

            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 overflow-x-scroll min-w-screen">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="pl-6 py-4 font-medium text-gray-900">Id</th>
                    <th scope="col" class="px-4 py-4 font-medium text-gray-900">Name</th>
                    <th scope="col" class="px-4 py-4 font-medium text-gray-900">Link</th>
                    <th scope="col" class="px-4 py-4 font-medium text-gray-900">Contact</th>
                    <th scope="col" class="px-4 py-4 font-medium text-gray-900">Address</th>
                    <th scope="col" class="px-4 py-4 font-medium text-gray-900">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">

                @foreach ($online_suppliers as $online_supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="pl-6 py-4  max-w-0">{{ $online_supplier->id }}</td>

                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $online_supplier->name}}</td>
                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $online_supplier->link}}</td>
                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $online_supplier->contact}}</td>
                        <td class="px-6 py-4 max-w-xs font-medium text-gray-700">{{ $online_supplier->address}}</td>
                        <td>
                            <div class="flex gap-1 mt-5">
                                <x-button wire:click="confirmSupplierEdit({{ $online_supplier->id }})" wire:loading.attr="disabled">
                                    {{ __('Edit') }}
                                </x-button>



                                <x-danger-button wire:click="confirmSupplierDeletion({{ $online_supplier->id }})" wire:loading.attr="disabled">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <div class="p-5">
                {{ $online_suppliers->links() }}
            </div>



            <x-dialog-modal wire:model="confirmingSSupplierDeletion">
                <x-slot name="title">
                    {{ __('Delete Supplier') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('Are you sure you want to delete the supplier?') }}

                </x-slot>

                <x-slot name="footer">
                    <div class="flex gap-3">
                        <x-secondary-button wire:click="$set('confirmingSupplierDeletion', false)" wire:loading.attr="disabled">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-danger-button wire:click="deleteSupplier({{ $confirmingSupplierDeletion }})" wire:loading.attr="disabled">
                            {{ __('Delete') }}
                        </x-danger-button>
                    </div>

                </x-slot>
            </x-dialog-modal>
            <x-dialog-modal wire:model="confirmingSupplierAdd">
                <x-slot name="title">
                    {{ isset($this->online_supplier->id) ? 'Edit Supplier' : 'Add Supplier' }}
                </x-slot>

                <x-slot name="content">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" wire:model="online_supplier.name" id="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        @error('online_supplier.name') <span class="text-red-500">{{ $message }}</span>@enderror

                        <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
                        <input type="text" wire:model="online_supplier.link" id="link" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        @error('online_supplier.link') <span class="text-red-500">{{ $message }}</span>@enderror

                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact:</label>
                        <input type="text" wire:model="online_supplier.contact" id="link" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        @error('online_supplier.link') <span class="text-red-500">{{ $message }}</span>@enderror

                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" wire:model="online_supplier.address" id="link" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        @error('online_supplier.address') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                        <div class="flex justify-end mt-4 gap-2">
                            <x-secondary-button wire:click="$set('confirmingSupplierAdd', false)" wire:loading.attr="disabled">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-button wire:click="saveOnlineSupplier">Save</x-button>
                        </div>
                    </div>
                </x-slot>
                <x-slot name="footer">
                </x-slot>
            </x-dialog-modal>
        </div>
    </div>
</div>

