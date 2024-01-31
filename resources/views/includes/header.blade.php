<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

    <a href="{{ route('home') }}" class="logo d-flex align-items-center">

      <h1 class="d-flex align-items-center">Sekolah JeWePe</h1>
    </a>

    <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
    <i class="mobile-nav-toggle mobile-nav-hide d-none fa-solid fa-xmark"></i>

    <nav id="navbar" class="navbar">
      <ul>
        @guest
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
        @endguest
          
        @auth
          @if (Auth::user()->role !== 'user')
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-bell-fill fs-5"></i>
                <!-- Counter - Alerts -->
                <span class="badge bg-danger badge-counter rounded-pill">2+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header text-uppercase fw-bold fs-6">
                  Notifications
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="me-3">
                    <div class="user-image">
                      <img src="https://ui-avatars.com/api/?name=julian+alfares" alt="">
                    </div>
                  </div>
                  <div>
                    <div class="small text-secondary">December 12, 2019</div>
                    <span class="fw-bold text-black">Julian Alfares commented on your post!</span>
                  </div>
                  <div class="me-3">
                    <div class="post-image">
                      <img src="http://sekolah-jewepe.test/uploads/images/blogs/nRpfV6FASF1SlZMRfv7uYHFvWVJsdJd0h0zbXT4G.png" alt="">
                    </div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="me-3">
                    <div class="user-image">
                      <img src="https://ui-avatars.com/api/?name=mikayla+sandra" alt="">
                    </div>
                  </div>
                  <div>
                    <div class="small text-secondary">December 12, 2019</div>
                    <span class="text-black">A new monthly report is ready to download!</span>
                  </div>
                  <div class="me-3">
                    <div class="post-image">
                      <img src="http://sekolah-jewepe.test/uploads/images/blogs/gnXkCHNIdpDtxnW6EKwRI3AJ0zxkgnO4vUseUYXi.jpg" alt="">
                    </div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-secondary" href="#">Show All Alerts</a>
              </div>
            </li>
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

