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
							Edit Comment
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
						<h4 class="card-title">Edit Comment</h4>
					</div>
					<div class="card-content">
						<div class="card-body">
							<form class="form form-vertical" action="{{ route('comment.update', $comment->id) }}" method="POST">
								@method('PATCH')
								@csrf
								<div class="form-body">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Name</label>
												<input
													type="text"
													class="form-control"
													placeholder="{{ $comment->user->name }}"
													disabled
												/>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Email</label>
												<input
													type="text"
													class="form-control"
													placeholder="{{ $comment->user->email }}"
													disabled
												/>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Article</label>
												<a
													href="{{ route('post', $comment->post->slug) }}"
													target="_blank"
													class="d-block"
												>
													{{ $comment->post->title }}
												</a>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label for="first-name-vertical">Comment</label>
												<textarea class="form-control" cols="30" rows="3" placeholder="{{ $comment->content }}" disabled></textarea>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<label class="mb-1">Show comment?</label>
												<div class="form-check">
													<input
														class="form-check-input form-check-success"
														type="radio"
														name="active"
														id="radioYes"
														value="1"
														{{ $comment->active == 1 ? 'checked' : '' }}
													/>
													<label
														class="form-check-label"
														for="publishRadio"
													>
														Yes
													</label>
												</div>
												<div class="form-check">
													<input
														class="form-check-input form-check-danger"
														type="radio"
														name="active"
														id="radioNo"
														value="0"
														{{ $comment->active == 0 ? 'checked' : '' }}
													/>
													<label
														class="form-check-label"
														for="unpublishedRadio"
													>
														No
													</label>
												</div>
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