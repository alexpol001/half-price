@extends('web.layouts.app')
@section('title')
    Написать в техподдержку
@endsection
@section('body-class')
    feedback-page
@endsection
@section('content')
    <section class="feedback">
        <div class="container">
            <div class="form">
                <h2>Написать в техподдержку</h2>
                <div class="form-wrap">
                    <form method="post">
                        @csrf
                        @include('components.interface.fields.select2', ['slug' => 'theme', 'dataModel' => \App\Models\Site\SupportTheme::getInstance()])
                        @include('components.interface.fields.simple', ['slug' => 'name'])
                        @include('components.interface.fields.simple', ['slug' => 'email'])
                        @include('components.interface.fields.textarea', ['slug' => 'message'])
                        <button type="submit" class="button button-primary">Написать в техподдержку</button>
                    </form>
                    {{--<form>--}}
                        {{--<div class="form-group">--}}
                            {{--<label>Выберите тему обращения</label>--}}
                            {{--<input class="form-control" placeholder="----">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label>Ваше имя</label>--}}
                            {{--<input class="form-control" placeholder="">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<label>Email</label>--}}
                            {{--<input class="form-control" placeholder="">--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<textarea class="form-control" placeholder="Напишите интересующий вас вопрос как можно более развернуто."></textarea>--}}
                        {{--</div>--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="g-recaptcha" data-sitekey="your_site_key"></div>--}}
                        {{--</div>--}}
                        {{--<button type="submit" class="button button-primary">Написать в техподдержку</button>--}}
                    {{--</form>--}}
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
