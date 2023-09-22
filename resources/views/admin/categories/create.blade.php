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
					<form action="{{ route('store-category') }}" enctype="multipart/form-data" method="POST">
						@csrf
						<div class="card-body">
							<div class="form-group">
								<label class="form-label" for="name">Nama*</label>
								<input type="text" class="form-control" name="name" placeholder="Nama kategori" />
								@if ($errors->has('name'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('name') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group">
								<label class="form-label" for="name">Icon*</label>
								<input type="text" class="form-control" name="icon" placeholder="Ex: fa-solid fa-microchip" />
								@if ($errors->has('icon'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('icon') }}</p>
									</span>
								@endif
							</div>
						</div>
						<!-- /.card-body -->
						<div class="card-footer">
							<input type="submit" class="btn btn-block btn-primary" value="Create"/>
						</div>
					</form>
				</div>
				<!-- /.card -->
			</div>
			<!--/.col (left) -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
@endsection

