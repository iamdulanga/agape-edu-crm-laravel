@extends('layouts.app')

@section('title', 'Edit Lead')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Lead</h1>
            <p class="mt-1 text-sm text-gray-500">Update lead information</p>
        </div>
        <div>
            <a href="{{ route('leads.index') }}"
               class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="overflow-hidden rounded-xl bg-white shadow ">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Lead Information</h3>
                <p class="mt-1 text-sm text-gray-500">Update lead details and assignment.</p>
            </div>

            <form method="POST" action="{{ route('leads.update', $lead) }}" class="bg-white">
                @csrf
                @method('PATCH')

                <div class="px-6 py-6 grid grid-cols-1 gap-y-6 gap-x-8 sm:grid-cols-2">
                    <!-- Personal Information -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $lead->first_name) }}" required
                               class="mt-1 block w-full rounded-md border {{ $errors->has('first_name') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $lead->last_name) }}" required
                               class="mt-1 block w-full rounded-md border {{ $errors->has('last_name') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}"
                               class="mt-1 block w-full rounded-md border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}"
                               class="mt-1 block w-full rounded-md border {{ $errors->has('phone') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div

                    <!-- Demographics -->
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" name="age" id="age" value="{{ old('age', $lead->age) }}" min="1" max="100"
                               class="mt-1 block w-full rounded-md border {{ $errors->has('age') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('age') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $lead->city) }}"
                               class="mt-1 block w-full rounded-md border {{ $errors->has('city') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Travel & Inquiry -->
                    <div>
                        <label for="passport" class="block text-sm font-medium text-gray-700">Passport</label>
                        <select name="passport" id="passport"
                                class="mt-1 block w-full rounded-md border {{ $errors->has('passport') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:text-sm">
                            <option value="">Select</option>
                            <option value="yes" {{ old('passport', $lead->passport) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ old('passport', $lead->passport) == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('passport') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="inquiry_date" class="block text-sm font-medium text-gray-700">Inquiry Date</label>
                        <input type="date" name="inquiry_date" id="inquiry_date" value="{{ old('inquiry_date', $lead->inquiry_date ? $lead->inquiry_date->format('Y-m-d') : '') }}"
                               class="mt-1 block w-full rounded-md border {{ $errors->has('inquiry_date') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                        @error('inquiry_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Academic Information -->
                    <div>
                        <label for="study_level" class="block text-sm font-medium text-gray-700">Study Level</label>
                        <select name="study_level" id="study_level"
                                class="mt-1 block w-full rounded-md border {{ $errors->has('study_level') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                            <option value="">Select</option>
                            <option value="foundation" {{ old('study_level', $lead->study_level) == 'foundation' ? 'selected' : '' }}>Foundation</option>
                            <option value="diploma" {{ old('study_level', $lead->study_level) == 'diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="bachelor" {{ old('study_level', $lead->study_level) == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
                            <option value="master" {{ old('study_level', $lead->study_level) == 'master' ? 'selected' : '' }}>Master</option>
                            <option value="phd" {{ old('study_level', $lead->study_level) == 'phd' ? 'selected' : '' }}>PhD</option>
                        </select>
                        @error('study_level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority" id="priority"
                                class="mt-1 block w-full rounded-md border {{ $errors->has('priority') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                            <option value="">Select</option>
                            <option value="very_high" {{ old('priority', $lead->priority) == 'very_high' ? 'selected' : '' }}>Very High</option>
                            <option value="high" {{ old('priority', $lead->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ old('priority', $lead->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ old('priority', $lead->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="very_low" {{ old('priority', $lead->priority) == 'very_low' ? 'selected' : '' }}>Very Low</option>
                        </select>
                        @error('priority') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status and Assignment -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border {{ $errors->has('status') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                            <option value="">Select</option>
                            <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Converted</option>
                            <option value="rejected" {{ old('status', $lead->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigned To</label>
                        <select name="assigned_to" id="assigned_to"
                                class="mt-1 block w-full rounded-md border {{ $errors->has('assigned_to') ? 'border-red-300' : 'border_gray-200' }} bg-white px-3 py-2 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">
                            <option value="">Unassigned</option>
                            @php
                                $assignableUsers = \App\Models\User::whereHas('roles', function($query) {
                                    $query->whereIn('name', ['counselor', 'manager']);
                                })->get();
                            @endphp
                            @foreach($assignableUsers as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Preferred Universities -->
                    <div class="sm:col-span-2">
                        <label for="preferred_universities" class="block text-sm font-medium text-gray-700">Preferred Universities</label>
                        <textarea name="preferred_universities" id="preferred_universities" rows="3"
                                  class="mt-1 block w-full rounded-md border {{ $errors->has('preferred_universities') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">{{ old('preferred_universities', $lead->preferred_universities) }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">List preferred institutions (one per line).</p>
                        @error('preferred_universities') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Special Notes -->
                    <div class="sm:col-span-2">
                        <label for="special_notes" class="block text-sm font-medium text-gray-700">Special Notes</label>
                        <textarea name="special_notes" id="special_notes" rows="4"
                                  class="mt-1 block w-full rounded-md border {{ $errors->has('special_notes') ? 'border-red-300' : 'border-gray-200' }} bg-white px-3 py-2 placeholder-gray-400 focus:border-blue-500  focus:ring-blue-500 sm:text-sm">{{ old('special_notes', $lead->special_notes) }}</textarea>
                        @error('special_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="border-t border-gray-100 px-6 py-4 bg-gray-50">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('leads.index') }}"
                           class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none  focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-500 px-4 py-2 text-sm font-semibold text-white shadow hover:from-blue-700 hover:to-blue-600 focus:outline-none  focus:ring-blue-500">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Lead
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
