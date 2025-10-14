@extends('layouts.masterlayout')

@section('content')
<div class="container">
    <h2>Supplier Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h4>{{ $supplier->name }}</h4>
            <p><strong>Contact Person:</strong> {{ $supplier->contact_person ?? '-' }}</p>
            <p><strong>Phone:</strong> {{ $supplier->phone ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $supplier->email ?? '-' }}</p>
            <p><strong>Address:</strong><br> {!! nl2br(e($supplier->address)) !!}</p>
            <p><strong>Notes:</strong><br> {!! nl2br(e($supplier->notes)) !!}</p>

            <div class="mt-3">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-light">Back</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

@endsection
