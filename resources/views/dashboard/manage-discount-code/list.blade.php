<x-dashboard>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200  mt-2">
        <div class="flex justify-between mx-7">
            <h2 class="text-2xl font-bold">MANAGE  DISCOUNT CODE</h2>


        <a href="{{route('managediscountcode.add')}}"   class="px-5 py-2 text-white bg-purple-500 rounded-md hover:bg--600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>

            Create
        </a>
        </div>

        <div class="mt-4">
            @if (session()->has('success'))
                <div class="px-4 py-2 text-white bg-green-500 rounded-md">
                    {{ session('success') }}
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

            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 overflow-x-scroll min-w-screen">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="pl-6 py-4 font-large text-gray-900">Id</th>
                  <th scope="col" class="px-6 py-4 font-large text-gray-900">Name</th>
                  <th scope="col" class="px-4 py-4 font-large text-gray-900">Code</th>
                  <th scope="col" class="px-6 py-4 font-large text-gray-900">Created by</th>
                  <th scope="col" class="px-6 py-4 font-large text-gray-900">Status</th>
                  <th scope="col" class="px-6 py-4 font-large text-gray-900">Created Date</th>
                  <th scope="col" class="px-6 py-4 font-large text-gray-900">Action</th>
                  <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 border-t border-gray-100">

            @foreach ($getRecord as $value)
            <tr class="hover:bg-gray-50">
                <td class="pl-6 py-4  max-w-0">{{ $value->id }}</td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-900">{{ $value->name}}</div>
                </td>

                <td class="px-6 py-4 max-w-0">
                    <div class="'font-medium text-gray-900" >{{ $value->code }}</div>
                </td>

                <td class="px-6 py-4 max-w-0">
                    <div class="'font-medium text-gray-900" >{{ $value->created_by_name }}</div>
                </td>

                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-700">{{ ($value->status == 0) ? 'Active' : 'Inactive' }}</div>
                </td>


                <td class="px-6 py-4  max-w-0">
                    <div class="font-medium text-gray-700">{{ date('d-m-Y', strtotime($value->created_at)) }}</div>
                </td>

                <td>
                    <div class="my-4 gap-0 ">
                        <x-button href="{{route('managediscountcode.edit')}}" wire:loading.attr="disabled" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                            Edit
                        </x-button>
                        <x-button href="{{route('managediscountcode.delete')}}"  wire:loading.attr="disabled" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Delete
                        </x-button>
                    </div>
                </td>
            </tr>
            @endforeach



          </tbody>
        </table>

        </div>
        </div>

</x-dashboard>
