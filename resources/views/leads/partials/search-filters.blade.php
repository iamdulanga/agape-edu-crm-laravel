<div class="mb-6 bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl shadow-lg border border-blue-100">
    <form action="{{ route('leads.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Search Term -->
        <div>
            <label class="block text-sm font-semibold text-blue-700 mb-1">Search</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name, email, phone..." 
                       class="pl-10 pr-4 py-2 w-full rounded-lg border border-blue-200 bg-white/80 shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition placeholder-blue-300 text-blue-900 font-medium outline-none">
            </div>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-semibold text-blue-700 mb-1">Status</label>
            <div class="relative">
                <select name="status" class="block w-full rounded-lg border border-blue-200 bg-white/80 py-2 pl-3 pr-8 shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition text-blue-900 font-medium outline-none appearance-none">
                    <option value="all">All Statuses</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>
        </div>

        <!-- Priority Filter -->
        <div>
            <label class="block text-sm font-semibold text-blue-700 mb-1">Priority</label>
            <div class="relative">
                <select name="priority" class="block w-full rounded-lg border border-blue-200 bg-white/80 py-2 pl-3 pr-8 shadow focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition text-blue-900 font-medium outline-none appearance-none">
                    <option value="all">All Priorities</option>
                    <option value="very_high" {{ request('priority') == 'very_high' ? 'selected' : '' }}>Very High</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="very_low" {{ request('priority') == 'very_low' ? 'selected' : '' }}>Very Low</option>
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-blue-500 text-white px-5 py-2 rounded-lg shadow hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 font-semibold transition">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/></svg>
                Search
            </button>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center bg-gradient-to-r from-gray-400 to-gray-500 text-white px-5 py-2 rounded-lg shadow hover:from-gray-500 hover:to-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 font-semibold transition">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Clear
            </a>
        </div>
    </form>
</div>