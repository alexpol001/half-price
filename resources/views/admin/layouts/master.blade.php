@php
    $setting = \App\Models\Site\Setting::query()->first();
@endphp
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ $setting ? $setting->title : 'Laravel' }}</title>
    @yield('pos-head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
@yield('template')
<!-- REQUIRED SCRIPTS -->
<script src="{{ asset('js/admin.js') }}"></script>
@yield('pos-end')
</body>
</html>
