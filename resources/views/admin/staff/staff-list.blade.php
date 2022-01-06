<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')

@endpush
{{ view('admin.templates.header') }}

<body class="nk-body npc-default has-apps-sidebar has-sidebar">

<div class="nk-app-root">
    {{ view('admin.templates.sidebar') }}
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            {{ view('admin.templates.topmenu') }}
            <!-- main header @e -->
            {{ view('admin.templates.sidemenus.sidemenu-default') }}

            <!-- content @s -->
            <div class="nk-content ">

                {{ view('admin.templates.alert') }}

                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">
                            <div class="nk-block-head nk-block-head-sm">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h3 class="nk-block-title page-title">Staff list</h3>
                                        <div class="nk-block-des text-soft">
                                            <p>Manage your staff here.</p>
                                        </div>
                                    </div><!-- .nk-block-head-content -->
                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                            <div class="toggle-expand-content" data-content="pageMenu">
                                                <ul class="nk-block-tools g-3">
                                                    @can('create-users')
                                                    <li><a href="#" data-toggle="modal" data-target="#modalCreateStaff" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add user</span></a></li>
                                                    @endcan
                                                        <!--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>-->
                                                    <!--<li class="nk-block-tools-opt">
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em class="icon ni ni-plus"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><span>Add User</span></a></li>
                                                                    <li><a href="#"><span>Add Team</span></a></li>
                                                                    <li><a href="#"><span>Import User</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>-->
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!-- .toggle-wrap -->
                                    </div><!-- .nk-block-head-content -->
                                </div><!-- .nk-block-between -->
                            </div><!-- .nk-block-head -->

                            <div class="nk-block nk-block-lg">
                                <?php if($users && $users->count() ) : ?>
                                    <div class="row g-gs">

                                        <?php foreach ($users as $user) : ?>
                                            <div class="col-sm-6 col-lg-4 col-xxl-3">
                                                <div class="card card-bordered">
                                                    <div class="card-inner">
                                                        <div class="team">
                                                            <?php if($user->status == 'active') : ?>
                                                                <div class="team-status bg-success text-white"><em class="icon ni ni-check-thick"></em></div>
                                                            <?php else : ?>
                                                                <div class="team-status bg-warning text-white"><em class="icon ni ni-na"></em></div>
                                                            <?php endif; ?>
                                                            <div class="team-options">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <!--<li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>-->
                                                                            <li><a href="{{ url('users/'.$user->id) }}"><em class="icon ni ni-eye"></em><span>View Details</span></a></li>
                                                                            <!--<li><a href="#"><em class="icon ni ni-mail"></em><span>Send Email</span></a></li>-->
                                                                            <?php //if($user->role_id != 1 && get_user()->role_id == 1) : ?>
                                                                            {{--<li class="divider"></li>
                                                                            <li><a href="#"><em class="icon ni ni-shield-star"></em><span>Reset Pass</span></a></li>
                                                                            <!--<li><a href="#"><em class="icon ni ni-shield-off"></em><span>Reset 2FA</span></a></li>-->
                                                                            <li><a href="#"><em class="icon ni ni-na"></em><span>Suspend User</span></a></li>--}}
                                                                            <?php //endif; ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="user-card user-card-s2">
                                                                <div class="user-avatar lg bg-primary">
                                                                    <!--<img src="./images/avatar/a-sm.jpg" alt="">-->
                                                                    <span>{{ $user->getInitialsAttribute() }}</span>
                                                                    <!--<div class="status dot dot-lg dot-success"></div>-->
                                                                </div>
                                                                <div class="user-info">
                                                                    <h6>{{ $user->getFullNameAttribute() }}</h6>
                                                                    <span class="sub-text">{{ $user->position_in_company?: 'NA' }}</span>
                                                                </div>
                                                            </div>
                                                            <ul class="team-info">
                                                                <li><span>Join Date</span><span>{{ format_date_time($user->created) }}</span></li>
                                                                <li><span>Remaining Holiday</span><span>{{ remaining_holiday($user) }}</span></li>
                                                                <li><span>Email</span><span>{{ $user->email }}</span></li>
                                                            </ul>
                                                            <div class="team-view">
                                                                <a href="{{ url('users/'.$user->id) }}" class="btn btn-block btn-dim btn-primary"><span>View Profile</span></a>
                                                            </div>
                                                        </div><!-- .team -->
                                                    </div><!-- .card-inner -->
                                                </div><!-- .card -->
                                            </div><!-- .col -->
                                        <?php endforeach; ?>

                                    </div>
                                <?php else : ?>
                                    <div class="col-md-12 mb-3" >
                                        <div class="alert alert-warning alert-icon">
                                            <em class="icon ni ni-alert-circle"></em> No users found please create one.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div><!-- .nk-block -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</div>


<div class="modal fade" tabindex="-1" id="modalCreateStaff">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>Staff</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('users') }}" id="create-form" class=" form-validate">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="notes">First name *</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="notes">Last name *</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email address *</label>
                                <div class="form-group-wrap">
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Holidays (Inc national holidays)</label>
                                <div class="form-group-wrap">
                                    <input type="number" name="holiday_allowance" class="form-control" min="0" value="{{ old('holiday_allowance', get_setting('default_holiday_days')) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="notes">Contact number </label>
                                <div class="form-control-wrap">
                                    <input type="tel" class="form-control" name="contact_number" value="{{ old('contact_number') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="notes">Additional contact number</label>
                                <div class="form-control-wrap">
                                    <input type="tel" class="form-control" name="contact_number2" value="{{ old('contact_number2') }}">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label" for="notes">Position in company</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="position_in_company" value="{{ old('position_in_company') }}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label" for="notes">Date of Employment</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control date-picker"  data-date-format="yyyy-mm-dd" name='dates' >
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label">User role * <br><small class="text-info">To add admin you need to be admin or have the permission granted to add admins</small></label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="role_id" id="create-assigned-to-id" data-search="on" required>
                                <?php foreach(roles() as $role) : ?>
                                <?php
                                    if($role->slug == 'super-admin' && !get_user()->hasRole('super-admin')) {
                                        continue;
                                    }
                                    if($role->slug == 'admin') {
                                        if(!get_user()->can('create-admins'))
                                        {
                                            continue;
                                        }
                                    }
                                ?>
                                <option value="{{ $role->id }}" {{ is_selected($role->id, old('role_id')) }}>{{ $role->title }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="notes">Emergency contact </label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="em_name" value="{{ old('em_name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="notes">Additional contact number</label>
                                <div class="form-control-wrap">
                                    <input type="tel" class="form-control" name="em_phone" value="{{ old('em_phone') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="notes">Additional contact number</label>
                                <div class="form-control-wrap">
                                    <input type="email" class="form-control" name="em_email" value="{{ old('em_email') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="status" value="active">

                    <div class="form-group">
                        <label class="form-label">New password (to have the system generate a password leave password field as is)</label>
                        <div class="form-control-wrap">
                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password">
                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                            </a>
                            <input type="password" name="password" class="form-control form-control-lg" id="password" value="GENERATE_ME_ONE" placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
            </div>
            {{--<div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>--}}
        </div>
    </div>
</div>

@push('scripts')

@endpush
<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript" src="{{ asset('assets/js/admin/staff-list.js') }}"></script>


</html>
