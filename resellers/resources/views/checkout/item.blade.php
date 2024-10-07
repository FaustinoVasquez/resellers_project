<tr class="product">
  <td>{{$item->ItemLocalSKU}}</td>
  <td>{{$item->ItemTitle}}</td>
  <td>${{number_format($item->ItemUnitPrice,2)}}</td>
  <td>${{number_format($item->ItemUnitShipping,2)}}</td>
  <td><input type="number" value="{{$item->ItemQuantityOrdered}}" class="form-control product-quantity" id="{{$item->Id}}"></td>
  <td>${{number_format($item->ItemTotalShipping,2)}}</td>
  <td>${{number_format($item->ItemTotal,2)}}</td>
</tr>
<tr class="row-close">
  <td colspan="7">
    <button class="btn btn-link btn-link-danger rmv-item" id="{{$item->Id}}">Remove Item</button>
  </td>
</tr>
