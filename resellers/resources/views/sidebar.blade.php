<h5 class="sidebartitle">Navigation</h5>
<ul class="nav nav-pills nav-stacked nav-bracket">
  <li><a href="{{ URL::to('dashboard') }}">
    <i class="fa fa-home"></i> <span>Dashboard</span></a>
  </li>
  <li class="nav-parent"><a href=""><i class="fa fa-edit"></i> <span>Order Management</span></a>
    <ul class="children">
      <li><a href="{{ URL::to('bc') }}"><i class="fa fa-caret-right"></i> Billing Center</a></li>
      <li><a href="{{ URL::to('omc') }}"><i class="fa fa-caret-right"></i> Order Management Center</a></li>
    </ul>
  </li>
  <li class="nav-parent"><a href=""><i class="fa fa-tag"></i> <span>Pricing</span></a>
    <ul class="children">
      <li><a href="{{ URL::to('pricing') }}"><i class="fa fa-caret-right"></i> Customized Pricing</a></li>
      <li><a href="{{ URL::to('rptv') }}"><i class="fa fa-caret-right"></i> RPTV & FP Bare Customized Pricing</a></li>
      <li><a href="{{ URL::to('fp') }}"><i class="fa fa-caret-right"></i> FP Customized Pricing</a></li>
      <li><a href="{{ URL::to('other') }}"><i class="fa fa-caret-right"></i> Other</a></li>
    </ul>
  </li>
  <li class="nav-parent"><a href=""><i class="fa fa-files-o"></i> <span>Reports</span></a>
    <ul class="children">
      <li><a href="{{ URL::to('PurchaseBySku') }}"><i class="fa fa-caret-right"></i> Purchase Report by SKU</a></li>
    </ul>
  </li>
  <li class="nav-parent"><a href=""><i class="fa fa-cogs"></i> <span>Settings</span></a>
     <ul class="children">
        <li><a href="{{ URL::to('SkuMapping') }}"><i class="fa fa-caret-right"></i> SKU Mapping</a></li>
        <li><a href="{{ URL::to('CarrierMapping') }}"><i class="fa fa-caret-right"></i> Carrier Mapping</a></li>
     </ul>
  </li>
    <li class="nav-parent"><a href=""><i class="fa fa-upload"></i> <span>Order Import</span></a>
        <ul class="children">
            <li><a href="{{ URL::to('ShipImport') }}"><i class="fa fa-caret-right"></i> Ship Station Import</a></li>
            <li><a href="{{ URL::to('NetImport') }}"><i class="fa fa-caret-right"></i> NetSuite Import</a></li>
        </ul>
    </li>
</ul>
