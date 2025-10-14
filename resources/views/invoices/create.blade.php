@extends('layouts.masterlayout')

@section('content')
<div class="col-12 grid-margin stretch-card">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Create Invoice</h4>

      <form class="forms-sample" method="POST" action="{{ route('invoices.store') }}">
        @csrf

        <!-- Customer Name -->
        <div class="form-group">
          <label for="customer_name">Customer Name</label>
          <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control" id="customer_name">
          @error('customer_name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
         <div class="form-group">
          <label for="customer_name">Mobile Number</label>
          <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control" id="mobile_number">
          @error('mobile_number') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
         <div class="form-group">
          <label for="customer_name">Genrated By</label>
          <input type="text" name="generated_by" value="{{ old('generated_by', auth()->user()->email) }}" class="form-control" id="generated_by"  readonly>
          @error('generated_by') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
         <div class="form-group">
          <label for="customer_name">Generated For</label>
          <input type="text" name="to_name" value="{{ old('to_name') }}" class="form-control" id="to_name">
          @error('to_name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
           <label for="paid_amt">Paid Amount</label>
            <input type="text" name="paid_amount" value="{{ old('paid_amount') }}" class="form-control">
        </div>

        <!-- Invoice Date -->
        <div class="form-group">
          <label for="invoice_date">Invoice Date</label>
          <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="form-control" id="invoice_date">
          @error('invoice_date') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
         
        <!-- Products Table -->
        <div class="form-group">
          <label>Products</label>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th width="120">Quantity</th>
                <th width="120">Unit Price</th>
              </tr>
            </thead>
            <tbody>
              @foreach($products as $product)
              <tr>
                <td>
                  <input type="checkbox" class="product-checkbox" data-price="{{ $product->price }}" name="products[{{ $product->id }}][id]" value="{{ $product->id }}">
                  {{ $product->name }}
                </td>
                <td>
                  <input type="number" name="products[{{ $product->id }}][quantity]" value="1" min="1" class="form-control quantity-input" data-price="{{ $product->price }}" disabled>
                </td>
                <td>
                  <input type="number" name="products[{{ $product->id }}][unit_price]" value="{{ $product->price }}" step="0.01" class="form-control unit-price-input" disabled>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          @error('products') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <!-- Invoice Summary -->
        <div class="mt-3">
            <p><strong>Subtotal:</strong> ₹<span id="subtotal">0.00</span></p>
            <p><strong>Tax (10%):</strong> ₹<span id="tax">0.00</span></p>
            <p><strong>Discount (5%):</strong> ₹<span id="discount">0.00</span></p>
            <p><strong>Total:</strong> ₹<span id="total">0.00</span></p>
        </div>

        <button type="submit" class="btn btn-primary me-2">Save Invoice</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-light">Cancel</a>
      </form>
    </div>
  </div>
</div>

<!-- Live calculation script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const subtotalEl = document.getElementById('subtotal');
    const taxEl = document.getElementById('tax');
    const discountEl = document.getElementById('discount');
    const totalEl = document.getElementById('total');
    // const totalel = document.getElementById('total_amt');


    function calculateTotals() {
        let subtotal = 0;

        checkboxes.forEach(checkbox => {
            const tr = checkbox.closest('tr');
            const qtyInput = tr.querySelector('.quantity-input');
            const priceInput = tr.querySelector('.unit-price-input');

            if (checkbox.checked) {
                const quantity = parseFloat(qtyInput.value) || 0;
                const unitPrice = parseFloat(priceInput.value) || 0;
                subtotal += quantity * unitPrice;
            }
        });

        const tax = subtotal * 0.10;
        const discount = subtotal * 0.05;
        const total = subtotal + tax - discount;

        subtotalEl.textContent = subtotal.toFixed(2);
        taxEl.textContent = tax.toFixed(2);
        discountEl.textContent = discount.toFixed(2);
        totalEl.textContent = total.toFixed(2);
        // totalel.value = total.toFixed(2);

    }

    checkboxes.forEach(checkbox => {
        const tr = checkbox.closest('tr');
        const qtyInput = tr.querySelector('.quantity-input');
        const priceInput = tr.querySelector('.unit-price-input');

        checkbox.addEventListener('change', function() {
            qtyInput.disabled = !this.checked;
            priceInput.disabled = !this.checked;
            calculateTotals();
        });

        qtyInput.addEventListener('input', calculateTotals);
        priceInput.addEventListener('input', calculateTotals);
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

@endsection
