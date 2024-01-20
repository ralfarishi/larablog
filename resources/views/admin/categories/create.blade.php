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
							Create Category
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
						<h4 class="card-title">Create Category</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form class="form form-vertical" action="{{ route('category.store') }}" method="POST">
								@csrf
								<div class="form-body">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Name*</label>
												<input
													type="text"
													class="form-control"
													name="name"
													placeholder="Category name"
													value="{{ old('name') }}"
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
												<label for="first-name-vertical">Icon*</label>
												<input
													type="text"
													class="form-control"
													name="icon"
													placeholder="Ex: bi bi-people-fill (bootstrap icons)"
													value="{{ old('icon') }}"
												/>
												@if ($errors->has('icon'))
													<span class="help-block text-danger">
														<p>{{ $errors->first('icon') }}</p>
													</span>
												@endif
											</div>
										</div>
										<div class="col-12 d-flex justify-content-end">
											<button
												type="submit"
												class="btn btn-primary btn-block me-1 my-2"
											>
												Submit
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

