@extends('web.layouts.app')
@section('title')
    Магазины на карте
@endsection
@section('body-class')
    map-page
@endsection
@section('search-value', $search)
@section('content')
    <section class="empty">
        <div class="container">
            <a class="to-back" href="/"><i class="fa fa-arrow-left"></i><span class="title">На главную</span></a>
        </div>
    </section>
    <section class="global-map">
        <div class="container">
            <h2 class="d-none">Магазины на карте</h2>
            @if ($search)
                @if ($count)
                    <h3>По запросу «{{$search}}» найдено {{$count}} магазинов</h3>
                @else
                    <h3>По запросу «{{$search}}» ничего не найдено {{$count}}, вы можете выбрать любой магазин из представленных ниже</h3>
                @endif
            @endif
        </div>
        <div id="shop-map" class="map_csh" style="width: 100%; height: 400px"></div>
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
                addressMap("{{$city}}");
                        @foreach ($shops as $shop)
                var marker = {
                        image: '{{\App\Models\Developer\CropImage::getCropImageUrl($shop->net, 'logo')}}',
                        title: '{{$shop->net->title}}',
                    };
                @if ($shop->isVisible())
                    marker.url = '/shop/{{$shop->id}}';
                @else
                    marker.content = 'Магазин в процессе регистрации';
                @endif
                addressMap('{{$shop->city}}, {{$shop->street}}, {{$shop->house}}', marker);
                @endforeach
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
                    // console.log([minX, minY, maxX, maxY]);
                    // let d = Math.sqrt(Math.pow(maxX - minX, 2) + Math.pow(maxY - minY, 2));
                    // if (d > 0) {
                    //     console.log(d);
                    //     zoom = 2.5 * (1 / d);
                    //     if (zoom > 12) {
                    //         zoom = 12
                    //     } else if (zoom < 8) {
                    //         zoom = 8;
                    //     }
                    // }
                    // console.log(zoom);
                    // console.log(position);
                    avgX /= n;
                    avgY /= n;
                    centerPosition = [avgX, avgY];
                }
            }
        });
    </script>
@endsection
