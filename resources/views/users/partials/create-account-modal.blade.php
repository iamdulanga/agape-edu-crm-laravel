<!-- Create Account Modal -->
<div id="create-account-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('create-account-modal')"></div>

        <!-- Modal panel -->
        <div class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6 sm:align-middle">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900">Create New Account</h3>
                        <button type="button" onclick="toggleModal('create-account-modal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Account Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Account Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" name="name" id="name" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                                    <input type="text" name="username" id="username" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" id="email" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                    <select name="role" id="role" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select a role</option>
                                        @php
                                            $currentUser = auth()->user();
                                            $ownerRole = \App\Models\Role::where('name', 'owner')->first();
                                            $ownerExists = $ownerRole && $ownerRole->users()->exists();
                                        @endphp
                                        
                                        @if($currentUser->hasRole('owner'))
                                            <option value="manager">Manager</option>
                                            <option value="counselor">Counselor</option>
                                        @elseif($currentUser->hasRole('manager'))
                                            <option value="counselor">Counselor</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Security Information -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Security</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                    <input type="password" name="password" id="password" required minlength="8"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200">
                            <button type="button" onclick="toggleModal('create-account-modal')"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create Account
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
