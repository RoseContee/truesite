<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ $setting['site_name'] }} Admin</title>

    <link rel="icon" type="image/png" href="{{ asset('public/'.$setting['favicon']) }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('public/assets/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/adminlte.min.css') }}">

    @yield('style')

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <!-- Like Numbers -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.like-users') }}">
                    <i class="fas fa-thumbs-up text-primary"></i>
                    <span class="badge badge-info navbar-badge">{{ $like_number }}</span>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin') }}" class="brand-link">
            <img src="{{ asset('public/assets/images/admin-logo.png') }}" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ $setting['site_name'] }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item @if ($menu == 'Complaints') menu-open @endif">
                        <a href="#" class="nav-link @if ($menu == 'Complaints') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Complaints
                                <i class="right fas fa-angle-left"></i>
                                <span class="badge badge-warning right pending-complaints-number">
                                    {{ $pending_complaints_number }}
                                </span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.complaints', 'pending') }}"
                                   class="nav-link @if ($menu == 'Complaints' && ($submenu ?? '') == 'pending') active @endif">
                                    <i class="far fa-circle nav-icon text-warning"></i>
                                    <p>
                                        Pending
                                        <span class="badge badge-warning right pending-complaints-number">
                                            {{ $pending_complaints_number }}
                                        </span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.complaints', 'approved') }}"
                                   class="nav-link @if ($menu == 'Complaints' && ($submenu ?? '') == 'approved') active @endif">
                                    <i class="far fa-circle nav-icon text-success"></i>
                                    <p>Approved</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.complaints', 'rejected') }}"
                                   class="nav-link @if ($menu == 'Complaints' && ($submenu ?? '') == 'rejected') active @endif">
                                    <i class="far fa-circle nav-icon text-danger"></i>
                                    <p>Rejected</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item @if ($menu == 'Users') menu-open @endif">
                        <a href="#" class="nav-link @if ($menu == 'Users') active @endif">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.users') }}"
                                   class="nav-link @if ($menu == 'Users' && ($submenu ?? '') != 'like') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.like-users') }}"
                                   class="nav-link @if ($menu == 'Users' && ($submenu ?? '') == 'like') active @endif">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Like Users</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}" class="nav-link @if ($menu == 'Profile') active @endif">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.logout') }}" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @yield('content')

    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="">{{ $setting['site_name'] }}</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('public/assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('public/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Ekko Lightbox -->
<script src="{{ asset('public/assets/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/assets/admin/js/adminlte.js') }}"></script>
<!-- Custom -->
<script src="{{ asset('public/assets/admin/js/custom.js') }}"></script>

<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

@yield('script')

</body>
</html>
