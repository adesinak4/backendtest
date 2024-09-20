<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Routes for makers (creating transactions)
Route::group(['middleware' => ['auth']], function () {
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
});

// Routes for checkers (approving/rejecting transactions)
Route::group(['middleware' => ['auth']], function () {
    Route::get('/transactions/pending', [TransactionController::class, 'pending'])->name('transactions.pending');
    Route::put('/transactions/{id}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::put('/transactions/{id}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
});

Route::get('/user', [UserController::class, 'show'])->name('user.show');
