@extends('layouts.admin_v2.template')

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Tambah Pengguna</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Tambah Pengguna</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<form action="{{ route('user.update', $user->id) }}" method="POST">
						@method('PATCH')
            @csrf
							<div class="card-body">
								<div class="form-group">
									<label class="form-label" for="name">Nama*</label>
									<input type="text" class="form-control" name="name" placeholder="Nama pengguna" value="{{ $user->name }}"/>
									@if ($errors->has('name'))
										<span class="help-block text-danger">
											<p>{{ $errors->first('name') }}</p>
										</span>
									@endif
								</div>
								<div class="form-group">
									<label class="form-label" for="email">Email*</label>
									<input
										type="email"
										class="form-control"
										name="email"
										placeholder="Email pengguna"
										value="{{ $user->email }}"
									/>
									@if ($errors->has('email'))
										<span class="help-block text-danger">
											<p>{{ $errors->first('email') }}</p>
										</span>
									@endif
								</div>
								<div class="form-group">
									<label class="form-label" for="email">Role</label>
									<input
										type="text"
										class="form-control"
										name="role"
										value="{{ Str::upper($user->role) }}"
										disabled
									/>
									@if ($errors->has('role'))
										<span class="help-block text-danger">
											<p>{{ $errors->first('role') }}</p>
										</span>
									@endif
								</div>
								<div class="form-group">
									<label class="form-label" for="password">Password</label>
									<input type="password" class="form-control" name="password" placeholder="Min. 5 karakter"/>
									<small class="text-danger">Kosongkan jika tidak ingin mengubah password</small>
									@if ($errors->has('password'))
										<span class="help-block text-danger">
											<p>{{ $errors->first('password') }}</p>
										</span>
									@endif
								</div>
							</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-block btn-primary">Update</button>
						</div>
          </form>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

