<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = false;
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = false;
    }

    if(isset($detail->date_start))
    {
        $detail->date_start = explode(' ', $detail->date_start)[0];
    }

    if(isset($detail->date_end))
    {
        $detail->date_end = explode(' ', $detail->date_end)[0];
    }

    $form_locked = false;
    if(in_array($detail->status, ['completed','deleted'])) {
        $form_locked = true;
    }

    $training_started = false;
    if(date('Y-m-d') >= $detail->date_start || $detail->status == 'live')
    {
        $training_started = true;
    }


    $ignore_links = [];

    if(isset($detail->stockLinks) && $detail->stockLinks->count())
     {
        foreach ($detail->stockLinks as $link)
        {
            $ignore_links[] = $link->updates_training_id_stock;
        }
    }


    // ---------------- Attendees
    $pending_attendees = $detail->attendees()->pendingTrainingBooking()->get();
    $paid_attendees = $detail->attendees()->activeTrainingBooking()->get();

    // ---------------- Attendees from parent linked course
    if(isset($detail->linkedToTrainingStock))
    {
        $parent_pending_attendees = $detail->linkedToTrainingStock->trainingDate->attendees()->pendingTrainingBooking()->get();
        $parent_paid_attendees = $detail->linkedToTrainingStock->trainingDate->attendees()->activeTrainingBooking()->get();
    }

?>

<!DOCTYPE html>
<html lang="eng" class="js">

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
                                            <h4 class="title nk-block-title">Training Ref #{{$detail->id}}</h4>
                                            <div class="nk-block-des">
                                                <p>
                                                    <?php if($created_by) : ?>
                                                        Training date created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
                                                    <?php endif; ?>
                                                    <?php if($detail->updated_by) : ?>
                                                        Last updated by <a href="{{ url('users/'.$updated_by->id) }}">{{ $updated_by->getFullNameAttribute() }}</a> on <b>{{ date('dS F Y H:ia', strtotime($detail->updated_at)) }}</b>.
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-printer"></em><span>Print training sheet</span></a></li>
                                                        {{--<li class="nk-block-tools-opt">
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
                                                        </li>--}}
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div>
                                    </div>

                                </div>

                                <div class="mb-3">
                                    <?php if($training_started) : ?>
                                    <div class="alert alert-success text-center"><b>LIVE</b> This training date is live</div>
                                    <?php endif; ?>

                                    <?php if($detail->status == 'completed') : ?>
                                        <div class="alert alert-success text-center"><b>FINISHED</b> Training finished</div>
                                    <?php endif; ?>

                                    <?php if($detail->status == 'deleted') : ?>
                                    <div class="alert alert-danger text-center"><b>CANCELLED</b> Training cancelled</div>
                                    <?php endif; ?>

                                    <?php if($detail->status == 'upcoming') : ?>
                                    <div class="alert alert-info text-center"><b>UPCOMING</b> Training upcoming</div>
                                    <?php endif; ?>
                                </div>

                                <div class="row g-gs">

                                    <div class="col-lg-5">
                                        <div class="card card-bordered h-100">
                                            <div class="card-inner">
                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <h4 class="nk-block-title">Course</h4>
                                                        </div>

                                                        <?php if(!$form_locked) : ?>
                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add stock link</span></a>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>

                                                <div class="mb-3">
                                                    <?php  $spaces =  training_spaces($detail->id); ?>
                                                    <h3>{{ $spaces->paid }} / {{ $spaces->remaining }}</h3>
                                                    <p>Booked / Vacant</p>
                                                </div>
                                                <form method="post" action="{{ url('training-dates/'.$detail->id) }}" id="edit-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Product *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="product_id" id="create-product-id" data-search="on" {{ ($form_locked || $training_started) ? 'disabled' : '' }}>
                                                                <option value="" selected="selected">Select a product</option>
                                                                <?php foreach($products as $product) : ?>
                                                                <option value="{{ $product->id }}" {{ is_selected($product->id, old('product_id',$detail->product_id)) }}>{{ $product->title }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group" id="show-end-date">
                                                                <label class="form-label">Training Start</label>
                                                                <div class="form-control-wrap">
                                                                    <div class="form-icon form-icon-left">
                                                                        <em class="icon ni ni-calendar"></em>
                                                                    </div>
                                                                    <input type="text" name="date_start" id="date-start" class="form-control date-picker" {{ ($form_locked || $training_started) ? 'disabled' : '' }} value="{{ old('date_start', $detail->date_start) }}" data-date-format="yyyy-mm-dd" readonly="readonly" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group" id="show-end-date">
                                                                <label class="form-label">Training Last Day</label>
                                                                <div class="form-control-wrap">
                                                                    <div class="form-icon form-icon-left">
                                                                        <em class="icon ni ni-calendar"></em>
                                                                    </div>
                                                                    <input type="text" name="date_end" id="date-end" class="form-control date-picker" {{ ($form_locked || $training_started) ? 'disabled' : '' }} value="{{ old('date_end', $detail->date_end) }}" data-date-format="yyyy-mm-dd" readonly="readonly" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Spaces available</label>
                                                        <div class="form-group-wrap">
                                                            <input type="number" class="form-control" name="stock" min="1" {{ $form_locked ? 'disabled' : '' }} value="{{ old('stock', $detail->stock) }}"  required>
                                                        </div>
                                                    </div>

                                                    <?php if(!$form_locked) : ?>
                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                    <?php endif; ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right side --}}
                                    <div class="col-lg-7">

                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <div class="nk-block nk-block-lg">
                                                    <div class="nk-block-head">
                                                        <div class="nk-block-between">
                                                            <div class="nk-block-head-content">
                                                                <h4 class="nk-block-title">Stock links</h4>
                                                            </div>

                                                            <?php if(!$form_locked) : ?>
                                                            <div class="nk-block-head-content">
                                                                <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add stock link</span></a>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>

                                                    </div>

                                                    <div class="alert alert-info">Any courses listed here will have the stock updated when <strong>{{ $detail->product_title }}</strong> is sold.</div>


                                                    <?php if(isset($detail->stockLinks) && $detail->stockLinks->count() ) : ?>
                                                        <div class="card card-bordered card-preview">

                                                            <table id="build-products" class="table table-orders">
                                                                <thead class="tb-odr-head">
                                                                <tr class="tb-odr-item">
                                                                    <th class="tb-odr-info">
                                                                        <span class="tb-odr-id">Course</span>
                                                                    </th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">Booked / Vacant</span>
                                                                    </th>
                                                                    <th class="tb-odr-action">&nbsp;</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="tb-odr-body">
                                                                    <?php foreach ($detail->stockLinks as $link) : ?>
                                                                        <?php
                                                                            $training_course = $link->trainingCourse;
                                                                            $spaces = training_spaces($training_course->id);
                                                                        ?>
                                                                        <tr>
                                                                            <td><a href="{{ url('training-dates/'.$training_course->id) }}">{{ $training_course->product_title }}</a></td>
                                                                            <td>{{ $spaces->paid.' / '.$spaces->remaining }}</td>
                                                                            <td>
                                                                                <div class="drodown">
                                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <?php if($training_course->status == 'upcoming') : ?>
                                                                                            <li><a class="destroy-btn" href="{{ url('delete-stock-link/'.$link->id) }}"><em class="icon ni ni-trash"></em><span>Delete Stock Link</span></a></li>
                                                                                            <?php endif; ?>
                                                                                            <li><a href="{{ url('training-dates/'.$training_course->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>

                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div><!-- .card-preview -->
                                                    <?php else : ?>

                                                        <div class="alert alert-warning">No stock links have been added.</div>

                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <div class="nk-block nk-block-lg">
                                                    <div class="nk-block-head">
                                                        <div class="nk-block-between">
                                                            <div class="nk-block-head-content">
                                                                <h4 class="nk-block-title">Attendees</h4>
                                                            </div>

                                                            <?php if(!$form_locked && $spaces->remaining) : ?>
                                                            <div class="nk-block-head-content">
                                                                <a href="{{ url('salesorders/create?product='.$detail->product_id) }}" target="_blank" class="btn btn-white btn-primary"><em class="icon ni ni-cart"></em><span>Sell Slot</span></a>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>

                                                    </div>

                                                    <?php if($paid_attendees->count() || $pending_attendees->count() || (isset($parent_pending_attendees) && $parent_pending_attendees->count()) || (isset($parent_paid_attendees) && $parent_paid_attendees->count()) ) : ?>
                                                        <ul class="nav nav-tabs">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-toggle="tab" href="#tabPaid">Paid</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#tabPending">Pending</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content">

                                                            <!-- Active training bookings  -->
                                                            <div class="tab-pane active" id="tabPaid">
                                                                <div class="card card-bordered card-preview">
                                                                    <?php if( $paid_attendees->count() || (isset($parent_paid_attendees) && $parent_paid_attendees->count()) ) : ?>
                                                                        <table class="table table-tranx">
                                                                            <thead>
                                                                            <tr class="tb-tnx-head">
                                                                                <th class="tb-tnx-id"><span class="">#</span></th>
                                                                                <th class="tb-tnx-info">
                                                                                <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                                                    <span>Billed to</span>
                                                                                </span>
                                                                                    <span class="tb-tnx-date d-md-inline-block d-none">
                                                                                    <span class="d-md-none">Attendee</span>
                                                                                    <span class="d-none d-md-block">
                                                                                        <span>Attendee</span>
                                                                                    </span>
                                                                                </span>
                                                                                </th>
                                                                                <th class="tb-tnx-amount is-alt">
                                                                                    <span class="tb-tnx-total">Total / paid</span>
                                                                                </th>
                                                                                <th class="tb-tnx-action">
                                                                                    <span>&nbsp;</span>
                                                                                </th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php foreach($paid_attendees as $orderItem) :  ?>
                                                                            <?php
                                                                            $salesOrder = $orderItem->salesOrder;

                                                                            if(isset($salesOrder->customer)) {
                                                                                $customer = $salesOrder->customer;
                                                                            }
                                                                            ?>
                                                                            <tr class="tb-tnx-item">
                                                                                <td class="tb-tnx-id">
                                                                                    <a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank"><span>#{{ $salesOrder->id }}</span></a>
                                                                                </td>
                                                                                <td class="tb-tnx-info">
                                                                                    <div class="tb-tnx-desc">
                                                                                        <span class="title">{!! isset($customer) ? '<a href="'.url('customers/'.$customer->id).'" target="_blank">'.$customer->getFullNameAttribute().'</a>' : 'NA' !!} </span>
                                                                                        <br><span class="badge badge-dot badge-success">{{ $orderItem->product_title }}</span>
                                                                                    </div>
                                                                                    <div class="tb-tnx-date">
                                                                                        <span class="date">{{ $orderItem->attendee }}</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="tb-tnx-amount is-alt">
                                                                                    <div class="tb-tnx-total">
                                                                                        <span class="amount">0.00</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="tb-tnx-action">
                                                                                    <div class="dropdown">
                                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                            <ul class="link-list-plain">
                                                                                                <ul class="link-list-plain">
                                                                                                    <li><a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank">View Order</a></li>
                                                                                                    {{--<li><a href="#">Edit</a></li>
                                                                                                    <li><a href="#">Remove</a></li>--}}
                                                                                                </ul>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endforeach; ?>

                                                                            <?php if(isset($parent_paid_attendees) && $parent_paid_attendees->count()) : ?>
                                                                                <?php foreach($parent_paid_attendees as $orderItem) :  ?>
                                                                                <?php
                                                                                $salesOrder = $orderItem->salesOrder;

                                                                                if(isset($salesOrder->customer)) {
                                                                                    $customer = $salesOrder->customer;
                                                                                }
                                                                                ?>
                                                                                <tr class="tb-tnx-item">
                                                                                    <td class="tb-tnx-id">
                                                                                        <a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank"><span>#{{ $salesOrder->id }}</span></a>
                                                                                    </td>
                                                                                    <td class="tb-tnx-info">
                                                                                        <div class="tb-tnx-desc">
                                                                                            <span class="title">{!! isset($customer) ? '<a href="'.url('customers/'.$customer->id).'" target="_blank">'.$customer->getFullNameAttribute().'</a>' : 'NA' !!} </span>
                                                                                            <br><span class="badge badge-dot badge-success">{{ $orderItem->product_title }}</span>
                                                                                        </div>
                                                                                        <div class="tb-tnx-date">
                                                                                            <span class="date">{{ $orderItem->attendee }}</span>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="tb-tnx-amount is-alt">
                                                                                        <div class="tb-tnx-total">
                                                                                            <span class="amount">0.00</span>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td class="tb-tnx-action">
                                                                                        <div class="dropdown">
                                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                                <ul class="link-list-plain">
                                                                                                    <ul class="link-list-plain">
                                                                                                        <li><a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank">View Order</a></li>
                                                                                                        {{--<li><a href="#">Edit</a></li>
                                                                                                        <li><a href="#">Remove</a></li>--}}
                                                                                                    </ul>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    <?php else : ?>
                                                                        <div class="alert alert-warning">There are no paid attendees</div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <!-- Pending training bookings -->
                                                            <div class="tab-pane" id="tabPending">
                                                                <div class="card card-bordered card-preview">
                                                                    <?php if($pending_attendees->count() || (isset($parent_pending_attendees) && $parent_pending_attendees->count())) : ?>
                                                                    <table class="table table-tranx">
                                                                        <thead>
                                                                        <tr class="tb-tnx-head">
                                                                            <th class="tb-tnx-id"><span class="">#</span></th>
                                                                            <th class="tb-tnx-info">
                                                                                <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                                                    <span>Billed to</span>
                                                                                </span>
                                                                                <span class="tb-tnx-date d-md-inline-block d-none">
                                                                                    <span class="d-md-none">Attendee</span>
                                                                                    <span class="d-none d-md-block">
                                                                                        <span>Attendee</span>
                                                                                    </span>
                                                                                </span>
                                                                            </th>
                                                                            <th class="tb-tnx-amount is-alt">
                                                                                <span class="tb-tnx-total">Total / paid</span>
                                                                            </th>
                                                                            <th class="tb-tnx-action">
                                                                                <span>&nbsp;</span>
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php foreach($pending_attendees as $orderItem) :  ?>
                                                                        <?php
                                                                            $salesOrder = $orderItem->salesOrder;

                                                                            if(isset($salesOrder->customer)) {
                                                                                $customer = $salesOrder->customer;
                                                                            }
                                                                        ?>
                                                                        <tr class="tb-tnx-item">
                                                                            <td class="tb-tnx-id">
                                                                                <a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank"><span>#{{ $salesOrder->id }}</span></a>
                                                                            </td>
                                                                            <td class="tb-tnx-info">
                                                                                <div class="tb-tnx-desc">
                                                                                    <span class="title">{!! isset($customer) ? '<a href="'.url('customers/'.$customer->id).'" target="_blank">'.$customer->getFullNameAttribute().'</a>' : 'NA' !!} </span>
                                                                                    <br><span class="badge badge-dot badge-warning">{{ $orderItem->product_title }}</span>
                                                                                </div>
                                                                                <div class="tb-tnx-date">
                                                                                    <span class="date">{{ $orderItem->attendee }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="tb-tnx-amount is-alt">
                                                                                <div class="tb-tnx-total">
                                                                                    <span class="amount">0.00</span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="tb-tnx-action">
                                                                                <div class="dropdown">
                                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                        <ul class="link-list-plain">
                                                                                            <li><a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank">View Order</a></li>
                                                                                            {{--<li><a href="#">Edit</a></li>
                                                                                            <li><a href="#">Remove</a></li>--}}
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php endforeach; ?>

                                                                        <?php if(isset($parent_pending_attendees) && $parent_pending_attendees->count()) : ?>
                                                                            <?php foreach($parent_pending_attendees as $orderItem) :  ?>
                                                                            <?php
                                                                                $salesOrder = $orderItem->salesOrder;

                                                                                if(isset($salesOrder->customer)) {
                                                                                    $customer = $salesOrder->customer;
                                                                                }
                                                                            ?>
                                                                            <tr class="tb-tnx-item">
                                                                                <td class="tb-tnx-id">
                                                                                    <a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank"><span>#{{ $salesOrder->id }}</span></a>
                                                                                </td>
                                                                                <td class="tb-tnx-info">
                                                                                    <div class="tb-tnx-desc">
                                                                                        <span class="title">{!! isset($customer) ? '<a href="'.url('customers/'.$customer->id).'" target="_blank">'.$customer->getFullNameAttribute().'</a>' : 'NA' !!} </span>
                                                                                        <br><span class="badge badge-dot badge-warning">{{ $orderItem->product_title }}</span>
                                                                                    </div>
                                                                                    <div class="tb-tnx-date">
                                                                                        <span class="date">{{ $orderItem->attendee }}</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="tb-tnx-amount is-alt">
                                                                                    <div class="tb-tnx-total">
                                                                                        <span class="amount">0.00</span>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="tb-tnx-action">
                                                                                    <div class="dropdown">
                                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                            <ul class="link-list-plain">
                                                                                                <li><a href="{{ url('salesorders/'.$salesOrder->id) }}" target="_blank">View Order</a></li>
                                                                                                {{--<li><a href="#">Edit</a></li>
                                                                                                <li><a href="#">Remove</a></li>--}}
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <?php else : ?>
                                                                        <div class="alert alert-warning">There are no pending attendees</div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div><!-- .card-preview -->
                                                        </div>


                                                    <?php else : ?>

                                                    <div class="alert alert-warning">There are no attendees</div>

                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>{{-- end right side --}}

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


<div class="modal fade" tabindex="-1" id="modalCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>Stock Link</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php if( isset($linkable) && $linkable->count() ) : ?>

                <div class="alert alert-info mb-3">
                    Stock links allow you to combine shorter length courses for correctly managing stock. When this course is booked, stock will also be deducted from the courses you select below
                </div>

                <form method="post" action="{{ url('training-dates/stock-link') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="training_date_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Course *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="updates_training_id_stock" id="updates-training-id-stock" data-search="on" required>
                                <option value="" selected="selected">Select a product</option>
                                <?php foreach($linkable as $course) : if($course->id == $detail->id || in_array($course->id,$ignore_links)) { continue; } ?>
                                <option value="{{ $course->id }}">{{ $course->product_title }} - {{ format_date($course->date_start) }} / {{ format_date($course->date_end) }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
                <?php else : ?>
                <div class="alert alert-warning">There are no courses that fall between {{ format_date($detail->date_start) }} / {{ format_date($detail->date_end) }}.</div>
                <?php endif; ?>
            </div>
            {{--<div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>--}}
        </div>
    </div>
</div>


{{ view('admin.templates.footer') }}
</body>


</html>
