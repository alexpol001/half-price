@extends('web.layouts.app')
@section('title')
    Регистрация
@endsection
@section('body-class')
    signup-page
@endsection
@section('content')
    <section class="signin">
        <div class="container">
            <div class="form">
                <h2>Регистрация</h2>
                <div class="form-wrap">
                    @php
                        $model = new \App\Models\Users\Shop();
                    @endphp
                    <form method="post">
                        <div class="form-group">
                            <p>У вас уже имеется аккаунт? Выполните <a href="/login">вход</a></p>
                        </div>
                        @include('components.interface.required-text')
                        @csrf
                        @include('components.interface.fields.select2', ['slug' => 'net_id', 'dataModel' => \App\Models\Client\Net::getInstance()])
                        @include('components.interface.fields.simple', ['slug' => 'email'])
                        @include('components.interface.fields.mask', ['slug' => 'phone', 'mask' => '+7 (999) 999-99-99'])
                        @include('components.interface.fields.password', ['slug' => 'password'])
                        @include('components.interface.fields.password', ['slug' => 'repassword'])
                        @include('components.interface.fields.address.russia-csh-map', [
                        'id' => 'shop-address',
                        'citySlug' => 'city',
                        'streetSlug' => 'street',
                        'houseSlug' => 'house'
                        ])
                        @include('components.interface.fields.check', ['slug' => 'agreement'])
                        <button type="submit" class="button button-primary">Зарегистрироваться</button>
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
