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
						<form action="{{ url('admin/komentar/') }}" id="data-delete-form" method="POST">
							@method('DELETE')
							@csrf
              <button type="submit" class="btn btn-danger">Hapus</button>
						</form>
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
			ajax: '{!! route('komentar.index') !!}',
			columns: [
				{
					data: null,
					searchable: false,
					orderable: false,
					render: function (data, type, row, meta) {
						var start = meta.settings._iDisplayStart;
						var length = meta.settings._iDisplayLength;
						return start + meta.row + 1;
					}
				},
				{data: 'user_name', name: 'user_name'},
				{data: 'user_email', name: 'user_email'},
				{data: 'post', name: 'post'},
				{data: 'content', name: 'content'},
				{data: 'status', name: 'status'},
				{data: 'actions', name: 'actions', orderable: false, searchable: false}
			],
			responsive: true,
			lengthChange: false,
      autoWidth: false,
			paging: true,
			pageLength: 5,
			drawCallback: function (settings) {
        // Mengatur ulang nomor urut pada setiap halaman
        var api = this.api();
        var startIndex = api.context[0]._iDisplayStart;
        api.column(0, {order: 'applied', search: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = startIndex + i + 1;
        });
    	}
		});
	});
</script>
@endsection
