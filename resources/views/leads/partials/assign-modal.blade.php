<div id="assign-modal-{{ $lead->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             onclick="toggleModal('assign-modal-{{ $lead->id }}')"></div>

        <div class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6 sm:align-middle">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Assign {{ $lead->first_name }} {{ $lead->last_name }}
            </h3>

            <form action="{{ route('leads.assign', $lead) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign to:</label>
                    <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select User</option>
                        @if(isset($assignableUsers) && $assignableUsers->count())
                            @foreach($assignableUsers as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} 
                                    @if($user->roles->count())
                                        ({{ $user->roles->first()->name }})
                                    @endif
                                </option>
                            @endforeach
                        @else
                            <!-- Fallback if no assignable users -->
                            <option value="">No users available</option>
                        @endif
                    </select>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        Assign
                    </button>
                    <button type="button" 
                            onclick="toggleModal('assign-modal-{{ $lead->id }}')"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>