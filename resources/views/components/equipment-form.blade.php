<div class="grid gap-4 mb-4 sm:grid-cols-2">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700"> Name:</label>
        <input type="text" wire:model="newEquipment.name" id="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('newEquipment.name') <span class="text-red-500">{{ $message }}</span>@enderror
    </div>
    <div>
        <label for="brand_name" class="block text-sm font-medium text-gray-700">Brand Name:</label>
        <input type="text" wire:model="newEquipment.brand_name" id="brand_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        @error('newEquipment.brand_name') <span class="text-red-500">{{ $message }}</span>@enderror
    </div>

    <div>
        <label for="last_maintenance" class="block text-sm font-medium text-gray-700">Last Maintenance</label>
        <input type="date" wire:model="newEquipment.last_maintenance" id="last_maintenance" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

        @error('newEquipment.last_maintenance') <span class="text-red-500">{{ $message }}</span>@enderror

    </div>
    <div>
        <label for="next_maintenance" class="block text-sm font-medium text-gray-700">Next Maintenance</label>
        <input type="date" wire:model="newEquipment.next_maintenance" id="next_maintenance" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

        @error('newEquipment.next_maintenance') <span class="text-red-500">{{ $message }}</span>@enderror

    </div>

    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>

        <select wire:model="newEquipment.category_id" id="category_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option disabled selected value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name}}</option>
            @endforeach
            @error('newEquipment.category_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </select>
    </div>


    <div>
        <label for="employee_id" class="block text-sm font-medium text-gray-700">Assign Staff</label>

        <select wire:model="newEquipment.employee_id" id="employee_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option disabled selected value="">Select Employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->first_name}}</option>
            @endforeach
            @error('newEquipment.employee_id') <span class="text-red-500">{{ $message }}</span>@enderror
        </select>
    </div>



    <div>
        <label for="purchased_date" class="block text-sm font-medium text-gray-700">Purchased Date:</label>
        <input type="date" id="purchased_date" wire:model="newEquipment.purchased_date"  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></input>
        @error('newEquipment.purchased_date') <span class="text-red-500">{{ $message }}</span>@enderror
    </div>




<div class="sm:col-span-2">
    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose quantity:</label>
    <div class="relative flex items-center max-w-[8rem]">
        <button type="button" id="decrement-button" data-input-counter-decrement="quantity-input" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
            </svg>
        </button>
        <input type="text" wire:model="newEquipment.quantity" id="quantity" data-input-counter data-input-counter-min="1" data-input-counter-max="50" aria-describedby="helper-text-explanation" class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="999" value="5" required />
        <button type="button" id="increment-button" data-input-counter-increment="quantity-input" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
            </svg>
        </button>
        @error('newEquipment.quantity') <span class="text-red-500">{{ $message }}</span>@enderror

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
