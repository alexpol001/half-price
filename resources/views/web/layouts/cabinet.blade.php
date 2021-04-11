<?php
    $cabinetActive = isset($cabinetActive) ? $cabinetActive : null;
    $user = \App\User::authUser();
?>
@section('body-class')
    cabinet-page
@endsection
@section('active-cabinet', 'active')
<!-- ./wrapper -->
<div class="wrapper" id="cabinet">
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2>@yield('title')</h2>
                    </div>
                    @yield('breadcrumbs')
                </div>
            </div>
            @yield('subTitle')
        </section>
        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="shop-info">
                            <div class="shop-img">
                                <img src="{{\App\Models\Developer\CropImage::getCropImageUrl($user->userInfo->shop->net, 'logo')}}" alt="">
                            </div>
                            <div class="shop-email">
                                {{$user->email}}
                            </div>
                        </div>
                        <ul class="left-menu">
                            <li class="menu-item">
                                <a href="/cabinet/users/shop-sale" class="@if ($cabinetActive == 'sales') active @endif">
                                    <i class="fas fa-tags"></i>
                                    <span class="menu-text">Скидки</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/cabinet/users/shop-product" class="@if ($cabinetActive == 'products') active @endif">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="menu-text">Товары</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-9">
                        <div class="right-content">
                            @yield('components')
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
