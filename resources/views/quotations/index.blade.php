@extends('layouts.masterlayout')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Quotations</h4>
    <a href="{{ route('quotations.create') }}" class="btn btn-primary mb-3">+ New Quotation</a>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>Quotation No</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Total</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($quotations as $q)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $q->quotation_no }}</td>
          <td>{{ $q->customer_name }}</td>
          <td>{{ $q->quotation_date }}</td>
          <td>₹{{ number_format($q->total,2) }}</td>
          <td><span class="badge bg-info text-dark">{{ ucfirst($q->status) }}</span></td>
          <td>
            <a href="{{ route('quotations.show', $q->id) }}" class="btn btn-sm btn-info">View</a>
            <a href="{{ route('quotations.pdf', $q->id) }}" class="btn btn-sm btn-secondary">PDF</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">No quotations found.</td></tr>
        @endforelse
      </tbody>
    </table>

    {{ $quotations->links() }}
  </div>
</div>
@endsection
