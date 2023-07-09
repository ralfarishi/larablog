<!DOCTYPE html>
<html lang="en">
	<head>
		@include('includes.admin_v2.head')
		@yield('page_css')
	</head>

	<body class="hold-transition sidebar-mini layout-fixed">
		<!--wrapper -->
		<div class="wrapper">
			<!-- Preloader -->
			{{-- <div
				class="preloader flex-column justify-content-center align-items-center"
			>
				<img
					class="animation__shake"
					src="{{ asset('images/AdminLTELogo.png') }}"
					alt="AdminLTELogo"
					height="60"
					width="60"
				/>
			</div> --}}

			@include('includes.admin_v2.header')

			<!-- page-wrapper -->
			<div class="content-wrapper">

				@yield('content')

			</div>
			<!-- /#page-wrapper -->
		</div>
		<!-- /#wrapper -->
		@include('includes.admin_v2.scripts')

		@yield('page_scripts')
	</body>
</html>
