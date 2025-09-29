@extends('layouts.masterlayout')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Create Quotation</h4>

    <form action="{{ route('quotations.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Customer Name</label>
        <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control">
        @error('customer_name') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <!-- <div class="mb-3">
        <label class="form-label">Customer Email (optional)</label>
        <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="form-control">
        @error('customer_email') <small class="text-danger">{{ $message }}</small> @enderror
      </div> -->

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Quotation Date</label>
          <input type="date" name="quotation_date" value="{{ old('quotation_date', date('Y-m-d')) }}" class="form-control">
          @error('quotation_date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Valid Until</label>
          <input type="date" name="valid_until" value="{{ old('valid_until') }}" class="form-control">
          @error('valid_until') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Terms (optional)</label>
        <textarea name="terms" class="form-control">{{ old('terms') }}</textarea>
      </div>

      <h5>Products</h5>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Product</th>
            <th width="120">Quantity</th>
            <th width="140">Unit Price</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>
              <label>
                <input type="checkbox" class="product-checkbox" name="products[{{ $product->id }}][id]" value="{{ $product->id }}">
                {{ $product->name }} <small class="text-muted">({{ $product->stock_quantity }} available)</small>
              </label>
            </td>
            <td>
              <input type="number" name="products[{{ $product->id }}][quantity]" value="1" min="1" class="form-control quantity-input" disabled>
            </td>
            <td>
              <input type="number" name="products[{{ $product->id }}][unit_price]" value="{{ $product->price }}" step="0.01" class="form-control unit-price-input" disabled>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @error('products') <small class="text-danger">{{ $message }}</small> @enderror

      <div class="row">
        <div class="col-md-3 mb-2">
          <label class="form-label">Tax %</label>
          <input type="number" name="tax_percent" value="{{ old('tax_percent', 0) }}" class="form-control" step="0.01">
        </div>
        <div class="col-md-3 mb-2">
          <label class="form-label">Discount %</label>
          <input type="number" name="discount_percent" value="{{ old('discount_percent', 0) }}" class="form-control" step="0.01">
        </div>
        <div class="col-md-6 mb-2">
          <label class="form-label">Preview Total</label>
          <div class="p-2 border">
            Subtotal: ₹<span id="q-subtotal">0.00</span> |
            Tax: ₹<span id="q-tax">0.00</span> |
            Discount: ₹<span id="q-discount">0.00</span> |
            Total: ₹<span id="q-total">0.00</span>
          </div>
        </div>
      </div>

      <button class="btn btn-success mt-3">Save Quotation</button>
      <a href="{{ route('quotations.index') }}" class="btn btn-light mt-3">Cancel</a>
    </form>
  </div>
</div>

<!-- JS to enable inputs and preview totals -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const subtotalEl = document.getElementById('q-subtotal');
    const taxEl = document.getElementById('q-tax');
    const discountEl = document.getElementById('q-discount');
    const totalEl = document.getElementById('q-total');
    const taxPercentInput = document.querySelector('input[name="tax_percent"]');
    const discountPercentInput = document.querySelector('input[name="discount_percent"]');

    function calculate() {
        let subtotal = 0;
        checkboxes.forEach(ch => {
            const tr = ch.closest('tr');
            const qty = tr.querySelector('.quantity-input');
            const price = tr.querySelector('.unit-price-input');
            if (ch.checked) {
                subtotal += (parseFloat(qty.value) || 0) * (parseFloat(price.value) || 0);
            }
        });
        const tax = subtotal * (parseFloat(taxPercentInput.value || 0)/100);
        const discount = subtotal * (parseFloat(discountPercentInput.value || 0)/100);
        const total = subtotal + tax - discount;
        subtotalEl.textContent = subtotal.toFixed(2);
        taxEl.textContent = tax.toFixed(2);
        discountEl.textContent = discount.toFixed(2);
        totalEl.textContent = total.toFixed(2);
    }

    checkboxes.forEach(ch => {
        const tr = ch.closest('tr');
        const qty = tr.querySelector('.quantity-input');
        const price = tr.querySelector('.unit-price-input');

        ch.addEventListener('change', function() {
            qty.disabled = !this.checked;
            price.disabled = !this.checked;
            calculate();
        });
        qty.addEventListener('input', calculate);
        price.addEventListener('input', calculate);
    });

    taxPercentInput.addEventListener('input', calculate);
    discountPercentInput.addEventListener('input', calculate);
});
</script>
@endsection
