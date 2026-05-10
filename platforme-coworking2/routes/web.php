<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EspaceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Admin\DashboardController;

// ============ Routes publiques ============
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/plan', [EspaceController::class, 'plan'])->name('plan');

Route::get('/espaces',      [EspaceController::class, 'index'])->name('espaces.index');
Route::get('/espaces/{id}', [EspaceController::class, 'show'])->name('espaces.show');

Route::post('/chatbot', [ChatbotController::class, 'repondre'])->name('chatbot.repondre');

// ============ Auth (invités seulement) ============
Route::middleware('guest:client')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:client')
    ->name('logout');

// ============ API sièges occupés ============
Route::get('/api/seats-occupied', function (\Illuminate\Http\Request $request) {
    $occupied = \App\Models\Reservation::where('espace_id', $request->espace_id)
        ->where('date', $request->date)
        ->where('statut', '!=', 'Annulée')
        ->where(function ($q) use ($request) {
            $q->whereBetween('heure_debut', [$request->heure_debut, $request->heure_fin])
              ->orWhereBetween('heure_fin',  [$request->heure_debut, $request->heure_fin])
              ->orWhere(function ($q2) use ($request) {
                  $q2->where('heure_debut', '<=', $request->heure_debut)
                     ->where('heure_fin',   '>=', $request->heure_fin);
              });
        })
        ->whereNotNull('numero_siege')
        ->pluck('numero_siege');

    return response()->json(['occupied' => $occupied]);
});

// ============ Routes protégées (client connecté) ============
Route::middleware('auth:client')->group(function () {

    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

    // Réservations
    Route::get('/reservations',                    [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create/{espace_id}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations',                   [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{id}',               [ReservationController::class, 'show'])->name('reservations.show');
    Route::delete('/reservations/{id}',            [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Avis
    Route::get('/avis/create/{espace_id}', [AvisController::class, 'create'])->name('avis.create');
    Route::post('/avis',                   [AvisController::class, 'store'])->name('avis.store');
    Route::delete('/avis/{id}',            [AvisController::class, 'destroy'])->name('avis.destroy');

    // Profil
    Route::get('/profil', [ClientController::class, 'profil'])->name('profil');
    Route::put('/profil', [ClientController::class, 'updateProfil'])->name('profil.update');
});

// ============ Routes admin (protégées) ============
Route::prefix('admin')->name('admin.')->middleware(['auth:client', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

    // Réservations
    Route::get('/reservations',         [ReservationController::class, 'adminIndex'])->name('reservations.index');
    Route::put('/reservations/{id}',    [ReservationController::class, 'updateStatut'])->name('reservations.update');
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Espaces
    Route::get('/espaces',           [EspaceController::class, 'adminIndex'])->name('espaces.index');
    Route::get('/espaces/create',    [EspaceController::class, 'create'])->name('espaces.create');
    Route::post('/espaces',          [EspaceController::class, 'store'])->name('espaces.store');
    Route::get('/espaces/{id}/edit', [EspaceController::class, 'edit'])->name('espaces.edit');
    Route::put('/espaces/{id}',      [EspaceController::class, 'update'])->name('espaces.update');
    Route::delete('/espaces/{id}',   [EspaceController::class, 'destroy'])->name('espaces.destroy');

    // Avis
    Route::get('/avis',         [\App\Http\Controllers\Admin\AvisController::class, 'index'])->name('avis.index');
    Route::delete('/avis/{id}', [\App\Http\Controllers\Admin\AvisController::class, 'destroy'])->name('avis.destroy');
// Clients
Route::get('/clients',         [ClientController::class, 'index'])->name('clients.index');
Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy'); // ← ajouter
}); // ← une seule accolade fermante