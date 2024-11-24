@props([
    /** @var \mixed */
    'service'
])

<div {{ $attributes->class(['mx-auto w-80 min-w-[300px] mt-5 pb-20 transform overflow-hidden rounded-lg bg-white shadow-md duration-300 hover:scale-105 hover:shadow-lg']) }}>
    <img class="h-48 w-full object-cover object-center" src="{{ asset('storage/'. $service->image)}}"
         alt="Product Image"/>
    <div class="p-4">
        <h2 class="mb-2 text-lg font-medium text-gray-900">{{ $service->name }}</h2>
        <p class="mb-2 text-base text-gray-700">{{ $service->description }}</p>

        <div class="fixed pt-9 bottom-2 w-4/5">
            <div class="flex items-center mb-1">
                @if($service->deal_value)
                    <span class="text-red-500 font-semibold">
                        Deal: {{ $service->deal_value }}{{ $service->deal_type === 'percentage' ? '%' : '' }} off
                    </span>
                    <p class="text-gray-500 line-through ml-2">{{ number_format($service->price, 2) }}</p>
                    <p class="text-xl font-bold text-green-500 ml-2">{{ number_format($service->final_price ?? $service->price, 2) }}</p>
                @else
                    <p class="text-xl font-bold">{{ number_format($service->price, 2) }}</p>
                @endif
            </div>
            <a href="{{ route('view-service', ['slug' => $service->slug]) }}"><x-button>Book Now</x-button></a>
        </div>
    </div>
</div>
