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
  @include('includes.admin.alerts')
  <div class="row">
    <div class="col-md-2">
      <div class="card">
        <div class="card-body">
            <div class="container-fluid text-right" style="margin-bottom: 16px;">
              <a class="btn btn-success" href="{{route('users.create')}}">
                Add New
              </a>
            </div>
            <table class="table">
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
                    <a href="{{url('admin/users/'.$user->id.'/edit')}}" class="btn btn-primary">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{$user->id}}">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                    <div class="modal fade" id="deleteModal{{$user->id}}" role="dialog">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Delete</h4>
                          </div>
                          <div class="modal-body">
                            <p>Are you sure you want to delete this post?</p>
                          </div>
                          <div class="modal-footer">
                            {{Form::open(['url'=>url('admin/users/'.$user->id),'method'=>'delete'])}}
                            <button type="submit" class="btn btn-default">Yes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                            {{Form::close()}}
                          </div>
                        </div>
                      </div>
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