<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @include ('includes.head')
  @yield ('page_css')
  @livewireStyles
</head>
<body class="bg-background text-foreground flex min-h-screen flex-col pt-16">
  <!--start-header-->
  @if (!isset($hideNavbar) || !$hideNavbar)
    @include ('includes.header')
  @endif
  <!--End-header-->
  <main id="main" class="flex-grow">
    <!--Main-Content-->
    @yield ('content-id')
    <!--Main-Content-end-->
  </main>
  <!--footer-starts-->
  @if (!isset($hideFooter) || !$hideFooter)
    @include ('includes.footer')
  @endif
  <!--global-Js-->
  @include ('includes.scripts')

  @yield ('page_scripts')
  @livewireScripts
</body>
</html>
