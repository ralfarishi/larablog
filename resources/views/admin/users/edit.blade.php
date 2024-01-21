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

	<section id="basic-vertical-layouts">
		<div class="row match-height">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Edit User</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form class="form form-vertical" action="{{ route('user.update', $user->id) }}" method="POST">
								@csrf
								@method('PATCH')
								<div class="form-body">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Name*</label>
												<input
													type="text"
													class="form-control"
													name="name"
													value="{{ $user->name }}"
												/>
												@if ($errors->has('name'))
													<span class="help-block text-danger">
														<p>{{ $errors->first('name') }}</p>
													</span>
												@endif
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Email*</label>
												<input
													type="text"
													class="form-control"
													name="email"
													value="{{ $user->email }}"
												/>
												@if ($errors->has('email'))
													<span class="help-block text-danger">
														<p>{{ $errors->first('email') }}</p>
													</span>
												@endif
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Role</label>
												<select name="role" id="" class="form-select" {{ $user->role === 'admin' ? 'disabled' : '' }}>
													<option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
													<option value="writter" {{ $user->role === 'writter' ? 'selected' : '' }}>Writter</option>
													<option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
												</select>
												@if ($errors->has('role'))
													<span class="help-block text-danger">
														<p>{{ $errors->first('role') }}</p>
													</span>
												@endif
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Password*</label>
												<input
													type="password"
													class="form-control"
													name="password"
													placeholder="Min. 8 characters"
												/>
												<small class="text-warning">Leave it blank if you're not changing password</small>
												@if ($errors->has('password'))
													<span class="help-block text-danger">
														<p>{{ $errors->first('password') }}</p>
													</span>
												@endif
											</div>
										</div>
										<div class="col-12 d-flex justify-content-end">
											<button
												type="submit"
												class="btn btn-primary btn-block me-1 my-2"
											>
												Update
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection