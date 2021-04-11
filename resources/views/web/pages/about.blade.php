@extends('web.layouts.app')
@section('title')
    О нас
@endsection
@section('body-class')
    about-page
@endsection
@section('active-about', 'active')
@section('content')
    @if ($n = count($advantage))
        <section class="commands">
            <div class="container h-100">
                <h2>Начните экономить вместе с нами!</h2>
                <div class="row command-list align-items-center h-100">
                    <div class="col-lg-4 col-md-6">
                        @for ($i = 0; $i < ceil($n / 2); $i++)
                            <div class="command-item">
                                <div class="command-icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="command-text">
                                    {{$advantage[$i]->title}}
                                </div>
                                <div class="command-image">
                                    <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($advantage[$i], 'logo')}}"
                                         alt="">
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="col-4 d-none d-lg-block">
                        <div class="commands-image">
                            <img src="img/about/economy.png" alt="">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        @for ($i = ceil($n / 2); $i < $n; $i++)
                            <div class="command-item">
                                <div class="command-icon">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="command-text">
                                    {{$advantage[$i]->title}}
                                </div>
                                <div class="command-image">
                                    <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($advantage[$i], 'logo')}}"
                                         alt="">
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>
    @endif
    @if (count($advantage2))
        <section class="advantage">
            <div class="container">
                <div class="row advantage-list">
                    @foreach($advantage2 as $item)
                        <div class="col-md-4">
                            <div class="advantage-item">
                                <div class="advantage-image">
                                    <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($item, 'logo')}}"
                                         alt="{{$item->title}}">
                                </div>
                                <h2 class="advantage-title">{{$item->title}}</h2>
                                <p class="advantage-text">{{$item->description}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @if (count($review))
        <section id="review" class="reviews">
            <div class="container">
                <h2>Отзывы наших пользователей</h2>
                <div class="review-slider owl-carousel owl-theme">
                    @foreach($review as $item)
                        <div class="review-slide">
                            <div class="review-image d-none d-lg-block">
                                <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($item, 'logo')}}" alt="{{$item->title}}">
                            </div>
                            <div class="review-content">
                                <div class="review-text">
                                    {{$item->description}}
                                </div>
                                <div class="review-info">
                                    <span class="name">{{$item->title}},</span>
                                    <span class="city">{{$item->city}}</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @endforeach
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
    <script src="{{ asset('plugins/owlcarousel/owl.carousel.js') }}"></script>
    <script>
        $(".review-slider").owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            smartSpeed: 500
        });
    </script>
@endsection
