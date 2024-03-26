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
                            <i class="bx bxs-user-detail icon nav-icon"></i>
                            <span class="menu-item" data-key="t-roles">@lang('translation.Roles')</span>
                        </a>
                    </li>
                @endcan

                @can('Logs')
                    <li>
                        <a href="{{ route('logs.index') }}">
                            <i class="bx bxs-user-detail icon nav-icon"></i>
                            <span class="menu-item" data-key="t-logs">@lang('translation.Logs')</span>
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
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                            @case(5)
                                <li>
                                    <a href="{{ route('groups.initialize') }}">
                                        <i class="bx bx-group icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-my.group">@lang('translation.MyGroup')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Groups.advisers')
                    <li>
                        <a href="{{ route('groups.index') }}">
                            <i class="bx bx-group icon nav-icon"></i>
                            <span class="menu-item" data-key="t-cycles">@lang('translation.Groups')</span>
                        </a>
                    </li>
                @endcan


                @can('Entities')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(2)
                                <li>
                                    <a href="{{ route('entities.index') }}">
                                        <i class="bx bx-building-house icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-entities">@lang('translation.Entities')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Applications.advisers')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(3)
                            @case(2)
                                <li>
                                    <a href="{{ route('proposals.applications.coordinator.index') }}">
                                        <i class="bx bx-list-check icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-applications">@lang('translation.Applications')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Proposals.advisers')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(2)
                            @case(3)
                                <li>
                                    <a href="{{ route('proposals.index') }}">
                                        <i class="bx bx-receipt icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-proposals">@lang('translation.ProposalsAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Proposals.students')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(2)
                            @case(3)
                                <li>
                                    <a href="{{ route('proposals.applications.index') }}">
                                        <i class="bx bx-receipt icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-proposals">@lang('translation.Proposals')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Preprofiles.students')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                                <li>
                                    <a href="{{ route('profiles.preprofile.index') }}">
                                        <i class="bx bx-file-blank icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-preprofile">@lang('translation.PreProfile')</span>
                                    </a>
                                </li>
                            @break

                            @case(2)
                            @case(5)

                            @case(3)
                                <li>
                                    <a href="{{ route('plannings.index') }}">
                                        <i class="bx bx-calendar icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-planing">@lang('translation.Plannings')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Activities.students')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(2)
                            @case(3)

                            @case(5)
                                <li>
                                    <a href="{{ route('activities.index') }}">
                                        <i class="bx bx-calendar-check icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-activities">@lang('translation.Activities')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Projects.students')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                            @case(2)

                            @case(3)
                            @case(4)
                                <li>
                                    <a href="{{ route('projects.index') }}">
                                        <i class="bx bx-code-block icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-projects">@lang('translation.Projects')</span>
                                    </a>
                                </li>
                            @break

                            @case(5)
                                <li>
                                    <a href="{{ route('evaluations.index') }}">
                                        <i class="bx bx-code-block icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-projects">@lang('translation.Projects')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Preprofiles.advisers')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                                <li>
                                    <a href="{{ route('profiles.preprofile.coordinator.index') }}">
                                        <i class="bx bx-file-blank icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-preprofileadviser">@lang('translation.PreProfileAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @case(2)
                            @case(5)

                            @case(3)
                                <li>
                                    <a href="{{ route('profiles.preprofile.coordinator.index') }}">
                                        <i class="bx bx-calendar icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-planing">@lang('translation.PlanningsAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Profiles.students')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                                <li>
                                    <a href="{{ route('profiles.index') }}">
                                        <i class="bx bx-file icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-profile">@lang('translation.Profiles')</span>
                                    </a>
                                </li>
                            @break
                        @endswitch
                    @endif
                @endcan

                @can('Profiles.advisers')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                                <li>
                                    <a href="{{ route('profiles.coordinator.index') }}">
                                        <i class="bx bx-file icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-profileadviser">@lang('translation.ProfilesAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @case(5)
                                <!--
                                                                                                                                                                Esta opción no se mostrará al Coordinador del protocolo.
                                                                                                                                                            -->
                            @break

                            @default
                        @endswitch
                    @endif

                @endcan

                @can('Activities.advisers')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(2)
                            @case(3)

                            @case(5)
                                <li>
                                    <a href="{{ route('activities.coordinator.index.groups') }}">
                                        <i class="bx bx-calendar-check icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-activities">@lang('translation.ActivitiesAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Stages')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                            @case(2)

                            @case(3)
                            @case(4)
                                <li>
                                    <a href="{{ route('stages.index') }}">
                                        <i class="bx bx-git-pull-request icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-stages">@lang('translation.Stages')</span>
                                    </a>
                                </li>
                            @break

                            @case(5)
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i class="bx bx-list-ol icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-evaluations">@lang('translation.Evaluations')</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('evaluations.execution', 2) }}" data-key="t-phases">@lang('translation.Planning')</a>
                                        </li>
                                        <li><a href="{{ route('phases.index') }}" data-key="t-phases">@lang('translation.Execution')</a>
                                        </li>
                                        <li><a href="{{ route('evaluations.execution', 3) }}" data-key="t-phases">@lang('translation.Memory')</a>
                                        </li>
                                        <li><a href="{{ route('stages.index') }}" data-key="t-stages">@lang('translation.ThematicAreas')</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>

                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Courses')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(4)
                                <li>
                                    <a href="{{ route('courses.index') }}">
                                        <i class="bx bx-book-reader icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-courses">@lang('translation.Courses')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
                @endcan

                @can('Projects.advisers')
                    @if (session('protocol') != null)
                        @switch(session('protocol')['id'])
                            @case(1)
                            @case(2)

                            @case(3)
                            @case(4)
                                <li>
                                    <a href="{{ route('projects.coordinator.index') }}">
                                        <i class="bx bx-code-block icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-projectsadviser">@lang('translation.ProjectsAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @case(5)
                                <li>
                                    <a href="{{ route('evaluations.coordinator.index') }}">
                                        <i class="bx bx-code-block icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-projectsadviser">@lang('translation.ProjectsAdviser')</span>
                                    </a>
                                </li>
                            @break

                            @default
                        @endswitch
                    @endif
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
                            <i class="bx bxs-user-detail icon nav-icon"></i>
                            <span class="menu-item" data-key="t-groupsassigned">@lang('translation.GroupsAssigned')</span>
                        </a>
                    </li>
                @endcan

                @can('Notifications')
                    <li>
                        <a href="{{ route('notifications.index') }}">
                            <i class="bx bx-bell icon nav-icon"></i>
                            <span class="menu-item" data-key="t-notifications">@lang('translation.Notifications')</span>
                        </a>
                    </li>
                @endcan

                @if (session('protocol') != null)
                    @switch(session('protocol')['id'])
                        @case(3)
                            @can('Forums.advisers')
                                <li>
                                    <a href="{{ route('forum.index') }}">
                                        <i class="bx bx-slideshow icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-forums">@lang('translation.Forums')</span>
                                    </a>
                                </li>
                            @endcan


                            @can('Workshops.advisers')
                                <li>
                                    <a href="{{ route('workshop.index') }}">
                                        <i class="bx bx-home-circle icon nav-icon"></i>
                                        <span class="menu-item" data-key="t-workshops">@lang('translation.Workshops')</span>
                                    </a>
                                </li>
                            @endcan

                            <li>
                                <a href="{{ route('forum.show.list.forums.workshops') }}">
                                    <i class="bx bx-home-circle icon nav-icon"></i>
                                    <span class="menu-item" data-key="t-workshops">Talleres y foros</span>
                                </a>
                            </li>
                        @break
                    @endswitch
                @endif

                
                @can('Events.students')
                    <a href="{{ route('events.index', $project->id) }}" style="margin-left: 5px" class="btn btn-primary float-end">
                        <i class="bx bx-home-circle icon nav-icon"></i><span class="menu-item" data-key="t-workshops">Defensas</span>
                    </a>
                @endcan

                @can('Events.advisers')
                    <a href="{{ route('events.coordinator.index', $project->id) }}" style="margin-left: 5px" class="btn btn-primary float-end">
                        <i class="bx bx-file icon nav-icon"></i><span class="menu-item" data-key="t-workshops">Defensas</span>
                    </a>
                @endcan


                @can('Withdrawals.students')
                    <li>
                        <a href="{{ route('withdrawals.index') }}">
                            <i class="bx bx-dislike icon nav-icon"></i>
                            <span class="menu-item" data-key="t-withdrawals">@lang('translation.Withdrawals')</span>
                        </a>
                    </li>
                @endcan
                @can('Withdrawals.advisers')
                    <li>
                        <a href="{{ route('withdrawals.coordinator.index') }}">
                            <i class="bx bx-dislike icon nav-icon"></i>
                            <span class="menu-item" data-key="t-withdrawalsadviser">@lang('translation.WithdrawalsAdviser')</span>
                        </a>
                    </li>
                @endcan

                @can('Extensions.advisers')
                    <li>
                        <a href="{{ route('extensions.coordinator.index') }}">
                            <i class="bx bx-plus icon nav-icon"></i>
                            <span class="menu-item" data-key="t-extensionsadviser">@lang('translation.ExtensionsAdviser')</span>
                        </a>
                    </li>
                @endcan

                @can('TypeAgreements.advisers')
                    <li>
                        <a href="{{ route('type_agreements.index') }}">
                            <i class="bx bx-highlight icon nav-icon"></i>
                            <span class="menu-item" data-key="t-withdrawalsadviser">@lang('translation.TypesAgreements')</span>
                        </a>
                    </li>
                @endcan

                @can('Agreements.protocol')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="bx bx-list-ol icon nav-icon"></i>
                            <span class="menu-item" data-key="t-agreements">Acuerdos</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('agreements.protocol.school') }}" data-key="t-agreementsprotocol">
                                    Acuerdos de protocolo
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
