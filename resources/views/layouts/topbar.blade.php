<header id="page-topbar" class="isvertical-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span
                            class="logo-txt">@lang('translation.SIGTG - FIA')</span>
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span
                            class="logo-txt">@lang('translation.SIGTG - FIA')</span>
                    </span>
                </a>

            </div>



            <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- Search -->
            {{-- <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="bx bx-search"></span>
                </div>
            </form> --}}

        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                @if ( session('school') != null)
                        {{ session('school')['name'] }}
                    @endif
                </button>
            </div>

            <div class="dropdown d-inline-block language-switch">
                <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    @switch(Session::get('lang'))
                        @case('en')
                            <img src="{{ URL::asset('/assets/images/flags/us.jpg') }}" alt="Header Language" height="16">
                        @break

                        @default
                            <img src="{{ URL::asset('/assets/images/flags/spain.jpg') }}" alt="Header Language" height="16">
                    @endswitch
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    <!-- item-->
                    <a href="{{ url('index/en') }}" class="dropdown-item notify-item language" data-lang="eng">
                        <img src="{{ URL::asset('/assets/images/flags/us.jpg') }}" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">English</span>
                    </a>
                    <!-- item-->
                    <a href="{{ url('index/es') }}" class="dropdown-item notify-item language" data-lang="sp">
                        <img src="{{ URL::asset('/assets/images/flags/spain.jpg') }}" alt="user-image" class="me-1"
                            height="12"> <span class="align-middle">Spanish</span>
                    </a>


                </div>
            </div>


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ isset(Auth::user()->avatar) && Auth::user()->avatar != '' ? asset(Auth::user()->avatar) : asset('/assets/images/users/avatar-1.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1"
                        key="t-henry">{{ ucfirst(isset(Auth::user()->first_name) ? Auth::user()->first_name : 'Sin nombre') }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <a class="dropdown-item" href="contacts-profile"><i
                            class='bx bx-user-circle text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">@lang('translation.My_Account') </span></a>
                    <a class="dropdown-item" href="apps-chat"><i
                            class='bx bx-chat text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">@lang('translation.Chat')</span></a>
                    <a class="dropdown-item" href="pages-faqs"><i
                            class='bx bx-buoy text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">@lang('translation.Support')</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item d-flex align-items-center" href="#"><i
                            class='bx bx-cog text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle me-3">@lang('translation.Settings')</span><span
                            class="badge badge-soft-success ms-auto">@lang('translation.New')</span></a>
                    <a class="dropdown-item" href="auth-lock-screen"><i
                            class='bx bx-lock text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">@lang('translation.Lock_screen') </span></a>
                    <a class="dropdown-item" href="auth-lock-screen"><i
                            class='bx bx-lock text-muted font-size-18 align-middle me-1'></i> <span
                            class="align-middle">@lang('translation.Lock_screen') </span></a>
                    <a class="dropdown-item " href="javascript:void();"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="bx bx-power-off font-size-16 align-middle me-1"></i> <span
                            key="t-logout">@lang('translation.Logout')</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
