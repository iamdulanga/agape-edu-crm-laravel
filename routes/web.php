<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LeadAssignmentController;
use App\Http\Controllers\LeadSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
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
    
    // Leads Management
    Route::resource('leads', LeadController::class);
    
    // Optional: Redirect root to dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::post('/leads/{lead}/assign', [LeadAssignmentController::class, 'assign'])->name('leads.assign');
    Route::post('/leads/{lead}/unassign', [LeadAssignmentController::class, 'unassign'])->name('leads.unassign');
    Route::post('/leads/bulk-assign', [LeadAssignmentController::class, 'bulkAssign'])->name('leads.bulk-assign');

    // Lead status routes
    Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status.update');
    Route::post('/leads/bulk-status', [LeadController::class, 'bulkStatusUpdate'])->name('leads.bulk-status.update');

    // Search routes
    Route::get('/leads/search', [LeadSearchController::class, 'search'])->name('leads.search');

    // Export routes
    Route::post('/leads/export', [ExportController::class, 'exportLeads'])->name('leads.export');
    Route::post('/leads/export/filtered', [ExportController::class, 'exportFilteredLeads'])->name('leads.export.filtered');
});