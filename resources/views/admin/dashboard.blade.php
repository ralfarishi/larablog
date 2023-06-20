@extends('layouts.admin.template')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Dashboard</h1>
	</div>
</div>
	<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-comments-o fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{$num_unread_comments}}</div>
						<div>Total Komentar!</div>
					</div>
				</div>
			</div>
			<a href="{{ route('admin.komentar') }}">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"
						><i class="fa fa-arrow-circle-right"></i
					></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-book fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">{{$total_posts}}</div>
						<div>Semua Artikel!</div>
					</div>
				</div>
			</div>
			<a href="{{route('artikel.index')}}">
				<div class="panel-footer">
					<span class="pull-left">View Details</span>
					<span class="pull-right"
						><i class="fa fa-arrow-circle-right"></i
					></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
</div>
@endsection
