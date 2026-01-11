<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail; // optional

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::latest()->get();
        return view('quotations.index', compact('quotations'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('quotations.create', compact('products'));
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'customer_name' => 'required|string|max:255',
    //         'customer_email' => 'nullable|email|max:255',
    //         'quotation_date' => 'required|date',
    //         'valid_until' => 'nullable|date|after_or_equal:quotation_date',
    //         'terms' => 'nullable|string',
    //         'products' => 'required|array|min:1',
    //         'products.*.id' => 'required|exists:products,id',
    //         'products.*.quantity' => 'required|integer|min:1',
    //         'products.*.unit_price' => 'required|numeric|min:0',
    //         'mobile_number'=> 'required|digits:10',
    //         'generated_by' => 'required|string|max:255',
    //         'to_name'=>'required|string|max:255',

    //     ]);

    //     // calculate totals server-side
    //     $subtotal = 0;
    //     foreach ($validated['products'] as $p) {
    //         $subtotal += $p['quantity'] * $p['unit_price'];
    //     }

    //     // allow tax & discount fields or use defaults
    //     $taxPercent = $request->input('tax_percent', 0); // e.g., 10 for 10%
    //     $discountPercent = $request->input('discount_percent', 0);

    //     $tax = round($subtotal * ($taxPercent/100), 2);
    //     $discount = round($subtotal * ($discountPercent/100), 2);
    //     $total = round($subtotal + $tax - $discount, 2);

    //     DB::beginTransaction();
    //     try {
    //         $quotation = Quotation::create([
    //             'quotation_no' => Quotation::generateQuotationNo(),
    //             'customer_name' => $validated['customer_name'],
    //             'customer_email' => $validated['customer_email'] ?? null,
    //             'quotation_date' => $validated['quotation_date'],
    //             'valid_until' => $validated['valid_until'] ?? null,
    //             'subtotal' => $subtotal,
    //             'tax' => $tax,
    //             'discount' => $discount,
    //             'total' => $total,
    //             'terms' => $validated['terms'] ?? null,
    //             'status' => 'draft',
    //             'created_by' => auth()->id() ?? null,
    //             'generated_by'=> $validated['generated_by'],
    //             'to_name'=>$validated['to_name'],
    //             'mobile_number'=> $validated['mobile_number'],
    //         ]);

    //         foreach ($validated['products'] as $p) {
    //             QuotationItem::create([
    //                 'quotation_id' => $quotation->id,
    //                 'product_id' => $p['id'],
    //                 'quantity' => $p['quantity'],
    //                 'unit_price' => $p['unit_price'],
    //                 'subtotal' => $p['quantity'] * $p['unit_price'],
    //             ]);
    //         }

    //         DB::commit();
    //         return redirect()->route('quotations.show', $quotation->id)
    //                          ->with('success', 'Quotation saved (Draft).');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => $e->getMessage()])->withInput();
    //     }
    // }
    public function store(Request $request)
{
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'mobile_number' => 'required|digits:10',
        'generated_by'  => 'required|string|max:255',
        'to_name'       => 'required|string|max:255',
        'quotation_date'=> 'required|date',
        'valid_until'   => 'nullable|date|after_or_equal:quotation_date',

        'products'      => 'required|array|min:1',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.unit_price' => 'required|numeric|min:0',

        'tax_percent'      => 'nullable|numeric|min:0',
        'discount_percent' => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {

        $subtotal = 0;

        foreach ($validated['products'] as $item) {
            $product = Product::lockForUpdate()->findOrFail($item['id']);

            // 🚫 HARD STOP: stock = 0
            if ($product->stock_quantity <= 0) {
                throw new \Exception("{$product->name} is out of stock");
            }

            // 🚫 HARD STOP: requested qty > stock
            if ($item['quantity'] > $product->stock_quantity) {
                throw new \Exception("Only {$product->stock_quantity} units available for {$product->name}");
            }

            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $tax = $subtotal * (($request->tax_percent ?? 0) / 100);
        $discount = $subtotal * (($request->discount_percent ?? 0) / 100);
        $total = $subtotal + $tax - $discount;

        $quotation = Quotation::create([
            'quotation_no'  => Quotation::generateQuotationNo(),
            'customer_name' => $validated['customer_name'],
            'mobile_number' => $validated['mobile_number'],
            'generated_by'  => $validated['generated_by'],
            'to_name'       => $validated['to_name'],
            'quotation_date'=> $validated['quotation_date'],
            'valid_until'   => $validated['valid_until'] ?? null,
            'subtotal'      => $subtotal,
            'tax'           => $tax,
            'discount'      => $discount,
            'total'         => $total,
            'status'        => 'draft',
            'created_by'    => auth()->id(),
        ]);

        foreach ($validated['products'] as $item) {
            QuotationItem::create([
                'quotation_id' => $quotation->id,
                'product_id'   => $item['id'],
                'quantity'     => $item['quantity'],
                'unit_price'   => $item['unit_price'],
                'subtotal'     => $item['quantity'] * $item['unit_price'],
            ]);
        }

        DB::commit();
        return redirect()->route('quotations.index')
            ->with('success', 'Quotation created successfully');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}


    public function show(Quotation $quotation)
    {
        $quotation->load('items.product');
        return view('quotations.show', compact('quotation'));
    }

    // mark as sent and optionally email
    public function send(Request $request, Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->withErrors(['error' => 'Already converted to invoice.']);
        }

        $quotation->update(['status' => 'sent']);

        // generate PDF and send via email if email present (optional)
        if ($quotation->customer_email) {
            $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
            // example: send email with pdf attached (requires QuotationMail below)
            try {
                Mail::to($quotation->customer_email)
                    ->send(new \App\Mail\QuotationMail($quotation, $pdf->output()));
            } catch (\Throwable $e) {
                // log and continue
                \Log::warning('Quotation email failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('quotations.show', $quotation->id)->with('success', 'Quotation sent.');
    }

    public function pdf(Quotation $quotation)
    {
        $quotation->load('items.product');
        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        return $pdf->stream("quotation-{$quotation->quotation_no}.pdf");
    }

    // convert quotation → invoice (transactional, checks stock)
    // public function convertToInvoice(Quotation $quotation)
    // {
    //     if (!in_array($quotation->status, ['sent','accepted','draft'])) {
    //         return back()->withErrors(['error' => 'Quotation cannot be converted (status: '.$quotation->status.')']);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // create invoice header using your Invoice model method
    //         $invoiceNo = Invoice::generateInvoiceNo();

    //         $invoice = Invoice::create([
    //             'invoice_no' => $invoiceNo,
    //             'customer_name' => $quotation->customer_name,
    //             'invoice_date' => now()->toDateString(),
    //             'subtotal' => $quotation->subtotal,
    //             'tax' => $quotation->tax,
    //             'discount' => $quotation->discount,
    //             'total' => $quotation->total,
    //             'created_by' => auth()->id() ?? null,
    //         ]);

    //         foreach ($quotation->items as $qItem) {
    //             // lock product row
    //             $product = Product::where('id', $qItem->product_id)->lockForUpdate()->first();

    //             if ($product->stock_quantity < $qItem->quantity) {
    //                 throw new \Exception("Insufficient stock for {$product->name}");
    //             }

    //             // create invoice item
    //             InvoiceItem::create([
    //                 'invoice_id' => $invoice->id,
    //                 'product_id' => $product->id,
    //                 'quantity' => $qItem->quantity,
    //                 'unit_price' => $qItem->unit_price,
    //                 'subtotal' => $qItem->subtotal,
    //             ]);

    //             // decrement stock and create stock movement
    //             $product->decrement('stock_quantity', $qItem->quantity);
    //             $product->refresh();

    //             StockMovement::create([
    //                 'product_id' => $product->id,
    //                 'type' => 'OUT',
    //                 'quantity' => $qItem->quantity,
    //                 'reference_type' => 'invoice',
    //                 'reference_id' => $invoice->id,
    //                 'balance_after' => $product->stock_quantity,
    //                 'note' => 'Converted from quotation ' . $quotation->quotation_no,
    //                 'created_by' => auth()->id() ?? null,
    //             ]);
    //         }

    //         // mark quotation converted
    //         $quotation->update([
    //             'status' => 'converted',
    //             'converted_invoice_id' => $invoice->id,
    //         ]);

    //         DB::commit();
    //         return redirect()->route('invoices.show', $invoice->id)->with('success', 'Quotation converted to invoice.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return back()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }
//     public function convertToInvoice(Request $request, Quotation $quotation)
// {
//     $request->validate([
//         'paid_amount' => 'required|numeric|min:0',
//     ]);

//     if (!in_array($quotation->status, ['sent','accepted','draft'])) {
//         return back()->withErrors(['error' => 'Quotation cannot be converted (status: '.$quotation->status.')']);
//     }

//     DB::beginTransaction();
//     try {
//         $invoiceNo = Invoice::generateInvoiceNo();

//         // Get paid, balance, payment status
//         $paidAmount = $request->paid_amount;
//         $totalAmount = $quotation->total;
//         $balanceAmount = $totalAmount - $paidAmount;

//         if ($paidAmount >= $totalAmount) {
//             $paymentStatus = 'paid';
//         } elseif ($paidAmount > 0) {
//             $paymentStatus = 'partial';
//         } else {
//             $paymentStatus = 'unpaid';
//         }

//         // Create Invoice
//         $invoice = Invoice::create([
//             'invoice_no' => $invoiceNo,
//             'customer_name' => $quotation->customer_name,
//             'invoice_date' => now()->toDateString(),
//             'subtotal' => $quotation->subtotal,
//             'tax' => $quotation->tax,
//             'discount' => $quotation->discount,
//             'total' => $quotation->total,
//             'paid_amount' => $paidAmount,
//             'balance_amount' => $balanceAmount,
//             'payment_status' => $paymentStatus,
//             'created_by' => auth()->id() ?? null,
//         ]);

//         foreach ($quotation->items as $qItem) {
//             $product = Product::where('id', $qItem->product_id)->lockForUpdate()->first();
//             if ($product->stock_quantity < $qItem->quantity) {
//                 throw new \Exception("Insufficient stock for {$product->name}");
//             }

//             InvoiceItem::create([
//                 'invoice_id' => $invoice->id,
//                 'product_id' => $product->id,
//                 'quantity' => $qItem->quantity,
//                 'unit_price' => $qItem->unit_price,
//                 'subtotal' => $qItem->subtotal,
//             ]);

//             $product->decrement('stock_quantity', $qItem->quantity);
//             $product->refresh();

//             StockMovement::create([
//                 'product_id' => $product->id,
//                 'type' => 'OUT',
//                 'quantity' => $qItem->quantity,
//                 'reference_type' => 'invoice',
//                 'reference_id' => $invoice->id,
//                 'balance_after' => $product->stock_quantity,
//                 'note' => 'Converted from quotation ' . $quotation->quotation_no,
//                 'created_by' => auth()->id() ?? null,
//             ]);
//         }

//         $quotation->update([
//             'status' => 'converted',
//             'converted_invoice_id' => $invoice->id,
//         ]);

//         DB::commit();
//         return redirect()->route('invoices.show', $invoice->id)->with('success', 'Quotation converted to invoice.');
//     } catch (\Throwable $e) {
//         DB::rollBack();
//         return back()->withErrors(['error' => $e->getMessage()]);
//     }
// }
public function convertToInvoice(Request $request, Quotation $quotation)
{
    $request->validate([
        'paid_amount' => 'required|numeric|min:0',
    ]);

    if (!in_array($quotation->status, ['sent','accepted','draft'])) {
        return back()->withErrors([
            'error' => 'Quotation cannot be converted (status: '.$quotation->status.')'
        ]);
    }

    DB::beginTransaction();

    try {
        $invoiceNo = Invoice::generateInvoiceNo();

        $paidAmount   = $request->paid_amount;
        $totalAmount  = $quotation->total;
        $balanceAmount = $totalAmount - $paidAmount;

        if ($paidAmount >= $totalAmount) {
            $paymentStatus = 'paid';
        } elseif ($paidAmount > 0) {
            $paymentStatus = 'partial';
        } else {
            $paymentStatus = 'unpaid';
        }

        // ✅ CREATE INVOICE (IMPORTANT CHANGES HERE)
        $invoice = Invoice::create([
            'invoice_no'    => $invoiceNo,
            'customer_name' => $quotation->customer_name,
            'invoice_date'  => now()->toDateString(),

            'subtotal' => $quotation->subtotal,
            'tax'      => $quotation->tax,
            'discount' => $quotation->discount,
            'total'    => $quotation->total,

            'paid_amount'    => $paidAmount,
            'balance_amount' => $balanceAmount,
            'payment_status' => $paymentStatus,

            // ✅ COPY FROM QUOTATION
            'generated_by'  => $quotation->generated_by,
            'to_name'       => $quotation->to_name,
            'mobile_number' => $quotation->mobile_number,

            'created_by' => auth()->id() ?? null,
        ]);

        foreach ($quotation->items as $qItem) {

            $product = Product::where('id', $qItem->product_id)
                ->lockForUpdate()
                ->first();

            if ($product->stock_quantity < $qItem->quantity) {
                throw new \Exception("Insufficient stock for {$product->name}");
            }

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'quantity'   => $qItem->quantity,
                'unit_price' => $qItem->unit_price,
                'subtotal'   => $qItem->subtotal,
            ]);

            $product->decrement('stock_quantity', $qItem->quantity);
            $product->refresh();

            StockMovement::create([
                'product_id'     => $product->id,
                'type'           => 'OUT',
                'quantity'       => $qItem->quantity,
                'reference_type' => 'invoice',
                'reference_id'   => $invoice->id,
                'balance_after'  => $product->stock_quantity,
                'note'           => 'Converted from quotation ' . $quotation->quotation_no,
                'created_by'     => auth()->id() ?? null,
            ]);
        }

        $quotation->update([
            'status' => 'converted',
            'converted_invoice_id' => $invoice->id,
        ]);

        DB::commit();

        return redirect()
            ->route('invoices.show', $invoice->id)
            ->with('success', 'Quotation converted to invoice.');

    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotations.index')
                        ->with('success', 'Quotation deleted successfully.');
    }
    // optional: expire a quotation (can be called by scheduler)
    public function expire(Quotation $quotation)
    {
        if ($quotation->status !== 'expired' && $quotation->valid_until && now()->gt($quotation->valid_until)) {
            $quotation->update(['status' => 'expired']);
        }
        return back();
    }

}
