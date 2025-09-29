@extends('layouts.masterlayout')

@section('content')
<div class="card">
  <div class="card-body">
    <h4>Quotation #{{ $quotation->quotation_no }}</h4>
    <p><strong>Customer:</strong> {{ $quotation->customer_name }} @if($quotation->customer_email) ({{ $quotation->customer_email }}) @endif</p>
    <p><strong>Date:</strong> {{ $quotation->quotation_date }} | <strong>Valid Until:</strong> {{ $quotation->valid_until }}</p>
    <p><strong>Status:</strong> <span class="badge bg-info text-dark">{{ ucfirst($quotation->status) }}</span></p>

    <table class="table table-bordered">
      <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
      <tbody>
        @foreach($quotation->items as $item)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $item->product->name }}</td>
          <td>{{ $item->quantity }}</td>
          <td>₹{{ number_format($item->unit_price,2) }}</td>
          <td>₹{{ number_format($item->subtotal,2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <p>Subtotal: ₹{{ number_format($quotation->subtotal,2) }}</p>
    <p>Tax: ₹{{ number_format($quotation->tax,2) }}</p>
    <p>Discount: ₹{{ number_format($quotation->discount,2) }}</p>
    <h4>Total: ₹{{ number_format($quotation->total,2) }}</h4>

    <div class="mt-3">
      <a href="{{ route('quotations.pdf', $quotation->id) }}" class="btn btn-secondary">Download PDF</a>

      <!-- @if($quotation->status !== 'sent')
      <form action="{{ route('quotations.send', $quotation->id) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-primary">Send (Mark Sent)</button>
      </form>
      @endif -->

      @if(! $quotation->converted_invoice_id)
      <form action="{{ route('quotations.convert', $quotation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Convert this quotation to an invoice? Stock will be checked & reduced.')">
        @csrf
        <button class="btn btn-success">Convert to Invoice</button>
      </form>
      @endif

      <a href="{{ route('quotations.index') }}" class="btn btn-light">Back</a>
    </div>
  </div>
</div>
@endsection
