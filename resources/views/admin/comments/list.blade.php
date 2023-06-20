@extends('layouts.admin.template')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Comments</h1>
        </div>
    </div>
    <section>
        @include('includes.admin.alerts')
        <div class="container-fluid text-right" style="margin-bottom: 16px;">

        </div>
        <table class="table" id="list-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Artikel</th>
                <th>Komentar</th>    

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
                        <h4 class="modal-title">Hapus Komentar</h4>
                    </div>
                    <div class="modal-body">
                        <p>Apa kamu yakin ingin menghapus komentar pada artikel ini?</p>
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
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
