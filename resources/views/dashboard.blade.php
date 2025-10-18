@extends('layouts.sidebar-layout')

@section('title', 'Dashboard')

@php
    $user = auth()->user();
    $userRoles = $user->roles->pluck('name')->toArray();
@endphp

@section('header')
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
        @if(in_array('owner', $userRoles))
            Owner Dashboard
        @elseif(in_array('manager', $userRoles))
            Manager Dashboard
        @elseif(in_array('counselor', $userRoles))
            Counselor Dashboard
        @else
            Dashboard
        @endif
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 text-lg font-medium">Welcome, {{ $user->name }}!</h3>

                    @if(in_array('owner', $userRoles))
                        <p class="mb-4">You are viewing the Owner Dashboard with full system access.</p>
                        @include('dashboard.partials.owner-stats')
                    @elseif(in_array('manager', $userRoles))
                        <p class="mb-4">You are viewing the Manager Dashboard with team management access.</p>
                        @include('dashboard.partials.manager-stats')
                    @elseif(in_array('counselor', $userRoles))
                        <p class="mb-4">You are viewing the Counselor Dashboard with lead management access.</p>
                        @include('dashboard.partials.counselor-stats')
                    @else
                        <p class="text-red-600">No valid role assigned. Contact administrator.</p>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('leads.index') }}" class="inline-block rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                            Manage Leads
                        </a>
                        @if(in_array('owner', $userRoles) || in_array('manager', $userRoles))
                            <a href="{{ route('users.index') }}" class="ml-3 inline-block rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                Manage Users
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
