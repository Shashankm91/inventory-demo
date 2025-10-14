@extends('layouts.masterlayout')

@section('content')
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Add Product</h4>

      <form class="forms-sample" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div class="form-group">
          <label for="name">Product Name</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Enter product name">
          @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Category -->
        <div class="form-group">
          <label for="category">Category</label>
          <input type="text" name="category" value="{{ old('category') }}" class="form-control" id="category" placeholder="Enter category">
          @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Unit -->
        <div class="form-group">
          <label for="unit">Unit</label>
          <input type="text" name="unit" value="{{ old('unit') }}" class="form-control" id="unit" placeholder="e.g. pcs, box">
          @error('unit') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Price -->
        <div class="form-group">
          <label for="price">Price</label>
          <input type="number" name="price" step="0.01" value="{{ old('price') }}" class="form-control" id="price" placeholder="Enter price">
          @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Stock -->
        <div class="form-group">
          <label for="stock_quantity">Stock Quantity</label>
          <input type="number" name="stock_quantity" value="{{ old('stock_quantity') }}" class="form-control" id="stock_quantity" placeholder="Enter stock quantity">
          @error('stock_quantity') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Image -->
        <div class="form-group">
          <label for="image">Product Image</label>
          <input type="file" name="image" class="form-control" id="image">
          @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        @if(auth()->user()->role === 'admin')
        <div class="form-group">
          <label for="cost_price">Cost Price</label>
          <input type="number" name="cost_price" value="{{ old('cost_price') }}" class="form-control" id="stock_quantity" placeholder="Enter stock quantity">
          @error('cost_price') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
          <label for="selling_price">Selling Price</label>
          <input type="number" name="selling_price" value="{{ old('selling_price') }}" class="form-control" id="stock_quantity" placeholder="Enter stock quantity">
          @error('selling_price') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        @endif
        <button type="submit" class="btn btn-primary me-2">Submit</button>
        <a href="{{ route('products.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

@endsection
