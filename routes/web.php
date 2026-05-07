<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AppointmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Grupo de rutas del panel de administración. Protegido únicamente con
| el middleware de autenticación estándar (auth) — sin revisión de roles.
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard del admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // ── Pacientes ────────────────────────────────────────────────────────
    Route::resource('patients', \App\Http\Controllers\Admin\PatientController::class);

    // ── Doctores ─────────────────────────────────────────────────────────
    Route::resource('doctors', \App\Http\Controllers\Admin\DoctorController::class)
        ->only(['index', 'edit', 'update']);
    Route::get('doctors/{doctor}/schedule', [\App\Http\Controllers\Admin\DoctorController::class, 'schedule'])
        ->name('doctors.schedule');

    // ── Usuarios ─────────────────────────────────────────────────────────
    Route::resource('usuarios', \App\Http\Controllers\Admin\UserController::class);

    // ── Roles ─────────────────────────────────────────────────────────────
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);

    // ── Citas Médicas ─────────────────────────────────────────────────────
    Route::resource('appointments', AppointmentController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    // Ruta especial: Atender cita (ConsultationManager)
    Route::get('appointments/{appointment}/consult', [AppointmentController::class, 'consult'])
        ->name('appointments.consult');

});
