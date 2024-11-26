<x-app-layout>
    <div class="bg-gray-100 py-8"
    x-data="{
        showCheckoutConfirmation: false,
        showRemoveConfirmation: false,
        cartServiceId: null,
        payMethod: '',
        proofOfPayment: null,
        referenceNumber: '',
        showError: false,
        validateAndSubmit() {
            if (!this.payMethod) {
                this.showError = 'Please select a payment method.';
                return;
            }
            if (this.payMethod === 'gcash') {
                if (!this.proofOfPayment) {
                    this.showError = 'Please upload proof of payment.';
                    return;
                }
                if (!this.referenceNumber || this.referenceNumber.length !== 4) {
                    this.showError = 'Please provide a valid 4-digit reference number.';
                    return;
                }
            }
            this.showError = false;
            $refs.checkoutForm.submit();
        },
        resetModal() {
            this.payMethod = '';
            this.proofOfPayment = null;
            this.referenceNumber = '';
            this.showError = false;
        }
    }"
>
   <div class="container mx-auto px-4 md:w-11/12">
            <h1 class="text-2xl font-semibold mb-4">Cart</h1>

            <!-- Success Message -->
            @if (session()->has('success'))
                <div class="px-4 py-2 text-white bg-green-500 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Unavailable Employee Slots Alert -->
            @if(session('unavailable_employees') && session('unavailable_employees')->isNotEmpty())
                <div x-show="showAlert" class="relative bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Some employee slots are no longer available. Please remove them from your cart to continue.</span>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                        @foreach(session('unavailable_employees') as $unavailable_employee)
                            <li>{{ $unavailable_employee['date'] }}:
                                {{ date('g:i a', strtotime($unavailable_employee['start_time'])) }} -
                                {{ date('g:i a', strtotime($unavailable_employee['end_time'])) }} :
                                {{ $unavailable_employee['first_name'] }}
                            </li>
                        @endforeach
                    </ul>
                    <button
                        class="absolute top-0 right-0 px-4 py-3"
                        @click="showAlert = false"
                    >
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1 1 0 01-1.415 0L10 11.415l-2.933 2.934a1 1 0 01-1.415-1.415l2.934-2.933-2.934-2.933a1 1 0 111.415-1.415L10 8.585l2.933-2.934a1 1 0 111.415 1.415L11.415 10l2.933 2.933a1 1 0 010 1.415z" />
                        </svg>
                    </button>
                </div>
            @endif


                    <!-- Remove Confirmation Modal -->

            <!-- Cart Content -->
                <!-- Cart Items -->
                <div x-data="{ showRemoveConfirmation: false, cartServiceId: null }" class="flex flex-col md:flex-row gap-4">
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
                                                <td class="py-4">
                                                    <button
                                                        type="button"
                                                        class="text-red-500 hover:text-red-600 font-semibold"
                                                        @click="showRemoveConfirmation = true; cartServiceId = {{ $service->pivot->id }}"
                                                    >
                                                        Remove
                                                    </button>
                                                </td>
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

                    <!-- Remove Confirmation Modal -->
                    <div x-show="showRemoveConfirmation" x-cloak class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center">
                        <div class="fixed inset-0 transition-opacity -z-10" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <div class="bg-white rounded-lg p-4 max-w-md mx-auto" @click.outside="showRemoveConfirmation = false">
                            <h2 class="text-xl font-semibold">Confirm Removal</h2>
                            <p>Are you sure you want to remove this item from the cart?</p>
                            <div class="mt-4 flex justify-end space-x-4">
                                <button @click="showRemoveConfirmation = false" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none">
                                    Cancel
                                </button>
                                <form x-ref="removeForm" method="post" :action="`{{ route('cart.remove-item', '') }}/${cartServiceId}`">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none">
                                        Confirm
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
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
                        <button @click="showCheckoutConfirmation = true" class="bg-purple-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Confirm</button>
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
                <img class="w-36 h-36"  src="{{ asset('images/qr.jpg')}}">
                <p class="font-sm text-red-500">If you pay in GCash. Please provide the Name and the last Four Digit in Reference Number</p>
                <div class="col-span-6 sm:col-span-4 my-2">
                <label for="Payment Name" >Payment Name:</label>
                <x-input id="payment_name" type="text" class="mt-1 block w-full" name="payment_name" />
                <x-input-error for="payment_name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 my-2">
            <label for="Payment Name" >Last Four Digit in Reference:</label>
            <x-input id="reference_no" type="text" class="mt-1 block w-full" name="reference_no" />
                <x-input-error for="reference_no" class="mt-2" />
            </div>
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
