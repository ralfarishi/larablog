@extends('layouts.admin_v2.template')

@section('content')
<!-- Content Header (Page header) -->
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
	<!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
      @include('includes.admin.alerts')
      <div class="container-fluid text-right" style="margin-bottom: 16px;">
        <a class="btn btn-success" href="{{ url('/admin/artikel/create')}} ">
          Buat Artikel
        </a>
      </div>
			<!-- left column -->
			<div class="col-md-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered table-striped" id="list-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Judul Artikel</th>
                  {{-- <th>Status</th> --}}
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
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Hapus Artikel</h4>
          </div>
          <div class="modal-body">
            <p>Apa kamu yakin ingin menghapus artikel ini?</p>
          </div>
          <div class="modal-footer">
            {{ Form::open(['url'=>url('admin/artikel/'),'method'=>'delete',"id"=>"data-delete-form"]) }}
              <button type="submit" class="btn btn-default">Yes</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            {{ Form::close() }}
          </div>
        </div>
      </div>
    </div>
	</div>
	<!-- /.container-fluid -->
</section>

{{-- <section>
  @include('includes.admin.alerts')
  <div class="container-fluid text-right" style="margin-bottom: 16px;">
    <a class="btn btn-success" href="{{ url('/admin/artikel/create')}} ">
      Buat Artikel
    </a>
  </div>
  <table class="table" id="list-table">
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

  <div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Hapus Artikel</h4>
        </div>
        <div class="modal-body">
          <p>Apa kamu yakin ingin menghapus artikel ini?</p>
        </div>
        <div class="modal-footer">
          {{Form::open(['url'=>url('admin/artikel/'),'method'=>'delete',"id"=>"data-delete-form"])}}
          <button type="submit" class="btn btn-default">Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          {{Form::close()}}
        </div>
      </div>
    </div>
  </div>
</section> --}}
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
                // {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
              ]
          });
      });
  </script>
@endsection
