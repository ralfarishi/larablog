@extends('layouts.admin_v2.template')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Login History</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Login History</li>
				</ol>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
        @include('includes.admin_v2.alerts')
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped" id="list-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Email</th>
                  <th>Aktivitas</th>
                  <th>Status</th>
                  <th>IP Address</th>
                  <th>Browser</th>
                  <th>Kota</th>
                  <th>Latitude</th>
                  <th>Longitute</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
			</div>
		</div>
    <div class="modal fade" id="deleteModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus History</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Apa anda yakin ingin menghapus history login ini?</p>
          </div>
          <div class="modal-footer justify-content-center">
            <form action="{{ url('dashboard/login-history/') }}" id="data-delete-form" method="POST">
							@method('DELETE')
							@csrf
              <button type="submit" class="btn btn-danger">Hapus</button>
						</form>
          </div>
        </div>
      </div>
    </div>
	</div>
</section>
@endsection

@section('page_scripts')
  <script type="text/javascript">
    $(function () {
      var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('login-history.index') !!}',
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
          {data: 'email', name: 'email'},
          {data: 'activity', name: 'activity'},
          {data: 'status', name: 'status'},
          {data: 'ip_address', name: 'ip_address'},
          {data: 'user_agent', name: 'user_agent'},
          {data: 'city', name: 'city'},
          {data: 'latitude', name: 'latitude'},
          {data: 'longitude', name: 'longitude'},
          {data: 'created_at', name: 'created_at'},
          // {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        paging: true,
        pageLength: 10,
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