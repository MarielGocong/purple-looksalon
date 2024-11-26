@php
    use App\Enums\UserRolesEnum;
    $role = UserRolesEnum::from(Auth::user()->role_id)->name;
@endphp
<x-dashboard>

    <div class="fixed top-5 right-4 z-50 space-y-4">
        @foreach ($nearExpirationSupplies as $supply)
            <div x-data="{ show: true }" x-show="show" x-transition
                 id="alert-expiration-{{ $supply->id }}"
                 class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800 shadow-lg"
                 role="alert">
              <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Warning: Expiring Soon</h3>
              </div>
              <div class="mt-2 mb-4 text-sm">
                Supply item <strong>{{ $supply->name }}</strong> from supplier is nearing its expiration date on <strong>{{ $supply->expiration_date }}</strong>. Please review and reorder if necessary.
              </div>
              <div class="flex">
                <a href="{{ route('managesupplies') }}?search={{ $supply->name }}" class="text-white bg-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                  <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                  </svg>
                  View Supply
                </a>
                <button @click="show = false"
                        type="button"
                        class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800"
                        aria-label="Close">
                  Dismiss
                </button>
              </div>
            </div>
        @endforeach
        @foreach ($lowQuantitySupplies as $supply)
        <div x-data="{ show: true }" x-show="show" x-transition
             id="alert-low-quantity-{{ $supply->id }}"
             class="p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400 dark:border-yellow-800 shadow-lg"
             role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Warning: Low Quantity</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
                Supply item <strong>{{ $supply->name }}</strong> has low quantity: <strong>{{ $supply->quantity }}</strong>. Please review and reorder if necessary.
            </div>
            <div class="flex">
                <a href="{{ route('managesupplies') }}?search={{ $supply->name }}" class="text-white bg-yellow-800 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                    <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                        <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                    </svg>
                    View Supply
                </a>
                <button @click="show = false"
                        type="button"
                        class="text-yellow-800 bg-transparent border border-yellow-800 hover:bg-yellow-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-yellow-600 dark:border-yellow-600 dark:text-yellow-500 dark:hover:text-white dark:focus:ring-yellow-800"
                        aria-label="Close">
                    Dismiss
                </button>
            </div>
        </div>
    @endforeach
    </div>

    <div class="p-4 sm:ml-64">
           <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">




    <!-- Booking Revenue This Month -->


        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
            <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
              <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="text-right">
              <p class="text-2xl">{{ $totalCustomers }}</p>
              <p>Customers</p>
            </div>
          </div>
        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
          <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
            <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          </div>
          <div class="text-right">
            <p class="text-2xl">{{ $totalEmployees }}</p>
            <p>Employees</p>
          </div>
        </div>


        {{-- <div class="bg-purple-500  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-600  text-white font-medium group">
          <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
            <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <div class="text-right">
            <p class="text-2xl">$75,257</p>
            <p>Balances</p>
          </div>
        </div> --}}
        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
          <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
            <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          </div>
          <div class="text-right">
            <p class="text-2xl">{{ $totalServices }}</p>
            <p>Services</p>
          </div>
        </div>
        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
          <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
            <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          </div>
          <div class="text-right">
            <p class="text-2xl">{{ $totalServicesActive }}</p>
            <p>Active Services</p>
          </div>
        </div>
        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
          <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
            <svg width="30" height="30" fill="none" viewBox="0 0 17 17" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/> <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/></svg>
          </div>
          <div class="text-right">
            <p class="text-2xl">{{ $totalUpcomingDeals }}</p>
            <p>Upcoming Deals</p>
          </div>
        </div>
        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
          <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
            <svg width="30" height="30" fill="none" viewBox="0 0 17 17" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/> <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/></svg>
          </div>
          <div class="text-right">
            <p class="text-2xl">{{ $totalOngoingDeals }}</p>
            <p>Ongoing Deals</p>
          </div>
        </div>

        <div class="bg-salonPurple  shadow-lg rounded-md flex items-center justify-between p-3 border-b-4 border-purple-400  text-white font-medium group">
            <div class="flex justify-center items-center w-14 h-14 bg-white rounded-full transition-all duration-300 transform group-hover:rotate-12">
                <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="stroke-current text-purple-800  transform transition-transform duration-500 ease-in-out"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div class="text-right">
                <p class="text-2xl">{{ $totalUpcomingAppointments }}</p>
                <p>Upcoming Appointments</p>
            </div>
        </div>
      </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const bookingRevenueData = @json($totals); // Monthly revenue data
            const months = @json($months); // Month names

            const options = {
                chart: {
                    maxHeight: "100%",
                    maxWidth: "100%",
                    type: "area",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: { enabled: false },
                    toolbar: { show: false },
                },
                tooltip: {
                    enabled: true,
                    x: { show: true },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        opacityFrom: 0.55,
                        opacityTo: 0,
                        shade: "#6a2695",
                        gradientToColors: ["#6a2695"],
                    },
                },
                dataLabels: { enabled: false },
                stroke: { width: 6 },
                grid: {
                    show: false,
                    strokeDashArray: 4,
                    padding: { left: 2, right: 2, top: 0 },
                },
                series: [{
                    name: "Booking Revenue",
                    data: bookingRevenueData,
                    color: "#6a2695",
                }],
                xaxis: {
                    categories: months,
                    labels: {
                        show: true,
                        rotate: -45,
                        style: {
                            colors: '#333',
                            fontSize: '12px',
                            fontWeight: 400,
                        },
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    show: true,
                    labels: {
                        formatter: function(value) {
                            return "₱" + value.toFixed(2); // Format as PHP currency
                        },
                    },
                },
            };

            if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("area-chart"), options);
                chart.render();
            }
        });
    </script>











<div class="px-4 pt-6">
    <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">
      <!-- Main widget -->
      <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-shrink-0">
            <span class="text-xl font-bold leading-none text-gray-900 sm:text-2xl dark:text-white">₱ {{ number_format($bookingRevenueThisMonth, 3) }}</span>
            <h3 class="text-base font-light text-gray-500 dark:text-gray-400">Sales Every Month</h3>
          </div>
          <div class="flex items-center justify-end flex-1 text-base
          {{ $percentageRevenueChangeLastMonth >= 0 ? 'text-green-500' : 'text-red-500' }} dark:text-green-500 text-center">

          {{ $percentageRevenueChangeLastMonth }}%
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
            </svg>
          </div>
        </div>
        <div id="area-chart"></div>
      </div>

      <!--Tabs widget -->
      <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <h3 class="flex items-center mb-4 text-lg font-semibold text-gray-900 dark:text-white">Top Customers
        <button data-popover-target="popover-description" data-popover-placement="bottom-end" type="button"><svg class="w-4 h-4 ml-2 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg><span class="sr-only">Show information</span></button>
        </h3>
        <div data-popover id="popover-description" role="tooltip" class="absolute z-10 invisible inline-block text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
          <div class="p-3 space-y-2">
              <h3 class="font-semibold text-gray-900 dark:text-white">Top Customer</h3>
              <p>Statistics is a branch of applied mathematics that involves the collection, description, analysis, and inference of conclusions from quantitative data.</p>
              <a href="#" class="flex items-center font-medium text-primary-600 dark:text-primary-500 dark:hover:text-primary-600 hover:text-primary-700">Read more <svg class="w-4 h-4 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></a>
          </div>
          <div data-popper-arrow></div>
        </div>


        <div id="fullWidthTabContent" class="border-t border-gray-200 dark:border-gray-600">
            <div id="faq" role="tabpanel" aria-labelledby="faq-tab">
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($topCustomers as $customer)
                    <li class="py-3 sm:py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}" alt="{{ $customer->name }} image">
                            </div>
                            <div class="flex-1 min-w-0 ms-4">
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                    {{ $customer->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                    {{ $customer->email }}
                                </p>
                            </div>
                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                ₱{{ number_format($customer->total_revenue, 2) }}
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- Card Footer -->




        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div id="serviceCategoryRevenueChart"></div>
a

            <script>
                // Data from Laravel
                const serviceCategoryRevenue = @json($serviceCategoryRevenue);

                // Prepare data for the chart
                const categories = serviceCategoryRevenue.map(item => item.category_name);
                const revenues = serviceCategoryRevenue.map(item => parseFloat(item.total_revenue));

                // Configure and render the chart
                const options = {
                    chart: { type: 'bar', height: 350 },
                    series: [{ name: 'Revenue', data: revenues }],
                    xaxis: { categories },
                    yaxis: { title: { text: 'Revenue ($)' } },
                    title: { text: 'Revenue by Service Category', align: 'center' },
                    colors: ['#4CAF50'], // Customize bar color
                    tooltip: { y: { formatter: value => `$${value.toFixed(2)}` } }
                };

                const chart = new ApexCharts(document.querySelector("#serviceCategoryRevenueChart"), options);
                chart.render();
            </script>


        </div>

        <div class="max-w-sm w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">

            <div class="flex justify-between items-start w-full">
                <div class="flex-col items-center">
                  <div class="flex items-center mb-1">
                      <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white me-1">Top 3 Grossing Service</h5>

                </div>


              </div>

            </div>

            <div class="py-4">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="flex items-center justify-between">
                        <label for="year" class="text-sm text-gray-700 dark:text-gray-200">Select Year:</label>
                        <select name="year" id="year" class="form-select text-sm text-gray-500 dark:text-gray-400" onchange="this.form.submit()">
                            @for ($i = 2020; $i <= \Carbon\Carbon::now()->year; $i++)
                                <option value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>

            <!-- Pie Chart -->
            <div class="py-6" id="pie-chart"></div>

            <!-- Display Top 3 Grossing Services -->
            <div class="py-6">
                <h6 class="text-lg font-semibold">Top Grossing Services for {{ $selectedYear }}</h6>
                <ul>
                    @foreach ($topGrossingServices as $service)
                        <li>{{ $service->service_name }} - ₱{{ number_format($service->total_earnings, 2) }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

        <script>
            const getChartOptions = () => {
                return {
                    series: @json($topGrossingServices->pluck('total_earnings')),
                    labels: @json($topGrossingServices->pluck('service_name')),
                    chart: {
                        height: 420,
                        width: "100%",
                        type: "pie",
                    },
                    stroke: {
                        colors: ["white"],
                    },
                    plotOptions: {
                        pie: {
                            labels: {
                                show: true,
                            },
                            size: "100%",
                            dataLabels: {
                                offset: -25
                            }
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                        },
                    },
                    legend: {
                        position: "bottom",
                        fontFamily: "Inter, sans-serif",
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return   "₱" + value
                            },
                        },
                    },
                    xaxis: {
                        labels: {
                            formatter: function (value) {
                                return "₱" + value
                            },
                            axisTicks: {
                                show: false,
                            },
                            axisBorder: {
                                show: false,
                            },
                        },
                    },
                }
            }

            if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
                chart.render();
            }
        </script>



      </div>
    </div>

    </div>
</div>






</x-dashboard>
