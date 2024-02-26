@extends('layouts.admin_v2.template')

@section('content')
<div class="page-heading">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Features</h3>
			</div>
			<div class="col-12 col-md-6 order-md-2 order-first">
				<nav
					aria-label="breadcrumb"
					class="breadcrumb-header float-start float-lg-end"
				>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard') }}">Dashboard</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							Edit User
						</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<section class="section">
		<div class="row">
			@include('includes.admin_v2.alerts')
			<div class="col-12 col-lg-4">
				<div class="card">
					<div class="card-body">
						<div
							class="d-flex justify-content-center align-items-center flex-column"
						>
							<div class="avatar avatar-2xl">
								@if (filter_var($user->display_picture, FILTER_VALIDATE_URL))
									<img src="{{ $user->display_picture }}" alt="Avatar" id="preview_image"/>
								@else
									<img src="{{ asset('uploads/' . $user->display_picture) }}" alt="Avatar" id="preview_image"/>
								@endif
							</div>
							
							<h3 class="mt-3">{{ $user->name }}</h3>
							<form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
								@csrf
								@method('PATCH')
								
								<div class="form-group mx-2 my-2">
									<label for="password-vertical">Update Image</label>
									<div class="form-check float-end">
										<div class="checkbox">
											@if (!filter_var($user->display_picture, FILTER_VALIDATE_URL))
												<input type="checkbox" id="checkbox1" class="form-check-input" name="default-image">
												<small for="checkbox1">Use default image</small>
											@endif
										</div>
									</div>
									<input
										class="form-control"
										type="file"
										id="image"
										name="display_picture"
									/>
								</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-8">
				<div class="card">
					<div class="card-body">
						
							<div class="form-group">
								<label for="name" class="form-label">Name</label>
								<input
									type="text"
									name="name"
									class="form-control"
									placeholder="My Name"
									value="{{ $user->name }}"
								/>
								@if ($errors->has('name'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('name') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group">
								<label for="email" class="form-label">Email</label>
								<input
									type="text"
									name="email"
									class="form-control"
									placeholder="My Email"
									value="{{ $user->email }}"
								/>
								@if ($errors->has('email'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('email') }}</p>
									</span>
								@endif
							</div>
							@if (Auth::user()->role == 'admin')
								<div class="form-group">
								<label for="role" class="form-label">Role</label>
								<select name="role" class="form-select" {{ $user->role == 'admin' ? 'disabled' : '' }}>
									@if ($user->role == 'admin')
										<option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
									@endif
									<option value="reader" {{ $user->role == 'reader' ? 'selected' : '' }}>Reader</option>
									<option value="writter" {{ $user->role == 'writter' ? 'selected' : '' }}>Writter</option>
								</select>
								@if ($errors->has('role'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('role') }}</p>
									</span>
								@endif
							</div>
							@endif
							<div class="form-group">
								<label for="password" class="form-label">Password</label>
								<input
									type="password"
									name="password"
									class="form-control"
									placeholder="Minimum 8 characters"
								/>
								<small class="text-warning">Leave it blank if you're not changing password</small>
								@if ($errors->has('password'))
									<span class="help-block text-danger">
										<p>{{ $errors->first('password') }}</p>
									</span>
								@endif
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-block btn-primary me-1 my-2">
									Update
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection

@section('page_scripts')
	<script type="text/javascript" src="{{ asset('admin_v2/js/jquery.min.js')}}"></script>

	<script>
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				var targetPreview = 'preview_'+$(input).attr('id');
				reader.onload = function(e) {
					$('#' + targetPreview).attr('src', e.target.result).show();
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
		$("#image").change(function() {
			readURL(this);
		});
	</script>
@endsection