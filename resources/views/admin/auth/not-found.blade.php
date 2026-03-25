@extends('admin.auth.layout')

@section('title', 'Not found')

@section('content')
    @if (!empty($error_message))
        <div class="alert text-danger text-center">
            {{ $error_message }}
        </div>
    @endif
    @if (!empty($info_message))
        <div class="alert text-info text-center">
            {{ $info_message }}
        </div>
    @endif
    <div class="col-12 text-center">
        @if (auth('admin')->check())
            <a href="{{ route('admin') }}" class="btn btn-primary btn-flat">Go to home</a>
        @else
            <a href="{{ route('admin.login') }}" class="btn btn-danger btn-flat">Back to login</a>
        @endif
    </div>
@endsection

@section('style')
    <style type="text/css">
        .login-box {
            width: auto;
        }
    </style>
@endsection
