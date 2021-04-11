@extends('web.layouts.app')
@section('title', __('Не найдено'))
@section('body-class')
    code-error
@endsection
@section('code-error')
    <div class="container">
        <section class="error">
            <h2>404</h2>
            <h3>Сожалеем, но запрашиваемая вами страница, не найдена!</h3>
        </section>
    </div>
@endsection
