@if($cart && $cart->count() > 0)
  @foreach($cart as $item)
    <li class="new">
      <!-- <span class="thumb"><img src="images/photos/user1.png" alt="" /></span> -->
      <!-- <span class="desc"> -->
        <span class="name">{{$item->ItemTitle}}</span>
        <span class="msg">Quantity: {{$item->ItemQuantityOrdered}}</span>
      <!-- </span> -->
    </li>
  @endforeach
  <li class="new"><a href="{{ URL::to('checkout') }}">Proceed to Checkout</a></li>
@else
<li class="new">
  <a href="">
  <span class="text-center">
    <span class="name"><i class="fa fa-times"></i>No items in cart</span>
  </span>
  </a>
</li>
@endif
