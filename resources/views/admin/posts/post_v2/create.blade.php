@extends('layouts.admin_v2.template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Buat Artikel</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Buat Artikel</li>
				</ol>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="card">
					<!-- /.card-header -->
					<!-- form start -->
					{{ Form::open(['url' => route("artikel.store"), 'method' => 'POST','files'=>true]) }}
						<div class="card-body">
							@include('admin.posts.post_v2.form')
						</div>
						<!-- /.card-body -->

						<div class="card-footer">
							<input type="submit" class="btn btn-block btn-primary" value="Save">
								Save
							</input>
						</div>
					{{ Form::close() }}
				</div>
				<!-- /.card -->
			</div>
			<!--/.col (left) -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>

{{-- 
<div class="row">
		<div class="col-md-12">
				{{Form::open(['url' => route("artikel.store"), 'method' => 'POST','files'=>true])}}
				@include('admin.posts.form')
				<div class="form-group text-center">
						<input type="submit" class="btn btn-primary" value="Save">
				</div>
				{{Form::close()}}
		</div>
</div> --}}
@endsection

