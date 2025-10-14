@extends('layouts.masterlayout')

@section('content')
<div class="container">
    <h2>Add Supplier</h2>

    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control @error('contact_person') is-invalid @enderror">
                @error('contact_person') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-success">Save Supplier</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-light">Cancel</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

@endsection
