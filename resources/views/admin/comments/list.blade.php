@extends('layouts.admin_v2.template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Semua Komentar</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Semua Komentar</li>
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
				@include('includes.admin_v2.alerts')
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-striped" id="list-table">
              <thead>
                <tr>
									<th>#</th>
									<th>Username</th>
									<th>Email</th>
									<th>Artikel</th>
									<th>Komentar</th>
									<th>Status</th>
									<th>Aksi</th>
								</tr>
              </thead>
              <tbody>
              {{--DataTable content loads here--}}
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
			</div>
			<!--/.col (left) -->
		</div>
		<!-- /.row -->
		<div class="modal fade" id="deleteModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus Komentar</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Apa kamu yakin ingin menghapus komentar ini?</p>
          </div>
          <div class="modal-footer justify-content-center">
            {{ Form::open(['url'=>url('admin/artikel/'),'method'=>'delete',"id"=>"data-delete-form"]) }}
              <button type="submit" class="btn btn-danger">Hapus</button>
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </div>
	</div>
	<!-- /.container-fluid -->
</section>
@endsection

@section('page_scripts')
<script type="text/javascript">

	$(function () {
		var table = $('#list-table').DataTable({
			processing: true,
			serverSide: true,
			ajax: '{!! route('admin.komentar') !!}',
			columns: [
				{
					data: null,
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						return meta.row + 1;
					}
				},
					// {data: 'id', name: 'id'},
				{data: 'user_name', name: 'user_name'},
				{data: 'user_email', name: 'user_email'},
				{data: 'post', name: 'post', orderable: false, searchable: false},
				{data: 'content', name: 'content'},
				{data: 'status', name: 'status'},
				{data: 'actions', name: 'actions', orderable: false, searchable: false}
			]
		});
	});
</script>
@endsection
