<div id="sidebar" class="w-64 flex flex-col h-full bg-gradient-to-b from-gray-800 to-gray-900 text-white shadow-xl">
    <!-- Header - Image eke wage -->
    <div class="p-6 border-b border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-white">CRM Panel</h2>
                <p class="text-gray-300 text-sm">Education Management</p>
            </div>
        </div>
    </div>

    <!-- Navigation - Image eke MAIN MENU section wage -->
    <nav class="flex-1 p-4">
        <div class="mb-6">
            <!-- MAIN MENU Heading -->
            <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-4 px-3">MAIN MENU</h3>

            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-3 py-3 rounded-lg transition-all duration-200
                              {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                        </svg>
                        <span class="font-medium flex-1">Dashboard</span>
                    </a>
                </li>

                <!-- Manage Leads -->
                <li>
                    <a href="{{ route('leads.index') }}"
                       class="flex items-center px-3 py-3 rounded-lg transition-all duration-200
                              {{ request()->routeIs('leads.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium flex-1">Manage Leads</span>
                    </a>
                </li>

                <!-- Manage Users (Roles based) -->
                @if(auth()->user()->hasRole('owner') || auth()->user()->hasRole('manager'))
                <li>
                    <a href="{{ route('users.index') }}"
                       class="flex items-center px-3 py-3 rounded-lg transition-all duration-200
                              {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                        </svg>
                        <span class="font-medium flex-1">Manage Users</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <!-- Reports Section (Commented for future use) -->
        {{--
        @if (in_array('owner', $userRoles) || in_array('manager', $userRoles))
        <div class="mb-6">
            <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold mb-4 px-3">REPORTS</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('reports.index') }}"
                       class="flex items-center px-3 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="font-medium">Reports</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif
        --}}
    </nav>

    <!-- User Info & Logout - Image eke rawindu section wage -->
    <div class="p-4 border-t border-gray-700 bg-gray-800">
        <!-- User Info -->
        <div class="flex items-center mb-4 px-2">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
            <div class="ml-3 min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-300 truncate">
                    @php
                        $role = auth()->user()->roles->first();
                    @endphp
                    {{ $role ? ucfirst($role->name) : 'User' }}
                </p>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-3 py-3 rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</div>
