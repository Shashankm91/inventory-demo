<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//      Route::patch('/profile', [ProfileController::class, 'show'])->name('profile.show');
// });
//Route::resource('products', ProductController::class)->middleware(['auth', 'role:admin']);
Route::resource('products', ProductController::class);
// Route::resource('invoices', InvoiceController::class)->middleware(['auth', 'role:employee,admin']);
Route::resource('invoices', InvoiceController::class)->middleware(['auth', 'role:employee,admin']);
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

Route::resource('quotations',QuotationController::class);
Route::post('quotations/{quotation}/send', [QuotationController::class,'send'])->name('quotations.send');
Route::post('quotations/{quotation}/convert', [QuotationController::class,'convertToInvoice'])->name('quotations.convert');
Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])
    ->name('quotations.pdf');

//  Route::middleware(['auth', 'role:admin'])->group(function () {
//     // routes only admin can access
//     Route::get('/products', [ProductController::class, 'index']);
//     // etc.
// });

// Route::middleware(['auth', 'role:employee,admin'])->group(function () {
//     // routes both employee and admin can access
//     Route::get('/invoices', [InvoiceController::class, 'index']);
//     // etc.
// });

Route::middleware(['auth'])->group(function () {
    // other resources...
    Route::resource('suppliers', SupplierController::class);
});   

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
require __DIR__.'/auth.php';
