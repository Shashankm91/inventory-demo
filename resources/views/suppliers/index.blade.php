@extends('layouts.masterlayout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Suppliers</h2>
        <div>
             @if(auth()->user()->role === 'admin')
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Add Supplier</a>
            @endif
        </div>
    </div>

    <!-- <form class="mb-3" method="GET" action="{{ route('suppliers.index') }}">
        <div class="input-group">
            <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search by name, phone or email">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form> -->
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="supplierTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <!-- <th>Notes</th> -->
                  @if(auth()->user()->role === 'admin')
                <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->contact_person ?? '-' }}</td>
                <td>{{ $supplier->phone ?? '-' }}</td>
                <td>{{ $supplier->email ?? '-' }}</td>
                <td class="text-wrap" style="max-width:240px;">{{ $supplier->address }}</td>
                <!-- <td style="max-width:240px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $supplier->notes }}</td> -->
                  @if(auth()->user()->role === 'admin')
                <td>
                    <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this supplier?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="8" class="text-center">No suppliers found.</td></tr>
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
    if ($('#supplierTable').length) {
        $('#supplierTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
        });
    }
});
</script>
@endsection