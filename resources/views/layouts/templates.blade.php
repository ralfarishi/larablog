<!DOCTYPE html>
<html>
<head>
    @include('includes.head')
    @yield('page_css')
</head>
<body>
    <!--start-header-->
    @include('includes.header')
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