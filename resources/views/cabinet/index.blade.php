<?php
/**
 * @var $model \App\Models\UwtModel
 * @var $title string
 */
?>
@extends('web.layouts.app')
@isset($title)
@section('title')
    {{$title}}
@endsection
@endisset
@isset($breadcrumbs)
@section('breadcrumbs')
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            @foreach($breadcrumbs as $title => $breadcrumb)
                @if ($breadcrumb !== 'active')
                    <li class="breadcrumb-item"><a href="{{$breadcrumb}}">{{$title}}</a></li>
                @else
                    <li class="breadcrumb-item active">{{$title}}</li>
                @endif
            @endforeach
        </ol>
    </div>
@endsection
@endisset
@isset($subTitle)
@section('subTitle')
    <div class="container-fluid">
        <h2 class="mt-2 pt-2">{{$subTitle}}</h2>
    </div>
@endsection
@endisset
@section('components')
    @isset($components)
        @foreach($components as $component)
            @include('components.'.key($component), reset($component))
        @endforeach
    @endisset
@endsection
@section('template')
    @include('web.layouts.cabinet')
@endsection
@section('pos-head')
    <link href="{{asset('css/fields.css')}}" rel="stylesheet">
    @parent
@endsection
@section('pos-end')
    @parent
    <script src="{{ asset('js/fields.js') }}"></script>
    @if ($toast = session('toast'))
        @include('components.interface.alert.toast', $toast)
    @endif
@endsection
