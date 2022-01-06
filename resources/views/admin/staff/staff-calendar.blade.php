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
                            <div class="nk-block">
                                <div class="card bg-transparent">
                                    <div class="card-inner py-3 border-bottom border-light rounded-0">
                                        <div class="nk-block-head nk-block-head-sm">
                                            <div class="nk-block-between">
                                                <div class="nk-block-head-content">
                                                    <h3 class="nk-block-title page-title">Calendar</h3>
                                                </div><!-- .nk-block-head-content -->
                                                <div class="nk-block-head-content">
                                                    @can('approve-holidays')
                                                        <a href="#" class="btn btn-outline-primary" data-dismiss="modal" data-toggle="modal" data-target="#requestsModal"><span>Requests</span></a>
                                                    @endcan
                                                    @can('add-staff-holidays')
                                                        <a href="#" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#addEventPopup"><em class="icon ni ni-plus"></em><span>Add Event</span></a>
                                                    @endcan
                                                    @cannot('add-staff-holidays')
                                                        <a href="#" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#addEventPopup"><em class="icon ni ni-plus"></em><span>Add Holiday</span></a>
                                                    @endcannot
                                                </div><!-- .nk-block-head-content -->
                                            </div><!-- .nk-block-between -->
                                        </div><!-- .nk-block-head -->
                                    </div>
                                </div>
                                <div class="card mt-0">
                                    <div class="card-inner">
                                        <div id="calendar" class="nk-calendar"></div>
                                    </div>
                                </div>
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


<div class="modal fade" tabindex="-1" id="requestsModal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Holiday Requests</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ url('action-holiday') }}" method="post" id="actionHolidayForm" class="form-validate is-alter">
                    @csrf
                    <div class="row gx-4 gy-3">

                        <div class="col-12">
                            <?php if(isset($holiday_requests) && $holiday_requests->count()) : ?>
                            <div class="mt-3 mb-3">
                                <table class="table card-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>User</th>
                                        <th>Dates</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($holiday_requests as $request) : ?>
                                    <?php
                                        $from = format_date($request->date_start);
                                        $to = format_date($request->date_end);
                                    ?>
                                    <tr>
                                        <td><a href="{{ url('users/'.$request->id) }}" target="_blank">{{ $request->user->getFullNameAttribute() }}</a> </td>
                                        <td>{{ $from.' - '.$to }}</td>
                                        <td><small>{{ $request->title }}</small></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="border-top-style: dashed; border-top-color: #e5e9f2">
                                            <div class="g-4 align-center flex-wrap response-action">
                                                <div class="g">
                                                    <div class="custom-control custom-control-sm custom-radio">
                                                        <input type="radio" class="custom-control-input" name="holiday[{{$request->id}}][status]" value="approved" id="holiday-approve-status-{{$request->id}}">
                                                        <label class="custom-control-label" for="holiday-approve-status-{{$request->id}}">Approved</label>
                                                    </div>
                                                </div>
                                                <div class="g">
                                                    <div class="custom-control custom-control-sm custom-radio">
                                                        <input type="radio" class="custom-control-input" name="holiday[{{$request->id}}][status]" value="declined" id="holiday-decline-status-{{$request->id}}">
                                                        <label class="custom-control-label" for="holiday-decline-status-{{$request->id}}">Declined</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 mb-2 reason" style="display: none;">
                                                <input type="text" name="holiday[{{$request->id}}][reason]" class="form-control" placeholder="Reason for declining">
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                            </div>
                            <?php else : ?>

                                <div class="alert alert-warning">No pending holiday for {{ date('Y') }} found</div>

                            <?php endif; ?>
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="addEventPopup">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ url('calendar') }}" method="post" id="addEventForm" class="form-validate is-alter">
                    <div class="row gx-4 gy-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="event-title">User *</label>
                                <div class="form-control-wrap">
                                    <select type="text" name="user_id" {{ get_user()->can('add-staff-holidays') ? '' : 'disabled' }} class="form-control form-select" id="user-id" required>
                                        <option>Select staff</option>
                                        <?php foreach(get_users() as $user) : ?>
                                            <option value="{{ $user->id }}" {{ is_selected($user->id, get_user()->id) }}>{{ $user->getFullNameAttribute() }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="event-title">Event Title *</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="title" class="form-control" id="event-title" value="Holiday" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info">
                                To enter <b>one whole day</b> just select the start date only
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="over1day" value="1">
                                    <label class="custom-control-label" for="over1day"> Request is for more than 1 day?</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start date off *</label>
                                <div class="row gx-2">
                                    <div class="w-55">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            <input type="text" name="start_date" id="event-start-date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly="readonly" required>
                                        </div>
                                    </div>
                                    <div class="w-45">
                                        <div class="form-control-wrap">
                                            <!--<div class="form-icon form-icon-left">
                                                <em class="icon ni ni-clock"></em>
                                            </div>
                                            <input type="text" name="start_time" id="event-start-time" data-time-interval="60" data-time-format="HH:mm:ss" class="form-control time-picker">-->
                                            <select name="start_type" id="event-start-type" class="form-control">
                                                <option value="full_day" selected>Full Day</option>
                                                <option value="half_day">Half Day</option>
                                                <option value="quarter_day">Quarter Day</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="show-end-date" style="display: none;">
                                <label class="form-label">Last date off</label>
                                <div class="row gx-2">
                                    <div class="w-55">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            <input type="text" name="end_date" id="event-end-date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly="readonly" >
                                        </div>
                                    </div>
                                    <div class="w-45">
                                        <div class="form-control-wrap">
                                            <!--<div class="form-icon form-icon-left">
                                                <em class="icon ni ni-clock"></em>
                                            </div>
                                            <input type="text" name="end_time" id="event-end-time" data-time-interval="60" data-time-format="HH:mm:ss" class="form-control time-picker">-->
                                            <select name="end_type" id="event-end-type" class="form-control">
                                                <option value="full_day" selected>Full Day</option>
                                                <option value="half_day">Half Day</option>
                                                <option value="quarter_day">Quarter Day</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="event-description">Event Description</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" name="description" id="event-description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="event-type" class="form-group">
                            <label class="form-label">Type *</label>
                            <div class="g-4 align-center flex-wrap">
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="type" data-event-class="event-primary" id="type-holiday" value="holiday" checked>
                                        <label class="custom-control-label" for="type-holiday">Holiday</label>
                                    </div>
                                </div>
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="type" data-event-class="event-danger" id="type-sick" value="sick" {{ get_user()->can('add-staff-holidays') ? '' : 'disabled' }} {{ is_checked('sick', old('type')) }}>
                                        <label class="custom-control-label" for="type-sick">Sick</label>
                                    </div>
                                </div>
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="type" data-event-class="event-danger-dim" id="type-absence" value="authorised-absence" {{ get_user()->can('add-staff-holidays') ? '' : 'disabled' }} {{ is_checked('authorised-absence', old('type')) }}>
                                        <label class="custom-control-label" for="type-absence">Authorised Absence</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @can('approve-holidays')
                        <div id="event-status" class="form-group ">
                            <label class="form-label">Status</label>
                            <div class="g-4 align-center flex-wrap">
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="status" id="status-pending" value="pending" {{ is_checked('pending', old('status')) }}>
                                        <label class="custom-control-label" for="status-pending">Pending</label>
                                    </div>
                                </div>
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="status" id="status-approved" value="approved" {{ is_checked('approved', old('status')) }}>
                                        <label class="custom-control-label" for="status-approved">Approved</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan

                        <div class="col-12">
                            <ul class="d-flex justify-content-between gx-4 mt-1">
                                <li>
                                    <button id="addEvent" type="submit" class="btn btn-primary">Add Event</button>
                                </li>
                                <li>
                                    <button id="resetEvent" data-dismiss="modal" class="btn btn-danger btn-dim">Discard</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="editEventPopup">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Event</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ url('calendar') }}" id="editEventForm" class="form-validate is-alter">
                    @method('PUT')
                    <input id="edit-event-id" type="hidden" name="id" value="">
                    <input id="delete-event-url" type="hidden" value="{{ url('calendar') }}">

                    <div class="row gx-4 gy-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="event-title">User *</label>
                                <div class="form-control-wrap">
                                    <select type="text" name="user_id" {{ get_user()->can('add-staff-holidays') ? '' : 'disabled' }} class="form-control form-select" id="edit-user-id" required>
                                        <option>Select staff</option>
                                        <?php foreach(get_users() as $user) : ?>
                                        <option value="{{ $user->id }}">{{ $user->getFullNameAttribute() }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="edit-event-title">Event Title *</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="title" class="form-control" id="edit-event-title" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="edit-over1day" value="1">
                                    <label class="custom-control-label" for="edit-over1day"> Request is for more than 1 day?</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Start date off *</label>
                                <div class="row gx-2">
                                    <div class="w-55">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            <input type="text" name="start_date" id="edit-event-start-date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly="readonly" required>
                                        </div>
                                    </div>
                                    <div class="w-45">
                                        <div class="form-control-wrap">
                                            <!--<div class="form-icon form-icon-left">
                                                <em class="icon ni ni-clock"></em>
                                            </div>
                                            <input type="text" name="start_time" id="event-start-time" data-time-interval="60" data-time-format="HH:mm:ss" class="form-control time-picker">-->
                                            <select name="start_type" id="edit-event-start-type" class="form-control">
                                                <option value="full_day" selected>Full Day</option>
                                                <option value="half_day">Half Day</option>
                                                <option value="quarter_day">Quarter Day</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="show-edit-end-date" style="display: none;">
                                <label class="form-label">Last date off</label>
                                <div class="row gx-2">
                                    <div class="w-55">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            <input type="text" name="end_date" id="edit-event-end-date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly="readonly" >
                                        </div>
                                    </div>
                                    <div class="w-45">
                                        <div class="form-control-wrap">
                                            <!--<div class="form-icon form-icon-left">
                                                <em class="icon ni ni-clock"></em>
                                            </div>
                                            <input type="text" name="end_time" id="event-end-time" data-time-interval="60" data-time-format="HH:mm:ss" class="form-control time-picker">-->
                                            <select name="end_type" id="edit-event-end-type" class="form-control">
                                                <option value="full_day" selected>Full Day</option>
                                                <option value="half_day">Half Day</option>
                                                <option value="quarter_day">Quarter Day</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="edit-event-description">Event Description</label>
                                <div class="form-control-wrap">
                                    <textarea class="form-control" name="description" id="edit-event-description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="edit-event-type" class="form-group">
                            <label class="form-label">Type *</label>
                            <div class="g-4 align-center flex-wrap">
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="type" data-event-class="event-primary" id="edit-type-holiday" {{ get_user()->can('add-staff-holidays') ? '' : 'checked' }} value="holiday">
                                        <label class="custom-control-label" for="edit-type-holiday">Holiday</label>
                                    </div>
                                </div>
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="type" data-event-class="event-danger" id="edit-type-sick" {{ get_user()->can('add-staff-holidays') ? '' : 'disabled' }} value="sick">
                                        <label class="custom-control-label" for="edit-type-sick">Sick</label>
                                    </div>
                                </div>
                                <div class="g">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" name="type" data-event-class="event-danger-dim" id="edit-type-absence" {{ get_user()->can('add-staff-holidays') ? '' : 'disabled' }} value="authorised-absence">
                                        <label class="custom-control-label" for="edit-type-absence">Authorised Absence</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{--<div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Event Category</label>
                                <div class="form-control-wrap">
                                    <select id="edit-event-theme" class="select-calendar-theme form-control form-control-lg">
                                        <option value="event-primary">Company</option>
                                        <option value="event-success">Seminars </option>
                                        <option value="event-info">Conferences</option>
                                        <option value="event-warning">Meeting</option>
                                        <option value="event-danger">Business dinners</option>
                                        <option value="event-pink">Private</option>
                                        <option value="event-primary-dim">Auctions</option>
                                        <option value="event-success-dim">Networking events</option>
                                        <option value="event-info-dim">Product launches</option>
                                        <option value="event-warning-dim">Fundrising</option>
                                        <option value="event-danger-dim">Sponsored</option>
                                        <option value="event-pink-dim">Sports events</option>
                                    </select>
                                </div>
                            </div>
                        </div>--}}
                        <div class="col-12">
                            <ul class="d-flex justify-content-between gx-4 mt-1">
                                <li>
                                    <button id="updateEvent" type="submit" class="btn btn-primary">Update Event</button>
                                </li>
                                <li>
                                    <button data-dismiss="modal" data-toggle="modal" data-target="#deleteEventPopup" class="btn btn-danger btn-dim">Delete</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" id="previewEventPopup">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div id="preview-event-header" class="modal-header">
                <h5 id="preview-event-title" class="modal-title"></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <div class="row gy-3 py-1">
                    <div class="col-sm-6">
                        <h6 class="overline-title">Start Time</h6>
                        <p id="preview-event-start"></p>
                    </div>
                    <div class="col-sm-6" id="preview-event-end-check">
                        <h6 class="overline-title">End Time</h6>
                        <p id="preview-event-end"></p>
                    </div>
                    <div class="col-sm-10" id="preview-event-description-check">
                        <h6 class="overline-title">Description</h6>
                        <p id="preview-event-description"></p>
                    </div>
                </div>
                @role('super-admin')
                <ul class="d-flex justify-content-between gx-4 mt-3">
                    <li>
                        <button data-dismiss="modal" data-toggle="modal" data-target="#editEventPopup" class="btn btn-primary">Edit Event</button>
                    </li>
                    <li>
                        <button data-dismiss="modal" data-toggle="modal" data-target="#deleteEventPopup" class="btn btn-danger btn-dim">Delete</button>
                    </li>
                </ul>
                @endrole
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="deleteEventPopup">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body modal-body-lg text-center">
                <div class="nk-modal py-4">
                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                    <h4 class="nk-modal-title">Are You Sure ?</h4>
                    <div class="nk-modal-text mt-n2">
                        <p class="text-soft">This event data will be removed permanently.</p>
                    </div>
                    <ul class="d-flex justify-content-center gx-4 mt-4">
                        <li>
                            <button data-dismiss="modal" id="deleteEvent" class="btn btn-success">Yes, Delete it</button>
                        </li>
                        <li>
                            <button data-dismiss="modal" data-toggle="modal" data-target="#editEventPopup" class="btn btn-danger btn-dim">Cancel</button>
                        </li>
                    </ul>
                </div>
            </div><!-- .modal-body -->
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/libs/fullcalendar.js') }}"></script>
    <script src="{{ asset('assets/js/admin/apps/calendar.js') }}"></script>
@endpush

<script type="text/javascript">
    var user_id = {!! get_user()->can('add-staff-holidays') ? "''" : get_user()->id !!};
    var show_requests_modal = {{ request()->exists('requests') ? 'true' : 'false' }};
</script>

<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

</html>
