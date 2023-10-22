@extends('layouts.admin_v2.template')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Dashboard</h1>
			</div>
			<!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Dashboard</li>
				</ol>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-lg-3 col-6">
				<div class="small-box bg-indigo">
					<div class="inner">
						<h3>{{ $totalPosts }}</h3>
						<p>Artikel Dibuat</p>
					</div>
					<div class="icon">
						<i class="fas fa-newspaper"></i>
					</div>
					<a href="{{ route('artikel.index') }}" class="small-box-footer"
						>View Details <i class="fas fa-arrow-circle-right"></i
					></a>
				</div>
			</div>
			<div class="col-lg-3 col-6">
				<div class="small-box bg-maroon">
					<div class="inner">
						<h3>{{ $totalComments }}</h3>
						<p>Komentar Diberikan</p>
					</div>
					<div class="icon">
						<i class="fa-solid fa-comments"></i>
					</div>
					<a href="{{ route('komentar.index') }}" class="small-box-footer"
						>View Details <i class="fas fa-arrow-circle-right"></i
					></a>
				</div>
			</div>
			@if (Auth::user()->role == 'admin')
				<div class="col-lg-3 col-6">
					<div class="small-box bg-fuchsia">
						<div class="inner">
							<h3>{{ $totalCategories }}</h3>
							<p>Kategori Dibuat</p>
						</div>
						<div class="icon">
							<i class="fa-solid fa-tags"></i>
						</div>
						<a href="{{ route('kategori.index') }}" class="small-box-footer"
							>View Details <i class="fas fa-arrow-circle-right"></i
						></a>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-teal">
						<div class="inner">
							<h3>{{ $totalUsers }}</h3>
							<p>Jumlah Pengguna</p>
						</div>
						<div class="icon">
							<i class="fa-solid fa-users"></i>
						</div>
						<a href="{{ route('user.index') }}" class="small-box-footer"
							>View Details <i class="fas fa-arrow-circle-right"></i
						></a>
					</div>
				</div>
			@endif
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
@endsection
