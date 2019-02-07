<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
  <link rel="icon" href="{{asset('img/unand.png')}}" type="image/ico" />
	<link rel="stylesheet" href="{{asset('dira/login/style.css')}}">

<body>
<div class="login-box">
	<h1>Login</h1>
	<form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
			{{ csrf_field() }}
					<div class="textbox">
						<i class="fa fa-user" ></i>
					  <input type="text" placeholder="Username" name="username" value="">
					</div>

					<div class="textbox">
						<i class="fa fa-lock" ></i>
					  <input type="password" placeholder="Password" name="password" value="">
					</div>

					<input class="btn" type="submit" name="" value="Sign in">
		</form>

</div>
</body>
</html>
