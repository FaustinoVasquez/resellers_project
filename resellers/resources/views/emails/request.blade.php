<p>
  {{$notes}}
</p>
<table style="border-collapse: collapse; width:400px">
  <thead>
    <th style="border: 1px solid black;">SKU number</th>
    <th style="border: 1px solid black;">Unit Price</th>
    <th style="border: 1px solid black;">Description</th>
    <th style="border: 1px solid black;">Quantity</th>
  </thead>
  <tbody>
    @foreach($request as $product)
      <?php $product = explode(';',$product) ?>
      <tr>
        <td style="border: 1px solid black; text-align:center">{{$product[0]}}</td>
        <td style="border: 1px solid black; text-align:center">{{$product[2]}}</td>
        <td style="border: 1px solid black; text-align:center">{{$product[3]}}</td>
        <td style="border: 1px solid black; text-align:center">{{$product[1]}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
