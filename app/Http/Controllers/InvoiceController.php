<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use PDF;
class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $products = Product::all();
        return view('invoices.create', compact('products'));
    }

    // public function store(Request $request)
    // {
    //     // Validation rules
    //     $validated = $request->validate([
    //         'customer_name'  => 'required|string|max:255',
    //         'customer_email' => 'required|email|max:255',
    //         'product_id'     => 'required|exists:products,id',
    //         'quantity'       => 'required|integer|min:1',
    //         'due_date'       => 'required|date|after:today',
    //     ]);

    //     $product = Product::findOrFail($request->product_id);

    //     // Ensure enough stock
    //     if ($product->quantity < $request->quantity) {
    //         return back()->withErrors(['quantity' => 'Not enough stock available'])->withInput();
    //     }

    //     // Create invoice
    //     $invoice = Invoice::create([
    //         'customer_name'  => $validated['customer_name'],
    //         'customer_email' => $validated['customer_email'],
    //         'product_id'     => $validated['product_id'],
    //         'quantity'       => $validated['quantity'],
    //         'due_date'       => $validated['due_date'],
    //         'total'          => $product->price * $validated['quantity'],
    //     ]);

    //     // Reduce stock
    //     $product->decrement('quantity', $validated['quantity']);

    //     return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    // }
            //     public function store(Request $request)
            //    {
            //     $validated = $request->validate([
            //         'customer_name' => 'required|string|max:255',
            //         'mobile_number'=> 'required|digits:10',
            //         'generated_by' => 'required|string|max:255',
            //         'invoice_date'  => 'required|date',
            //         'products'      => 'required|array',
            //         'products.*.id' => 'required|exists:products,id',
            //         'products.*.quantity' => 'required|integer|min:1',
            //         'products.*.unit_price' => 'required|numeric|min:0',
            //         'to_name'=>'required|string|max:255',
            //         'total_amount'   => 'required|numeric',
            //         'paid_amount'    => 'nullable|numeric',
            //     ]);

            //     $invoiceNo = Invoice::generateInvoiceNo(); // Use your model method

            //     $subtotal = 0;
            //     $invoiceItems = [];

            //     foreach ($validated['products'] as $item) {
            //         $product = Product::findOrFail($item['id']);

            //         if ($product->stock_quantity < $item['quantity']) {
            //             return back()->withErrors(['products' => "Not enough stock for {$product->name}"])->withInput();
            //         }

            //         $product->decrement('stock_quantity', $item['quantity']);

            //         $itemSubtotal = $item['quantity'] * $item['unit_price'];
            //         $subtotal += $itemSubtotal;

            //         $invoiceItems[] = [
            //             'product_id' => $product->id,
            //             'quantity'   => $item['quantity'],
            //             'unit_price' => $item['unit_price'],
            //             'subtotal'   => $itemSubtotal,
            //         ];
            //     }

            //     // Example tax and discount (demo)
            //     $tax = $subtotal * 0.10;
            //     $discount = $subtotal * 0.05;
            //     $total = $subtotal + $tax - $discount;

            //     $invoice = Invoice::create([
            //         'invoice_no'    => $invoiceNo,
            //         'customer_name' => $validated['customer_name'],
            //         'invoice_date'  => $validated['invoice_date'],
            //         'subtotal'      => $subtotal,
            //         'tax'           => $tax,
            //         'discount'      => $discount,
            //         'total'         => $total,
            //         'created_by'    => auth()->id() ?? null,
            //         'mobile_number'=> $validated['mobile_number'],
            //         'generated_by'=> $validated['generated_by'],
            //         'to_name'=>$validated['to_name']

            //     ]);

            //     foreach ($invoiceItems as $item) {
            //         $invoice->items()->create($item);
            //     }

            //     return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
            // }
public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'mobile_number' => 'required|digits:10',
        'generated_by'  => 'required|string|max:255',
        'invoice_date'  => 'required|date',
        'products'      => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.unit_price' => 'required|numeric|min:0',
        'to_name'       => 'required|string|max:255',
        'paid_amount'  =>'required'          
    ]);

    $invoiceNo = Invoice::generateInvoiceNo(); // Use your model method

    $subtotal = 0;
    $invoiceItems = [];

    foreach ($validated['products'] as $item) {
        $product = Product::findOrFail($item['id']);

        if ($product->stock_quantity < $item['quantity']) {
            return back()->withErrors(['products' => "Not enough stock for {$product->name}"])->withInput();
        }

        $product->decrement('stock_quantity', $item['quantity']);

        $itemSubtotal = $item['quantity'] * $item['unit_price'];
        $subtotal += $itemSubtotal;

        $invoiceItems[] = [
            'product_id' => $product->id,
            'quantity'   => $item['quantity'],
            'unit_price' => $item['unit_price'],
            'subtotal'   => $itemSubtotal,
        ];
    }

    // Example tax and discount (demo)
    $tax = $subtotal * 0.10;
    $discount = $subtotal * 0.05;
    $total = $subtotal + $tax - $discount;

    $paidAmount = $validated['paid_amount'] ?? 0;
    $balanceAmount = $total - $paidAmount;

    $invoice = Invoice::create([
        'invoice_no'    => $invoiceNo,
        'customer_name' => $validated['customer_name'],
        'invoice_date'  => $validated['invoice_date'],
        'subtotal'      => $subtotal,
        'tax'           => $tax,
        'discount'      => $discount,
        'total'         => $total,
        'paid_amount'   => $paidAmount,
        'balance_amount'=> $balanceAmount,
        'created_by'    => auth()->id() ?? null,
        'mobile_number' => $validated['mobile_number'],
        'generated_by'  => $validated['generated_by'],
        'to_name'       => $validated['to_name']
    ]);

    foreach ($invoiceItems as $item) {
        $invoice->items()->create($item);
    }

    return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
}

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function pdf($id)
    {
    $invoice = Invoice::findOrFail($id);

    $pdf = PDF::loadView('invoices.pdf', compact('invoice'));

    return $pdf->download('invoice_'.$invoice->id.'.pdf');
    }
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Optional: restore stock quantities before deleting
        foreach ($invoice->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock_quantity', $item->quantity);
            }
        }

        // Delete invoice items first (if using a relationship with cascade, this may not be necessary)
        $invoice->items()->delete();

        // Delete the invoice
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

}
