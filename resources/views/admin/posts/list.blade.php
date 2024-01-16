@extends('layouts.admin_v2.template')

@section('page_css')
  <link rel="stylesheet" href="{{ asset('admin_v2/css/dataTables.bootstrap5.min.css') }}">
@endsection

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
              Article List
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>

<section class="section">
  @include('includes.admin_v2.alerts')
  <div class="card">
    <div class="card-header">
      <a href="{{ route('article.create') }}" class="btn btn-primary float-end"> Create</a>
      <h5 class="card-title">Article List</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table" id="list-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Writter</th>
              <th>Category</th>
              <th>Tags</th>
              <th>Total Comment</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            {{-- render datatable here --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white">Delete Article</h5>
          <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-x">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        <div class="modal-body">
          <p>Are you sure want to delete this article?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <form action="{{ url('admin/artikel/') }}" method="post" id="data-delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('page_scripts')
  @include('includes.admin_v2.plugin-script')

  <script type="text/javascript">
    $(function () {
      var table = $('#list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('article.index') !!}',
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
          {data: 'title', name: 'title'},
          {data: 'writer', name: 'writer'},
          {data: 'category.name', name: 'category.name', orderable: false},
          {data: 'tags', name: 'tags'},
          {data: 'comment_count', name: 'comment_count'},
          {data: 'status', name: 'status'},
          {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        paging: true,
        pageLength: 5,
        drawCallback: function (settings) {
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
