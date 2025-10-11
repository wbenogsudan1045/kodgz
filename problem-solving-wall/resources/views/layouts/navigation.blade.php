<!-- navigation.blade.php -->
<nav x-data="{ open: false }" class="nav-bg-green" style="background-color: #86efac;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">

            <!-- Left Section (Logo Only) -->
            <div class="flex items-center space-x-6">
                <!-- Make-shift Logo (Always GS as Link) -->
                <a href="{{ route('dashboard') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-md font-bold text-gray-800 hover:bg-yellow-400 transition"
                    style="background-color: #fde68a;">
                    GS
                </a>
            </div>

            <!-- Center Section (Search Bar) -->
            <div class="flex flex-1 justify-center px-6">
                <div class="relative w-full max-w-md">
                    <!-- Icon -->
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <!-- Input -->
                    <form action="{{ route('boards.search') }}" method="GET" class="relative w-full max-w-md">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>

                        <input type="text" name="q" placeholder="Search boards..." class="w-full pl-12 pr-4 py-2 rounded-full text-gray-800 text-sm
                  placeholder-gray-500 border-0 focus:outline-none focus:ring-2 focus:ring-green-500"
                            style="background-color: #fef3c7;" />
                    </form>

                </div>
            </div>

            <!-- Right Section (Icons) -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <!-- Notifications Dropdown -->
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="relative p-2 hover:bg-green-400 hover:bg-opacity-50 rounded-full transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span
                                    class="absolute top-1 right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="max-h-64 overflow-y-auto space-y-2">
                            @forelse(auth()->user()->notifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                            class="block px-3 py-2 rounded-md text-sm transition 
                                {{ $notification->read_at ? 'bg-yellow-50 text-gray-700' : 'bg-yellow-200 font-semibold text-black' }} hover:bg-yellow-300">
                                            <p>{{ $notification->data['message'] }}</p>
                                            <p class="text-xs text-gray-600">
                                                Board: {{ $notification->data['board_name'] }} ·
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </a>
                            @empty
                                <p class="text-sm text-gray-600 px-3 py-2">No notifications yet.</p>
                            @endforelse
                        </div>
                    </x-slot>
                </x-dropdown>



                <!-- User Dropdown -->
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="p-2 hover:bg-green-400 hover:bg-opacity-50 rounded-full transition">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-600">
                                {{ Auth::user()->name }}
                            </div>
                            <hr class="my-1">
                            <!-- ✅ Updated to go to profile.show -->
                            <x-dropdown-link :href="route('profile.show')" class="text-sm">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-sm"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="p-2 hover:bg-green-400 hover:bg-opacity-50 rounded-full transition">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background-color: #a7f3d0;">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-gray-700">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>