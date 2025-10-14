@extends('layouts.masterlayout');

@section('content')
<div class="container">
    <h2 class="mb-4">Invoice #{{ $invoice->invoice_no }}</h2>

    <p><strong>Customer:</strong> {{ $invoice->customer_name }}</p>
    <p><strong>Date:</strong> {{ $invoice->invoice_date }}</p>

    <table class="table table-bordered">
        <thead class="table-dark">
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
                <td>₹{{ number_format($item->unit_price,2) }}</td>
                <td>₹{{ number_format($item->subtotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h5>Summary</h5>
    <p>Subtotal: ₹{{ number_format($invoice->subtotal,2) }}</p>
    <p>Tax: ₹{{ number_format($invoice->tax,2) }}</p>
    <p>Discount: ₹{{ number_format($invoice->discount,2) }}</p>
    <h4>Total: ₹{{ number_format($invoice->total,2) }}</h4>

    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-secondary">Download PDF</a>
    <a href="{{ route('invoices.index') }}" class="btn btn-primary">Back</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

@endsection
