<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('img/unand.png')}}" type="image/ico" />

    <title>{{ config('app.name', 'Laravel') }} | Login</title>

    <!-- Bootstrap -->

    <link href="{{ URL::asset('/gantela/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ URL::asset('/gantela/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ URL::asset('/gantela/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ URL::asset('/gantela/vendors/animate.css/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ URL::asset('/gantela/build/css/custom.min.css') }}" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
              <img src="{{asset('img/unand.png')}}" width="170px" height="100px" alt="">
                <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                    {{ csrf_field() }}
                    <h1>SIM-ASSET UNAND</h1>
                    <div>
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>

                        @if ($errors->has('username'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div>
                        <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">
                            {{ __('Login') }}
                        </button><br>
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        <a class="btn btn-link" href="{{ url('qrLogin') }}">
                            {{ __('QRLogin') }}
                        </a>
                        <a class="btn btn-link" href="{{ url('qrLogin2') }}">
                            {{ __('QRLogin') }}
                        </a>
                    </div>

                    <div class="clearfix"></div>
                </form>
            </section>
        </div>
      </div>
    </div>
  </body>
</html>
