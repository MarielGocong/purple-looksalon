@php
    $userRole = Auth::User()?->role()->first()->name;
@endphp
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 z-50 sticky top-0">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 sticky z-50">
        <div class="flex justify-between h-24">
            <div class="flex">
                <!-- Logo -->
               @if(isset($mainLogoRoute))
                    @php
                        {{ $appMarkRoute = $mainLogoRoute;}}
                    @endphp
                @else
                    @php
                        {{ $appMarkRoute = route('dashboard'); }}
                    @endphp

               @endif


                <div class="shrink-0 flex items-center">
                    <a href="{{ $appMarkRoute }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>


            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6">

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                        {{ __('About') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
                        {{ __('Contact') }}
                    </x-nav-link>

                   <!-- Navigation Links for cust facing web-->

                    <x-web.navlinks />

                    <!-- Navigation Links form pages-->
                    @if (isset( $navlinks))
                    {{ $navlinks }}
                    @endif

                    <!-- Auth Navigation Links -->
                    @auth
                    @if($userRole == 'Customer')
                    <x-nav-link href="{{ route('customerview') }}" :active="request()->routeIs('dashboard/customer/view/appointment')">
                        {{ __('My Appointment') }}
                    </x-nav-link>
                    @endif
                    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <div class="ml-3 relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" type="button" class="relative rounded-full p-1 text-gray-600 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 inline-block w-3 h-3 rounded-full bg-red-600"></span>
                            @endif
                        </button>

                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-2">
                                <p class="text-gray-700 px-4 py-2 font-semibold">Notifications</p>
                                <hr class="my-2">

                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.redirectToAppointment', $notification->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $notification->read_at ? '' : 'bg-blue-100' }}">
                                        {{ $notification->data['message'] }}
                                    </a>
                                @empty
                                    <p class="block px-4 py-2 text-sm text-gray-700">No new notifications</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    @endif
                    @else

                    <a href="{{ route('login') }}" class="text-salonPurple hover:text-white border border-salonPurple hover:bg-salonPurple focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-1 mb-2 dark:border-purple-400 dark:text-purple-400 dark:hover:text-white dark:hover:bg-purple-500 dark:focus:ring-purple-900">Login</a>


                    <a href="{{ route('register') }}" class="focus:outline-none text-white bg-salonPurple hover:bg-secondary focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-secondary dark:hover:bg-purple-700 dark:focus:ring-purple-900">Register</a>

                    @endif

                </div>


                @auth


                <!-- Settings Dropdown -->
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center  px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        @endif
                                        <div class="ml-2">
                                            {{ Auth::user()->name }}
                                        </div>
                                             <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>

                        </x-slot>

                        <x-slot name="content">
                            @if($userRole == 'Customer')

                            <x-dropdown-link href="{{ route('cart') }}">
                                {{ __('Cart') }}
                            </x-dropdown-link>
                            <x-dropdown-link href="{{ route('customerview') }}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-secondary text-gray-600 hover:text-gray-800 border-l-4 border-transparent hover:border-purple-500 pr-6
                            {{ request()->is('dashboard/customer/view/appointment') ? 'bg-gray-100 border-purple-500 text-gray-900 font-semibold' : '' }}
                            ">
                                {{ __('My Appointments') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>
                            @endif
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('about') }}" :active="request()->routeIs('about')">
                {{ __('About') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('contact') }}" :active="request()->routeIs('contact')">
                {{ __('Contact') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('services') }}" :active="request()->routeIs('services')">
                {{ __('Services') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                {{ __('Sign In') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                {{ __('Register') }}
            </x-responsive-nav-link>
            @if($userRole == 'Employee')
            <x-responsive-nav-link href="{{ route('manageservices') }}" :active="request()->routeIs('manageservices')">
                {{ __('Manage Services') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('manageequipments') }}" :active="request()->routeIs('manageequipments')">
                {{ __('Manage Equipments') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('manageonlinesuppliers') }}" :active="request()->routeIs('manageonlinesuppliers')">
                {{ __('Manage Supplies') }}
            </x-responsive-nav-link>
            @endif

            @if($userRole == 'Admin')
            <x-responsive-nav-link href="{{ route('manageusers') }}" :active="request()->routeIs('manageusers')">
                {{ __('Manage Users') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('manageappointments') }}" :active="request()->routeIs('manageappointments')">
                {{ __('Manage Appointment') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('manageservices') }}" :active="request()->routeIs('manageservices')">
                {{ __('Manage Services') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('managecategories') }}" :active="request()->routeIs('managecategories')">
                {{ __('Manage Categories') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('manageemployees') }}" :active="request()->routeIs('manageemployees')">
                {{ __('Manage Employees') }}
            </x-responsive-nav-link>

            <div class="pt-4 pb-1 border-t border-gray-200">
                <x-responsive-nav-link href="{{ route('manageequipments') }}" :active="request()->routeIs('manageequipments')">
                    {{ __('Manage Equipments') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('manageonlinesuppliers') }}" :active="request()->routeIs('manageonlinesuppliers')">
                    {{ __('Manage Supplies') }}
                </x-responsive-nav-link>

            </div>

            <div class="pt-4 pb-1 border-t border-gray-200">

            </div>
            @endif


        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>




                </div>
            @endif
        </div>
    </div>
</nav>
