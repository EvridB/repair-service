<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Models\Request as RepairRequest;

// 1. Главная страница - Форма создания (Клиент)
Route::get('/', function () {
    return view('create');
})->name('requests.create');

Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

// 2. Панель диспетчера
Route::get('/dispatcher', [RequestController::class, 'index'])->name('dispatcher.index');

// 3. Панель мастера (Исправлено имя модели!)
Route::get('/master', function () {
    // Берем все заявки, кроме новых, чтобы мастер видел назначенные ему
    $orders = RepairRequest::where('status', '!=', 'new')->get();
    return view('master', compact('orders'));
})->name('master.index');

// 4. Кнопки действий
Route::patch('/requests/{id}/cancel', [RequestController::class, 'cancel'])->name('requests.cancel');
Route::patch('/requests/{id}/done', [RequestController::class, 'done'])->name('requests.done');
Route::post('/requests/{id}/assign', [RequestController::class, 'assign'])->name('requests.assign');
Route::post('/requests/{id}/take', [RequestController::class, 'takeToWork'])->name('requests.take');
