<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});



// Dashboard Route (UPDATED)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Products
Route::resource('products', ProductController::class);
Route::post('/products/import', [ProductController::class, 'import'])
    ->name('products.import');
// Invoices
Route::resource('invoices', InvoiceController::class)
    ->middleware(['auth', 'role:employee,admin']);

Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
    ->name('invoices.pdf');

// Quotations
Route::resource('quotations', QuotationController::class);
Route::post('quotations/{quotation}/send', [QuotationController::class, 'send'])
    ->name('quotations.send');
Route::post('quotations/{quotation}/convert', [QuotationController::class, 'convertToInvoice'])
    ->name('quotations.convert');
Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])
    ->name('quotations.pdf');

// Suppliers
Route::middleware(['auth'])->group(function () {
    Route::resource('suppliers', SupplierController::class);
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
