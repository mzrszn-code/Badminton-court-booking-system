<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

// Guest routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Courts (public view)
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

// About Us
Route::get('/about', function () {
    return view('about');
})->name('about');

// Calendar (public view)
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/activity', [ProfileController::class, 'activity'])->name('profile.activity');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Get available time slots (AJAX)
    Route::get('/api/available-slots', [BookingController::class, 'getAvailableSlots'])->name('api.available-slots');

    // Payments
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/bookings/{booking}/payment', [PaymentController::class, 'submit'])->name('payments.submit');

    // Check-in
    Route::get('/checkin/{code}', [CheckinController::class, 'verify'])->name('checkin.verify');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manage Bookings
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject');

    // Manage Courts
    Route::get('/courts', [AdminController::class, 'courts'])->name('courts.index');
    Route::get('/courts/create', [AdminController::class, 'createCourt'])->name('courts.create');
    Route::post('/courts', [AdminController::class, 'storeCourt'])->name('courts.store');
    Route::get('/courts/{court}/edit', [AdminController::class, 'editCourt'])->name('courts.edit');
    Route::put('/courts/{court}', [AdminController::class, 'updateCourt'])->name('courts.update');
    Route::delete('/courts/{court}', [AdminController::class, 'deleteCourt'])->name('courts.delete');

    // Maintenance Mode
    Route::get('/maintenance', [AdminController::class, 'maintenance'])->name('maintenance.index');
    Route::post('/maintenance', [AdminController::class, 'storeMaintenance'])->name('maintenance.store');
    Route::post('/courts/{court}/toggle-maintenance', [AdminController::class, 'toggleMaintenance'])->name('courts.toggle-maintenance');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');

    // Activity Logs
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities.index');

    // Check-in verification
    Route::post('/verify-checkin', [CheckinController::class, 'adminVerify'])->name('verify-checkin');

    // Payment Management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::post('/payments/{payment}/approve', [AdminController::class, 'approvePayment'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');
});
