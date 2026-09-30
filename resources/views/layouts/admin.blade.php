<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — EventTicket</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="w-56 bg-gray-900 text-gray-300 flex-shrink-0">
            <div class="px-5 py-5 border-b border-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">ET</div>
                <span class="font-bold text-white text-lg">EventTicket</span>
            </div>
            <nav class="p-3 space-y-1 text-sm">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.events.index') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.events.*') ? 'bg-gray-800 text-white' : '' }}">
                    Events
                </a>
                <a href="{{ route('admin.orders.index') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white' : '' }}">
                    Orders
                </a>
                <a href="{{ route('admin.tickets.check-in') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.tickets.*') ? 'bg-gray-800 text-white' : '' }}">
                    Check-in
                </a>
                <a href="{{ route('admin.customer-dashboard.edit') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('admin.customer-dashboard.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Tampilan Customer
                </a>
                <a href="{{ route('admin.banners.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium {{ request()->routeIs('admin.banners.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Slide Banner
                </a>

                <div class="border-t border-gray-800 my-3"></div>
                <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded-lg hover:bg-gray-800">
                    ← Frontend
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-800 text-red-400">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
