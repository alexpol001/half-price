@extends('web.layouts.app')
@section('title')
    Помощь
@endsection
@section('body-class')
    faq-page
@endsection
@section('active-faq', 'active')
@section('content')
    @if ($n = count($faq))
    <section class="faq">
        <div class="container">
            <div class="accordion-list">
                <h2>Помощь: часто задаваемые вопросы</h2>
                @for($i = 0; $i < $n; $i++)
                <div class="accordion-item">
                    <div class="title">
                        <h3>{{$faq[$i]->title}}</h3>
                        <div class="icon"><i class="cl">X</i> <i class="fa fa-angle-down"></i></div>
                    </div>
                    <div class="content" style="display: none">
                        {{$faq[$i]->description}}
                    </div>
                </div>
                @endfor
                <p class="feedback-text">
                    Не нашли ответ на свой вопрос? Задайте его нам.
                </p>
                <div class="text-center">
                    <a class="button button-primary" href="/feedback">Написать в техподдержку</a>
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection
@section('pos-head')
    @parent
    <link href="{{asset('plugins/owlcarousel/owl.carousel.css')}}" rel="stylesheet">
@endsection
@section('pos-end')
    @parent
    <script src="{{ asset('web/js/accordion.js') }}"></script>
@endsection
