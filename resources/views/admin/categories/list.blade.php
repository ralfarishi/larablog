@extends('layouts.admin_v2.template')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Semua Kategori</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Semua Kategori</li>
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
			<div class="container-fluid text-right" style="margin-bottom: 16px;">
        <a class="btn btn-success" href="{{ route('create-category') }} ">
          Buat Kategori Baru
        </a>
      </div>
			<div class="col-md-12">
				@include('includes.admin_v2.alerts')
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-striped" id="list-table">
              <thead>
                <tr>
									<th>#</th>
									<th>Nama Kategori</th>
									<th>Icon</th>
									<th>Total Artikel</th>
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
            <h4 class="modal-title">Hapus Kategori</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Apa kamu yakin ingin menghapus kategori ini?</p>
          </div>
          <div class="modal-footer justify-content-center">
						<form action="{{ url('admin/kategori/') }}" id="data-delete-form" method="POST">
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
			ajax: '{!! route('list-categories') !!}',
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
				{data: 'name', name: 'name'},
				{data: 'icon', name: 'icon'},
				{data: 'total_posts', name: 'total_posts'},
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
