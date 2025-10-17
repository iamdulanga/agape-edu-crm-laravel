<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeadAssignmentController;
use App\Http\Controllers\LeadSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Optional: Redirect root to dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Search routes - must be before resource routes to avoid conflicts
    Route::get('/leads/search', [LeadSearchController::class, 'search'])->name('leads.search');

    // Bulk actions - must be before resource routes to avoid conflicts
    Route::post('/leads/bulk-assign', [LeadAssignmentController::class, 'bulkAssign'])->name('leads.bulk-assign');
    Route::post('/leads/bulk-status', [LeadController::class, 'bulkStatusUpdate'])->name('leads.bulk-status.update');

    // Export routes - must be before resource routes to avoid conflicts
    Route::post('/leads/export', [ExportController::class, 'exportLeads'])->name('leads.export');
    Route::post('/leads/export/filtered', [ExportController::class, 'exportFilteredLeads'])->name('leads.export.filtered');

    // Leads Management Resource Routes
    Route::resource('leads', LeadController::class);

    // Lead specific actions
    Route::post('/leads/{lead}/assign', [LeadAssignmentController::class, 'assign'])->name('leads.assign');
    Route::post('/leads/{lead}/unassign', [LeadAssignmentController::class, 'unassign'])->name('leads.unassign');
    Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status.update');

    // User Management Routes (Role-based access control)
    Route::middleware(['role:owner,manager'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
