<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('title')</title>

		<!-- Google font -->
		<link
			href="https://fonts.googleapis.com/css?family=Oswald:700"
			rel="stylesheet"
		/>
		<link
			href="https://fonts.googleapis.com/css?family=Lato:400"
			rel="stylesheet"
		/>

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="{{ asset('css/404/style.css') }}" />

	</head>
	<body>
		<div id="notfound">
			<div class="notfound-bg">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div class="notfound">
				<div class="notfound-404">
					<h1>@yield('code')</h1>
				</div>
				<h2>@yield('title')</h2>
				
				@yield('message')
			</div>
		</div>
	</body>
</html>
