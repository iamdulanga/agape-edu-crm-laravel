<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AGAPE EDU CRM')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="font-sans antialiased bg-gray-100 h-screen overflow-hidden">

    <!-- Full Page Wrapper -->
    <div class="flex h-full w-full">

        <!-- Sidebar (Fixed Width) -->
        <aside class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 text-white flex flex-col">
            @include('layouts.sidebar')
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- Top Navbar -->
            <nav class="bg-white shadow-sm border-b px-6 py-4 flex justify-between items-center sticky top-0 z-20">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">AGAPE EDU CRM</a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('leads.index') }}" class="text-gray-600 hover:text-gray-900">Leads</a>
                    @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('manager'))
                        <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-900">Users</a>
                    @endif
                    <span class="text-gray-700">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900">Logout</button>
                    </form>
                </div>
            </nav>

            <!-- Header (Optional) -->
            @hasSection('header')
                <header class="bg-white shadow-sm px-6 py-4">
                    @yield('header')
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 p-6 bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
