<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <!-- Sidebar Toggle (Topbar) -->
    <form class="form-inline">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
    </form>

    <!-- Topbar Search -->
    <div
        class="d-sm-inline-block form-inline mr-auto navbar-search">
        <a href="{{url('trash')}}" class="btn btn-danger">Trash</a>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <div class="topbar-divider d-none d-sm-block"></div>
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="modal" data-target="#logoutModal">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">LogOut</span>
                    <i class="fas fa-power-off text-gray-700"></i>
            </a>
            @if (isset(Auth::user()->email))
            <!-- Dropdown - User Information -->
            @else
        <script>
            window.location = "/";
        </script>
    @endif
        </li>
    </ul>
</nav>
