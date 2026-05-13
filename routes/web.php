<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Manager\BranchController;
use App\Http\Controllers\Manager\MenuController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Manager\StockRequestController as ManagerStockRequest;
use App\Http\Controllers\Manager\ExpenseController as ManagerExpense;
use App\Http\Controllers\Manager\PromotionController as ManagerPromo;
use App\Http\Controllers\Manager\ReportController as ManagerReport;
use App\Http\Controllers\Manager\TransactionController as ManagerTransaction;

Route::get('/', function () {
    if (!auth()->check()) return redirect('/login');

    return match(auth()->user()->role) {
        'manager' => redirect('/manager/dashboard'),
        'admin'   => redirect('/admin/dashboard'),
        'kasir'   => redirect('/kasir/dashboard'),
        default   => redirect('/login'),
    };
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', [ManagerDashboard::class, 'index'])->name('dashboard');
        Route::resource('branches', BranchController::class);
        Route::post('branches/{id}/restore', [BranchController::class, 'restore'])
             ->name('branches.restore');
        Route::resource('menus', MenuController::class);
        Route::post('menus/{id}/restore', [MenuController::class, 'restore'])
             ->name('menus.restore');
        Route::get('stock-requests', [ManagerStockRequest::class, 'index'])->name('stock-requests.index');
        Route::get('stock-requests/{stockRequest}', [ManagerStockRequest::class, 'show'])->name('stock-requests.show');
        Route::post('stock-requests/{stockRequest}/approve', [ManagerStockRequest::class, 'approve'])->name('stock-requests.approve');
        Route::post('stock-requests/{stockRequest}/reject', [ManagerStockRequest::class, 'reject'])->name('stock-requests.reject');
        Route::get('expenses', [ManagerExpense::class, 'index'])->name('expenses.index');
        Route::post('expenses/{expense}/verify', [ManagerExpense::class, 'verify'])->name('expenses.verify');
        Route::post('expenses/{expense}/reject', [ManagerExpense::class, 'reject'])->name('expenses.reject');
        Route::resource('promotions', ManagerPromo::class)
             ->except(['show']);
        Route::get('reports', [ManagerReport::class, 'index'])->name('reports.index');
        Route::get('transactions', [ManagerTransaction::class, 'index'])->name('transactions.index');
        Route::post('branches/{branch}/add-kasir', [BranchController::class, 'addKasir'])
            ->name('branches.add-kasir');
        Route::post('stock-requests/{stockRequest}/confirm-delivery', [ManagerStockRequest::class, 'confirmDelivery'])
            ->name('stock-requests.confirm-delivery');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    });

Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('kasir.dashboard'))->name('dashboard');
    });

Route::get('/content/manager', function () {
    return view('content.manager'); 
});

require __DIR__.'/auth.php';
