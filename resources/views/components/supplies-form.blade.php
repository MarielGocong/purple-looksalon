
<div class="grid gap-4 mb-4 sm:grid-cols-2">

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700"> Name:</label>
        <input type="text" wire:model="newSupplies.name" id="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('newSupplies.name') <span class="text-red-500">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
        <input type="text" wire:model="newSupplies.description" id="description" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('newSupplies.description') <span class="text-red-500">{{ $message }}</span>@enderror
    </div>

    <div>
        <label for="color_code" class="block text-sm font-medium text-gray-700">Color Code</label>
        <input type="text" wire:model="newSupplies.color_code" id="color_code" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

        @error('newSupplies.color_code') <span class="text-red-500">{{ $message }}</span>@enderror

    </div>
    <div>
        <label for="color_shade" class="block text-sm font-medium text-gray-700">Color Shade</label>
        <input type="text" wire:model="newSupplies.color_shade" id="color_shade" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

        @error('newSupplies.color_shade') <span class="text-red-500">{{ $message }}</span>@enderror

    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>

        <select wire:model="newSupplies.category_id" id="category_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option disabled selected value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name}}</option>
            @endforeach
            @error('newSupplies.category_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </select>
    </div>

    <div>
        <label for="online_supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>

        <select wire:model="newSupplies.online_supplier_id" id="online_supplier_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option disabled selected value="">Select Supplier</option>
            @foreach ($online_suppliers as $online_supplier)
                <option value="{{ $online_supplier->id }}">{{ $online_supplier->name}}</option>
            @endforeach
            @error('newSupplies.online_supplier_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </select>
    </div>

    <div>
        <label for="size" class="block text-sm font-medium text-gray-700">Size</label>
        <input type="text" wire:model="newSupplies.size" id="size" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

        @error('newSupplies.size') <span class="text-red-500">{{ $message }}</span>@enderror

    </div>

    <div>
        <label for="expiration_date" class="block text-sm font-medium text-gray-700">Expiration Date:</label>
        <input type="date" id="expiration_date" wire:model="newSupplies.expiration_date"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></input>
        @error('newSupplies.expiration_date') <span class="text-red-500">{{ $message }}</span>@enderror
    </div>

    <div class="sm:col-span-2">

            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose quantity:</label>
            <div class="relative flex items-center max-w-[8rem]">
                <button type="button" id="decrement-button" onclick="decrementQuantity()" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                    </svg>
                </button>
                <input type="text" wire:model="newSupplies.quantity" id="quantity" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="999" value="5" required />
                <button type="button" id="increment-button" onclick="incrementQuantity()" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                    </svg>
                </button>
                @error('newSupplies.quantity') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>
    </div>

    <div class="sm:col-span-2">
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
</div>
