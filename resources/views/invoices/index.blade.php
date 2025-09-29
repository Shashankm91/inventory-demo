@extends('layouts.masterlayout')

@section('content')
<div class="container">
    <h2 class="mb-4">All Invoices</h2>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">+ Create Invoice</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
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
@endsection
