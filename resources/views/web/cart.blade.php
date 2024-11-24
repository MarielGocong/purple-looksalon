<x-app-layout>
    <div class="bg-gray-100 py-8" x-data="{ showCheckoutConfirmation: false }">
        <div class="container mx-auto px-4 md:w-11/12">
            <h1 class="text-2xl font-semibold mb-4">Cart</h1>

            <div class="mt-4">
                @if (session()->has('success'))
                    <div class="px-4 py-2 text-white bg-green-500 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            @if(session('unavailable_employees'))


                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">The following employee slots are no longer available. Please remove them from your cart to continue.</span>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                        @foreach(session('unavailable_employees') as $unavailable_employee)
                            <li>{{ $unavailable_employee['date'] }}:
                                {{ date('g:i a', strtotime($unavailable_employee['start_time'])) }} -
                                {{ date('g:i a', strtotime($unavailable_employee['end_time'])) }} :
                                {{ $unavailable_employee['first_name'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-4">
                <div class="md:w-3/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="text-left font-semibold">Service</th>
                                    <th class="text-left font-semibold">Price</th>
                                    <th class="text-left font-semibold">Date</th>
                                    <th class="text-left font-semibold">Time Slot</th>
                                    <th class="text-left font-semibold">Staff Assigned</th>
                                    <th class="text-left font-semibold"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($cart->services) && $cart->services->isNotEmpty())
                                    @foreach($cart->services as $service)
                                        <tr>
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <img class="h-16 w-16 mr-4" src="{{ '/storage/' . $service->image }}" alt="{{ $service->name . ' image'}}">
                                                    <span class="font-semibold"> {{ $service->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">PHP {{ number_format($service->pivot->price, 2, '.', ',') }}</td>
                                            <td class="py-4">{{ $service->pivot->date }}</td>
                                            <td class="py-4">
                                                {{ date('g:i a', strtotime($service->pivot->time)) }}
                                            </td>
                                            <td class="py-4">{{ $service->pivot->first_name }}</td>
                                            <form action="{{ route('cart.remove-item', ['cart_service_id' => $service->pivot->id]) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <td class="py-4">
                                                    <button type="submit" class="text-red-500 hover:text-red-600 font-semibold">Remove</button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center pt-8">No items in cart</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="md:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold mb-4">Summary</h2>
                        <div class="flex justify-between mb-2">
                            <span>Subtotal</span>
                            <span>PHP {{ number_format($cart?->total, 2, '.', ',') }}</span>
                        </div>

                        <hr class="my-2">
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">Total</span>
                            <span class="font-semibold">PHP {{ number_format($cart?->total, 2, '.', ',') }}</span>
                        </div>
                        <button @click="showCheckoutConfirmation = true" class="bg-purple-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Confirmation Modal -->
        <div x-show="showCheckoutConfirmation" x-cloak class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center">
            <div class="fixed inset-0 transition-opacity -z-10" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="bg-white rounded-lg p-4 max-w-md mx-auto" @click.outside="showCheckoutConfirmation = false">
                <h2 class="text-xl font-semibold">Confirm Checkout</h2>
                <p>Are you sure you want to checkout?</p>
                <div class="mt-4 flex justify-end space-x-4">
                    <button @click="showCheckoutConfirmation = false" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <form action="{{ route('cart.checkout') }}" method="post">
                        @csrf
                        <button class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none">
                            Confirm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
