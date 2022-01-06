<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-app-name">
                <div class="nk-header-app-logo">
                    <img src="{{ asset('assets/files/img/Jenflow-Logo.png') }}" alt="Jenflow Systems Ltd">
                </div>
            </div>
            <div class="nk-header-menu is-light">
                <div class="nk-header-menu-inner">
                    <!-- Global Search -->
                    <livewire:admin.global-search />

                    <!-- Menu -->
                    <!--<ul class="nk-menu nk-menu-main">
                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link">
                                <span class="nk-menu-text">Overview</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="#" class="nk-menu-link">
                                <span class="nk-menu-text">Components</span>
                            </a>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-text">Use-Case Panel</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item">
                                    <a href="#" class="nk-menu-link"><span class="nk-menu-text">Some other Panel</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="#" class="nk-menu-link"><span class="nk-menu-text">File Manangement Panel</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="#" class="nk-menu-link"><span class="nk-menu-text">Subscription Panel</span></a>
                                </li>

                            </ul>
                        </li>
                    </ul>-->
                    <!-- Menu -->
                </div>
            </div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    {{--<li class="dropdown chats-dropdown hide-mb-xs">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-na"><em class="icon ni ni-comments"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Recent Chats</span>
                                <a href="#">Setting</a>
                            </div>
                            <div class="dropdown-body">
                                <ul class="chat-list">
                                    <li class="chat-item is-unread">
                                        <a class="chat-link" href="#">
                                            <div class="chat-media user-avatar bg-pink">
                                                <span>AB</span>
                                                <span class="status dot dot-lg dot-success"></span>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Nom Nom Dave</div>
                                                    <span class="time">4:49 AM</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">Hi, i need some mental help ?</div>
                                                    <div class="status unread">
                                                        <em class="icon ni ni-bullet-fill"></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li><!-- .chat-item -->
                                    <li class="chat-item">
                                        <a class="chat-link" href="#">
                                            <div class="chat-media user-avatar user-avatar-multiple">
                                                <div class="user-avatar">
                                                    <img src="./images/avatar/c-sm.jpg" alt="">
                                                </div>
                                                <div class="user-avatar">
                                                    <span>AB</span>
                                                </div>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Chuck Palumbo</div>
                                                    <span class="time">27 Mar</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">You: What up hunky balls</div>
                                                    <div class="status sent">
                                                        <em class="icon ni ni-check-circle"></em>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </li><!-- .chat-item -->
                                    <li class="chat-item">
                                        <a class="chat-link" href="#">
                                            <div class="chat-media user-avatar">
                                                <img src="./images/avatar/a-sm.jpg" alt="">
                                                <span class="status dot dot-lg dot-success"></span>
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-from">
                                                    <div class="name">Larry Barry</div>
                                                    <span class="time">3 Apr</span>
                                                </div>
                                                <div class="chat-context">
                                                    <div class="text">Vasup?</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li><!-- .chat-item -->

                                </ul><!-- .chat-list -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="html/chats.html">View All</a>
                            </div>
                        </div>
                    </li>--}}

                    <?php
                        $notifications = get_user()->notificationsUnread;
                    ?>
                    <li class="dropdown notification-dropdown">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-{{ $notifications->count() ? 'danger' : 'off' }}">
                                <div class="pulse"></div>
                                <em class="icon ni ni-bell"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">{{ $notifications->count() }} Notification(s)</span>
                                <?php if($notifications->count()) : ?>
                                    <a id="mark-notification-read" href="{{ url('read-notifications') }}">Mark All as Read</a>
                                <?php endif; ?>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                <?php if($notifications->count()) : ?>

                                    <?php foreach($notifications as $notification) : ?>

                                        <?php if($notification->url) : ?>
                                            <a href="{{ url('view-notification/'.$notification->id.'?url='.$notification->url) }}" class="nk-notification-item dropdown-inner">
                                                <div class="nk-notification-icon">
                                                    <em class="icon icon-circle bg-primary-dim ni ni-link"></em>
                                                </div>
                                                <div class="nk-notification-content">
                                                    <div class="nk-notification-text">{!! $notification->title !!} </div>
                                                    <div class="nk-notification-time">{{ relative_time($notification->created_at) == 'Just now' ? 'Just now' : relative_time($notification->created_at).' ago' }}</div>
                                                </div>
                                            </a>
                                        <?php else : ?>
                                            <div class="nk-notification-item dropdown-inner">
                                                <div class="nk-notification-icon">
                                                    <em class="icon icon-circle bg-info-dim ni ni-eye"></em>
                                                </div>
                                                <div class="nk-notification-content">
                                                    <div class="nk-notification-text">{{ $notification->title }}</div>
                                                    <div class="nk-notification-time">{{ relative_time($notification->created_at) == 'Just now' ? 'Just now' : relative_time($notification->created_at).' ago' }}</div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                <?php else :  ?>

                                    <div class="nk-notification-item dropdown-inner justify-center">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-happy"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You are upto date.</div>
                                            <div class="nk-notification-time">No notifications to view.</div>
                                        </div>
                                    </div>

                                <?php endif; ?>
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->

                            <!--<div class="dropdown-foot center">
                                <a href="#">View All</a>
                            </div>-->
                        </div>
                    </li>
                    <li class="dropdown list-apps-dropdown d-lg-none">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-na"><em class="icon ni ni-menu-circled"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <div class="dropdown-body">
                                <ul class="list-apps">
                                    <?php if(!get_user()->hasRole('warehouse','warehouse-supervisor')) : ?>
                                    <li>
                                        <a href="/">
                                            <span class="list-apps-media"><em class="icon ni ni-dashboard bg-primary text-white"></em></span>
                                            <span class="list-apps-title">Overview Dashboard</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="list-apps-media"><em class="icon ni ni-cart bg-info-dim"></em></span>
                                            <span class="list-apps-title">Sales Dashboard</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="list-apps-media"><em class="icon ni ni-meter bg-purple-dim"></em></span>
                                            <span class="list-apps-title">Production Dashboard</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    {{--<li>
                                        <a href="html/apps/messages.html">
                                            <span class="list-apps-media"><em class="icon ni ni-chat bg-success-dim"></em></span>
                                            <span class="list-apps-title">Messages</span>
                                        </a>
                                    </li>--}}
                                    @can('view-files')
                                    <li>
                                        <a href="#">
                                            <span class="list-apps-media"><em class="icon ni ni-folder bg-purple-dim"></em></span>
                                            <span class="list-apps-title">File Manager</span>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                                <ul class="list-apps">
                                    <li>
                                        <a href="{{ url('calendar') }}">
                                            <span class="list-apps-media"><em class="icon ni ni-calendar bg-danger-dim"></em></span>
                                            <span class="list-apps-title">Staff Calendar</span>
                                        </a>
                                    </li>
                                    @can('view-users')
                                    <li>
                                        <a href="{{ url('users') }}">
                                            <span class="list-apps-media"><em class="icon ni ni-user-list bg-primary-dim"></em></span>
                                            <span class="list-apps-title">Staff</span>
                                        </a>
                                    </li>
                                    @endcan
                                </ul>
                            </div><!-- .nk-dropdown-body -->
                        </div>
                    </li>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>{{ get_user()->getInitialsAttribute() }}</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ get_user()->getFullNameAttribute() }}</span>
                                        <span class="sub-text">{{ get_user()->email }}</span>
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
                                    {{--<li><a href="#"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li>--}}
                                    {{--<li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>--}}
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ url('logout') }}"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
