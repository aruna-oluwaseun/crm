<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }

    if(!$user_role = $detail->roles[0]) {
        $user_role = (object) [id => null, 'slug' => null];
    }

    $role_permissions = $user_role->permissions;
    $role_permissions_slugs = [];
    if($role_permissions) {

        foreach ($role_permissions as $role_permission)
        {
            $role_permissions_slugs[] = $role_permission->slug;
        }
    }

?>
<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/editors/summernote.css?ver=2.2.0') }}">
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

                            <div class="nk-block nk-block-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Staff Detail</h4>
                                        </div>

                                        {{--<div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        --}}{{--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>--}}{{--

                                                        --}}{{--<li class="nk-block-tools-opt">
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-plus"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right" style="">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><span>Add User</span></a></li>
                                                                        <li><a href="#"><span>Add Team</span></a></li>
                                                                        <li><a href="#"><span>Import User</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>--}}{{--
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div>--}}
                                    </div>
                                </div>
                            </div>

                            <div class="nk-block">
                                <div class="card card-bordered">
                                    <div class="card-aside-wrap">
                                        <div class="card-inner card-inner-lg">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content" style="display: none">
                                                        <h4 class="nk-block-title">{{ $detail->title }}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                User created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
                                                                <?php if($detail->updated_by) : ?>
                                                                    Last updated by <a href="{{ url('users/'.$updated_by->id) }}">{{ $updated_by->getFullNameAttribute() }}</a> on <b>{{ date('dS F Y H:ia', strtotime($detail->updated_at)) }}</b>.
                                                                <?php endif; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content">
                                                        <a href="{{ url('calendar') }}" class="btn btn-primary"><em class="icon ni ni-plus"></em><span>Add Holiday</span></a>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Details -->
                                            <div id="tab-details" class="tab card">

                                                <form method="post" action="{{ url('users/'.$detail->id) }}" id="user-detail-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">First name *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $detail->first_name) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Last name *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $detail->last_name) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-8">
                                                           <div class="form-group">
                                                                <label class="form-label">Email address *</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $detail->email) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                           <div class="form-group">
                                                                <label class="form-label">Holiday Allowance (inclusive of national holidays)</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="number" name="holiday_allowance" @notrole('super-admin') disabled @endrole class="form-control" value="{{ old('holiday_allowance', $detail->holiday_allowance) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">User role <small class="text-info">(Only admins and user granted create admins can manage roles)</small></label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" {{ get_user()->can('create-users') ? '' : 'disabled' }} name="role_id" id="create-assigned-to-id" data-search="on">
                                                                        <option selected="selected">Select role</option>
                                                                        <?php foreach(roles() as $role) : ?>
                                                                        <?php
                                                                        if($role->slug == 'super-admin' && !get_user()->hasRole('super-admin')) {
                                                                            continue;
                                                                        }
                                                                        if($role->slug == 'admin' && !get_user()->hasRole('admin','super-admin') && !get_user()->cannot('create-admins')) {
                                                                            continue;
                                                                        }
                                                                        ?>
                                                                        <option value="{{ $role->id }}" {{ is_selected($role->id, $user_role->id) }}>{{ $role->title }}</option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Position in company</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" @notrole('super-admin') disabled @endrole name="position_in_company" value="{{ old('position_in_company', $detail->position_in_company) }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                          <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Position in company</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" @notrole('super-admin') disabled @endrole name="position_in_company" value="{{ old('position_in_company', $detail->position_in_company) }}">
                                                                </div>
                                                            </div>
                                                        </div>


                                                       
                                                    </div>


                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Contact number </label>
                                                                <div class="form-control-wrap">
                                                                    <input type="tel" class="form-control" name="contact_number" value="{{ old('contact_number', $detail->contact_number) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Additional contact number</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="tel" class="form-control" name="contact_number2" value="{{ old('contact_number2', $detail->contact_number2) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Emergency contact  </label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="em_name" value="{{ old('em_name', $detail->em_name) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Additional contact number</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="tel" class="form-control" name="em_phone" value="{{ old('em_phone', $detail->em_phone) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Additional contact number</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="email" class="form-control" name="em_email" value="{{ old('em_email', $detail->em_email) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input name="change_password" type="checkbox" class="custom-control-input" id="change-password" value="1" {{ is_checked(1, old('change_password')) }}>
                                                            <label class="custom-control-label" for="change-password"> Do you want to change password?</label>
                                                        </div>
                                                    </div>

                                                    <div id="change-password-form" class="row mb-3 pl-3 mb-4" style="display: none">
                                                        <div class="col-md-6">
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
                                                                <div class="custom-control custom-checkbox">
                                                                    <input name="send_password_email" type="checkbox" class="custom-control-input" id="send-password-email" checked="checked" value="1" {{ is_checked(1, old('send_password_email')) }}>
                                                                    <label class="custom-control-label" for="send-password-email"> Email password to <span class="text-primary" id="password-email">{{ old('email', $detail->email) }}</span></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">

                                                        </div>
                                                    </div>

                                                    @can('create-users')
                                                    <div class="form-group">
                                                        <label class="form-label">Status</label>
                                                        <div class="g-4 align-center flex-wrap">
                                                            <div class="g">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" name="status" id="status-active" value="active" {{ is_checked('active', old('status', $detail->status)) }}>
                                                                    <label class="custom-control-label" for="status-active">Active</label>
                                                                </div>
                                                            </div>
                                                            <div class="g">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" name="status" id="status-disabled" value="suspended" {{ is_checked('suspended', old('status', $detail->status)) }}>
                                                                    <label class="custom-control-label" for="status-disabled">Suspended</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endcan

                                                    @cannot('create-users')
                                                        <input type="hidden" name="status" value="{{ $detail->status }}">
                                                    @endcannot

                                                    <div id="customer-notes" class="form-group" style="display: {{ $detail->status == 'suspended' ? '' : 'none' }};">
                                                        <div class="form-group">
                                                            <label class="form-label">Suspend reason (optional)</label>
                                                            <div class="form-group-wrap">
                                                                <textarea name="notes" class="form-control">{{ old('notes', $detail->notes) }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>


                                            </div><!-- end Details -->

                                            <!-- User Activity -->
                                            <div id="tab-activity" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>Review the users activity.</p>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <!-- end user activity -->

                                            <!-- User Activity -->
                                            <div id="tab-permissions" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <h4 class="nk-block-title">Role Level : <span class="text-primary">{{ $user_role->title ?: 'Not Specified' }}</span></h4>
                                                            <p>Permissions checked are based on your roles access.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="divider mt-0"></div>

                                                <?php
                                                    $permissions = permissions();
                                                ?>
                                                <p><b>{{ $permissions->count() }}</b> permissions available</p>

                                                <div class="alert alert-info">Please note : only <b>Super Admins</b> can manage your permissions. Default roles cannot be unselected</div>

                                                <?php if($user_role->id !== null && permissions()) : ?>
                                                <form action="{{ url('users/permissions/'.$detail->id) }}" method="post">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="row">
                                                        <?php foreach($permissions as $permission) : ?>
                                                        <div class="col-md-4">
                                                            <div class="custom-control custom-checkbox mb-3" style="display: block">
                                                                <input name="permissions[]" type="checkbox" {{ (!get_user()->hasRole('super-admin') || in_array($permission->slug, $role_permissions_slugs)) ? 'disabled' : '' }} {{ $detail->can($permission->slug) ? 'checked="checked"' : '' }} class="custom-control-input" id="{{ $permission->slug }}" value="{{ $permission->slug }}">
                                                                <label class="custom-control-label" for="{{ $permission->slug }}"> {{ $permission->title }} {!! in_array($permission->slug, $role_permissions_slugs) ? ' - Default for <b>'.$user_role->title.'</b>' : ''  !!}</label>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>

                                                    </div>

                                                    <div class="form-group mt-3">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>

                                                </form>
                                                <?php else : ?>

                                                <div class="alert alert-warning">No role or permissions found</div>

                                                <?php endif; ?>

                                            </div>
                                            <!-- end user activity -->

                                            <!-- Build products -->
                                            <div id="tab-holidays" style="display: none;" class="tab card">

                                                <?php
                                                    // scope of current year in controller
                                                    $approved = $detail->holidays()->approved()->get();
                                                    $pending = $detail->holidays()->pending()->get();
                                                    $declined = $detail->holidays()->declined()->get();

                                                    if(!$days_taken = $approved->sum('time_off'))
                                                    {
                                                        $days_taken = 0;
                                                    }

                                                    $national_days_off = 0;
                                                    $bookable = $detail->holiday_allowance;
                                                    if($national_holidays && $national_holidays->count())
                                                    {
                                                        $national_days_off = $national_holidays->count();
                                                        #$bookable-= $national_days_off;
                                                    }

                                                    $remaining = $bookable-$days_taken;

                                                ?>

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <h4 class="nk-block-title">Holiday</h4>
                                                            <p>The total days remaining <span class="badge badge-pill badge-sm badge-success">{{ $remaining }}</span> of <span class="badge badge-pill badge-sm badge-primary">Total {{ $detail->holiday_allowance }} / Bookable {{ $bookable }}</span>
                                                            Please note your days remaining factors in national holidays and Christmas break.
                                                            </p>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="divider mt-0"></div>

                                                <ul class="nav nav-tabs">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab" href="#tabItem1">Approved</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#tabItem2">Pending</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#tabItem3">Declined</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <!-- APPROVED -->
                                                    <div class="tab-pane active" id="tabItem1">
                                                        <p><b>{{ $approved->count() }}</b> rows found for {{ date('Y') }}</p>


                                                        <?php if($approved && $approved->count()) : ?>

                                                        <div class="card card-bordered card-preview">
                                                            <table id="suppliers" class="table table-suppliers table-responsive">
                                                                <thead class="tb-odr-head">
                                                                <tr class="tb-odr-item">
                                                                    <th class="tb-odr-info">
                                                                        <span class="tb-odr-id">Title</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">From</span>
                                                                        <span class="d-none d-md-inline-block">Time</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">To</span>
                                                                        <span class="d-none d-md-inline-block">Time</span>
                                                                    </th>

                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">Status</span>
                                                                        <span class="d-none d-lg-inline-block"></span>
                                                                    </th>
                                                                    {{--<th class="tb-odr-action">&nbsp;</th>--}}
                                                                </tr>
                                                                </thead>
                                                                <tbody class="tb-odr-body">
                                                                <?php foreach($approved as $item) : ?>
                                                                <?php
                                                                $from = explode(' ', $item->date_start);
                                                                $to = explode(' ', $item->date_end);

                                                                $class = 'warning';
                                                                if($item->status == 'approved') {
                                                                    $class = 'success';
                                                                } elseif($item->status == 'declined') {
                                                                    $class = 'danger';
                                                                }
                                                                ?>
                                                                <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                                    <td class="tb-odr-info">
                                                                        <span class="tb-odr-id">{{ $item->title }}</span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ format_date($from[0]) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $from[1] == '00:00:00' ? 'All day' : date('H:ia',strtotime($from[1])) }}</span>
                                                                </span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ format_date($to[0]) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $to[1] == '23:59:00' ? 'All day' : date('H:ia',strtotime($to[1])) }}</span>
                                                                </span>
                                                                    </td>
                                                                    <td>
                                                                <span class="tb-odr-total">
                                                                    <span class="badge badge-dot-xs badge-{{ $class }}">{{ ucfirst($item->status) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status pl-0" >
                                                               <?php if($item->approved_by || $item->updated_by) : ?>
                                                                    <br><small class="text-muted">Actioned by : {{ get_user($item->approved_by ?: $item->updated_by)->getFullNameAttribute() }}</small>
                                                                <?php endif; ?>
                                                                </span>
                                                                    </td>
                                                                    {{--<td class="tb-odr-action">
                                                                        <div class="tb-odr-btns d-none d-md-inline">
                                                                            <a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                                                                        </div>
                                                                        <div class="dropdown">
                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                <ul class="link-list-plain">
                                                                                    <li><a href="{{ url('suppliers/'.$item->supplier_id) }}" target="_blank" class="text-primary">View supplier</a></li>
                                                                                    <li><a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </td>--}}
                                                                </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <?php else : ?>

                                                        <div class="alert alert-warning">No days off found.</div>

                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- PENDING -->
                                                    <div class="tab-pane" id="tabItem2">
                                                        <p><b>{{ $pending->count() }}</b> rows found for {{ date('Y') }}</p>


                                                        <?php if($pending && $pending->count()) : ?>

                                                        <div class="card card-bordered card-preview">
                                                            <table id="suppliers" class="table table-suppliers table-responsive">
                                                                <thead class="tb-odr-head">
                                                                <tr class="tb-odr-item">
                                                                    <th class="tb-odr-info">
                                                                        <span class="tb-odr-id">Title</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">From</span>
                                                                        <span class="d-none d-md-inline-block">Time</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">To</span>
                                                                        <span class="d-none d-md-inline-block">Time</span>
                                                                    </th>

                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">Status</span>
                                                                        <span class="d-none d-lg-inline-block"></span>
                                                                    </th>
                                                                    {{--<th class="tb-odr-action">&nbsp;</th>--}}
                                                                </tr>
                                                                </thead>
                                                                <tbody class="tb-odr-body">
                                                                <?php foreach($pending as $item) : ?>
                                                                <?php
                                                                $from = explode(' ', $item->date_start);
                                                                $to = explode(' ', $item->date_end);

                                                                $class = 'warning';
                                                                if($item->status == 'approved') {
                                                                    $class = 'success';
                                                                } elseif($item->status == 'declined') {
                                                                    $class = 'danger';
                                                                }
                                                                ?>
                                                                <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                                    <td class="tb-odr-info">
                                                                        <span class="tb-odr-id">{{ $item->title }}</span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ format_date($from[0]) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $from[1] == '00:00:00' ? 'All day' : date('H:ia',strtotime($from[1])) }}</span>
                                                                </span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ format_date($to[0]) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $to[1] == '23:59:00' ? 'All day' : date('H:ia',strtotime($to[1])) }}</span>
                                                                </span>
                                                                    </td>
                                                                    <td>
                                                                <span class="tb-odr-total">
                                                                    <span class="badge badge-dot-xs badge-{{ $class }}">{{ ucfirst($item->status) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status pl-0" >
                                                               <?php if($item->approved_by || $item->updated_by) : ?>
                                                                    <br><small class="text-muted">Actioned by : {{ get_user($item->approved_by ?: $item->updated_by)->getFullNameAttribute() }}</small>
                                                                <?php endif; ?>
                                                                </span>
                                                                    </td>
                                                                    {{--<td class="tb-odr-action">
                                                                        <div class="tb-odr-btns d-none d-md-inline">
                                                                            <a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                                                                        </div>
                                                                        <div class="dropdown">
                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                <ul class="link-list-plain">
                                                                                    <li><a href="{{ url('suppliers/'.$item->supplier_id) }}" target="_blank" class="text-primary">View supplier</a></li>
                                                                                    <li><a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </td>--}}
                                                                </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <?php else : ?>

                                                        <div class="alert alert-warning">No pending days off found.</div>

                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- DECLINED -->
                                                    <div class="tab-pane" id="tabItem3">
                                                        <p><b>{{ $declined->count() }}</b> rows found for {{ date('Y') }}</p>


                                                        <?php if($declined && $declined->count()) : ?>

                                                        <div class="card card-bordered card-preview">
                                                            <table id="suppliers" class="table table-suppliers table-responsive">
                                                                <thead class="tb-odr-head">
                                                                <tr class="tb-odr-item">
                                                                    <th class="tb-odr-info">
                                                                        <span class="tb-odr-id">Title</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">From</span>
                                                                        <span class="d-none d-md-inline-block">Time</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">To</span>
                                                                        <span class="d-none d-md-inline-block">Time</span>
                                                                    </th>

                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">Status</span>
                                                                        <span class="d-none d-lg-inline-block"></span>
                                                                    </th>
                                                                    {{--<th class="tb-odr-action">&nbsp;</th>--}}
                                                                </tr>
                                                                </thead>
                                                                <tbody class="tb-odr-body">
                                                                <?php foreach($declined as $item) : ?>
                                                                <?php
                                                                $from = explode(' ', $item->date_start);
                                                                $to = explode(' ', $item->date_end);

                                                                $class = 'warning';
                                                                if($item->status == 'approved') {
                                                                    $class = 'success';
                                                                } elseif($item->status == 'declined') {
                                                                    $class = 'danger';
                                                                }
                                                                ?>
                                                                <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                                    <td class="tb-odr-info">
                                                                        <span class="tb-odr-id">{{ $item->title }}</span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ format_date($from[0]) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $from[1] == '00:00:00' ? 'All day' : date('H:ia',strtotime($from[1])) }}</span>
                                                                </span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ format_date($to[0]) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $to[1] == '23:59:00' ? 'All day' : date('H:ia',strtotime($to[1])) }}</span>
                                                                </span>
                                                                    </td>
                                                                    <td>
                                                                <span class="tb-odr-total">
                                                                    <span class="badge badge-dot-xs badge-{{ $class }}">{{ ucfirst($item->status) }}</span>
                                                                </span>
                                                                        <span class="tb-odr-status pl-0" >
                                                               <?php if($item->approved_by || $item->updated_by) : ?>
                                                                    <br><small class="text-muted">Actioned by : {{ get_user($item->approved_by ?: $item->updated_by)->getFullNameAttribute() }}</small>
                                                                <?php endif; ?>
                                                                </span>
                                                                    </td>
                                                                    {{--<td class="tb-odr-action">
                                                                        <div class="tb-odr-btns d-none d-md-inline">
                                                                            <a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                                                                        </div>
                                                                        <div class="dropdown">
                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                <ul class="link-list-plain">
                                                                                    <li><a href="{{ url('suppliers/'.$item->supplier_id) }}" target="_blank" class="text-primary">View supplier</a></li>
                                                                                    <li><a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </td>--}}
                                                                </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <?php else : ?>

                                                        <div class="alert alert-warning">No days off found.</div>

                                                        <?php endif; ?>
                                                    </div>
                                                </div>


                                            </div><!-- end Build Products -->

                                            <!-- Build products -->
                                            <div id="tab-activity" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>Activity.</p>
                                                        </div>

                                                    </div>
                                                </div>



                                                <div class="alert alert-warning">Activity report coming soon.</div>

                                            </div><!-- end Build Products -->


                                        </div><!-- .card-inner -->

                                        <!-- Menu -->
                                        <div class="card-aside card-aside-left user-aside toggle-slide toggle-slide-left toggle-break-lg toggle-screen-lg" data-content="userAside" data-toggle-screen="lg" data-toggle-overlay="true">

                                            <div class="card-inner-group" data-simplebar="init">
                                                <div class="simplebar-wrapper" style="margin: 0px;">
                                                    <div class="simplebar-height-auto-observer-wrapper">
                                                        <div class="simplebar-height-auto-observer"></div>
                                                    </div>
                                                    <div class="simplebar-mask">
                                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                            <div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;">
                                                                <div class="simplebar-content" style="padding: 0px;">
                                                                    {{--<div class="card-inner">
                                                                        --}}{{--<a href="{{ url('products/create?id='.$detail->id) }}" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $detail->title }}"><em class="icon ni ni-copy"></em></a>--}}{{--

                                                                    </div>--}}<!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-card">
                                                                            <div class="user-avatar bg-primary">
                                                                                <span>{{ $detail->getInitialsAttribute() }}</span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="lead-text">{{ $detail->getFullNameAttribute() }}</span>
                                                                                <span class="sub-text">{{ $detail->email }}</span>
                                                                            </div>
                                                                            {{--<div class="user-action">
                                                                                <div class="dropdown">
                                                                                    <a class="btn btn-icon btn-trigger mr-n2" data-toggle="dropdown" href="#" aria-expanded="false"><em class="icon ni ni-more-v"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right" style="">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <li><a href="#"><em class="icon ni ni-camera-fill"></em><span>Change Photo</span></a></li>
                                                                                            <!--<li><a href="#"><em class="icon ni ni-edit-fill"></em><span>Update Profile</span></a></li>-->
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>--}}
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <!--<li><a href="#tab-addresses"><em class="icon ni ni-book"></em><span>Addresses</span></a></li>-->
                                                                            <li><a href="#tab-holidays"><em class="icon ni ni-calendar-booking"></em></em><span>Holidays / Sick</span></a></li>
                                                                            <?php if(get_user()->hasRole('super-admin','admin')) : ?>
                                                                            <li><a href="#tab-permissions"><em class="icon ni ni-shield-check"></em><span>Permissions</span></a></li>
                                                                            <li><a href="#tab-activity"><em class="icon ni ni-activity-round-fill"></em><span>Account Activity</span></a></li>
                                                                            <?php endif; ?>
                                                                        </ul>
                                                                    </div><!-- .card-inner -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="simplebar-placeholder" style="width: auto; height: 550px;"></div>
                                                </div>
                                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                                </div>
                                                <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                                    <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                                </div>
                                            </div><!-- .card-inner-group -->

                                        </div><!-- card-aside -->
                                    </div><!-- card-aside-wrap -->
                                </div><!-- .card -->
                            </div>

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


@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/admin/staff.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>


</html>
