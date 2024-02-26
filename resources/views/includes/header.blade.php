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
          @if (Auth::user()->role !== 'reader')
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="false">
                <i class="bi bi-bell-fill fs-5"></i>
                <!-- Counter - Alerts -->
                @php
                  $countUnread = $notifications->where('is_read', false)->count()
                @endphp
                @if ($countUnread)
                  <span class="badge bg-danger badge-counter rounded-pill">
                    {{
                      $countUnread > 3 ? $countUnread . '+' : $countUnread
                    }}
                </span>
                @endif
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <div class="dropdown-list-header">
                  <h6 class="dropdown-header text-uppercase fw-bold fs-6">
                    Notifications
                  </h6>
                </div>
                <div class="dropdown-list-content">
                  @foreach ($notifications as $notification)
                    <div class="dropdown-delete-container d-flex align-items-center">
                      <div class="dropdown-delete">
                        {{-- <a href="{{ route('delete-notification', $notification->id) }}">
                          <i class="bi bi-trash-fill text-danger fs-5"></i>
                        </a> --}}
                       <form action="{{ route('delete-notification', $notification->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                          <button class="btn btn-sm btn-danger" type="submit">
                            <i class="bi bi-trash-fill"></i>
                          </button>
                       </form>
                      </div>
                      <a class="dropdown-item d-flex align-items-center" href="{{ route('mark-as-read', $notification->id) }}">
                        <div class="me-3">
                          <div class="user-image">
                            <img src="https://ui-avatars.com/api/?name={{ $notification->commenter->slug }}" alt="">
                          </div>
                        </div>
                        <div>
                          <div class="small text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                          <span class="text-black {{ $notification->is_read ? '' : 'fw-bold' }}">
                            {{
                              $notification->commenter->name . ' ' . $notification->message . ' ' . $notification->posts->title
                            }}
                          </span>
                        </div>
                        <div class="me-3">
                          <div class="post-image">
                            <img src="{{ asset('uploads/' . $notification->posts->image) }}" alt="">
                          </div>
                        </div>
                      </a>
                    </div>
                  @endforeach
                </div>
                <div class="dropdown-list-footer d-flex align-items-center m-2 gap-2">
                  {{-- <a class="text-center small text-primary" href="{{ route('read-all-notifications') }}">Read all</a> --}}
                  @if ($notifications->count() > 0)
                    <form action="{{ route('read-all-notifications') }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <button class="btn btn-sm btn-primary" type="submit">Read All</button>
                    </form>
                    <form action="{{ route('delete-all-notifications') }}" method="POST">
                      @csrf
                      @method('POST')
                      <button class="btn btn-sm btn-danger" type="submit">Delete All</button>
                    </form>
                  @else
                    <div class="small text-secondary mx-auto">
                      No notifications
                    </div>
                  @endif
                </div>
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

