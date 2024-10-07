<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>MI Technologies, Inc. | Login</title>

  <link href="{{ URL::asset('css/style.default.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('css/login.css') }}" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="signin">


<section>

    <div class="signinpanel">

        <div class="row">

            <div class="col-md-6 col-md-offset-3">
                <form method="post" action="/password/email">
                    {{csrf_field()}}
                    <img class="img-responsive" src="{{ URL::asset('images/MI_Technologies_blue.png') }}"/>
                    <h4 class="text-center">Password Reset</h4>
                    @include('partials/errors')
                    @include('partials/success')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <p class="text-center">
                      Use your e-mail address to reset your password. An e-mail will be sent to your account wiht the link to reset your password.
                    </p>

                    <div class="form-group">
                      <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address">
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn btn-primary btn-block">
                          Send Password Reset Link
                      </button>
                    </div>

                </form>
            </div><!-- col-sm-5 -->

        </div><!-- row -->

        <div class="signup-footer">
            <div class="pull-left">
                &copy; MI Technologies, Inc. {{Carbon\Carbon::now()->format('Y')}}
            </div>
        </div>

    </div><!-- signin -->

</section>


<script src="{{URL::asset('js/jquery-1.11.1.min.js')}}"></script>
<script src="{{URL::asset('js/jquery-migrate-1.2.1.min.js')}}"></script>
<script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
<script src="{{URL::asset('js/modernizr.min.js')}}"></script>
<script src="{{URL::asset('js/jquery.sparkline.min.js')}}"></script>
<script src="{{URL::asset('js/jquery.cookies.js')}}"></script>

<script src="{{URL::asset('js/toggles.min.js')}}"></script>
<script src="{{URL::asset('js/retina.min.js')}}"></script>

</body>
</html>
