<header id="page-topbar" class="ishorizontal-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ url('/') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span class="logo-txt">@lang('translation.SIGTG - FIA')</span>
                    </span>
                </a>

                <a href="{{ url('/') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span class="logo-txt">@lang('translation.SIGTG - FIA')</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>
        <div class="d-flex">
        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{ (isset(Auth::user()->avatar) && Auth::user()->avatar != '')  ? asset(Auth::user()->avatar) : asset('/assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ucfirst(isset(Auth::user()->first_name) ? Auth::user()->first_name : "Sin nombre")}}</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end pt-0">
                <a class="dropdown-item" href="contacts-profile"><i class='bx bx-user-circle text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">My Account</span></a>
                <a class="dropdown-item" href="apps-chat"><i class='bx bx-chat text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Chat</span></a>
                <a class="dropdown-item" href="pages-faqs"><i class='bx bx-buoy text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Support</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="#"><i class='bx bx-cog text-muted font-size-18 align-middle me-1'></i> <span class="align-middle me-3">Settings</span><span class="badge badge-soft-success ms-auto">New</span></a>
                <a class="dropdown-item" href="auth-lock-screen"><i class='bx bx-lock text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Lock screen</span></a>
                <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">@lang('translation.Logout')</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
            </div>
        </div>
    </div>
    </div>
</header>
