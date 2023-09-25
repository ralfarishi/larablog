@extends('layouts.admin_v2.template')

@section('page_css')
	<link rel="stylesheet" href="{{ asset('css/use-bootstrap-tag.min.css') }}">
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Edit Artikel</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Edit Artikel</li>
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
					<form action="{{ route('artikel.update', $post->id) }}" enctype="multipart/form-data" method="POST">
						@method('PATCH')
						@csrf
						<div class="card-body">
							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
								<label class="form-label" for="title">Title*</label>
								<input type="text" class="form-control" name="title" value="{{ $post->title }}" />
								@if ($errors->has('title'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('title') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
								<label class="form-label" for="content">Content*</label>

								<textarea name="content" class="form-control" id="content">
									{!! $post->content !!}
								</textarea>
								@if ($errors->has('content'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('content') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group">
								<label>Kategori*</label>
								<select class="form-control" style="width: 100%;" name="category_id">
									<option value="{{ $post->category_id }}">{{ $post->category->name }}</option>
									@foreach ($categories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group {{ $errors->has('featured_image') ? ' has-error ':''}}">
								<label class="form-label" for="feature_image">Image*</label>
								<div class="input-group">
									<div class="custom-file">
										<input class="form-control custom-file-input" type="file" name="feature_image" id="feature_image">
										<label
											class="custom-file-label"
											id="file-label"
											>Choose file</label
										>
									</div>
								</div>
								<img id="preview_featured_image" class="inputImgPreview w-25 mt-2" src="{{ asset('uploads/' . $post->featured_image ?? '') }}" class="img-thumbnail"/>

								@if ($errors->has('featured_image'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('featured_image') }}</p>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
								<label class="form-label" for="tags">Tags*</label>
								<input name="tags" class="form-control" id="tags" type="text" placeholder="Pisahkan tags dengan tanda koma atau Enter" data-ub-tag-variant="dark" value="{{ $post->tags }}"/>
								@if ($errors->has('tags'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('tags') }}</p>
									</span>
								@endif
							</div>

							<div class="form-group clearfix">
								<label>Buka Komentar : </label>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioYes" name="allowed_comment" value="1"{{ $post->allowed_comment == 1 ? 'checked' : '' }}>
									<label for="radioYes">Ya</label>
								</div>
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioNo" name="allowed_comment" value="0" {{ $post->allowed_comment == 0 ? 'checked' : '' }}>
									<label for="radioNo">Tidak</label>
								</div>
							</div>

							<div class="form-group clearfix">
								<label>Publish Artikel : </label>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioYesActive" name="active" value="1" {{ $post->active == 1 ? 'checked' : '' }}>
									<label for="radioYesActive">Ya</label>
								</div>
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioNoActive" name="active" value="0" {{ $post->active == 0 ? 'checked' : '' }}>
									<label for="radioNoActive">Tidak</label>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
						
						<div class="card-footer">
							<input type="submit" class="btn btn-block btn-primary" value="Save"/>
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

@section('page_scripts')
	<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
	<script type="text/javascript" src="{{ asset('js/use-bootstrap-tag.min.js')}}"></script>
	<script type="text/javascript" src="{{URL::asset('back/js/select2.min.js')}}"></script>

	<script type="text/javascript">
		CKEDITOR.replace('content');

		UseBootstrapTag(document.getElementById('tags'))

		document.getElementById('featured_image').addEventListener('change', function(e) {
			var fileName = e.target.files[0].name;
			document.getElementById('file-label').textContent = fileName;
		});

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				var targetPreview = 'preview_'+$(input).attr('id');
				reader.onload = function(e) {
						$('#'+targetPreview).attr('src', e.target.result).show();
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#featured_image").change(function() {
			readURL(this);
		});
	</script>
@endsection

