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
              Login History
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
      <h5 class="card-title">Login History</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table" id="list-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Email</th>
              <th>Activity</th>
              <th>Status</th>
              <th>IP Address</th>
              <th>Browser</th>
              <th>City</th>
              <th>Latitude</th>
              <th>Longitute</th>
              <th>Date</th>
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
          <h5 class="modal-title text-white">Delete Login History</h5>
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
          <p>Are you sure want to delete this login history?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <form action="{{ url('admin/login-history/') }}" method="post" id="data-delete-form">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger">Delete</button>
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