<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

    <a href="{{ route('home') }}" class="logo d-flex align-items-center">

      <h1 class="d-flex align-items-center">Sekolah JeWePe</h1>
    </a>

    <i class="mobile-nav-toggle mobile-nav-show fa-solid fa-bars"></i>
    <i class="mobile-nav-toggle mobile-nav-hide d-none fa-solid fa-xmark"></i>

    <nav id="navbar" class="navbar">
      <ul>
        <li>
          <a href="{{ route('home') }}" @if(Request::segment('1') == '') class="active" @endif>
            Home
          </a>
        </li>
        @guest
          <li>
            <a href="{{ route('login') }}">
              Login
            </a>
          </li>
        @endguest
          
        @auth
          @if (Auth::user()->role !== 'user')
            <li class="dropdown">
              <a href="#">
                <span>{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down dropdown-indicator"></i>
              </a>
              <ul>
                <li>
                  <a href="{{ route('dashboard') }}">
                    Dashboard
                  </a>
                </li>
                <li>
                  <a
                    href="{{ route('logout') }}"
                    onclick="
                      event.preventDefault();
                      document.getElementById('logout-form').submit();"
                  >
                    Logout
                  </a>
                </li>
              </ul>
            </li>
          @else
            <li>
              <a
                href="{{ route('logout') }}"
                onclick="
                  event.preventDefault();
                  document.getElementById('logout-form').submit();"
              >
                Logout
              </a>
            </li>
          @endif
        @endauth
      </ul>

      <form
        id="logout-form"
        action="{{ route('logout') }}"
        method="POST"
        style="display: none"
      >
        @csrf
      </form>
    </nav><!-- .navbar -->

  </div>
</header>

