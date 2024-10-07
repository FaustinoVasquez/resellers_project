<style>
    .gap{
        color:whitesmoke;
        padding:4px 0 4px 0;
        width:620px;
        font-size:14px;
        background-color:#2e3436;
    }
    h2{
        color: #000000;
        font: 18px arial,sans-serif;
        font-weight: bold;
    }
    h3{
        color: #000000;
        font: 14px arial, sans-serif;
        font-weight: bold;
    }
</style>
<h2>Order Cancellation - Reseller Portal</h2>
<h3>{{date("D M j G:i:s T Y")}}</h3>
<br><br>

    <p class="gap">Order Summary</p>

<p>
<table style="color:#222222; font: 12px arial, sans-serif; width:620px; ">
    <tr>
        <td width="30%"><b>Sales Order Number:</b></td>
        <td>{{$summary['OrderNumber']}}</td>
    </tr>
    <tr>
        <td><b>Sales Order Date:</b></td>
        <td>{{$summary['orderDate']}}</td>
    </tr>
    <tr>
        <td><b>Sales Order Shipping Method:</b></td>
        <td>{{$summary['Shipping']}}</td>
    </tr>
</table>
</p>

    <p class="gap">Shipping Information</p>
<p>
<table style="color: #222222; font: 12px arial, sans-serif; width:620px; ">

    <tr>
        <td width="30%"><b>ShipName:</b></td>
        <td><b>{{$shipData['ShipName']}}</b></td>
    </tr>
    <tr>
        <td><b>ShipAddress:</b></td>
        <td>{{$shipData["ShipAddress"]}}</td>
    </tr>
    <tr>
        <td><b>ShipEmail:</b></td>
        <td>{{$shipData["ShipEmail"]}}</td>
    </tr>
    <tr>
        <td><b>ShipCity:</b></td>
        <td>{{$shipData["ShipCity"]}}</td>
    </tr>
    <tr>
        <td><b>ShipPhone:</b></td>
        <td>{{$shipData['ShipPhone']}}</td>
    </tr>
</table>
</p>


<p class="gap">Order Details</p>

<p>
<table border=2 style="font-size:11px;border-collapse:collapse;">
    <tr style="color:navy; font-weight:bold; background-color:#ccc">
        <th>SKU</th>
        <th>Product</th>
        <th>Unit Price</th>
        <th>Ordered</th>
        <th>Item Status</th>
        <th>Ext. Price</th>
    </tr>

    @foreach($products as $product)
        <tr>
            <td>{{$product['SKU']}}</td>
            <td width=320>{{ $product['Product'] }}</td>
            <td align="right">{{ money_format('%i', $product['PricePerUnit']) }}</td>
            <td align="center">{{$product['QuantityOrdered']}}</td>
            <td width=100>{{$product['Status']}}</td>
            <td align="right">{{ money_format('%i', $product['extPrice'])}}</td>
        </tr>
    @endforeach
</table>
</p>
<p>
<table style="color: #222222 ; font: 12px arial, sans-serif; width:620px; ">
    <tr>
        <td width="30%"><b>Total Amount:</b></td>
        <td><b>US {{money_format('%i',$total['totalOrder'])}}</b></td>
    </tr>
</table>
</p>
