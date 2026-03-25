@extends('admin.auth.layout')

@section('title', 'Forgot password')

@section('content')
    <p class="login-box-msg pb-1 font-weight-bold">Reset your password</p>
    <p class="login-box-msg">Enter your account email address so we can reset your password.</p>

    <form action="{{ route('admin.forgot-password') }}" method="post">
        @csrf
        @include('admin.partials.messages')
        <div class="input-group">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required
                   value="{{ old('email') }}" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        @error('email')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
        <div class="row mt-3">
            <div class="col-4">
                <a href="{{ route('admin.login') }}" class="btn btn-danger btn-block btn-flat">Back</a>
            </div>
            <div class="col-4"></div>
            <!-- /.col -->
            <div class="col-4 text-right">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Next</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
@endsection
