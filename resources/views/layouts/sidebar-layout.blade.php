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
        <aside >
            @include('layouts.sidebar')
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">

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
