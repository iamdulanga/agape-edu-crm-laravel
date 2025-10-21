<div id="add-lead-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             onclick="toggleModal('add-lead-modal')"></div>

        <div class="relative inline-block transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl sm:p-6 sm:align-middle">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900">Add New Lead</h3>
                        <button type="button" onclick="toggleModal('add-lead-modal')" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('leads.store') }}" enctype="multipart/form-data" class="space-y-6" id="add-lead-form">
                        <div id="form-warning" class="hidden mb-4 rounded-md bg-yellow-50 border border-yellow-200 px-4 py-3 text-yellow-800 text-sm"></div>
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Personal Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Age *</label>
                                    <input type="number" name="age" value="{{ old('age') }}" min="1" max="100" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                    <input type="text" name="city" value="{{ old('city') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Contact Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Academic Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Study Level *</label>
                                    <select name="study_level" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Study Level</option>
                                        <option value="foundation" {{ old('study_level') == 'foundation' ? 'selected' : '' }}>Foundation</option>
                                        <option value="diploma" {{ old('study_level') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="bachelor" {{ old('study_level') == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
                                        <option value="master" {{ old('study_level') == 'master' ? 'selected' : '' }}>Master</option>
                                        <option value="phd" {{ old('study_level') == 'phd' ? 'selected' : '' }}>PhD</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Passport *</label>
                                    <select name="passport" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Passport Status</option>
                                        <option value="yes" {{ old('passport') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('passport') == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Universities *</label>
                                    <textarea name="preferred_universities" rows="3" required placeholder="Enter preferred universities separated by commas"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('preferred_universities') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Additional Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                                    <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Priority</option>
                                        <option value="very_high" {{ old('priority') == 'very_high' ? 'selected' : '' }}>🔴 Very High</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🟠 High</option>
                                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                                        <option value="very_low" {{ old('priority') == 'very_low' ? 'selected' : '' }}>🔵 Very Low</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Inquiry Date *</label>
                                    <input type="date" name="inquiry_date" value="{{ old('inquiry_date', date('Y-m-d')) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Notes</label>
                                    <textarea name="special_notes" rows="3" placeholder="Any additional notes or comments about the student"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('special_notes') }}</textarea>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Student Picture (optional)</label>
                                    <div class="flex items-center space-x-4">
                                        <input type="file" name="avatar" id="avatar" accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or GIF (MAX. 2MB)</p>
                                    @error('avatar')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-200">
                            <button type="button"
                                    onclick="toggleModal('add-lead-modal')"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <span class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create Lead
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    function capitalizeAfterComma(str) {
        return str.replace(/(^|,\s*)([a-zA-Z])/g, function(match, p1, p2) {
            return p1 + p2.toUpperCase();
        });
    }
    document.getElementById('add-lead-form').addEventListener('submit', function(e) {
        var warning = document.getElementById('form-warning');
        var firstName = document.querySelector('input[name="first_name"]');
        var lastName = document.querySelector('input[name="last_name"]');
        var age = document.querySelector('input[name="age"]');
        var city = document.querySelector('input[name="city"]');
        var email = document.querySelector('input[name="email"]');
        var phone = document.querySelector('input[name="phone"]');
        var preferredUniversities = document.querySelector('textarea[name="preferred_universities"]');
        var specialNotes = document.querySelector('textarea[name="special_notes"]');
        var priority = document.querySelector('select[name="priority"]').value;
        var studyLevel = document.querySelector('select[name="study_level"]').value;
        var passport = document.querySelector('select[name="passport"]').value;
        var inquiryDate = document.querySelector('input[name="inquiry_date"]').value;
        var errors = [];

        // Capitalize first letters
        firstName.value = capitalizeFirst(firstName.value.trim());
        lastName.value = capitalizeFirst(lastName.value.trim());
        city.value = capitalizeFirst(city.value.trim());
        if (specialNotes.value) specialNotes.value = capitalizeFirst(specialNotes.value.trim());
        preferredUniversities.value = capitalizeAfterComma(preferredUniversities.value.trim());

        // Validate first name and last name (no numbers)
        if (/\d/.test(firstName.value)) errors.push('First Name cannot contain numbers.');
        if (/\d/.test(lastName.value)) errors.push('Last Name cannot contain numbers.');
        // Validate age
        if (!/^\d+$/.test(age.value) || parseInt(age.value) > 100 || parseInt(age.value) < 1) errors.push('Age must be a number between 1 and 100.');
        // Validate city (no numbers)
        if (/\d/.test(city.value)) errors.push('City cannot contain numbers.');
        // Validate email
        if (!email.value.includes('@') || !email.checkValidity()) errors.push('Please enter a valid email address.');
        // Validate phone (only numbers)
        if (!/^\d+$/.test(phone.value)) errors.push('Phone number can only contain numbers.');
        // Validate preferred universities (no numbers)
        if (/\d/.test(preferredUniversities.value)) errors.push('Preferred Universities cannot contain numbers.');
        // Required selects
        if (!priority || !studyLevel || !passport || !inquiryDate) errors.push('Please fill all required fields.');

        if (errors.length > 0) {
            e.preventDefault();
            warning.textContent = errors.join(' ');
            warning.classList.remove('hidden');
            window.scrollTo({ top: warning.offsetTop - 100, behavior: 'smooth' });
        } else {
            warning.classList.add('hidden');
        }
    });
</script>
