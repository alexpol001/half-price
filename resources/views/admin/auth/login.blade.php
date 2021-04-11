@extends('admin.layouts.master')
@isset($title)
@section('title')
    {{$title}}
@endsection
@endisset
@section('template')
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>Админ</b>ПАНЕЛЬ</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Введите Email и Пароль</p>
                <form method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autocomplete="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Пароль" required autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    Запомнить меня
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                {{--@if (Route::has('password.request'))--}}
                {{--<p class="mb-1">--}}
                    {{--<a href="{{ route('password.request') }}">I forgot my password</a>--}}
                {{--</p>--}}
                {{--@endif--}}
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@endsection
