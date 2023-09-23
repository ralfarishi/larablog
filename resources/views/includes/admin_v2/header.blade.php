<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#" role="button"
				><i class="fa-solid fa-bars"></i
			></a>
		</li>
		<li class="nav-item d-none d-sm-inline-block">
			<a href="{{ route('dashboard') }}" class="nav-link">Home</a>
		</li>
	</ul>

	<ul class="navbar-nav ml-auto">
		<li class="nav-item">
			<a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
				<i class="fa-solid fa-sign-out-alt"></i>
			</a>
		</li>

		<form
			id="logout-form"
			action="{{ route('logout') }}"
			method="POST"
			style="display: none"
		>
			{{ csrf_field() }}
		</form>
	</ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="{{ route('home') }}" class="brand-link">
		<img
			src="{{ asset('images/AdminLTELogo.png') }}"
			alt="AdminLTE Logo"
			class="brand-image img-circle elevation-3"
			style="opacity: 0.8"
		/>
		<span class="brand-text font-weight-light">Sekolah JeWePe</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul
				class="nav nav-pills nav-sidebar flex-column"
				data-widget="treeview"
				role="menu"
				data-accordion="false"
			>
				<!-- Add icons to the links using the .nav-icon class
					with font-awesome or any other icon font library -->
				<li class="nav-item">
					<a href="{{ url('admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
						<i class="nav-icon fa-solid fa-gauge"></i>
						<p>Dashboard</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fa-solid fa-file-lines"></i>
						<p>
							Artikel
							<i class="fa-solid fa-angle-left right"></i>
							<span class="badge badge-info right">2</span>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="{{ route('artikel.index') }}" class="nav-link {{ request()->routeIs('artikel.index') ? 'active' : '' }}">
								<i class="far fa-regular fa-circle nav-icon"></i>
								<p>Semua Artikel</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('artikel.create') }}" class="nav-link {{ request()->routeIs('artikel.create') ? 'active' : '' }}">
								<i class="far fa-regular fa-circle nav-icon"></i>
								<p>Buat Artikel</p>
							</a>
						</li>
					</ul>
				</li>
				<li class="nav-item">
					<a href="{{ route('komentar.index') }}" class="nav-link ">
						<i class="nav-icon fa-solid fa-comments"></i>
						<p>Komentar</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('kategori.index') }}" class="nav-link ">
					<i class="nav-icon fa-solid fa-bookmark"></i>
						<p>Kategori</p>
					</a>
				</li>
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fa-solid fa-users"></i>
						<p>Users</p>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>