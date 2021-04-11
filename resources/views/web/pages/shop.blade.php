@extends('web.layouts.app')
@section('title')
    Скидка
@endsection
@section('body-class')
    showcase-page
@endsection
@section('content')
    <section class="showcase">
        <div class="container">
            <h2>Скидки в магазине «{{$shop->net->title}}»<span class="hint">{{$shop->city.', '.$shop->street.', '.$shop->house}}</span></h2>
        </div>
        <div class="container">
            <div class="shop-preview">
                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md-4">
                        <div class="shop-logo">
                            <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($shop->net, 'logo')}}" alt="">
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-9 col-md-8">
                        <div class="shop-info">
                            <h3>{{$shop->net->title}}</h3>
                            <div class="sale-value">
                                Скидки от 50%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (count($category))
                <div class="row">
                    <div class="col-lg-3">
                        <div class="filter">
                            <div class="filter-item">
                                <a href="/shop/{{$shop->id}}"
                                   class="filter-header{{!$activeCategory ? ' active' : ''}}">
                                    <h3>Продукты</h3>
                                </a>
                                <div class="filter-content">
                                    @foreach($category as $item)
                                        <div class="filter-value">
                                            <a href="/shop/{{$shop->id}}?category={{$item->id}}"
                                               class="{{$item->id == $activeCategory ? 'active' : ''}}">{{$item->title}}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="product-list">
                            <div class="row">
                                @include('web.components.products', ['products' => $products, 'class' => 'col-lg-4 col-md-6'])
                            </div>
                        </div>
                        @if (($n = $products->lastPage()) > 1)
                            @php
                                   $pageUrl = "/shop/$shop->id?".($activeCategory ? "category=$activeCategory&" : "")."page=";
                                   $currentPage = $products->currentPage();
                                   if ($currentPage < 5) {
                                   $min = 1;
                                   $max = $currentPage + 2;
                                   $max = $max < 5 ? 5 : $max;
                                   } elseif ($n - 5 < $currentPage) {
                                   $max = $n;
                                   $min = $currentPage - 2;
                                   $min = $min > $n - 5 ? $n - 5 : $min;
                                   }
                                   else {
                                   $min = $currentPage - 2;
                                   $max = $currentPage + 2;
                                   }
                                   $firstDots = $min - 1;
                                   $firstDots = $firstDots > 1 ? $firstDots : 0;
                                   $lastDots = $max + 1;
                                   $lastDots = $lastDots < $n ? $lastDots : $n + 1;
                            @endphp
                            <div class="pagination-product">
                                <div class="pages">
                                    <a href="{{$pageUrl.($currentPage - 1)}}"
                                       class="last{{($currentPage == 1) ? ' d-none' : ''}}">
                                        <i class="fa fa-arrow-circle-left"></i>
                                        Назад
                                    </a>
                                    @for ($i = 1; $i <= $n; $i++)
                                        @if (($firstDots == $i) || ($lastDots == $i))
                                            <div class="points">
                                                ...
                                            </div>
                                        @endif
                                        @if ($i == $n || $i == $currentPage || $i == 1 || ($i >= $min && $i <= $max))
                                            <a href="{{$pageUrl.$i}}" class="{{$i == $currentPage ? 'active' : ''}}">
                                                {{$i}}
                                            </a>
                                        @endif
                                    @endfor
                                    <a href="{{$pageUrl.($currentPage + 1)}}" class="next{{($currentPage == $n) ? ' d-none' : ''}}">
                                        Вперед
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <h4>Магазин в процессе регистрации</h4>
            @endif
        </div>
    </section>
@endsection
@section('pos-head')
    @parent
    <link href="{{asset('plugins/owlcarousel/owl.carousel.css')}}" rel="stylesheet">
@endsection
