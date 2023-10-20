<?php

use App\Http\Controllers\MedicineController;
use App\Http\Controllers\userController;
use App\Models\Medicine;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::prefix('/medicine')->name('medicine.')->group(function() {
    Route::get('/create', [MedicineController::class, 'create'])->name('create');
    Route::post('/store', [MedicineController::class, 'store'])->name('store');
    Route::get('/', [MedicineController::class, 'index'])->name('home');
    Route::get('/{id}', [MedicineController::class, 'edit'])->name('edit');
    Route::patch('/{id}', [MedicineController::class, 'update'])->name('update');
    Route::delete('/{id}', [MedicineController::class, 'destroy'])->name('delete');
    Route::get('/data/stock', [MedicineController::class, 'stock'])->name('stock');
    Route::get('/data/stock/{id}', [MedicineController::class, 'stockEdit'])->name('stock.edit');
    Route::patch('/data/stock/{id}', [MedicineController::class, 'stockUpdate'])->name('stock.update');
});

Route::prefix('/user')->name('user.')->group(function(){
    Route::get('/', [userController::class, 'index'])->name('home');
    Route::get('/create', [userController::class, 'create'])->name('create');
    Route::post('/store', [userController::class, 'store'])->name('store');
    Route::get('/{id}', [userController::class, 'edit'])->name('edit');
    Route::patch('/{id}', [userController::class, 'update'])->name('update');
    Route::delete('/{id}', [userController::class, 'destroy'])->name('delete');

});