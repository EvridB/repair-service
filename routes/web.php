<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Models\Request as RepairRequest;
use App\Models\User;

// ЭКРАН 1: Создание заявки
Route::get('/', function () {
    return view('create');
})->name('home');

Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

// ЭКРАН 2: Панель диспетчера
Route::get('/dispatcher', function () {
    return view('dispatcher', [
        'requests' => RepairRequest::all(),
        'masters' => User::where('role', 'master')->get()
    ]);
})->name('dispatcher.index');

Route::post('/requests/{id}/assign', [RequestController::class, 'assign'])->name('requests.assign');

// ЭКРАН 3: Панель мастера
Route::get('/master', function () {
    $master = User::where('role', 'master')->first();
    return view('master', [
        'requests' => RepairRequest::where('assignedTo', $master->id)->get(),
        'master' => $master
    ]);
})->name('master.index');

Route::post('/requests/{id}/take', [RequestController::class, 'takeToWork'])->name('requests.take');
