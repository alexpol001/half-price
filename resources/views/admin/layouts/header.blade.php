<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-secondary">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="/img/admin/profile.svg" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">{{\App\User::authUser()->email}}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="/img/admin/profile.svg" class="img-circle elevation-2" alt="User Image">

                    <p>
                        {{\App\User::authUser()->email}} - Администратор
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a class="btn btn-default btn-flat float-right"
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                           document.getElementById('logout-form')
                           .submit();">
                        {{ __('Выйти') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
