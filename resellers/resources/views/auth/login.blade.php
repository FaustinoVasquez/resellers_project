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
                <form method="post" action="{{ URL::to('login') }}">
                    {{csrf_field()}}
                    <img class="img-responsive" src="{{ URL::asset('images/MI_Technologies_blue.png') }}"/>
                    <h4 class="text-center">Sign In to Resellers Portal</h4>
                    @include('partials/errors')
                    @include('partials/success')
                    <input type="text" name="usr" class="form-control uname" placeholder="Username"/>
                    <input type="password" class="form-control pword" placeholder="Password" name="password"/>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="checkbox ">
                          <label>
                            <input type="checkbox" name="remember"> Remember Me
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <a href="{{ URL::to('password/email') }}" class="pull-right" style="margin-top:9px"><small>Forgot Your Password?</small></a>
                      </div>
                    </div>
                    <button class="btn btn-primary btn-block">Sign In</button>

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


<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/jquery.cookies.js"></script>

<script src="js/toggles.min.js"></script>
<script src="js/retina.min.js"></script>

</body>
</html>
