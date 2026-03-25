@extends('admin.partials.layout')

@section('title', ucwords($status).' complaints')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ ucwords($status) }} Complaints</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Complaints</li>
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
                            <table id="complaints" class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 20px;">No</th>
                                    <th>User</th>
                                    <th>Domain</th>
                                    <th>Title</th>
                                    <th>Complaints</th>
                                    <th>Screenshot</th>
                                    @if ($status == 'rejected')
                                        <th>Reason</th>
                                    @endif
                                    <th style="width: 90px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($complaints as $index => $complaint)
                                    <tr id="complaint-{{ $complaint['id'] }}">
                                        <td>{{ ++$index }}</td>
                                        <td>{{ $complaint['user']['email'] ?? 'Unknown' }}</td>
                                        <td>{{ $complaint['domain'] }}</td>
                                        <td>{{ $complaint['title'] }}</td>
                                        <td class="text-break">
                                            {{ Str::limit($complaint['complaints'], 200, '...') }}
                                            @if (strlen($complaint['complaints']) > 200)
                                                <a href="javascript:void(0);" class="view-complaint" data-ref="{{ $complaint['id'] }}">More</a>
                                            @endif
                                        </td>
                                        <td>
                                            <a id="view-{{ $complaint['id'] }}" href="{{ asset('public/'.$complaint['screenshot']) }}" data-toggle="lightbox"
                                               data-title="{{ $complaint['title'] }}&lt;br&gt;&lt;p class='small text-break'&gt;{{ $complaint['complaints'] }}&lt;/p&gt;">
                                                <img src="{{ asset('public/'.$complaint['screenshot']) }}" class="img-fluid" style="max-width: 100px;"
                                                     alt="{{ $complaint['title'] }}" title="{{ $complaint['title'] }}">
                                            </a>
                                        </td>
                                        @if ($status == 'rejected')
                                            <td class="text-break">{{ $complaint['reason'] }}</td>
                                        @endif
                                        <td>
                                            <button class="btn btn-info px-2 py-1 m-1 view-complaint" data-ref="{{ $complaint['id'] }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if ($status == 'pending')
                                                <button class="btn btn-primary px-2 py-1 m-1 action-complaint"
                                                        data-ref="{{ $complaint['id'] }}" data-user="{{ $complaint['user']['email'] ?? 'Unknown' }}"
                                                        data-domain="{{ $complaint['domain'] }}" data-title="{{ $complaint['title'] }}">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Domain</th>
                                    <th>Title</th>
                                    <th>Complaints</th>
                                    <th>Screenshot</th>
                                    @if ($status == 'rejected')
                                        <th>Reason</th>
                                    @endif
                                    <th>Action</th>
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

    <div id="actionModal" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="overlay" style="display: none;">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Update Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="action-error" class="alert alert-danger" style="display: none;">
                        <button type="button" class="close" onclick="$(this).parent().hide();" aria-hidden="true">&times;</button>
                        <span></span>
                    </div>
                    <div class="form-group">
                        <label>User</label>
                        <div id="user" class="pl-2"></div>
                    </div>
                    <div class="form-group">
                        <label>Domain</label>
                        <div id="domain" class="pl-2"></div>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <div id="title" class="pl-2"></div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <label for="status" id="status-error" class="small text-danger font-weight-normal"
                               style="display:none;">Please select status.</label>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea id="reason" name="reason" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        let complaint;
        $(function() {
            let table = $('#complaints').DataTable({
                "responsive": true,
                "lengthMenu": [100, 250, 500],
                "autoWidth": false,
                "columnDefs": [{
                    "sortable": false,
                    @if ($status == 'rejected')
                        "targets": [5, 7],
                    @else
                        "targets": [5, 6],
                    @endif
                }, {
                    "searchable": false,
                    @if ($status == 'rejected')
                        "targets": [0, 5, 7],
                    @else
                        "targets": [0, 5, 6],
                    @endif
                }]
            });

            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });

            $(document).on('click', '.view-complaint', function(event) {
                event.preventDefault();
                let ref = $(this).data('ref');
                $('#view-' + ref).click();
            });

            $('#actionModal').on('hidden.bs.modal', function () {
                $('#action-error').hide();
                complaint = null;
                $('#user').text('');
                $('#domain').text('');
                $('#title').text('');
                $('#status').val('');
                $('#status-error').hide();
                $('#reason').val('');
            });

            $(document).on('click', '.action-complaint', function(event) {
                event.preventDefault();

                $('#action-error').hide();
                complaint = $(this).data('ref');
                $('#user').text($(this).data('user'));
                $('#domain').text($(this).data('domain'));
                $('#title').text($(this).data('title'));
                $('#status').val('');
                $('#status-error').hide();
                $('#reason').val('');

                $('#actionModal').modal('show');
            });

            $('#status').on('change', function() {
                if ($(this).val()) $('#status-error').hide();
                else $('#status-error').show();
            });

            $('#submit').on('click', function() {
                if (!complaint) {
                    $('#action-error').show().find('span').html('Please select the complaint you want to update.');
                    setTimeout(function() {
                        $('#actionModal').modal('hide');
                    }, 1500);
                    return;
                }
                let status = $('#status').val();
                if (!status) {
                    $('#status-error').show(); return;
                }
                let reason = $('#reason').val();
                $('#actionModal .overlay').show();
                $.ajax({
                    url: '{{ route('admin.update-complaint') }}',
                    method: 'POST',
                    data: {
                        complaint: complaint,
                        status: status,
                        reason: reason,
                    },
                    success: function(data) {
                        $('#actionModal .overlay').hide();
                        if (data.success) {
                            table.row('#complaint-' + complaint).remove().draw();
                            var number = parseInt($($('.pending-complaints-number')[0]).text().trim());
                            if (number > 0) {
                                $('.pending-complaints-number').text(number - 1);
                            }
                            $('#actionModal').modal('hide');
                        } else if (data.message) {
                            $('#action-error').show().find('span').html(data.message);
                            if (data.message.includes('Please select the complaint you want to update.')) {
                                setTimeout(function() {
                                    location.reload(true);
                                }, 1500);
                            }
                        }
                    },
                    error: function(data) {
                        $('#actionModal .overlay').hide();
                        $('#action-error').show().find('span').html('Some went wrong.');
                        setTimeout(function() {
                            location.reload(true);
                        }, 1500);
                    }
                })
            });
        });
    </script>
@endsection
