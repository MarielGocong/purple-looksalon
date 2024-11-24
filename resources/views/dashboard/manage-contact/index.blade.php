<x-dashboard>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200  mt-2">
            <h2 class="text-2xl font-bold">Contact Messages</h2>
        <div class="flex justify-between mx-7">


    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full bg-white border border-gray-200 rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-4 text-left">Name</th>
                <th class="p-4 text-left">Email</th>
                <th class="p-4 text-left">Subject</th>
                <th class="p-4 text-left">Message</th>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Action</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($concerns as $concern)
                <tr class="border-b">
                    <td class="p-4">{{ $concern->name }}</td>
                    <td class="p-4">{{ $concern->email }}</td>
                    <td class="p-4">{{ $concern->subject }}</td>
                    <td class="p-4">{{ $concern->message }}</td>
                    <td class="p-4">{{ $concern->created_at->format('d-m-Y') }}</td>
                    <td>
                        <x-button wire:click="#" wire:loading.attr="disabled" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80  rounded-lg text-xs px-4 py-2 inline-flex items-center me-1 mb-2">
                            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Delete
                        </x-button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">No contact messages found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $concerns->links() }}
    </div>
</div>
</x-dashboard>
