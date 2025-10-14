@extends('layouts.masterlayout')

@section('content')
<div class="container">
    <h2 class="mb-4">All Products</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">+ Add Product</a>

    <table class="table table-bordered table-striped" id="productsTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Stock Qty</th>
                @if(auth()->user()->role === 'admin')
                    <th>Cost Price</th>
                @endif
                <th>Selling Price</th>
                @if(auth()->user()->role === 'admin')
                  <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" class="rounded">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->unit }}</td>
                <td>₹{{ number_format($product->price, 2) }}</td>
                <td>
                    @if($product->stock_quantity > 5)
                        <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                    @else
                        <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                    @endif
                </td>
                @if(auth()->user()->role === 'admin')
                    <td>₹{{ number_format($product->cost_price, 2) }}</td>
                @endif
                <td>₹{{ number_format($product->selling_price, 2) }}</td>
                @if(auth()->user()->role === 'admin')
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this product?')" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->role === 'admin' ? 10 : 9 }}" class="text-center">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
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
    if ($('#productsTable').length) {
        $('#productsTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
        });
    }
});
</script>
@endsection
