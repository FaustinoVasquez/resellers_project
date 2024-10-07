<ul class="nav navbar-nav">
    <li><a href="/">Home</a></li>

    @if(Auth::check())

        @if (Auth::User()->role =='admin')
            <li><a href="{{ url('admin') }}">Admin</a></li>
        @endif
        @if ((Auth::User()->customerid >0) and (Auth::User()->customerid >0))
            <li class="dropdown">
                <a tabindex="0" data-toggle="dropdown" data-submenu>
                    Order Management<span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a tabindex="0" href="{{route('BillingCenter')}}">Billing Center</a></li>
                    <li><a tabindex="0" href="{{route('OrderManager')}}">Order Management Center</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a tabindex="0" data-toggle="dropdown" data-submenu>
                    Pricing<span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a tabindex="0" href="{{route('RptvCustPrice')}}">RPTV & FP Bare Customized Pricing</a></li>
                    <li><a tabindex="0" href="{{route('FpCustPrice')}}"> FP Customized Pricing</a></li>
                    <li><a tabindex="0" href="{{route('Other')}}"> Other</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a tabindex="0" data-toggle="dropdown" data-submenu>
                    Reports<span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a tabindex="0" href="{{route('PurchaseBySku')}}">Purchase Report by SKU</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a tabindex="0" data-toggle="dropdown" data-submenu>
                    Settings<span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a tabindex="0" href="{{route('SkuMapping')}}">SKU Mapping</a></li>
                    <li><a tabindex="0" href="{{route('CarrierMapping')}}">Carrier Mapping</a></li>
                    <li><a tabindex="0" href="{{route('ShipStationImport')}}">ShipStation Import</a></li>
                    <li><a tabindex="0" href="{{route('NetSuiteImport')}}">NetSuite Import</a></li>
                </ul>
            </li>
        @endif
    @endif
</ul>
