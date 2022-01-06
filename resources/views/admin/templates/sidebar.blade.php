<div class="nk-apps-sidebar is-dark">
    <div class="nk-apps-brand">
        <a href="" class="logo-link">
            <img class="logo-light logo-img" src="{{ asset('assets/files/img/menu-logo.png') }}"  alt="logo">{{--srcset="./images/logo-small2x.png 2x"--}}
            <img class="logo-dark logo-img" src="{{ asset('assets/files/img/menu-logo-dark.png') }}" alt="logo-dark">{{--srcset="./images/logo-dark-small2x.png 2x"--}}
        </a>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-body">
            <div class="nk-sidebar-content" data-simplebar>
                <div class="nk-sidebar-menu">
                    <!-- Menu -->
                    <ul class="nk-menu apps-menu">
                        <?php if(!get_user()->hasRole('warehouse','warehouse-supervisor')) : ?>
                            <li class="nk-menu-item">
                                <a href="/" class="nk-menu-link" title="Overview Dashboard">
                                    <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link" title="Sales Dashboard">
                                    <span class="nk-menu-icon"><em class="icon ni ni-cart"></em></span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="#" class="nk-menu-link" title="Production Dashboard">
                                    <span class="nk-menu-icon"><em class="icon ni ni-meter"></em></span>
                                </a>
                            </li>

                        <li class="nk-menu-hr"></li>
                        <?php endif; ?>


                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link" title="Mailbox">
                                <span class="nk-menu-icon"><em class="icon ni ni-inbox"></em></span>
                            </a>
                        </li>
                        {{--<li class="nk-menu-item">
                            <a href="#" class="nk-menu-link" title="Messages">
                                <span class="nk-menu-icon"><em class="icon ni ni-chat"></em></span>
                            </a>
                        </li>--}}
                        @can('view-files')
                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link" title="File Manager">
                                <span class="nk-menu-icon"><em class="icon ni ni-folder"></em></span>
                            </a>
                        </li>
                        @endcan
                        {{--<li class="nk-menu-item">
                            <a href="#" class="nk-menu-link" title="Chats">
                                <span class="nk-menu-icon"><em class="icon ni ni-chat-circle"></em></span>
                            </a>
                        </li>--}}
                        <li class="nk-menu-item">
                            <a href="{{ url('calendar') }}" class="nk-menu-link" title="Staff Calendar">
                                <span class="nk-menu-icon"><em class="icon ni ni-calendar"></em></span>
                            </a>
                        </li>

                        <li class="nk-menu-hr"></li>
                        @can('view-users')
                        <li class="nk-menu-item">
                            <a href="{{ url('users') }}" class="nk-menu-link" title="Go to Staff">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                @can('view-settings')
                <div class="nk-sidebar-footer">
                    <ul class="nk-menu nk-menu-md">
                        <li class="nk-menu-item">
                            <a href="{{ url('settings') }}" class="nk-menu-link" title="Settings">
                                <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endcan
            </div>
            <div class="nk-sidebar-profile nk-sidebar-profile-fixed dropdown">
                <a href="#" data-toggle="dropdown" data-offset="50,-60">
                    <div class="user-avatar">
                        <span><span>{{ get_user()->getInitialsAttribute() }}</span></span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-md ml-4">
                    <div class="dropdown-inner user-card-wrap d-none d-md-block">
                        <div class="user-card">
                            <div class="user-avatar">
                                <span>{{ get_user()->getInitialsAttribute() }}</span>
                            </div>
                            <div class="user-info">
                                <span class="lead-text">{{ get_user()->getFullNameAttribute() }}</span>
                                <span class="sub-text text-soft">{{ get_user()->email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-inner">
                        <ul class="link-list">
                            <li><a href="{{ url('users/'.current_user_id()) }}"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                            @can('modify-settings')
                                <li><a href="{{ url('settings') }}"><em class="icon ni ni-setting-alt"></em><span>Settings</span></a></li>
                            @endcan
                            <!--<li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>-->
                        </ul>
                    </div>
                    <div class="dropdown-inner">
                        <ul class="link-list">
                            <li><a href="{{ url('logout') }}"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
