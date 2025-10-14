@extends('layouts.masterlayout')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Quotations</h4>
    <a href="{{ route('quotations.create') }}" class="btn btn-primary mb-3">+ New Quotation</a>

    <table class="table table-bordered table-striped" id="quotationTable">
      <thead class="table-dark">
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
    if ($('#quotationTable').length) {
        $('#quotationTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
        });
    }
});
</script>
@endsection
