<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    {{-- <body data-layout="horizontal" data-sidebar="dark"> --}}
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ url('/') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span
                    class="logo-txt">@lang('translation.SIGTG - FIA')</span>
            </span>
        </a>

        <a href="{{ url('/') }}" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span
                    class="logo-txt">@lang('translation.SIGTG - FIA')</span>
            </span>
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo-sm.svg') }}" alt="" height="22">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->


            <ul class="metismenu list-unstyled" id="side-menu">

                @can('Users')
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class="bx bx-user-plus icon nav-icon"></i>
                            <span class="menu-item" data-key="t-users">@lang('translation.Users')</span>
                        </a>
                    </li>
                @endcan

                @can('Roles')
                    <li>
                        <a href="{{ route('roles.index') }}">
                            <i class="bx bx-user-plus icon nav-icon"></i>
                            <span class="menu-item" data-key="t-roles">@lang('translation.Roles')</span>
                        </a>
                    </li>
                @endcan

                @can('Schools')
                    <li>
                        <a href="{{ route('schools.index') }}">
                            <i class="bx bx-building-house icon nav-icon"></i>
                            <span class="menu-item" data-key="t-schools">@lang('translation.Schools')</span>
                        </a>
                    </li>
                @endcan

                @can('Protocols')
                    <li>
                        <a href="{{ route('protocols.index') }}">
                            <i class="bx bx-collection icon nav-icon"></i>
                            <span class="menu-item" data-key="t-protocols">@lang('translation.Protocols')</span>
                        </a>
                    </li>
                @endcan

                @can('Cycles')
                    <li>
                        <a href="{{ route('cycles.index') }}">
                            <i class="bx bx-shape-circle icon nav-icon"></i>
                            <span class="menu-item" data-key="t-cycles">@lang('translation.Cycles')</span>
                        </a>
                    </li>
                @endcan

                @can('Groups.students')
                    <li>
                        <a href="{{ route('groups.initialize') }}">
                            <i class="bx bx-group icon nav-icon"></i>
                            <span class="menu-item" data-key="t-my.group">@lang('translation.MyGroup')</span>
                        </a>
                    </li>
                @endcan

                @can('Groups.advisers')
                    <li>
                        <a href="{{ route('groups.index') }}">
                            <i class="bx bx-group icon nav-icon"></i>
                            <span class="menu-item" data-key="t-cycles">@lang('translation.Groups')</span>
                        </a>
                    </li>
                @endcan

                @can('Preprofiles.students')
                    <li>
                        <a href="{{ route('profiles.preprofile.index') }}">
                            <i class="bx bx-file-blank icon nav-icon"></i>
                            <span class="menu-item" data-key="t-preprofile">@lang('translation.PreProfile')</span>
                        </a>
                    </li>
                @endcan

                @can('Preprofiles.advisers')
                    <li>
                        <a href="{{ route('profiles.preprofile.coordinator.index') }}">
                            <i class="bx bx-file-blank icon nav-icon"></i>
                            <span class="menu-item" data-key="t-preprofileadviser">@lang('translation.PreProfileAdviser')</span>
                        </a>
                    </li>
                @endcan

                @can('Profiles.students')
                    <li>
                        <a href="{{ route('profiles.index') }}">
                            <i class="bx bx-file icon nav-icon"></i>
                            <span class="menu-item" data-key="t-profile">@lang('translation.Profiles')</span>
                        </a>
                    </li>
                @endcan

                @can('Profiles.advisers')
                    <li>
                        <a href="{{ route('profiles.coordinator.index') }}">
                            <i class="bx bx-file icon nav-icon"></i>
                            <span class="menu-item" data-key="t-profileadviser">@lang('translation.ProfilesAdviser')</span>
                        </a>
                    </li>
                @endcan

                @can('Stages')
                    <li>
                        <a href="{{ route('stages.index') }}">
                            <i class="bx bx-git-pull-request icon nav-icon"></i>
                            <span class="menu-item" data-key="t-schools">@lang('translation.Stages')</span>
                        </a>
                    </li>
                @endcan

                @can('Projects.students')
                    <li>
                        <a href="{{ route('projects.index') }}">
                            <i class="bx bx-code-block icon nav-icon"></i>
                            <span class="menu-item" data-key="t-projects">@lang('translation.Projects')</span>
                        </a>
                    </li>
                @endcan

                @can('Projects.advisers')
                    <li>
                        <a href="{{ route('projects.coordinator.index') }}">
                            <i class="bx bx-code-block icon nav-icon"></i>
                            <span class="menu-item" data-key="t-projectsadviser">@lang('translation.ProjectsAdviser')</span>
                        </a>
                    </li>
                @endcan
                {{-- <li>
                @can('Projects.coordinator.extension')
                    <li>
                        <a href="{{ route('extensions.index') }}">
                            <i class="bx bx-git-pull-request icon nav-icon"></i>
                            <span class="menu-item" data-key="t-extensions">@lang('Extensions')</span>
                        </a>
                    </li>
                @endcan
                       </li> --}}

                @can('Assigned.groups')
                    <li>
                        <a href="{{ route('groups.assigned') }}">
                            <i class="bx bx-file icon nav-icon"></i>
                            <span class="menu-item" data-key="t-groupsassigned">@lang('translation.GroupsAssigned')</span>
                        </a>
                    </li>
                @endcan


                {{-- <li>
                    <a href="{{ route('evaluations_documents.index') }}">
                        <i class="bx bx-file icon nav-icon"></i>
                        <span class="menu-item" data-key="t-evaluationsdocuments">@lang('EvaluationsDocuments')</span>
                    </a>
                </li> --}}

                {{-- <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ url('/') }}">
                        <i class="bx bx-tachometer icon nav-icon"></i>
                        <span class="menu-item" data-key="t-dashboards">@lang('translation.Dashboard')</span>
                        <span class="badge rounded-pill bg-success">@lang('translation.5+')</span>
                    </a>
                </li>

                <li>
                    <a href="layouts-vertical">
                        <i class="bx bx-layout icon nav-icon"></i>
                        <span class="menu-item" data-key="t-vertical">@lang('translation.Vertical')</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-components">@lang('translation.Components')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bxl-bootstrap icon nav-icon"></i>
                        <span class="menu-item" data-key="t-bootstrap">@lang('translation.Bootstrap')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ui-alerts" data-key="t-alerts">@lang('translation.Alerts')</a></li>
                        <li><a href="ui-buttons" data-key="t-buttons">@lang('translation.Buttons')</a></li>
                        <li><a href="ui-cards" data-key="t-cards">@lang('translation.Cards')</a></li>
                        <li><a href="ui-carousel" data-key="t-carousel">@lang('translation.Carousel')</a></li>
                        <li><a href="ui-dropdowns" data-key="t-dropdowns">@lang('translation.Dropdowns')</a></li>
                        <li><a href="ui-grid" data-key="t-grid">@lang('translation.Grid')</a></li>
                        <li><a href="ui-images" data-key="t-images">@lang('translation.Images')</a></li>
                        <li><a href="ui-modals" data-key="t-modals">@lang('translation.Modals')</a></li>
                        <li><a href="ui-offcanvas" data-key="t-offcanvas">@lang('translation.Offcanvas')</a></li>
                        <li><a href="ui-placeholders" data-key="t-placeholders">@lang('translation.Placeholders')</a></li>
                        <li><a href="ui-progressbars" data-key="t-progress-bars">@lang('translation.Progress_Bars')</a></li>
                        <li><a href="ui-tabs-accordions" data-key="t-tabs-accordions">@lang('translation.Tabs_&_Accordions')</a></li>
                        <li><a href="ui-typography" data-key="t-typography">@lang('translation.Typography')</a></li>
                        <li><a href="ui-video" data-key="t-video">@lang('translation.Video')</a></li>
                        <li><a href="ui-general" data-key="t-general">@lang('translation.General')</a></li>
                        <li><a href="ui-colors" data-key="t-colors">@lang('translation.Colors')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-disc icon nav-icon"></i>
                        <span class="menu-item" data-key="t-extended">@lang('translation.Extended')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="extended-lightbox" data-key="t-lightbox">@lang('translation.Lightbox')</a></li>
                        <li><a href="extended-rangeslider" data-key="t-range-slider">@lang('translation.Range_Slider')</a></li>
                        <li><a href="extended-sweet-alert" data-key="t-sweet-alert">@lang('translation.SweetAlert_2')</a></li>
                        <li><a href="extended-rating" data-key="t-rating">@lang('translation.Rating')</a></li>
                        <li><a href="extended-notifications" data-key="t-notifications">@lang('translation.Notifications')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bxs-eraser icon nav-icon"></i>
                        <span class="menu-item" data-key="t-forms">@lang('translation.Forms')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="form-elements" data-key="t-basic-elements">@lang('translation.Basic_Elements')</a></li>
                        <li><a href="form-validation"data-key="t-validation">@lang('translation.Validation')</a></li>
                        <li><a href="form-advanced"data-key="t-advanced-plugins">@lang('translation.Advanced_Plugins')</a></li>
                        <li><a href="form-editors"data-key="t-editors">@lang('translation.Editors')</a></li>
                        <li><a href="form-uploads"data-key="t-file-upload">@lang('translation.File_Upload')</a></li>
                        <li><a href="form-wizard"data-key="t-wizard">@lang('translation.Wizard')</a></li>
                        <li><a href="form-mask" data-key="t-mask">@lang('translation.Mask')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-list-ul icon nav-icon"></i>
                        <span class="menu-item" data-key="t-tables">@lang('translation.Tables')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="tables-basic" data-key="t-bootstrap-basic">@lang('translation.Bootstrap_Basic')</a></li>
                        <li><a href="tables-advanced" data-key="t-advanced-tables">@lang('translation.Advance_Tables')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bxs-bar-chart-alt-2 icon nav-icon"></i>
                        <span class="menu-item" data-key="t-charts">@lang('translation.Charts')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="charts-apex" data-key="t-apex-charts">@lang('translation.Apex')</a></li>
                        <li><a href="charts-chartjs" data-key="t-chartjs-charts">@lang('translation.Chartjs')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-aperture icon nav-icon"></i>
                        <span class="menu-item" data-key="t-icons">@lang('translation.Icons')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="icons-feather" data-key="t-feather">@lang('translation.Feather')</a></li>
                        <li><a href="icons-boxicons" data-key="t-boxicons">@lang('translation.Boxicons')</a></li>
                        <li><a href="icons-materialdesign" data-key="t-material-design">@lang('translation.Material_Design')</a></li>
                        <li><a href="icons-dripicons" data-key="t-dripicons">@lang('translation.Dripicons')</a></li>
                        <li><a href="icons-fontawesome" data-key="t-font-awesome">@lang('translation.Font_awesome')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-map icon nav-icon"></i>
                        <span class="menu-item" data-key="t-maps">@lang('translation.Maps')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="maps-google" data-key="t-google">@lang('translation.Google')</a></li>
                        <li><a href="maps-vector" data-key="t-vector">@lang('translation.Vector')</a></li>
                        <li><a href="maps-leaflet" data-key="t-leaflet">@lang('translation.Leaflet')</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="bx bx-share-alt icon nav-icon"></i>
                        <span class="menu-item" data-key="t-multi-level">@lang('translation.Multi_Level')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="javascript: void(0);" data-key="t-level-1.1">@lang('translation.Level_1_1')</a></li>
                        <li><a href="javascript: void(0);" class="has-arrow"
                                data-key="t-level-1.2">@lang('translation.Level_1_2')</a>
                            <ul class="sub-menu" aria-expanded="true">
                                <li><a href="javascript: void(0);" data-key="t-level-2.1">@lang('translation.Level_2_1')</a></li>
                                <li><a href="javascript: void(0);" data-key="t-level-2.2">@lang('translation.Level_2_2')</a></li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
