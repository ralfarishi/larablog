<!-- Navigation -->
<nav
	class="navbar navbar-default navbar-static-top"
	role="navigation"
	style="margin-bottom: 0"
>
	<div class="navbar-header">
		<button
			type="button"
			class="navbar-toggle"
			data-toggle="collapse"
			data-target=".navbar-collapse"
		>
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="{{route('admin.dashboard')}}"
			>Mading JeWePe - Admin</a
		>
	</div>
	<!-- /.navbar-header -->

	<ul class="nav navbar-top-links navbar-right">
		<li class="dropdown">
			<a href="{{route('home')}}" target="_blank">
				<i class="fa fa-home fa-fw"></i>
			</a>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li>
					<a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
				</li>
				<li class="divider"></li>
				<li>
					<a
						href="{{route('logout')}}"
						onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
						><i class="fa fa-sign-out fa-fw"></i> Logout</a
					>
				</li>
			</ul>
			<!-- /.dropdown-user -->
		</li>
		<form
			id="logout-form"
			action="{{ route('logout') }}"
			method="POST"
			style="display: none"
		>
			{{ csrf_field() }}
		</form>
		<!-- /.dropdown -->
	</ul>
	<!-- /.navbar-top-links -->

	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<li>
					<a href="{{url('admin/dashboard')}}"
						><i class="fa fa-dashboard fa-fw"></i> Dashboard</a
					>
				</li>
				<li>
					<a href="#"
						><i class="fa fa-pencil fa-fw"></i> Artikel<span
							class="fa arrow"
						></span
					></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('artikel.index') }}"
								><i class="fa fa-list fa-fw"></i>Semua Artikel</a
							>
						</li>
						<li>
							<a href="{{ route('artikel.create') }}"
								><i class="fa fa-plus fa-fw"></i>Buat Artikel</a
							>
						</li>
					</ul>
					<!-- /.nav-second-level -->
				</li>
				<li>
					<a href="{{ url('admin/komentar') }}"
						><i class="fa fa-comments" aria-hidden="true"></i> Komentar</a
					>
				</li>
				<li>
					<a href="{{ route('users.index') }}"
						><i class="fa fa-users fa-fw" aria-hidden="true"></i> Users</a
					>
				</li>
			</ul>
		</div>
		<!-- /.sidebar-collapse -->
	</div>
	<!-- /.navbar-static-side -->
</nav>
