<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

    <a href="{{ route('home') }}" class="logo d-flex align-items-center">

      <h1 class="d-flex align-items-center">Sekolah JeWePe</h1>
    </a>

    <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
    <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    <nav id="navbar" class="navbar">
      <ul>
        <li>
          <a href="{{ route('home') }}" @if(Request::segment('1') == '') class="active" @endif>
            Home
          </a>
        </li>
        <li>
          <a href="{{ route('login') }}">
            Login
          </a>
        </li>
      </ul>
    </nav><!-- .navbar -->

  </div>
</header>

