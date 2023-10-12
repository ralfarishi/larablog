@extends('layouts.admin_v2.template')

@section('page_css')
	<link rel="stylesheet" href="{{ asset('admin_v2/css/toastui-editor/toastui-editor.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin_v2/css/toastui-editor/prism.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin_v2/css/toastui-editor/toastui-plugin-code-syntax-highlight.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('admin_v2/css/use-bootstrap-tag.min.css') }}">
@endsection

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
					<form action="{{ route('artikel.store') }}" enctype="multipart/form-data" method="POST" id="createPost">
						@csrf
						<div class="card-body">
							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
								<label class="form-label" for="title">Title*</label>
								<input type="text" class="form-control" name="title" placeholder="Judul artikel" />
								@if ($errors->has('title'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('title') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
								<label class="form-label">Content*</label>
								<div id="editor"></div>
								<input type="hidden" name="content" id="content">
								@if ($errors->has('content'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('content') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group">
								<label>Kategori*</label>
								<select class="form-control" style="width: 100%;" name="category_id">
									@foreach ($categories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group {{ $errors->has('featured_image') ? ' has-error ':''}}">
								<label class="form-label" for="feature_image">Image*</label>
								<div class="input-group">
									<div class="custom-file">
										<input class="form-control custom-file-input" type="file" name="featured_image" id="featured_image">
										<label
											class="custom-file-label"
											id="file-label"
											>Choose file</label
										>
									</div>
								</div>
								<img id="preview_featured_image" class="inputImgPreview w-25 mt-2" src="{{ isset($post) ? $post->featured_image : '' }}" />

								@if ($errors->has('featured_image'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('featured_image') }}</p>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
								<label class="form-label" for="tags">Tags*</label>
								<input name="tags" class="form-control" id="tags" type="text" placeholder="Pisahkan tags dengan tanda koma atau Enter" data-ub-tag-variant="dark"/>
								@if ($errors->has('tags'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('tags') }}</p>
									</span>
								@endif
							</div>

							{{-- {{ $post->allowed_comment == 1 ? 'checked' : '' }} --}}
							<div class="form-group clearfix">
								<label>Buka Komentar : </label>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioYes" name="allowed_comment" value="1" checked>
									<label for="radioYes">Ya</label>
								</div>
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioNo" name="allowed_comment" value="0">
									<label for="radioNo">Tidak</label>
								</div>
							</div>

							<div class="form-group clearfix">
								<label>Publish Artikel : </label>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioYesActive" name="active" value="1">
									<label for="radioYesActive">Ya</label>
								</div>
								<div class="icheck-danger d-inline">
									<input type="radio" id="radioNoActive" name="active" value="0" checked>
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
	<script type="text/javascript" src="{{ asset('admin_v2/js/toastui-editor/toastui-editor-all.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('admin_v2/js/toastui-editor/toastui-plugin-code-syntax-highlight-all.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('admin_v2/js/use-bootstrap-tag.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('back/js/select2.min.js')}}"></script>

	<script>
		const { Editor } = toastui;
		const { codeSyntaxHighlight } = Editor.plugin;

		const editor = new Editor({
			el: document.querySelector('#editor'),
			height: '450px',
			initialEditType: 'markdown',
  		previewStyle: 'vertical',
			plugins: [codeSyntaxHighlight]
		});

		document.querySelector('#createPost').addEventListener('submit', e => {
			e.preventDefault();
			document.querySelector('#content').value = editor.getMarkdown();
			e.target.submit();
		});
	</script>

	<script type="text/javascript">
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

