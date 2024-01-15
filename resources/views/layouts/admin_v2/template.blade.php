<!DOCTYPE html>
<html lang="en">
	<head>
		@include('includes.admin_v2.head')
		@yield('page_css')
	</head>

	<body>
		<script src="{{ asset('admin_v2/js/initTheme.js') }}"></script>
		<div id="app">
			@include('includes.admin_v2.header')

			<div id="main">
				<header class="mb-3">
					<a href="#" class="burger-btn d-block d-xl-none">
						<i class="bi bi-justify fs-3"></i>
					</a>
				</header>
				
				@yield('content')
			</div>
		</div>
		
		@include('includes.admin_v2.scripts')
		@yield('page_scripts')
	</body>
</html>
