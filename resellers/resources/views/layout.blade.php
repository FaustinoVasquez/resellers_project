<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8" />
    <title>Laravel</title>

    <!-- Latest compiled and minified CSS -->
    {{--<link rel="stylesheet" href="{{ asset('assets/jquery-ui/jquery-ui.min.css') }}"/>--}}
    {{--<link rel="stylesheet" href="{{ asset('assets/jquery-ui/jquery-ui.theme.min.css') }}"/>--}}
    {{--<link rel="stylesheet" href="{{ asset('assets/jqgrid/css/ui.jqgrid.css') }}"/>--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/jqgrid/css/trirand/ui.jqgrid-bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/bootstrap-jqgrid.css') }}"/>
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/datepicker/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}"/>
    {{--<link rel="stylesheet" href="{{ asset('assets/jqgrid/css/ui.jqgrid-bootstrap-ui.css') }}"/>--}}

    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">--}}
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Portal2</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            @include('partials/mainMenuOptions')

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->fullname }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ route('logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

        <!-- Scripts -->

<script type="text/ecmascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="text/ecmascript" src="{{ asset('assets/jqgrid/js/trirand/i18n/grid.locale-en.js') }}"></script>
<script type="text/ecmascript" src="{{ asset('assets/jqgrid/js/trirand/jquery.jqGrid.min.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="{{ asset('assets/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/common.js') }}"></script>
<script src="{{ asset('assets/js/jQuery.MultiFile.min.js') }}"></script>
<script src="{{ asset('assets/js/formatter.js') }}"></script>
<script src="{{ asset('assets/js/jquery.bootpag.min.js') }}"></script>
@yield('scripts')
</body>
</html>
