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
		<!-- Small boxes (Stat box) -->
		<div class="row justify-content-center">
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-info">
					<div class="inner">
						<h3>{{ $num_unread_comments }}</h3>

						<p>Total Komentar</p>
					</div>
					<div class="icon">
						<i class="ion ion-chatbubbles"></i>
					</div>
					<a href="{{ route('admin.komentar') }}" class="small-box-footer"
						>View Details <i class="fas fa-arrow-circle-right"></i
					></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
				<!-- small box -->
				<div class="small-box bg-success">
					<div class="inner">
						<h3>{{ $total_posts }}</h3>

						<p>Semua Artikel</p>
					</div>
					<div class="icon">
						<i class="ion ion-ios-paper"></i>
					</div>
					<a href="{{ route('artikel.index') }}" class="small-box-footer"
						>View Details <i class="fas fa-arrow-circle-right"></i
					></a>
				</div>
			</div>
			<!-- ./col -->
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
@endsection
