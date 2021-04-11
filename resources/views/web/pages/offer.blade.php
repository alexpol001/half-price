@extends('web.layouts.app')
@section('title')
    Размещение акционных товаров
@endsection
@section('body-class')
    place-catalog-page
@endsection
@section('active-offer', 'active')
@section('content')
    <section class="empty">
        <div class="container">
            <a class="to-back" href="/"><i class="fa fa-arrow-left"></i><span class="title">На главную</span></a>
        </div>
    </section>
    <section class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-6 text-center">
                    <h2>Размещение товаров на <strong>«Полцены»</strong></h2>
                    <p class="offer">Станьте нашим партнёром прямо сейчас!</p>
                    <a href="/register" class="button button-primary">Пройти регистрацию</a>
                </div>
                <div class="col-lg-4 col-md-6 d-none d-md-block">
                    <div class="text-right">
                        <img src="img/place-catalog/product.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (count($advantage))
        <section class="advantage">
            <div class="container">
                <h2>Преимущества нашего сервиса</h2>
                <div class="row advantage-list">
                    @foreach($advantage as $item)
                        <div class="col-md-4">
                            <div class="advantage-item">
                                <div class="advantage-image">
                                    <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($item, 'logo')}}"
                                         alt="{{$item->title}}">
                                </div>
                                <h3 class="advantage-title">{{$item->title}}</h3>
                                <p class="advantage-text">{{$item->description}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @if ($n = count($condition))
        <section class="conditions">
            <div class="container">
                <h2>Условия для публикации у нас</h2>
                <div class="accordion-list">
                    @for ($i = 0; $i < $n; $i++)
                        <div class="accordion-item">
                            <div class="title @if (!$i) active @endif">
                                <h3>{{($i + 1).'. '.$condition[$i]->title}}</h3>
                                <div class="icon"><i class="cl">X</i> <i class="fa fa-angle-down"></i></div>
                            </div>
                            <div class="content" @if ($i) style="display:none;" @endif>
                                {{$condition[$i]->description}}
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>
    @endif
    @if ($n = count($howToPlace))
        <section class="stages">
            <div class="container">
                <h2>Как разместить у нас свои каталоги?</h2>
                <div class="stage-list">
                    @for ($i = 1; $i <= $n; $i++)
                        <div class="stage-item">
                            <div class="row">
                                <div class="col-md-5 text-right">
                                    <div class="content d-none @if ($i % 2 == 0) d-md-inline-block @endif">
                                        <h3>{{$howToPlace[$i-1]->title}}</h3>
                                        <p>{{$howToPlace[$i-1]->description}}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    @php
                                        $color = '#6a54ac';
                                        $j = $i % 3;
                                        switch ($j) {
                                            case 1:
                                                $color = '#6a54ac';
                                                break;
                                            case 2:
                                                $color = '#41b952';
                                                break;
                                            case 0:
                                                $color = '#4c95ba';
                                                break;
                                        }
                                    @endphp
                                    <div class="num" style="background: {{$color}};">
                                        {{$i}}
                                    </div>
                                    @if ($i != $n)
                                        <br>
                                        <div class="arrow d-none d-md-inline-block">
                                            <img src="img/place-catalog/arrow1.png" alt="">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <div class="content @if ($i % 2 != 0) d-md-inline-block @else d-md-none @endif">
                                        <h3>{{$howToPlace[$i-1]->title}}</h3>
                                        <p>{{$howToPlace[$i-1]->description}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                    {{--<div class="stage-item">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-md-5 text-right"></div>--}}
                    {{--<div class="col-md-2 text-center">--}}
                    {{--<div class="num" style="background: #6a54ac;">--}}
                    {{--1--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="arrow d-none d-md-inline-block">--}}
                    {{--<img src="img/place-catalog/arrow1.png" alt="">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-5">--}}
                    {{--<div class="content">--}}
                    {{--<h3>Зарегистрируйтесь на нашем сайте</h3>--}}
                    {{--<p>Пройдите быструю регистрацию, с указанием всех необходимых данных.</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="stage-item">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-md-5 text-right d-none d-md-inline-block">--}}
                    {{--<div class="content">--}}
                    {{--<h3>Добавить акции в личном кабинете</h3>--}}
                    {{--<p>После оставления заявки мы свяжемся с вами и дадим подробную инструкцию</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-2 text-center">--}}
                    {{--<div class="num" style="background: #41b952;">--}}
                    {{--2--}}
                    {{--</div>--}}
                    {{--<br>--}}
                    {{--<div class="arrow d-none d-md-inline-block">--}}
                    {{--<img src="img/place-catalog/arrow1.png" alt="">--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-5">--}}
                    {{--<div class="content d-inline-block d-md-none">--}}
                    {{--<h3>Добавить акции в личном кабинете</h3>--}}
                    {{--<p>После оставления заявки мы свяжемся с вами и объясним следующие шаги.</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="stage-item">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-md-5 text-right"></div>--}}
                    {{--<div class="col-md-2 text-center">--}}
                    {{--<div class="num" style="background: #4c95ba;">--}}
                    {{--3--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-md-5">--}}
                    {{--<div class="content">--}}
                    {{--<h3>Увидеть ваши товары в «Полцены»</h3>--}}
                    {{--<p>Ваши акционные товары появятся на сайте через некоторое время.</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
                <div class="text-center">
                    <a href="/register" class="button button-secondary">Пройти регистрацию</a>
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
