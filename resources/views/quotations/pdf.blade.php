<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Quotation {{ $quotation->quotation_no }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #000; padding: 6px; }
    th { background: #eee; }
    .text-right { text-align: right; }
  </style>
</head>
<body>
  <h2>Quotation #{{ $quotation->quotation_no }}</h2>
  <p><strong>Customer:</strong> {{ $quotation->customer_name }}</p>
  <p><strong>Date:</strong> {{ $quotation->quotation_date }}</p>

  <table>
    <thead>
      <tr><th>#</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
    </thead>
    <tbody>
      @foreach($quotation->items as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->product->name }}</td>
        <td class="text-right">{{ $item->quantity }}</td>
        <td class="text-right">₹{{ number_format($item->unit_price,2) }}</td>
        <td class="text-right">₹{{ number_format($item->subtotal,2) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
        <td class="text-right">₹{{ number_format($quotation->subtotal,2) }}</td>
      </tr>
      <tr>
        <td colspan="4" class="text-right"><strong>Tax</strong></td>
        <td class="text-right">₹{{ number_format($quotation->tax,2) }}</td>
      </tr>
      <tr>
        <td colspan="4" class="text-right"><strong>Discount</strong></td>
        <td class="text-right">₹{{ number_format($quotation->discount,2) }}</td>
      </tr>
      <tr>
        <td colspan="4" class="text-right"><strong>Total</strong></td>
        <td class="text-right">₹{{ number_format($quotation->total,2) }}</td>
      </tr>
    </tbody>
  </table>

  @if($quotation->terms)
  <p><strong>Terms:</strong></p>
  <p>{{ $quotation->terms }}</p>
  @endif
</body>
</html>
