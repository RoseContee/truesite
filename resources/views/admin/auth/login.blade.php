@extends('admin.auth.layout')

@section('title', 'Login')

@section('content')
    <p class="login-box-msg">Sign In</p>

    <form action="{{ route('admin.login') }}" method="post">
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
        <div class="input-group mt-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required
                   placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        @error('password')
            <div class="small text-danger">{{ $message }}</div>
        @enderror
        <div class="row mt-3">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">
                        Remember Me
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    <div class="social-auth-links text-center my-3">
        <a href="{{ route('admin.auth-google') }}" class="btn btn-block btn-info">
            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
    </div>
    <!-- /.social-auth-links -->

    {{--<p class="text-center mb-1">
        <a href="{{ route('admin.forgot-password') }}">I forgot my password</a>
    </p>--}}
@endsection
