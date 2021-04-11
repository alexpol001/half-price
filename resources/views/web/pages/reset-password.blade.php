@extends('web.layouts.app')
@section('title')
    Восстановление пароля
@endsection
@section('body-class')
    signin-page
@endsection
@section('content')
    <section class="signin">
        <div class="container">
            <div class="form">
                <h2>Восстановление пароля</h2>
                <div class="form-wrap">
                    @if (session('status'))
                        @section('pos-end')
                            @parent
                            @include('components.interface.alert.toast', [
                                'type' => 'success',
                                'message' => 'На ваш email отправлено сообщение'
                            ])
                        @endsection
                    @endif
                    @php
                        $model = new \App\User();
                    @endphp
                    <form method="post" action="{{ route('password.email') }}">
                        @csrf
                        @include('components.interface.fields.simple', ['slug' => 'email'])
                        <button type="submit" class="button button-primary">Отправить запрос</button>
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
