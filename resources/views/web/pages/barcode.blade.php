<?php
$shop = $product->shop;
?>
@extends('web.layouts.app')
@section('title')
    Скидка
@endsection
@section('body-class')
    product-code-page
@endsection
@section('content')
    <section class="product">
        <div class="container">
            <a class="to-back" href="/shop/{{$product->shop->id}}"><i class="fa fa-arrow-left"></i><span class="title">В магазин</span></a>
            <div class="product-card">
                <div class="product-logo">
                    <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($product->product, 'logo')}}" alt="">
                </div>
                <div class="product-info">
                    <div class="product-header">
                        <div class="net">
                            <div class="net-name">{{$product->shop->net->title}}</div>
                            <span class="border left"></span>
                            <div class="net-logo">
                                <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($product->shop->net, 'logo')}}"
                                     alt="{{$product->shop->net->title}}">
                            </div>
                            <span class="border right"></span>
                        </div>
                        <div class="sale-date">До {{$product->getDate()}}</div>
                        <div class="sale-value">-{{$product->shopSale->sale}}%</div>
                    </div>
                    <div class="product-content">
                        <div class="product-description">
                            <span class="product-title">{{$product->product->title}}:</span>
                            {{\App\Helpers\CommonHelper::cropString($product->product->description, 100)}}
                        </div>
                        @php
                            $price = $product->price * (1 - $product->shopSale->sale / 100);
                        @endphp
                        <div class="volume-price">
                            {{$product->product->measure_value}} {{$product->product->measure->short_title}}
                            /{{number_format((1 / $product->product->measure_value) * $price)}}
                            ₽ за {{$product->product->measure->short_title}}.
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="price">{{number_format($price, 2, ',', ' ')}} ₽</div>
                        <div class="old-price">{{number_format($product->price, 2, ',', ' ')}}
                            ₽
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="address">
                <h2>Адрес магазина</h2>
                <div id="shop-map" class="map_csh" style="width: 100%; height: 250px"></div>
            </div>
        </div>
    </section>
    <section class="bar-code" id="bar-code">
        <div class="container">
            <p class="bar-instuction">Для того, чтобы приобрести товар со скидкой, предъявите данный штрихкод на
                кассе.
            </p>
            <p class="bar-instuction">
                Внимание скидка действует только на товары со сроком годности до {{$product->getLifeDate()}}
            </p>
            <div class="code">
                <h2 class="title">Штрихкод товара</h2>
                <div class="code-image">
                    <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($product->shopSale, 'code')}}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection
@section('pos-end')
    @parent
    <script src="//api-maps.yandex.ru/2.1/?apikey=7f016030-99bd-4e82-a2d4-963e6b7a98a3&lang=ru_RU"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            let $country = 'Россия, ';
            let zoom = 10;
            let centerPosition = [55.76, 37.64];
            let positions = [];
            let map = null,
                map_created = false;

            ymaps.ready(function () {
                if (map_created) return;
                map_created = true;

                map = new ymaps.Map('shop-map', {
                    center: centerPosition,
                    zoom: zoom,
                    controls: []
                });

                map.controls.add('zoomControl', {
                    position: {
                        right: 10,
                        top: 10
                    }
                });

                initMap();
            });

            function initMap() {
                var marker = {
                    image: '{{\App\Models\Developer\CropImage::getCropImageUrl($shop->net, 'logo')}}',
                    title: '{{$shop->net->title}}',
                };
                marker.url = '/shop/{{$shop->id}}';
                addressMap('{{$shop->city}}, {{$shop->street}}, {{$shop->house}}', marker);
            }

            function addressMap(address, marker) {
                if (address && map_created) {
                    var geocode = ymaps.geocode($country + address);
                    geocode.then(function (res) {

                        var position = res.geoObjects.get(0).geometry.getCoordinates();
                        defineCenter(position);

                        if (marker) {
                            let params1 = {};
                            let params2 = {};

                            if (marker.url) {
                                params1.url = marker.url;
                            }
                            if (marker.title) {
                                params1.hintContent = marker.title + ' - ' + address;
                            }
                            if (marker.content) {
                                params1.balloonContent = marker.content;
                            }
                            if (marker.image) {
                                params2.iconLayout = 'default#image';
                                params2.iconImageHref = marker.image;
                                params2.iconImageSize = [35, 35];
                            }
                            let placemark = new ymaps.Placemark(position, params1, params2);
                            map.geoObjects.add(placemark);
                        }

                        map.setCenter(centerPosition, zoom);

                        map.geoObjects.events.add('click', function (e) {
                            // Объект на котором произошло событие
                            let target = e.get('target');
                            let url = target.properties.get('url');
                            if (url) {
                                window.location.href = url;
                            }
                        });
                    });
                }
            }

            function defineCenter(position) {
                positions.push(position);
                let n = positions.length;
                let avgX = 0;
                let avgY = 0;
                let minX = 10000;
                let maxX = -10000;
                let minY = 10000;
                let maxY = -10000;
                if (n > 0) {
                    for (let i = 0; i < n; i++) {
                        let x = positions[i][0];
                        let y = positions[i][1];
                        avgX += x;
                        avgY += y;
                        minX = minX > x ? x : minX;
                        minY = minY > y ? y : minY;
                        maxX = maxX < x ? x : maxX;
                        maxY = maxY < y ? y : maxY;
                    }
                    avgX /= n;
                    avgY /= n;
                    centerPosition = [avgX, avgY];
                }
            }
        });
    </script>
@endsection
