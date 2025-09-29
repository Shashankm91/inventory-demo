@extends('layouts.masterlayout');

@section('content')
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Edit Product</h4>

      <form class="forms-sample" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div class="form-group">
          <label for="name">Product Name</label>
          <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" id="name">
          @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Category -->
        <div class="form-group">
          <label for="category">Category</label>
          <input type="text" name="category" value="{{ old('category', $product->category) }}" class="form-control" id="category">
          @error('category') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Unit -->
        <div class="form-group">
          <label for="unit">Unit</label>
          <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" class="form-control" id="unit">
          @error('unit') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Price -->
        <div class="form-group">
          <label for="price">Price</label>
          <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="form-control" id="price">
          @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Stock -->
        <div class="form-group">
          <label for="stock_quantity">Stock Quantity</label>
          <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="form-control" id="stock_quantity">
          @error('stock_quantity') <span class="text-danger small">{{ $message }}</span> @enderror
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

        <!-- Image -->
        <div class="form-group">
          <label for="image">Product Image</label>
          <input type="file" name="image" class="form-control" id="image">
          @if($product->image)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="80">
            </div>
          @endif
          @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary me-2">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>
@endsection
