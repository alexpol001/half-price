@extends('web.layouts.app')
@section('title')
    Политика конфиденциальности
@endsection
@section('body-class')
    article-page
@endsection
@section('active-politics', 'active')
@section('content')
    <section class="article">
        <div class="container">
            <h2>Политика конфиденциальности</h2>
            <div class="content">
                {!! $content !!}
            </div>
            {{--<div class="content">--}}
                {{--<h3>1. Общие положения</h3>--}}
                {{--<p>Настоящая политика обработки персональных данных составлена в соответствии с требованиями Федерального закона от 27.07.2006. №152-ФЗ--}}
                    {{--«О персональных данных» и определяет порядок обработки персональных данных и меры по обеспечению безопасности персональных данных--}}
                    {{--Полцены (далее – Оператор).--}}
                {{--</p>--}}
                {{--<p>--}}
                    {{--Оператор ставит своей важнейшей целью и условием осуществления своей деятельности соблюдение прав и свобод человека и гражданина при--}}
                    {{--обработке его персональных данных, в том числе защиты прав на неприкосновенность частной жизни, личную и семейную тайну.--}}
                {{--</p>--}}
                {{--<h3>--}}
                    {{--2. Основные понятия, используемые в Политике--}}
                {{--</h3>--}}
                {{--<p>Настоящая политика обработки персональных данных составлена в соответствии с требованиями Федерального закона от 27.07.2006. №152-ФЗ--}}
                    {{--«О персональных данных» и определяет порядок обработки персональных данных и меры по обеспечению безопасности персональных данных--}}
                    {{--Полцены (далее – Оператор).--}}
                {{--</p>--}}
            {{--</div>--}}
        </div>
    </section>
@endsection
@section('pos-head')
    @parent
    <link href="{{asset('plugins/owlcarousel/owl.carousel.css')}}" rel="stylesheet">
@endsection
