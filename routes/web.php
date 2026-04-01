<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SalleController;
use App\Http\Controllers\Admin\CourController;
use App\Http\Controllers\Admin\GroupeController;
use App\Http\Controllers\Admin\EmploiDuTempsController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\AnalyseDonneesController;
use App\Http\Controllers\Enseignant\PlanningController;
use App\Http\Controllers\Enseignant\ReservationController as EnseignantReservationController;
use App\Http\Controllers\Etudiant\EmploiDuTempsController as EtudiantEmploiDuTempsController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Routes protégées
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Routes Admin
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Gestion utilisateurs
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');
        Route::resource('users', UserController::class);
        
        // Gestion salles
        Route::resource('salles', SalleController::class);
        
        // Gestion cours
        Route::resource('cours', CourController::class);
        
        // Gestion groupes
        Route::resource('groupes', GroupeController::class);
        
        // Gestion emplois du temps
        Route::resource('emploi-du-temps', EmploiDuTempsController::class);
        Route::post('emploi-du-temps/check-conflict', [EmploiDuTempsController::class, 'checkConflict'])->name('emploi-du-temps.check-conflict');
        
        // Statistiques / Audit
        Route::get('statistiques', [StatistiqueController::class, 'index'])->name('statistiques');

        // Analyse des données (occupation / heatmap)
        Route::get('analyse-donnees', [AnalyseDonneesController::class, 'index'])->name('analyse_donnees');
        
        // Réservations
        Route::get('reservations', [StatistiqueController::class, 'reservations'])->name('reservations.index');
        Route::post('reservations/{reservation}/approve', [StatistiqueController::class, 'approveReservation'])->name('reservations.approve');
        Route::post('reservations/{reservation}/reject', [StatistiqueController::class, 'rejectReservation'])->name('reservations.reject');
    });

    // Routes Enseignant
    Route::middleware(['role:enseignant'])->prefix('enseignant')->name('enseignant.')->group(function () {
        // Planning
        Route::get('planning', [PlanningController::class, 'index'])->name('planning');
        
        // Cours
        Route::get('cours', [PlanningController::class, 'cours'])->name('cours');
        
        // Réservations
        Route::resource('reservations', EnseignantReservationController::class);
    });

    // Routes Étudiant
    Route::middleware(['role:etudiant'])->prefix('etudiant')->name('etudiant.')->group(function () {
        // Emploi du temps
        Route::get('emploi-du-temps', [EtudiantEmploiDuTempsController::class, 'index'])->name('emploi-du-temps');
        
        // Cours
        Route::get('cours', [EtudiantEmploiDuTempsController::class, 'cours'])->name('cours');
        
        // Réservations
        Route::get('reservations', [EtudiantEmploiDuTempsController::class, 'reservations'])->name('reservations');
        Route::post('reservations', [EtudiantEmploiDuTempsController::class, 'storeReservation'])->name('reservations.store');
        Route::delete('reservations/{id}/cancel', [EtudiantEmploiDuTempsController::class, 'cancelReservation'])->name('reservations.cancel');
    });
});
