<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Invoice #{{ $invoice->invoice_no }}</h2>
    <p><strong>Customer:</strong> {{ $invoice->customer_name }}</p>
    <p><strong>Date:</strong> {{ $invoice->invoice_date }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">₹{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Subtotal:</strong> ₹{{ number_format($invoice->subtotal,2) }}</p>
    <p><strong>Tax:</strong> ₹{{ number_format($invoice->tax,2) }}</p>
    <p><strong>Discount:</strong> ₹{{ number_format($invoice->discount,2) }}</p>
    <h3><strong>Total:</strong> ₹{{ number_format($invoice->total,2) }}</h3>
</body>
</html>
