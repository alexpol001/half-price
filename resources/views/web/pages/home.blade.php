@extends('web.layouts.app')
@section('title')
    Главная
@endsection
@section('body-class')
    home-page
@endsection
@section('content')
    <section class="home-banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 text-center">
                    <h2>«<strong>Полцены</strong>» продукты со скидками от <strong>50%</strong>!
                        <br>
                        Экономьте вместе с нами!
                    </h2>
                    <div class="icons">
                        <div class="icon">
                            <img src="img/home/cheese.png" alt="">
                        </div>
                        <div class="icon">
                            <img src="img/home/steak.png" alt="">
                        </div>
                        <div class="icon">
                            <img src="img/home/salmon.png" alt="">
                        </div>
                    </div>
                    <p class="offer-text">Выберите магазин на карте с отличной скидкой!</p>
                    <div class="offer-button">
                        <div class="offer-arrow">
                            <img src="img/home/arrow.png" alt="">
                        </div>
                        <a href="/map" class="button button-secondary">Перейти</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($n = count($howToUse))
        <section class="instruction">
            <div class="container">
                <h2>Как пользоваться нашим сервисом?</h2>
                <div class="instruction-list">
                    <div class="row">
                        @for ($i = 0; $i < $n; $i++)
                            <div class="col-lg-4 col-md-6">
                                <div class="instruction-item">
                                    <div class="instruction-image">
                                        <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($howToUse[$i], 'logo')}}"
                                             alt="">
                                    </div>
                                    <h3 class="instruction-title">{{($i + 1).'. '.$howToUse[$i]->title}}</h3>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="more">
                    <a href="/map" id="products-more">Магазины на карте</a>
                </div>
            </div>
        </section>
    @endif
    @if (count($review))
        <section class="review">
            <div class="container">
                <div class="border-wrap">
                    <h2>Отзывы наших пользователей</h2>
                    <div class="review-list">
                        <div class="row">
                            @foreach($review as $item)
                                <div class="col-lg-6">
                                    <div class="review-item">
                                        <div class="review-header">
                                            <span class="name">{{$item->title}},</span> <span
                                                    class="city">{{$item->city}}</span>
                                            <div class="review-score">
                                                @for ($i = 0; $i < $item->stars; $i++)
                                                    <i class="fa fa-star"></i>
                                                @endfor
                                                @for ($i = 0; $i < 5 - $item->stars; $i++)
                                                    <div class="empty">
                                                        <i class="fa fa-star"></i>
                                                        <img src="img/star-zero.png" alt="">
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            {{$item->description}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="more">
                        <a href="/about#review">Посмотреть все отзывы</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
