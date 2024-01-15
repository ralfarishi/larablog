@extends('layouts.admin_v2.template')

@section('page_css')
	<link href="{{ asset('admin_v2/css/iconly.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="page-heading">
	<h3>Profile Statistics</h3>
</div>

<div class="page-content">
	<section class="row justify-content-center">
		<div class="col-6 col-lg-3 col-md-6">
			<div class="card">
				<div class="card-body px-4 py-4-5">
					<div class="row">
						<div
							class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start"
						>
							<div class="stats-icon purple mb-2">
								<i class="iconly-boldPaper"></i>
							</div>
						</div>
						<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
							<h6 class="text-muted font-semibold">Articles Created</h6>
							<h6 class="font-extrabold mb-0">{{ $totalPosts }}</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-lg-3 col-md-6">
			<div class="card">
				<div class="card-body px-4 py-4-5">
					<div class="row">
						<div
							class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start"
						>
							<div class="stats-icon blue mb-2">
								<i class="iconly-boldChat"></i>
							</div>
						</div>
						<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
							<h6 class="text-muted font-semibold">Comments Given</h6>
							<h6 class="font-extrabold mb-0">{{ $totalComments }}</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		@if (Auth::user()->role == 'admin')
			<div class="col-6 col-lg-3 col-md-6">
				<div class="card">
					<div class="card-body px-4 py-4-5">
						<div class="row">
							<div
								class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start"
							>
								<div class="stats-icon green mb-2">
									<i class="iconly-boldCategory"></i>
								</div>
							</div>
							<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
								<h6 class="text-muted font-semibold">
									Categories Created
								</h6>
								<h6 class="font-extrabold mb-0">{{ $totalCategories }}</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-6 col-lg-3 col-md-6">
				<div class="card">
					<div class="card-body px-4 py-4-5">
						<div class="row">
							<div
								class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start"
							>
								<div class="stats-icon red mb-2">
									<i class="iconly-boldUser"></i>
								</div>
							</div>
							<div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
								<h6 class="text-muted font-semibold">Total Users</h6>
								<h6 class="font-extrabold mb-0">{{ $totalUsers }}</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endif
	</section>
</div>
@endsection

@section('page_scripts')
	<script src="{{ asset('admin_v2/js/dashboard.js') }}"></script>
@endsection
