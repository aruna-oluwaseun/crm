<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }

    $product_title = isset($detail->product) ? $detail->product->title : 'NA - potential error';

    $form_locked = false;
    if($detail->status == 'completed') {
        $form_locked = true;
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
                                            <h4 class="title nk-block-title">Production order #{{$detail->id}}</h4>
                                            <div class="nk-block-des">
                                                <p>
                                                    Production order created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
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
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-printer"></em><span>Print build sheet</span></a></li>
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
                                <div class="row g-gs">

                                    <?php if($detail->is_urgent && !$form_locked) : ?>
                                        <div class="col-md-12"><div class="alert alert-danger">This production order is <b>URGENT</b>, please prioritise it.</div></div>
                                    <?php endif; ?>

                                    <?php if($form_locked) : ?>
                                        <div class="col-md-12"><div class="alert alert-success">Production order completed on {{ long_date_time($detail->completed_at) }}.</div></div>
                                    <?php endif; ?>

                                    <div class="col-lg-6">
                                        <div class="card card-bordered h-100">
                                            <div class="card-inner">
                                                <div class="card-head">
                                                    <h5 class="card-title">Product being produced</h5>
                                                </div>
                                                <form method="post" action="{{ url('productionorders/'.$detail->id) }}" id="edit-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Product *</label>
                                                        <div class="form-control-wrap">
                                                            <select disabled="disabled" class="form-select" name="product_id" data-search="on" required>
                                                                <option value="{{$detail->product_id}}" selected>{{ $product_title }}</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="product_id" value="{{$detail->product_id}}">
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Qty required *</label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="number" {{ $form_locked ? 'disabled' : '' }} name="qty" value="{{ old('qty', $detail->qty) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Due date *</label>
                                                                <div class="form-control-wrap">
                                                                    <div class="form-icon form-icon-left">
                                                                        <em class="icon ni ni-calendar"></em>
                                                                    </div>
                                                                    <input type="text" class="form-control date-picker"  {{ $form_locked ? 'disabled' : '' }}  name="due_date" id="due-date" data-date-format="yyyy-mm-dd" value="{{ old('due_date', $detail->due_date) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <?php if($users = get_users()) : ?>
                                                        <label class="form-label" for="notes">Assign to </label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="assigned_to_id" id="assigned-to-id"  {{ ($form_locked && $detail->assigned_to_id) ? 'disabled' : '' }}  data-search="on">
                                                                <option value="" selected="selected">Select staff member</option>
                                                                <?php foreach($users as $user) : ?>
                                                                    <option value="{{ $user->id }}" {{ is_selected($user->id, old('assigned_to_id', $detail->assigned_to_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Batch Ref</label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="text" name="batch" value="{{ old('batch', $detail->batch) }}" id="batch">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Location</label>
                                                                <div class="form-control-wrap">
                                                                    <input class="form-control" type="text" name="location" value="{{ old('location', $detail->location) }}" id="location">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Notes</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control" name="notes" id="notes">{{ old('notes', $detail->notes) }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" name="is_urgent" id="is-urgent" {{ $form_locked ? 'disabled' : '' }} value="1" {{ is_checked('1', old('is_urgent', $detail->is_urgent)) }}>
                                                            <label class="custom-control-label" for="is-urgent">Is urgent</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <?php if($users = get_users()) : ?>
                                                        <label class="form-label" for="notes">Assembled by</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="assembled_by_id" id="assembled-by-id" {{ ($form_locked && $detail->assembled_by_id) ? 'disabled' : '' }} data-search="on">
                                                                <option value="" selected="selected">Select staff member</option>
                                                                <?php foreach($users as $user) : ?>
                                                                <option value="{{ $user->id }}" {{ is_selected($user->id, old('assembled_by_id', $detail->assembled_by_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="form-group">
                                                        <?php if($users = get_users()) : ?>
                                                        <label class="form-label" for="notes">Checked by</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="checked_by_id" id="checked-by-id" {{ ($form_locked && $detail->checked_by_id) ? 'disabled' : '' }} data-search="on">
                                                                <option value="" selected="selected">Select staff member</option>
                                                                <?php foreach($users as $user) : ?>
                                                                <option value="{{ $user->id }}" {{ is_selected($user->id, old('checked_by_id', $detail->checked_by_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Status</label>
                                                        <div class="g-4 align-center flex-wrap">
                                                            <?php if(!$form_locked) : ?>
                                                                <div class="g">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="status" id="status-pending" value="pending" {{ is_checked('pending', old('status', $detail->status)) }}>
                                                                        <label class="custom-control-label" for="status-pending">Pending</label>
                                                                    </div>
                                                                </div>
                                                                <div class="g">
                                                                    <div class="custom-control custom-radio">
                                                                        <input type="radio" class="custom-control-input" name="status" id="status-processing" value="processing" {{ is_checked('processing', old('status', $detail->status)) }}>
                                                                        <label class="custom-control-label" for="status-processing">Processing</label>
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="g">
                                                                    <div class="custom-control custom-radio ">
                                                                        <input type="radio" class="custom-control-input" name="status" id="status-complete" value="completed" {{ $form_locked ? 'readonly' : '' }} {{ is_checked('completed', old('status', $detail->status)) }}>
                                                                        <label class="custom-control-label" for="status-complete">Complete</label>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div id="status-warning" style="display: none;" class="alert alert-info"><b><em class="icon ni ni-alert"></em> Warning :</b> once you complete this production order you will not be able to make changes to it. The build stock will also be updated.</div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <div class="nk-block nk-block-lg">
                                                    <div class="nk-block-head">
                                                        <div class="nk-block-between">
                                                            <div class="nk-block-head-content">
                                                                <h4 class="nk-block-title">Build Products</h4>
                                                                <p>The following products will be used to create <strong class="text-primary">{{ $detail->qty }} x {{ $product_title }}</strong>.</p>
                                                            </div>

                                                            <?php if(!$form_locked) : ?>
                                                            <div class="nk-block-head-content">
                                                                <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Product</span></a>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>

                                                    </div>
                                                    <div class="card card-bordered card-preview">
                                                        <table id="build-products" class="table table-orders">
                                                            <thead class="tb-odr-head">
                                                            <tr class="tb-odr-item">
                                                                <th class="tb-odr-info">
                                                                    <span class="tb-odr-id">Product</span>
                                                                </th>
                                                                <th class="tb-odr-amount">
                                                                    <span class="tb-odr-total">Required</span>
                                                                    <span class="tb-odr-status d-none d-md-inline-block">Available</span>
                                                                </th>
                                                                <th class="tb-odr-action">&nbsp;</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="tb-odr-body">
                                                                <?php if(isset($detail->buildProducts) && $detail->buildProducts->count()) : ?>
                                                                    <?php foreach($detail->buildProducts as $item) : $uom = uom($item->unit_of_measure_id); ?>
                                                                        <?php
                                                                            $stock = stock_level($item->product_id);
                                                                            $stock_level = $uom->id ? $stock->actual_unit_stock : $stock->actual;

                                                                        ?>
                                                                        <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                                            <td class="tb-odr-info">
                                                                                <span class="tb-odr-id"><a href="{{ url('products/'.$item->product_id) }}" target="_blank">{{ $item->product_title }}</a></span>
                                                                            </td>
                                                                            <td class="tb-odr-amount">
                                                                                <span class="tb-odr-total">
                                                                                    <span class="amount">{{ $uom->id ? number_format($item->qty).' '.$uom->title : ' x '.number_format($item->qty) }}</span>
                                                                                </span>
                                                                                <span class="tb-odr-status">
                                                                                    <span class="text-success">{{ $stock_level }}</span>
                                                                                </span>
                                                                            </td>
                                                                            <td class="tb-odr-action">
                                                                                <?php if(!$form_locked) : ?>
                                                                                <div class="tb-odr-btns d-none d-md-inline">
                                                                                    <a href="{{ url('destroy-build-item/'.$item->id) }}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                                                                                </div>
                                                                                <?php endif; ?>
                                                                                <div class="dropdown">
                                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                        <ul class="link-list-plain">
                                                                                            <li><a href="{{ url('products/'.$item->product_id) }}" target="_blank" class="text-primary">View</a></li>
                                                                                            <?php if(!$form_locked) : ?>
                                                                                            <li><a href="{{ url('destroy-build-item/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                            <?php endif; ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                <?php else : ?>
                                                                    <div class="alert alert-warning">There are no build products for {{ $product_title }}, you should add some, in the product detail.</div>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div><!-- .card-preview -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <div class="card-head">
                                                    <h4 class="nk-block-title">Process production order</h4>
                                                    <p>Here you can specify what was actually used in this production order.</p>
                                                </div>

                                                <form action="{{ url('process-production-order/'.$detail->id) }}" method="post">
                                                    @csrf
                                                    <h6>We produced : </h6>
                                                    <div class="form-group">
                                                        <label class="form-label">{{ $product_title }}</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-text-hint">
                                                                <span class="overline-title">Qty</span>
                                                            </div>
                                                            <input class="form-control" type="text" name="production_order[qty]" {{ $form_locked ? 'disabled' : '' }} value="{{ $detail->qty }}">
                                                        </div>
                                                    </div>

                                                    <h6>We used : </h6>
                                                    <div class="card card-bordered card-preview">
                                                        <table id="build-products" class="table table-orders">
                                                            <thead class="tb-odr-head">
                                                            <tr class="tb-odr-item">
                                                                <th class="tb-odr-info">
                                                                    <span class="tb-odr-id">Product</span>
                                                                </th>
                                                                <th class="tb-odr-amount">
                                                                    <span class="tb-odr-total">We Used</span>
                                                                </th>
                                                                <th class="tb-odr-action">&nbsp;</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="tb-odr-body">
                                                            <?php if(isset($detail->buildProducts) && $detail->buildProducts->count()) : ?>
                                                                <?php foreach($detail->buildProducts as $item) : $uom = uom($item->unit_of_measure_id); ?>
                                                                <?php
                                                                    $qty = $form_locked ? $item->qty_used : $item->qty;
                                                                    $stock = stock_level($item->product_id);
                                                                ?>
                                                                <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                                    <td class="tb-odr-info">
                                                                        <span class="tb-odr-id"><a href="{{ url('products/'.$item->product_id) }}" target="_blank">{{ $item->product_title }}</a></span>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                        <span class="tb-odr-total">
                                                                            <div class="form-group">
                                                                                <div class="form-control-wrap">
                                                                                    <div class="form-text-hint">
                                                                                        <span class="overline-title">{{ $uom->id ? $uom->title : 'Qty' }}</span>
                                                                                    </div>
                                                                                    <input type="number" name="build_product[{{$item->id}}][qty_used]" class="form-control" {{ $form_locked ? 'disabled' : '' }} value="{{ number_format($qty) }}" step="0.0001">
                                                                                </div>
                                                                            </div>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <div class="alert alert-warning">There are no build products for {{ $product_title }}, you should add some, in the product detail.</div>
                                                            <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div><!-- .card-preview -->

                                                    <?php if(!$form_locked) : ?>
                                                        <button type="submit" class="btn btn-lg btn-primary mt-4">Save and complete</button>
                                                    <?php endif; ?>
                                                </form>


                                            </div>
                                        </div>
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

<div class="modal fade" tabindex="-1" id="modalCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>build product</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php if( isset($products) && $products->count() ) : ?>
                <form method="post" action="{{ url('store-build-item') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="production_order_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Product *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="product_id" id="add-product-id" data-search="on" required>
                                <option value="" selected="selected">Select a product</option>
                                <?php foreach($products as $product) : ?>
                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Qty *</label>
                                <input class="form-control" type="number" name="qty" id="add-qty" min="0" value="0" step="0.0001">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Unit *</label>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="unit_of_measure_id" id="add-unit-of-measure-id" required>
                                        <option value="unit">Unit</option>
                                        <?php if($uoms = uoms()) : ?>
                                            <?php foreach($uoms as $uom) : ?>
                                                <option value="{{ $uom->id }}">{{ $uom->title }}</option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
                <?php else : ?>
                <div class="alert alert-warning">You can't add a product to a production order without any products, please <a href="{{ url('products/create') }}">create a product</a></div>
                <?php endif; ?>
            </div>
            {{--<div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>--}}
        </div>
    </div>
</div>

<table style="display: none" id="build-product-prototype">
    <tbody>
        <tr class="tb-odr-item remove-target">
            <td class="tb-odr-info">
                <span class="tb-odr-id"><a href="{{ url('products') }}/{PRODUCT_ID}" target="_blank">{PRODUCT_TITLE}</a></span>
            </td>
            <td class="tb-odr-amount">
                <span class="tb-odr-total">
                    <span class="amount">{QTY}</span>
                </span>
                <span class="tb-odr-status">
                    <span class="text-success">NA</span>
                </span>
            </td>
            <td class="tb-odr-action">
                <div class="tb-odr-btns d-none d-md-inline">
                    <a href="{{ url('destroy-build-item') }}/{ID}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                </div>
                <div class="dropdown">
                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                        <ul class="link-list-plain">
                            <li><a href="{{ url('products') }}/{PRODUCT_ID}" target="_blank" class="text-primary">View</a></li>
                            <li><a href="{{ url('destroy-build-item') }}/{ID}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>

</table>

{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript" src="{{ asset('assets/js/admin/production-order.js') }}"></script>

</html>
