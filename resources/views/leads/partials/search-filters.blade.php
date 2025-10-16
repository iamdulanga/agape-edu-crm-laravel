<div class="mb-6 bg-white p-4 rounded-lg shadow">
    <form action="{{ route('leads.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search Term -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Name, email, phone..." 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="all">All Statuses</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <!-- Priority Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Priority</label>
            <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="all">All Priorities</option>
                <option value="very_high" {{ request('priority') == 'very_high' ? 'selected' : '' }}>Very High</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="very_low" {{ request('priority') == 'very_low' ? 'selected' : '' }}>Very Low</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Search
            </button>
            <a href="{{ route('leads.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Clear
            </a>
        </div>
    </form>
</div>