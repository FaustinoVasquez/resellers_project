<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>MI Technologies, Inc. | @yield('title')</title>

  <link href="css/style.default.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <link href="css/font.roboto.css" rel="stylesheet">
  <link href="css/gritter.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->

  <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/jqgrid/css/trirand/ui.jqgrid-bootstrap.css') }}"/>
  <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/bootstrap-jqgrid.css') }}"/>
  <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/datepicker/css/bootstrap-datepicker3.min.css') }}"/>
  <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('css/custom.css') }}"/>

  {{--<link rel="stylesheet" href="{{ asset('assets/jqgrid/css/ui.jqgrid-bootstrap-ui.css') }}"/>--}}

  @yield('css')

</head>

<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status-load"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

  <div class="leftpanel">

    <div class="logopanel">
        <h1><img class="img-responsive" src="{{ URL::asset('images/MI_Technologies_blue.png') }}"/></h1>
    </div><!-- logopanel -->

    <div class="leftpanelinner">

        <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <h5 class="sidebartitle actitle">Account</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
              <li><a href="{{ URL::to('usersettings') }}"><i class="glyphicon glyphicon-user"></i> Account Settings</a></li>
              <li><a href="{{ URL::to('logout') }}"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
            </ul>
        </div>

        <!-- Sidebar -->
        @include('sidebar')

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">

    <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group">
              <button class="btn btn-default dropdown-toggle tp-icon" data-toggle="dropdown" id="button-cart">
                <i class="glyphicon glyphicon-shopping-cart"></i>
                <div id="cart-counter">
                </div>
              </button>
              <div class="dropdown-menu dropdown-menu-head pull-right">
                <h5 class="title">Your cart</h5>
                <ul class="dropdown-list gen-list">
                  <div id="cart-loading"><i class="fa fa-spinner fa-spin"></i></div>
                  <div id="cart-items">
                  </div>
                </ul>
              </div>
            </div>
          </li>
          <li>
            <div class="btn-group hidden-xs">
              <button type="button" class="btn btn-default dropdown-toggle tp-icon" data-toggle="dropdown" id="name-drop" aria-expanded="false">
                {{Auth::user()->fullname}}
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="{{ URL::to('usersettings') }}"><i class="glyphicon glyphicon-user"></i> Account Settings</a></li>
                <li><a href="{{ URL::to('logout') }}"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
              </ul>
            </div>
            <div class="btn-group visible-xs">
              <button type="button" class="btn btn-default tp-icon" id="name-drop-xs" aria-expanded="false">
                {{Auth::user()->fullname}}
                <span class="caret"></span>
              </button>
            </div>
          </li>
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->

    <div class="pageheader">
      <h2>@yield('title') @yield('buttons')</h2>
      <!-- <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="index.html">Bracket</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </div> -->
    </div>

    <div class="contentpanel">
      @yield('page')
    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

  <div class="rightpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#rp-alluser" data-toggle="tab"><i class="fa fa-users"></i></a></li>
        <li><a href="#rp-favorites" data-toggle="tab"><i class="fa fa-heart"></i></a></li>
        <li><a href="#rp-history" data-toggle="tab"><i class="fa fa-clock-o"></i></a></li>
        <li><a href="#rp-settings" data-toggle="tab"><i class="fa fa-gear"></i></a></li>
    </ul>
  </div><!-- rightpanel -->


</section>


<script src="{{ URL::asset('js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery-ui-1.10.3.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('js/modernizr.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.sparkline.min.js') }}"></script>
<script src="{{ URL::asset('js/toggles.min.js') }}"></script>
<script src="{{ URL::asset('js/retina.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.cookies.js') }}"></script>

<script src="{{ URL::asset('js/flot/jquery.flot.min.js') }}"></script>
<script src="{{ URL::asset('js/flot/jquery.flot.resize.min.js') }}"></script>
<script src="{{ URL::asset('js/flot/jquery.flot.spline.min.js') }}"></script>
<script src="{{ URL::asset('js/raphael-2.1.0.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.gritter.min.js') }}"></script>

<script src="{{ URL::asset('js/custom.js') }}"></script>

<script src="{{ asset('js/common/common.js') }}"></script>
<script type="text/ecmascript" src="{{ asset('assets/jqgrid/js/trirand/i18n/grid.locale-en.js') }}"></script>
<script type="text/ecmascript" src="{{ asset('assets/jqgrid/js/trirand/jquery.jqGrid.min.js') }}"></script>
<script src="{{ asset('assets/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/common.js') }}"></script>
<script src="{{ asset('assets/js/jQuery.MultiFile.min.js') }}"></script>
<script src="{{ asset('assets/js/formatter.js') }}"></script>
<script src="{{ asset('assets/js/jquery.bootpag.min.js') }}"></script>
<script src="{{ asset('js/cart/cart.js') }}"></script>

@yield('scripts')

</body>
</html>
