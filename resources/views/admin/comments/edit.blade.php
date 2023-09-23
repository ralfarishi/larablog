@extends('layouts.admin_v2.template')

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Edit Komentar</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Edit Komentar</li>
				</ol>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="card">
					<!-- /.card-header -->
					@include('includes.admin_v2.alerts')
					<!-- form start -->
					<form action="{{ route('komentar.update', $comment->id) }}" method="POST">
						@method('PATCH')
						@csrf
						<div class="card-body">
							<div class="form-group">
								<label for="user_name" class="form-label">Nama</label>
								<input type="text" name="user_name" class="form-control" placeholder="{{ $comment->user_name }}" disabled>
            	</div>
            
							<div class="form-group">
								<label for="user_email" class="form-label">Email</label>
								<input type="text" name="user_email" class="form-control" placeholder="{{ $comment->user_email }}" disabled>
							</div>

							<div class="form-group">
								<label class="form-label" for="article">Artikel</label><br/>
								<a href="{{ route('post', $comment->post->slug) }}" target="_blank">{{$comment->post->title}}</a>
							</div>
							
							<div class="form-group">
								<label for="comment" class="form-label">Komentar</label>
								
								<textarea name="content" class="form-control" placeholder="{{ $comment->content }}" disabled></textarea>
							</div>
							
							<div class="form-group clearfix">
								<label>Tampilkan?</label>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioYes" name="active" value="1" {{ $comment->active == 1 ? 'checked' : '' }}>
									<label for="radioYes">Ya</label>
								</div>
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioNo" name="active" value="0" {{ $comment->active == 0 ? 'checked' : '' }}>
									<label for="radioNo">Tidak</label>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
						<div class="card-footer">
							<input type="submit" class="btn btn-block btn-primary" value="Save" />
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
