@extends('admin.layouts.master')
@section('title', '404 Страница не найдена')
@section('content')
    <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Упс! Страница не найдена.</h3>

            <p>
                Мы не смогли найти страницу, которую вы искали.
                Вы можете <a href="/admin">вернуться на главную</a> или воспользоваться формой поиска.
            </p>

            <form class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search">

                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <!-- /.input-group -->
            </form>
        </div>
        <!-- /.error-content -->
    </div>
@endsection
@section('template')
    @include('admin.layouts.cabinet')
@endsection
