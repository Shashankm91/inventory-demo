@extends('layouts.masterlayout')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span>
        Dashboard
    </h3>
</div>

{{-- TOP CARDS --}}
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                     class="card-img-absolute" alt="circle" />
                <h4 class="mb-3">Products</h4>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                     class="card-img-absolute" alt="circle" />
                <h4 class="mb-3">Invoices</h4>
                <h2>{{ $totalInvoices }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                     class="card-img-absolute" alt="circle" />
                <h4 class="mb-3">Quotations</h4>
                <h2>{{ $totalQuotations }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- SECOND ROW --}}
<div class="row mt-4">

    {{-- BALANCE DUE INVOICES --}}
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-danger">Pending Payment Invoices</h4>

                @if($balanceInvoices->count())
                    <div class="table-responsive">
                        <table class="table table-striped" id="balanced_invoices">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($balanceInvoices as $invoice)
                                    <tr>
                                        <td>#{{ $invoice->invoice_no }}</td>
                                        <td>{{ $invoice->customer_name }}</td>
                                        <td class="text-danger">
                                            ₹ {{ number_format($invoice->balance_amount, 2) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('invoices.show', $invoice->id) }}"
                                               class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No pending invoices 🎉</p>
                @endif
            </div>
        </div>
    </div>

    {{-- LOW STOCK PRODUCTS --}}
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-danger">Low Stock Products</h4>

                @if($lowStockProducts->count())
                    <div class="table-responsive">
                        <table class="table table-striped" id="low_stocks">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <span class="badge badge-danger">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Stock levels are healthy 👍</p>
                @endif
            </div>
        </div>
    </div>

</div>
<!-- ✅ 1️⃣ jQuery (only once, first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ 2️⃣ Bootstrap JS (your template bundle) -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

<!-- ✅ 3️⃣ DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- ✅ 4️⃣ Your page-specific script -->
<script>
$(document).ready(function () {
    if ($('#balanced_invoices').length) {
        $('#balanced_invoices').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
        });
    }
});
</script>
@endsection
