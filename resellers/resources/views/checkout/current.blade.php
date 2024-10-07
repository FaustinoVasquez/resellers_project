@if($cart && $cart->count() > 0)
  <div class="row">
    <div class="col-sm-12 table-responsive">
      <table id="cart-table" class="table">
        <thead>
          <th>SKU</th>
          <th>Name</th>
          <th>Unit Price</th>
          <th>Unit Shipping</th>
          <th>Quantity Ordered</th>
          <th>Total Shipping</th>
          <th>Total</th>
        </thead>
        <tbody>
          @foreach($cart as $item)
            @include('checkout.item')
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@else
  <h2 class="text-center">You don't have any items on you shopping cart</h2>
@endif
