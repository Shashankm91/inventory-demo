@extends('layouts.masterlayout')

@section('content')
<div class="container">
    <h2 class="mb-4">All Invoices</h2>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">+ Create Invoice</a>

   <div class="table-responsive">
     <table class="table table-bordered table-striped" id="invoiceTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Paid Amount</th>
                <th>Balance Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td>{{ $invoice->customer_name }}</td>
                <td>{{ $invoice->invoice_date }}</td>
                <td>₹{{ number_format($invoice->total, 2) }}</td>
                <td>{{$invoice->paid_amount}}</td>
                <td>{{$invoice->balance_amount}}</td>
                <td>
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-secondary">PDF</a>
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this invoice?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">No invoices found.</td></tr>
            @endforelse
        </tbody>
    </table>
   </div>
</div>
@endsection

@section('scripts')
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
    if ($('#invoiceTable').length) {
        $('#invoiceTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
        });
    }
});
</script>
@endsection