<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Sekolah JWP | Log in</title>

		<!-- Google Font: Source Sans Pro -->
		<link
			rel="stylesheet"
			href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
		/>
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ asset('admin_v2/css/fontawesome-free/css/all.min.css') }}" />
		<!-- icheck bootstrap -->
		<link
			rel="stylesheet"
			href="{{ asset('admin_v2/css/icheck-bootstrap.min.css') }}"
		/>
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ asset('admin_v2/css/adminlte.min.css') }}" />
	</head>
	<body class="hold-transition login-page">
		<div class="login-box">
			<!-- /.login-logo -->
			<div class="card card-outline card-primary">
				<div class="card-header text-center">
					<a href="{{ route('home') }}" class="h1"><b>Sekolah</b>JeWePe</a>
				</div>
				<div class="card-body">
					<p class="login-box-msg">Sign in to start your session</p>
					@include('includes.admin_v2.alerts')
					<form id="login_form" action="{{ route('auth') }}" method="POST">
						{{ csrf_field() }}
						<div class="input-group mb-3 form-group">
							<input
								type="email"
								name="email"
								class="form-control"
								placeholder="Email"
								value="{{ old('email') }}"
								autofocus
							/>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-envelope"></span>
								</div>
							</div>
						</div>
						@if ($errors->has('email'))
							<span class="help-block">
								<p class="text-danger">{{ $errors->first('email') }}</p>
							</span>
						@endif
						<div class="input-group mb-3 form-group">
							<input
								type="password"
								class="form-control"
								placeholder="Password"
								name="password"
								value=""
							/>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
							
						</div>
						@if ($errors->has('password'))
							<span class="help-block">
								<p class="text-danger">{{ $errors->first('password') }}</p>
							</span>
						@endif
						<div class="row">
							<div class="col-8">
								<div class="icheck-primary">
									<input type="checkbox" id="remember" />
									<label for="remember"> Remember Me </label>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-4">
								<input type="submit" class="btn btn-primary btn-block"/>
							</div>
							<!-- /.col -->
						</div>
					</form>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
		<!-- /.login-box -->

		<!-- jQuery -->
		<script src="{{ asset('admin_v2/js/jquery.min.js') }}"></script>
		<!-- Bootstrap 4 -->
		<script src="{{ asset('admin_v2/js/bootstrap.bundle.min.js') }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ asset('admin_v2/js/adminlte.min.js') }}"></script>
	</body>
</html>
