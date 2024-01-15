<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include('includes.head')
		@yield('page_css')
	</head>
	<body class="page-blog">
		<!--start-header-->
		@if (!isset($hideNavbar) || !$hideNavbar)
			@include('includes.header')
		@endif
		<!--End-header-->
		<main id="main">
			<!--Main-Content-->
			@yield('content-id')
			<!--Main-Content-end-->
		</main>
		<!--footer-starts-->
		{{-- @include('includes.footer') --}}
		<!--global-Js-->
		@include('includes.scripts')
		
		@yield('page_scripts')
	</body>
</html>