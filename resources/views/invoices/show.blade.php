@extends('layouts.masterlayout')

@section('content')
<div class="container my-4">

    <div class="card shadow-sm p-4" style="max-width: 900px; margin:auto;">

        {{-- Header --}}
        <div class="text-center mb-3">
            <h4 class="fw-bold mb-0">ESTIMATE</h4>
            <h5 class="fw-bold text-uppercase mb-1">RM TRADERS</h5>
            <p class="mb-0">Itwari Main Road, Umred</p>
            <p class="mb-0">Mobile No: {{ $invoice->customer_mobile ?? '-' }}</p>
        </div>

        <hr>

        {{-- Invoice Meta --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="mb-1"><strong>Billed To:</strong></p>
                <p class="mb-0">{{ $invoice->customer_name }}</p>
            </div>

            <div class="col-md-6 text-end">
                <p class="mb-1"><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
                <p class="mb-1"><strong>Date:</strong> {{ $invoice->invoice_date }}</p>
                <p class="mb-0"><strong>Place of Supply:</strong> Maharashtra</p>
            </div>
        </div>

        {{-- Items Table --}}
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width:5%">S.N</th>
                    <th>Description of Goods</th>
                    <th style="width:10%">Qty</th>
                    <th style="width:10%">Unit</th>
                    <th style="width:15%">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-start">{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Pcs</td>
                    <td>{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="row justify-content-end mt-3">
            <div class="col-md-4">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-start">Subtotal</th>
                        <td class="text-end">₹{{ number_format($invoice->subtotal,2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">Tax</th>
                        <td class="text-end">₹{{ number_format($invoice->tax,2) }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">Discount</th>
                        <td class="text-end">₹{{ number_format($invoice->discount,2) }}</td>
                    </tr>
                    <tr class="border-top">
                        <th class="text-start fs-5">Total</th>
                        <th class="text-end fs-5">₹{{ number_format($invoice->total,2) }}</th>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        {{-- Signature --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <p>Customer Signature</p>
            </div>
            <div class="col-md-6 text-end">
                <p>Authorised Signatory</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="text-center mt-4">
            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-secondary me-2">
                Download PDF
            </a>
            <a href="{{ route('invoices.index') }}" class="btn btn-primary">
                Back
            </a>
        </div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
@endsection
