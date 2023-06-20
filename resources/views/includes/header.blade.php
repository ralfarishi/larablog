<header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="{{ url('/') }}" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>Sekolah JeWePe<span>.</span></h1>
      </a>
     {{ Form::open(['url'=>route('blog.search'),'method'=>'get', 'class'=>'input-group mt-2 w-25']) }}
      <div class="input-group">
        {{ Form::text('search_str',null,['placeholder'=>'Cari artikel', 'class'=>'form-control']) }}
        <span class="input-group-text">
          <i class="bi bi-search"></i>
        </span>
      </div>
      {{-- <button class="btn btn-info" id="button-addon2" type="submit">Search</button> --}}
     {{ Form::close() }}
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="{{ url('/') }}" @if(Request::segment('1') == '') class="active" @endif>Home</a></li>
          <li><a href="{{ url('/login') }}">Login</a></li>
        </ul>
      </nav><!-- .navbar -->

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
    </div>
  </header>

