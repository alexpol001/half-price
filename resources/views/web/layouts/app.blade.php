<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
@php
    $setting = \App\Models\Site\Setting::query()->first();
    $social = \App\Models\Site\Social::all();
@endphp
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ $setting ? $setting->title : 'Laravel' }}</title>
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    {{--<link href="{{asset('web/frontend/css/bootstrap.min.css')}}" rel="stylesheet">--}}
    <link href="{{asset('web/css/site.css')}}" rel="stylesheet">
    <link href="{{asset('web/css/modal-sidebar.css')}}" rel="stylesheet">
    <link href="{{asset('web/css/fixed-widget.css')}}" rel="stylesheet">
    <link rel="shortcut icon" href="/img/favicon.png" type="image/x-icon">
    <!-- Yandex.Metrika counter -->

    <script type="text/javascript" >

        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};

            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})

        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(55666231, "init", {

            clickmap:true,

            trackLinks:true,

            accurateTrackBounce:true,

            webvisor:true

        });

    </script>

    <noscript><div><img src="https://mc.yandex.ru/watch/55666231" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

    <!-- /Yandex.Metrika counter -->
    @yield('pos-head')
</head>
<body class="@yield('body-class')">
<header class="main-header">
    <div class="container">
        <div class="wrap">
            <a href="/" class="logo">
                <img src="/img/logo.png" alt="">
                <h1 class="d-none">Полцены</h1>
            </a>
            <ul class="nav menu d-none d-lg-block">
                <li class="menu-item">
                    <a href="/map" class="@yield('active-map')">
                        <i class="fas fa-location-arrow"></i>
                        <span class="menu-text">Магазины на карте</span>
                    </a>
                </li>
                <li class="menu-item">
                    <div class="search">
                        <input type="text" class="form-control" value="@yield('search-value')" placeholder="Поиск магазинов">
                        <button class="global-search">Найти</button>
                        <div class="clearfix"></div>
                    </div>
                </li>
                @if (!\App\User::authUser())
                    <li class="menu-item">
                        <a href="/login" class="@yield('active-login')">
                            <i class="fas fa-user-plus"></i>
                            <span class="menu-text">Войти как магазин</span>
                        </a>
                    </li>
                @else
                    <li class="menu-item">
                        <a href="/cabinet/users/shop-sale" class="@yield('active-cabinet')">
                            <i class="fas fa-chart-line"></i>
                            <span class="menu-text">Кабинет</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                           document.getElementById('logout-form')
                           .submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="menu-text">Выход</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endif
                <li class="menu-item">
                    <a class="active">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="menu-text">Санкт-Петербург</span>
                    </a>
                </li>
            </ul>
            <a href="#" data-target="#menuModal" data-toggle="modal" class="menu-toggle d-xl-none d-lg-none">
                <i class="fas fa-bars"></i>
            </a>
        </div>
    </div>
</header>
@yield('template')
@yield('content')
@yield('code-error')
<!-- REQUIRED SCRIPTS -->
<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <ul class="nav menu">
                    <li class="menu-item">
                        <a href="/about" class="@yield('active-about')">
                            О нас
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/faq" class="@yield('active-faq')">
                            Помощь
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/about#review">
                            Отзывы
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/offer" class="@yield('active-offer')">
                            Размещение акций
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/politics" class="@yield('active-politics')">
                            Пользовательское соглашение
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-3">
                @if (count($social))
                    <ul class="social">
                        @foreach($social as $item)
                            <li class="social-item">
                                <a href="{{$item->reference}}" title="{{$item->title}}">
                                    <i class="{{$item->icon}}"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="col-md-3">
                <a class="btn feedback" href="/feedback">
                    Написать в поддержку
                </a>
            </div>
            <div class="col-md-5">
                <p>
                    По общим вопросам обращаться: <a href="mailto:{{$setting->email}}">{{$setting->email}}</a>
                </p>
            </div>
            <div class="col-md-4">
                <p class="copyright">
                    &copy; {{date('Y')}} «Полцены» Все права защищены.
                </p>
            </div>
        </div>
        <p class="developer">
            Веб-приложение разработано <a href="http://symbweb.ru" target="_blank"
                                          title="Самые качественные сайты и веб-приложения любой сложности!">Digital-агентством
                Симбиоз</a>
        </p>
    </div>
</footer>

<!-- Modal -->
<div class="modal right fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Меню</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><i
                            class="fa fa-times"></i></button>
            </div>

            <div class="modal-body">
                <ul class="nav menu">
                    <li class="menu-item">
                        <div class="search">
                            <input type="text" value="@yield('search-value')" class="form-control" placeholder="Поиск магазинов">
                            <button class="global-search">Найти</button>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                    <li class="menu-item">
                        <a href="/" @section('active-home', 'active')>
                            <span class="menu-text">Главная</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="active">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="menu-text">Санкт-Петербург</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/map" class="@yield('active-map')">
                            <i class="fas fa-location-arrow"></i>
                            <span class="menu-text">Магазины на карте</span>
                        </a>
                    </li>
                    @if (!\App\User::authUser())
                        <li class="menu-item">
                            <a href="/login" class="@yield('active-login')">
                                <i class="fas fa-user-plus"></i>
                                <span class="menu-text">Войти как магазин</span>
                            </a>
                        </li>
                    @else
                        <li class="menu-item">
                            <a href="/cabinet/users/shop-sale" class="@yield('active-cabinet')">
                                <i class="fas fa-chart-line"></i>
                                <span class="menu-text">Кабинет</span>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                           document.getElementById('logout-form')
                           .submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="menu-text">Выход</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endif
                </ul>
            </div>

        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>
<!-- modal -->

<!-- Fixed Widget -->
<div class="fixed-widget">
    <div class="widget-item">
        <a href="/map" class="to-map">
            <i class="fas fa-map-marker-alt"></i><span class="d-none d-md-inline">Магазины на карте</span>
        </a>
    </div>
</div>
<!-- fixed Widget -->
<script src="{{ asset('js/app.js') }}"></script>
{{--<script href="{{asset('web/frontend/js/bootstrap.min.js')}}"></script>--}}
<script>
    $(document).ready(function () {
        $('.global-search').on('click', function () {
            let search = $(this).closest('.search').find('input');
            window.location.href = '/map?search='+search.val();
        });
    });
</script>
@yield('pos-end')
@if ($toast = session('toast'))
    @include('components.interface.alert.toast', $toast)
@endif
</body>
</html>
