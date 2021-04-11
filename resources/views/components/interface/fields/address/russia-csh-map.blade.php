<div id="{{$id}}">
    <div class="row">
        <div class="col-md-6">
            <div class="js-form-address-map">
                @include('components.interface.fields.simple', ['slug' => $citySlug, 'hint' => 'Начните писать, чтобы увидеть подсказки'])
                @include('components.interface.fields.simple', ['slug' => $streetSlug, 'hint' => 'Начните писать, чтобы увидеть подсказки'])
                @include('components.interface.fields.simple', ['slug' => $houseSlug, 'hint' => 'Начните писать, чтобы увидеть подсказки'])
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="title pb-3 pt-md-1">
                Проверьте правильность указанного адреса
            </div>
            <div id="{{$id}}-map" class="map_csh" style="width: 100%; height: 250px"></div>
        </div>
    </div>
</div>
@section('pos-head')
    @parent
    <link href="{{asset('plugins/jquery-master/css/style.css')}}" rel="stylesheet">
@endsection
@section('pos-end')
    @parent
    <script src="{{ asset('plugins/jquery-master/js/core.js') }}"></script>
    <script src="{{ asset('plugins/jquery-master/js/fias.js') }}"></script>
    <script src="//api-maps.yandex.ru/2.1/?apikey=7f016030-99bd-4e82-a2d4-963e6b7a98a3&lang=ru_RU" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            let $container = $('#{{$id}}'),
                $city = $container.find('#{{$citySlug}}'),
                $street = $container.find('#{{$streetSlug}}'),
                $building = $container.find('#{{$houseSlug}}');

            let map = null,
                map_created = false;

            $city.blur(function () {
                mapUpdate()
            });
            $street.focus(function () {
                $city.trigger('change');
            });
            $street.blur(function () {
                mapUpdate();
            });
            $building.focus(function () {
                $city.trigger('change');
            });
            $building.on('change, blur, input', function () {
                mapUpdate()
            });
            // $city.change(function () {
            //     console.log($(this).val());
            // });

            $()
                .add($city)
                .add($street)
                .add($building)
                .fias({
                    parentInput: $container.find('.js-form-address-map'),
                    verify: true,
                    change: function (obj) {
                        if (obj) {
                            setLabel($(this), obj.type);

                            if (obj.parents) {
                                $.fias.setValues(obj.parents, '.js-form-address-map');
                            }
                        }

                        mapUpdate();
                    },

                });

            $city.fias('type', $.fias.type.city);
            $street.fias('type', $.fias.type.street);
            $building.fias('type', $.fias.type.building);

            $city.fias('withParents', true);
            $street.fias('withParents', true);
            $building.fias('withParents', true);

            // Отключаем проверку введённых данных для строений
            $building.fias('verify', false);

            ymaps.ready(function () {
                if (map_created) return;
                map_created = true;

                map = new ymaps.Map('{{$id}}-map', {
                    center: [55.76, 37.64],
                    zoom: 12,
                    controls: []
                });

                map.controls.add('zoomControl', {
                    position: {
                        right: 10,
                        top: 10
                    }
                });

                mapUpdate();
            });

            function setLabel($input, text) {
                text = text.charAt(0).toUpperCase() + text.substr(1).toLowerCase();
                $input.parent().find('label').text(text);
            }

            function mapUpdate() {
                var zoom = 4;

                var address = 'Россия, ' + $city.val() + ', ' + $street.val() + ', ' + $building.val();

                if ($building.val() !== '') {
                    zoom = 16;
                } else if ($street.val() !== '') {
                    zoom = 13;
                } else if ($city.val() != '') {
                    zoom = 10;
                } else {
                    return;
                }

                if (address && map_created) {
                    var geocode = ymaps.geocode(address);
                    geocode.then(function (res) {
                        map.geoObjects.each(function (geoObject) {
                            map.geoObjects.remove(geoObject);
                        });

                        var position = res.geoObjects.get(0).geometry.getCoordinates(),
                            placemark = new ymaps.Placemark(position, {}, {});

                        map.geoObjects.add(placemark);
                        map.setCenter(position, zoom);
                    });
                }
            }
        });
    </script>
@endsection
