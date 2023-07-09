@extends('layouts.admin_v2.template')

@section('content')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Semua Artikel</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Semua Artikel</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
      <div class="container-fluid text-right" style="margin-bottom: 16px;">
        <a class="btn btn-success" href="{{ url('/admin/artikel/create')}} ">
          Buat Artikel
        </a>
      </div>
			<div class="col-md-12">
        @include('includes.admin_v2.alerts')
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped" id="list-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Judul Artikel</th>
                  <th>Aksi</th>
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
            <h4 class="modal-title">Hapus Artikel</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Apa anda yakin ingin menghapus artikel ini?</p>
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
</section>
@endsection

@section('page_scripts')
  <script type="text/javascript">
    $(function () {
      var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('artikel.index') !!}',
        columns: [
          {
            data: null,
            searchable: false,
            orderable: false,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            }
          },
          {data: 'title', name: 'title'},
          {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ]
      });
    });
  </script>
@endsection
