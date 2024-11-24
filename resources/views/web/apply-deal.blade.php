<x-layout-app>


    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Apply Deal: {{ $deal->name }}</h1>

        <div class="bg-white p-6 border rounded-lg shadow max-w-md mx-auto">
            <h2 class="text-lg font-semibold">{{ $service->name }}</h2>
            <p class="text-gray-600">Original Price: ${{ number_format($service->price, 2) }}</p>
            <p class="text-green-600">Discounted Price: ${{ number_format($discountedPrice, 2) }}</p>

            <p class="mt-4">{{ $deal->description }}</p>
            <p class="text-purple-600 font-semibold">Discount: {{ $deal->discount }}%</p>

            <a href="{{ route('services.book', $service->id) }}" class="inline-flex items-center px-4 py-2 mt-4 text-white bg-purple-500 rounded hover:bg-purple-800">
                Book Now with Discount
            </a>
        </div>
    </div>
</x-layout-app>
