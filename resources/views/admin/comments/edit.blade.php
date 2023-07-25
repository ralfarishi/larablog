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
					{{ Form::model($comment, [ 'route' => array('admin.comments.update', $comment->id), 'method'=>'PATCH']) }}
						<div class="card-body">
							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                {{ Form::label('user_name', 'User name') }}
                {{ Form::text("user_name", null, array("class"=>"form-control","id"=>"user_name")) }}
                @if ($errors->has('user_name'))
									<span class="help-block">
										<strong>{{ $errors->first('user_name') }}</strong>
									</span>
                @endif
            	</div>
            
							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
								{{ Form::label('user_email', 'User Email') }}
								{{ Form::text("user_email", null, array("class"=>"form-control","id"=>"user_email")) }}
								@if ($errors->has('user_email'))
									<span class="help-block">
										<strong>{{ $errors->first('user_name') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
								{{ Form::label('blog_title', 'Blog') }}
								{{--{{Form::text("post[title]", null, array("class"=>"form-control","id"=>"blog_title","disabled"=>"disabled"))}}--}}
								<a href="{{ route('post', $comment->post->slug) }}" target="_blank">{{$comment->post->title}}</a>
								@if ($errors->has('user_email'))
									<span class="help-block">
										<strong>{{ $errors->first('user_name') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
								{{ Form::label('comment', 'Comment*') }}

								{{ Form::textarea("content", null, array("class"=>"form-control","id"=>"comment")) }}
								@if ($errors->has('content'))
									<span class="help-block">
										<strong>{{ $errors->first('content') }}</strong>
									</span>
								@endif
							</div>

							<div class="form-group clearfix">
								<label>Approve : </label>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioYes" name="active" value="1" {{ $comment->active == 1 ? 'checked' : '' }}>
									<label for="radioYes">Yes</label>
								</div>
								<div class="icheck-primary d-inline">
									<input type="radio" id="radioNo" name="active" value="0" {{ $comment->active == 0 ? 'checked' : '' }}>
									<label for="radioNo">No</label>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
						<div class="card-footer">
							<input type="submit" class="btn btn-block btn-primary" value="Save" />
						</div>
					{{ Form::close() }}
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
