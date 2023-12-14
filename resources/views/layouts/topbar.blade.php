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
                    @if (session('school') != null)
                        {{ session('school')['name'] }}
                    @endif
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    @if (session('protocol') != null)
                        {{ session('protocol')['name'] }}
                        @else
                        Seleccione protocolo
                    @endif
                </button>

                @role('admin')
                    <div class="dropdown-menu dropdown-menu-end">
                        @if (session('allProtocols') != null)
                            @forelse (session("allProtocols") as $protocol)
                                <a href="{{ route('sessions.set.protocol', $protocol->id) }}"
                                    class="dropdown-item notify-item" data-lang="eng">
                                    <span class="align-middle">{{ $protocol->name }}</span>
                                </a>
                            @empty
                            @endforelse
                        @endif
                    </div>
                @endrole

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

            @php
                            $notifications = getNotifications();
                        @endphp
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-sm" data-feather="bell"></i>
                    <span class="noti-dot bg-danger rounded-pill {{count($notifications)==0?'d-none':''}}">{{ count($notifications) }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-size-15"> Notificaciones </h5>
                            </div>
                            <div class="col-auto">
                                <a href="{{route('notifications.mark.as.read', ['all'=>1])}}" class="small"> Marcar todo como leído</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 250px;">
                        {{-- <h6 class="dropdown-header bg-light">New</h6> --}}
                        @forelse ($notifications as $notification)
                             <a href="{{route('notifications.mark.as.read', ['usernoti_id'=>$notification->id])}}" class="text-reset notification-item">
                            <div class="d-flex border-bottom align-items-start">
                                        {{-- <div class="flex-shrink-0">
                                            <img src="{{ URL::asset('assets/images/users/avatar-3.jpg') }}" class="me-3 rounded-circle avatar-sm"
                                                alt="user-pic">
                                        </div> --}}
                                    <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notification->notification->title }}</h6>
                                        <div class="text-muted">
                                            <p class="mb-1 font-size-13">{{ $notification->notification->message }}</p>
                                            <p class="mb-0 font-size-10 text-uppercase fw-bold"><i class="mdi mdi-clock-outline"></i>
                                         {{-- @lang('translation.1_hour_ago') --}}
                                                {{ formatDateTime($notification->notification->created_at) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                             </a>
                        @empty
                            <h6 class="dropdown-header bg-light text-center">Sin notificaciones</h6>
                        @endforelse
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 btn-block text-center" href="{{route('notifications.index')}}">
                            <i class="uil-arrow-circle-right me-1"></i> <span>Ver todo</span>
                        </a>
                    </div>
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
