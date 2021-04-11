@extends('web.layouts.app')
@section('title')
    Вход в личный кабинет
@endsection
@section('body-class')
    signin-page
@endsection
@section('content')
    <section class="signin">
        <div class="container">
            <div class="form">
                <h2>Вход в личный кабинет</h2>
                <div class="form-wrap">
                    @php
                        $model = new \App\User();
                    @endphp
                    <form method="post">
                        <div class="form-group">
                            <p>У вас еще нет аккаунта? <a href="/register">Зарегистрируйтесь</a></p>
                        </div>
                        @csrf
                        @include('components.interface.fields.simple', ['slug' => 'email'])
                        @include('components.interface.fields.password', ['slug' => 'password'])
                        <div class="form-group">
                            <a class="hint" href="/password/reset">Забыли пароль?</a>
                        </div>
                        @include('components.interface.fields.check', ['slug' => 'remember'])
                        <button type="submit" class="button button-primary">Войти</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('pos-head')
    @parent
    <link href="{{asset('css/fields.css')}}" rel="stylesheet">
@endsection
@section('pos-end')
    <script src="{{ asset('js/fields.js') }}"></script>
    @parent
@endsection
