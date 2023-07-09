@extends('layouts.admin_v2.template')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Semua Pengguna</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item active">Semua Pengguna</li>
				</ol>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
<section class="content">
  @include('includes.admin_v2.alerts')
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            {{-- <div class="container-fluid text-right" style="margin-bottom: 16px;">
              <a class="btn btn-success" href="{{ route('users.create') }}">
                Add New
              </a>
            </div> --}}
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>

                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->name }}</td>
                  <td>
                    {{-- <a href="{{url('admin/users/'.$user->id.'/edit')}}" class="btn btn-primary">
                      <span class="fas fa-edit"></span>
                    </a> --}}
                    <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{$user->id}}">
                      <span class="fas fa-trash"></span>
                    </a>
                    <div class="modal fade" id="deleteModal{{$user->id}}" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Delete User</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p>Are you sure you want to delete this user?</p>
                          </div>
                          <div class="modal-footer justify-content-center">
                            {{Form::open(['url'=>url('admin/users/'.$user->id),'method'=>'delete'])}}
                              <button type="submit" class="btn btn-danger">Yes</button>
                            {{Form::close()}}
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>

          <div class="text-center">
            {{ $users->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection