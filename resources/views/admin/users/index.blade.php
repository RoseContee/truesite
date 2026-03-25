@extends('admin.partials.layout')

@section('title', ($status == 'like' ? ucwords($status) : '').' Users')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ ($status == 'like' ? ucwords($status) : '') }} Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="users" class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 20px;">No</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="width: 30px;">Like</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td>{{ ++$index }}</td>
                                        <td>
                                            @if ($user['avatar'])
                                                <img src="{{ $user['avatar'] }}" class="img-fluid img-circle" style="max-width: 50px;"
                                                     alt="{{ $user['name'] }}" title="{{ $user['name'] }}">
                                            @endif
                                        </td>
                                        <td>{{ $user['name'] }}</td>
                                        <td>{{ $user['email'] }}</td>
                                        <td>
                                            @if ($user['like'])
                                                <i class="fas fa-thumbs-up text-primary"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Like</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            let table = $('#users').DataTable({
                "responsive": true,
                "lengthMenu": [100, 250, 500],
                "autoWidth": false,
                "columnDefs": [{
                    "sortable": false,
                    "targets": [1, 4],
                }, {
                    "searchable": false,
                    "targets": [1, 4],
                }]
            });
        });
    </script>
@endsection
