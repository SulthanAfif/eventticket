<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CustomerDashboardSettingController;
use App\Http\Controllers\Admin\BannerController;

Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('events.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order:code}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order:code}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order:code}/sync', [OrderController::class, 'sync'])->name('orders.sync');
    Route::get('/orders/{order:code}/tickets', [OrderController::class, 'downloadTickets'])->name('orders.tickets.download');
});

Route::post('/midtrans/callback', [OrderController::class, 'callback'])
    ->name('midtrans.callback');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('banners', BannerController::class)->only(['index', 'store', 'destroy']);

    Route::post('/dashboard/settings', [DashboardController::class, 'updateSettings'])->name('dashboard.settings.update');
    Route::post('/admin/dashboard/settings', [App\Http\Controllers\Admin\DashboardController::class, 'updateSettings'])
    ->name('admin.dashboard.settings.update');

    Route::get('customer-dashboard-settings', [CustomerDashboardSettingController::class, 'edit'])
        ->name('customer-dashboard.edit');
    Route::post('customer-dashboard-settings', [CustomerDashboardSettingController::class, 'update'])
        ->name('customer-dashboard.update');

    Route::resource('events', AdminEventController::class)->except(['show']);
    Route::post('events/{event}/ticket-types', [AdminEventController::class, 'storeTicketType'])
        ->name('events.ticket-types.store');
    Route::delete('events/{event}/ticket-types/{ticketType}', [AdminEventController::class, 'destroyTicketType'])
        ->name('events.ticket-types.destroy');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

    Route::get('check-in', [AdminTicketController::class, 'checkInForm'])->name('tickets.check-in');
    Route::post('check-in', [AdminTicketController::class, 'checkIn'])->name('tickets.check-in.store');

    Route::post('/admin/dashboard/settings', [App\Http\Controllers\Admin\DashboardController::class, 'updateSettings'])
    ->name('admin.dashboard.settings.update');


});

require __DIR__.'/auth.php';
