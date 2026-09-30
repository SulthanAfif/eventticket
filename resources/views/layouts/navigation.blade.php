<nav class="bg-white border-b border-gray-100 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('events.index') }}" class="flex items-center gap-2 shrink-0">
                    <x-application-logo class="w-9 h-9" />
                    <span class="font-bold text-lg text-gray-900 tracking-tight">EventTicket</span>
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        Events
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            My Orders
                        </x-nav-link>
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                                Admin
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-gray-900 rounded-lg hover:bg-gray-50">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900 px-3 py-2">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Daftar</a>
                @endif
            </div>

            {{-- Mobile menu button --}}
            <div class="flex items-center sm:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div x-show="open" x-cloak class="absolute top-16 inset-x-0 bg-white border-b shadow-lg p-4 space-y-2 sm:hidden">
                    <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-50">Events</a>
                    @auth
                        <a href="{{ route('orders.index') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-50">My Orders</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-50">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-gray-50">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm bg-indigo-600 text-white text-center">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
