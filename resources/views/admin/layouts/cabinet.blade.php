<!-- ./wrapper -->
<div class="wrapper" id="app">

@include('admin.layouts.header')

@include('admin.layouts.left')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div>
                    @yield('breadcrumbs')
                </div>
            </div>
            @yield('subTitle')
        </section>
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@include('admin.layouts.right')

<!-- Main Footer -->
    @include('admin.layouts.footer')
</div>
