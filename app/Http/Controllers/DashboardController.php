<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Invoice;
use App\Models\Quotation;

class DashboardController extends Controller
{
    public function index()
    {
        // Top cards
        $totalProducts   = Product::count();
        $totalInvoices   = Invoice::count();
        $totalQuotations = Quotation::count();

        // Balance Due Invoices
        $balanceInvoices = Invoice::where('balance_amount', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Low Stock Products (Threshold = 5)
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalInvoices',
            'totalQuotations',
            'balanceInvoices',
            'lowStockProducts'
        ));
    }
}
