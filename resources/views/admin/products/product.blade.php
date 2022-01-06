<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
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
                                            <h4 class="nk-block-title">Product Detail</h4>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        {{--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>--}}
                                                        <li><a href="{{ url('products/create') }}" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Product</span></a></li>

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
                            </div>

                            <div class="nk-block">
                                <div class="card card-bordered">
                                    <div class="card-aside-wrap">
                                        <div class="card-inner card-inner-lg">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">{{ $detail->title }}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                Product created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
                                                                <?php if($detail->updated_by) : ?>
                                                                    Last updated by <a href="{{ url('users/'.$updated_by->id) }}">{{ $updated_by->getFullNameAttribute() }}</a> on <b>{{ date('dS F Y H:ia', strtotime($detail->updated_at)) }}</b>.
                                                                <?php endif; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Details -->
                                            <div id="tab-details" class="tab card">

                                                <form method="post" action="{{ url('products/'.$detail->id) }}" id="product-detail-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Product *</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="title" value="{{ old('title', $detail->title) }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">URL Slug <span>Url friendly title</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input readonly type="text" class="form-control" name="slug" value="{{ old('slug', $detail->slug) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Product Code</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="code" value="{{ old('slug', $detail->code) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Description</label>
                                                        <div class="form-group-wrap">
                                                            <textarea name="description" class="summernote-minimal form-control">
                                                                {{ old('description', $detail->description) }}
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                   <div class="form-group">
                                                        <label class="form-label">Short description </label>
                                                        <div class="form-group-wrap">
                                                            <input type="text" name="short_description" class="form-control" maxlength="200" value="{{ old('short_description', $detail->short_description) }}">
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info">The short description will be used in SEO lookups. Keep it short and sweet.</div>

                                                    <div class="form-group">
                                                        <label class="form-label">Keywords </label>
                                                        <div class="form-group-wrap">
                                                            <input type="text" name="keywords" class="form-control" value="{{ old('keywords', $detail->keywords) }}">
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info">Keywords will be used in SEO lookups. Don't overkill it.</div>

                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Product type</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="product_type_id" class="form-control form-select" data-search="on">
                                                                        <option value="0">Select product type</option>
                                                                        <?php if($product_types = product_types()) : ?>
                                                                            <?php foreach($product_types as $type) : ?>
                                                                                <option value="{{ $type->id }}" data-product-type-status="{{ $type->status }}" {{ is_selected($type->id, old('product_type_id', $detail->product_type_id)) }}>{{ $type->title }} {{ $type->status != 'active' ? '(Category '.ucfirst($type->status).')' : '' }}</option>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Availability lead time</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="lead_time_id" class="form-control form-select" data-search="on">
                                                                        <option value="0">Select lead time</option>
                                                                        <?php if($lead_times = lead_times()) : ?>
                                                                            <?php foreach($lead_times as $time) : ?>
                                                                                <option value="{{ $time->id }}" {{ is_selected($time->id, old('lead_time_id', $detail->lead_time_id)) }}>{{ $time->title }}</option>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">

                                                        <!-- Costings -->
                                                        <div class="col-md-6">
                                                            <div class="card card-preview mb-3">
                                                                <div class="card-inner">
                                                                    <h4>Costings</h4>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Vat Type</label>
                                                                        <div class="form-control-wrap">
                                                                            <select id="vat-type-id" name="vat_type_id" class="form-select form-control" data-search="on">
                                                                                <?php if($vat_types = vat_types()) : ?>
                                                                                <?php foreach($vat_types as $vats) : ?>
                                                                                <option value="{{ $vats->id }}" data-value="{{ $vats->value }}" {{ is_selected($vats->id, old('vat_type_id', $detail->vat_type_id)) }}>{{ $vats->title }}</option>
                                                                                <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Net cost</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="net-cost" type="text" name="net_cost" class="form-control" value="{{ old('net_cost', $detail->net_cost) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">VAT</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="vat-cost" readonly type="text" name="vat_cost" class="form-control" value="{{ old('vat_cost', $detail->vat_cost) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Gross</label>
                                                                        <div class="form-control-wrap">
                                                                            <input id="gross-cost" readonly type="text" name="gross_cost" class="form-control form-control-lg" value="{{ old('gross_cost', $detail->gross_cost) }}">
                                                                        </div>
                                                                    </div>

                                                                    <hr>

                                                                    <div class="row mb-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Sale Cost</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="sale-net-cost" type="text" name="sale_net_cost" class="form-control" value="{{ old('sale_net_cost', $detail->sale_net_cost) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Sale VAT</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="sale-vat-cost" readonly type="text" name="sale_vat_cost" class="form-control" value="{{ old('sale_vat_cost', $detail->sale_vat_cost) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Gross</label>
                                                                        <div class="form-control-wrap">
                                                                            <input id="sale-gross-cost" readonly type="text" name="sale_gross_cost" class="form-control form-control-lg" value="{{ old('sale_gross_cost', $detail->sale_gross_cost) }}">
                                                                        </div>
                                                                    </div>

                                                                    <p id="sale-saving" style="display: {{ $detail->sale_net_cost ? 'block' : 'none' }}">Saving of <mark>0%</mark></p>

                                                                    <hr>

                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input name="deposit_allowed" type="checkbox" class="custom-control-input" id="deposit-allowed" value="1">
                                                                            <label class="custom-control-label" for="deposit-allowed"> Allow deposit on this product?</label>
                                                                        </div>
                                                                    </div>

                                                                    <div id="deposit-costings" style="display: {{ $detail->deposit_allowed ? 'block' : 'none' }}">
                                                                        <div class="row mb-4">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Net deposit amount</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input id="deposit-net-cost" type="text" name="deposit_net_cost" class="form-control" value="{{ old('deposit_net_cost', $detail->deposit_net_cost) }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Deposit VAT</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input id="deposit-vat-cost" readonly type="text" name="deposit_vat_cost" class="form-control" value="{{ old('deposit_vat_cost', $detail->deposit_vat_cost) }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="form-label">Deposit Gross</label>
                                                                            <div class="form-control-wrap">
                                                                                <input id="deposit-gross-cost" readonly type="text" name="deposit_gross_cost" class="form-control form-control-lg" value="{{ old('deposit_gross_cost', $detail->deposit_gross_cost) }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Options -->
                                                        <div class="col-md-6">

                                                            <div class="alert alert-info">
                                                                <b>Please note :</b> Once unit level (i.e 10grams was added via purchase order etc) stock has been added to this product the unit of measure field can't be adjusted
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Product Weight</label>
                                                                        <div class="form-control-wrap">
                                                                            <input name="weight" type="number" class="form-control" id="weight" value="{{ old('weight', $detail->weight ? number_format($detail->weight) : '') }}" step="0.0001">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php
                                                                    $allow_unit_change = true;
                                                                    if(isset($detail->stock) && $detail->stock->count())
                                                                    {
                                                                        // Only allow the unit level to be changed if not unit stock has been adjusted
                                                                        if($detail->stock->unit_level_stock) {
                                                                            $allow_unit_change = false;
                                                                        }
                                                                    }
                                                                ?>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Unit</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select" {{ $allow_unit_change ? '' : 'disabled' }} name="unit_of_measure_id" id="unit-of-measure-id" >
                                                                                <option value="unit">Unit</option>
                                                                                <?php if($uoms = uoms()) : ?>
                                                                                <?php foreach($uoms as $uom) : ?>
                                                                                <option value="{{ $uom->id }}" {{ is_selected($uom->id, old('unit_of_measure_id', $detail->unit_of_measure_id)) }}>{{ $uom->title }}</option>
                                                                                <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <?php
                                                                $is_packaging = false;
                                                                if($detail->is_shipping_box || $detail->is_shipping_pallet) {
                                                                    $is_packaging = true;
                                                                }
                                                            ?>
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input name="is_packaging" type="checkbox" class="custom-control-input" id="is-packaging" value="1" {{ is_checked(1, old('is_packaging', $is_packaging)) }}>
                                                                    <label class="custom-control-label" for="is-packaging"> Is this item a Box or Pallet for shipping?</label>
                                                                </div>
                                                            </div>
                                                            <div id="packaging-options" style="display: {{ $is_packaging ? 'block' : 'none' }}; padding-left: 15px;">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_shipping_box" type="checkbox" class="custom-control-input" id="is-shipping-box" value="1" {{ is_checked(1, old('is_shipping_box', isset($detail->is_shipping_box) ? $detail->is_shipping_box : null)) }}>
                                                                        <label class="custom-control-label" for="is-shipping-box"> It's a Box</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_shipping_pallet" type="checkbox" class="custom-control-input" id="is-shipping-pallet" value="1" {{ is_checked(1, old('is_shipping_pallet', isset($detail->is_shipping_pallet) ? $detail->is_shipping_pallet : null)) }}>
                                                                        <label class="custom-control-label" for="is-shipping-pallet"> It's a Pallet</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="product-options" style="display: {{ $is_packaging ? 'none' : 'block' }}">
                                                                <div class="form-group">
                                                                    <label class="form-label">Assembly time in minutes</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="form-text-hint" style="right: 20px">
                                                                            <span class="overline-title">Mins</span>
                                                                        </div>
                                                                        <input type="number" name="assembly_minutes" id="assembly-minutes" class="form-control" value="{{ old('assembly_minutes', $detail->assembly_minutes) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="form-label">Commodity Code</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" name="commodity_code" id="commodity-code" class="form-control" value="{{ old('commodity_code', $detail->commodity_code) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="builds_by_unit" type="checkbox" class="custom-control-input" id="builds-by-unit" value="1" {{ is_checked(1, old('builds_by_unit', isset($detail->builds_by_unit) ? $detail->builds_by_unit : null)) }}>
                                                                        <label class="custom-control-label" for="builds-by-unit"> Is this product built or added to buildable products per unit rather than it's weight?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_manufactured" type="checkbox" class="custom-control-input" id="is-manufactured" value="1" {{ is_checked(1, old('is_manufactured', $detail->is_manufactured)) }}>
                                                                        <label class="custom-control-label" for="is-manufactured"> Is this product manufactured? <em data-toggle="tooltip" data-placement="top" title="Will be available to select in production orders" class="icon ni ni-info"></em></label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_discountable" type="checkbox" class="custom-control-input" id="is-discountable" value="1" {{ is_checked(1, old('is_discountable', $detail->is_discountable)) }}>
                                                                        <label class="custom-control-label" for="is-discountable"> Is this product discountable? <em data-toggle="tooltip" data-placement="top" title="Allows the product to be discounted with any discount codes, installer discounts etc" class="icon ni ni-info"></em></label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_training" type="checkbox" class="custom-control-input" id="is-training" value="1" {{ is_checked(1, old('is_training', $detail->is_training)) }}>
                                                                        <label class="custom-control-label" for="is-training"> Training product?</label>
                                                                    </div>
                                                                </div>

                                                                <div id="assessment-product" class="form-group" style="display: {{ $detail->is_assessment ? 'block' : 'none' }}; padding-left: 15px">
                                                                    <hr>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_assessment" type="checkbox" class="custom-control-input" id="is-assessment" value="1" {{ is_checked(1, old('is_assessment', $detail->is_assessment)) }}>
                                                                        <label class="custom-control-label" for="is-assessment"> Do this training product require customers to have an assessment?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-control custom-checkbox">
                                                                        <input name="is_free_shipping" type="checkbox" class="custom-control-input" id="free-shipping" value="1" {{ is_checked(1, old('is_free_shipping', $detail->is_free_shipping)) }}>
                                                                        <label class="custom-control-label" for="free-shipping"> Free ship item?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-control custom-switch">
                                                                        <input name="is_available_online" type="checkbox" class="custom-control-input" id="available-online" value="1" {{ is_checked(1, old('is_available_online', $detail->is_available_online)) }}>
                                                                        <label class="custom-control-label" for="available-online"> Available to purchase online?</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>


                                                    </div>



                                                    <div class="form-group">
                                                        <label class="form-label">Status * </label>
                                                        <div class="g-4 align-center flex-wrap">
                                                            <div class="g">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" name="status" id="status-active" value="active" {{ is_checked('active', old('status', $detail->status)) }}>
                                                                    <label class="custom-control-label" for="status-active">Active</label>
                                                                </div>
                                                            </div>
                                                            <div class="g">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" name="status" id="status-disabled" value="disabled" {{ is_checked('disabled', old('status', $detail->status)) }}>
                                                                    <label class="custom-control-label" for="status-disabled">Disabled</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="status-warning" style="display: none;" class="alert alert-info"><b><em class="icon ni ni-alert"></em> Warning :</b> no reason for this warning have a nice day.</div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>


                                            </div><!-- end Details -->

                                            <!-- Build products -->
                                            <div id="tab-categories" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>Link this product to categories for displaying on your online store.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#link-category" class="btn btn-primary" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Category</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->categories) && $detail->categories->count()) : ?>

                                                <?php
                                                    $categories = $detail->categories()->paginate(30)->fragment('tab-categories');;
                                                ?>
                                                <div class="card card-bordered card-preview">
                                                    <table id="build-products" class="table table-orders">
                                                        <thead class="tb-odr-head">
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>Store link</th>
                                                            <th class="tb-odr-action"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tb-odr-body">

                                                        <?php foreach($categories as $item) : ?>
                                                        <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                            <td><a href="{{ url('categories/'.$item->id) }}" target="_blank">{{ $item->title }}</a></td>
                                                            <td>#</td>
                                                            <td class="tb-odr-action">
                                                                <div class="dropdown">
                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                        <ul class="link-list-plain">
                                                                            <li><a href="{{ url('categories/'.$item->id) }}" target="_blank" class="text-primary">View Category</a></li>
                                                                            <li><a href="{{ url('products/unlink-category/'.$detail->id.'/'.$item->id) }}#tab-categories" class="text-danger destroy-btn">Delete Link</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="mt-3">{{ $categories->links() }}</div>
                                                <?php else : ?>

                                                <div class="alert alert-warning">You have not linked any categories.</div>

                                                <?php endif; ?>

                                            </div>

                                            <!-- Attributes -->
                                            <div id="tab-attributes" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>Add product attributes, these will be available ot select online in the shop.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#modalAttribute" data-product-id="{{ $detail->id }}" class="btn btn-primary" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Attribute</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->attributes) && $detail->attributes->count()) : ?>

                                                    <div class="card card-bordered card-preview">
                                                        <table class="table table-tranx">
                                                            <thead>
                                                            <tr class="tb-tnx-head">
                                                                <th class="tb-tnx-id"><span class="">Attribute</span></th>
                                                                <th class="tb-tnx-info">
                                                                    <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                                        <span>Title</span>
                                                                    </span>
                                                                    <span class="tb-tnx-date d-md-inline-block d-none">
                                                                        <span class="d-md-none">Date</span>
                                                                        <span class="d-none d-md-block">
                                                                            <span>Added</span>
                                                                            <span>Exclude Associated Attributes</span>
                                                                        </span>
                                                                    </span>
                                                                </th>
                                                                <th class="tb-tnx-amount is-alt">
                                                                    <span class="tb-tnx-total">Adjustment</span>
                                                                </th>
                                                                <th class="tb-tnx-action">
                                                                    <span>&nbsp;</span>
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php foreach($detail->attributes as $attribute) : ?>
                                                            <?php

                                                                $excludes = [];
                                                                if($attribute->pivot->exclude_value_attributes) {
                                                                    $excludes = get_excluded_attributes($attribute->pivot->exclude_value_attributes, $attribute->pivot->value_id);
                                                                }

                                                                $attribute_product = get_product($attribute->pivot->value_id);

                                                            ?>

                                                            <tr class="tb-tnx-item">
                                                                <td class="tb-tnx-id">
                                                                    <a href="{{ url('attributes/' . $attribute->id) }}" target="_blank"><span>{{ $attribute->title }}</span></a>
                                                                </td>
                                                                <td class="tb-tnx-info">
                                                                    <div class="tb-tnx-desc">
                                                                        <span class="title"><a href="{{ url('products/'.$attribute->pivot->value_id.'#tab-attributes') }}" target="_blank">{{ $attribute->pivot->attribute_title ?: $attribute_product->title }}</a></span>
                                                                    </div>
                                                                    <div class="tb-tnx-date">
                                                                        <span class="date">{{ format_date($attribute->pivot->created_at) }}</span>
                                                                        <span class="date">
                                                                            <?php if(isset($excludes) && !empty($excludes)) : ?>
                                                                                {{ @implode(', ',$excludes) }}
                                                                            <?php else : ?>
                                                                                None
                                                                            <?php endif; ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="tb-tnx-amount is-alt">
                                                                    <div class="tb-tnx-total">
                                                                        <span class="amount">
                                                                            <?php if($attribute->pivot->net_adjustment) : ?>
                                                                                <?php if($attribute->pivot->net_adjustment > 0.00) : ?>
                                                                                    <span class="text-success">+ &pound;{{ number_format($attribute->pivot->net_adjustment, 2,'.',',') }}</span>
                                                                                <?php else : ?>
                                                                                    - &pound;{{ number_format($attribute->pivot->net_adjustment, 2,'.',',') }}
                                                                                <?php endif; ?>
                                                                            <?php else : ?>
                                                                                No adjustment
                                                                            <?php endif; ?>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td class="tb-tnx-action">
                                                                    <div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                <li><a href="{{ url('products/'.$attribute->pivot->value_id.'#tab-attributes') }}">View</a></li>
                                                                                {{--<li><a href="#">Edit</a></li>--}}
                                                                                <li><a href="{{ url('attributes/unlink-product/'.$detail->id.'/'.$attribute->pivot->id) }}" class="destroy-btn">Remove</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <?php endforeach; ?>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                {{--<div class="mt-3">{{ $categories->links() }}</div>--}}
                                                <?php else : ?>

                                                <div class="alert alert-warning">You have not linked any attributes.</div>

                                                <?php endif; ?>

                                            </div>

                                            <!-- Media -->
                                            <div id="tab-media" style="display:none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>Upload photos for this product.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#file-upload" class="btn btn-primary" data-toggle="modal"><em class="icon ni ni-upload-cloud"></em> <span>Upload</span></a>
                                                        </div>
                                                    </div>
                                                </div>


                                                <?php if(isset($detail->media) && $detail->media->count()) : ?>
                                                    <div class="nk-files-list" style="display: flex; flex-wrap: wrap;">
                                                        <?php foreach($detail->media as $upload) : ?>
                                                            <?php

                                                                $upload->public = false;
                                                                if(isset($upload->custom_properties['custom_headers']['ACL']))
                                                                {
                                                                    $upload->public = true;
                                                                }
                                                                //$upload->default_photo = $upload->pivot->default_photo;

                                                                if($upload->size)
                                                                {
                                                                    $upload->size_kb = $upload->size * 0.001; // Kilobytes
                                                                    $upload->size_mb = $upload->size_kb * 0.001; // Megabytes
                                                                }
                                                            ?>
                                                            <div class="nk-file-item nk-file">
                                                                <div class="nk-file-info">
                                                                    <div class="nk-file-title">
                                                                        <div class="nk-file-icon">
                                                                            <span class="nk-file-icon-type">
                                                                                <?php if(isset($upload->public) && $upload->public) : ?>
                                                                                <a data-fancybox="photo" href="{{ $upload->getUrl() }}" title="{{ $upload->name ?: $upload->file_name }}">
                                                                                    <img src="{{ $upload->getUrl('thumb') }}" title="">
                                                                                </a>
                                                                                <?php else : ?>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72">
                                                                                    <path d="M50,61H22a6,6,0,0,1-6-6V22l9-11H50a6,6,0,0,1,6,6V55A6,6,0,0,1,50,61Z" style="fill:#755de0"></path>
                                                                                    <path d="M27.2223,43H44.7086s2.325-.2815.7357-1.897l-5.6034-5.4985s-1.5115-1.7913-3.3357.7933L33.56,40.4707a.6887.6887,0,0,1-1.0186.0486l-1.9-1.6393s-1.3291-1.5866-2.4758,0c-.6561.9079-2.0261,2.8489-2.0261,2.8489S25.4268,43,27.2223,43Z" style="fill:#fff"></path>
                                                                                    <path d="M25,20.556A1.444,1.444,0,0,1,23.556,22H16l9-11h0Z" style="fill:#b5b3ff"></path>
                                                                                </svg>
                                                                                <?php endif; ?>
                                                                            </span>
                                                                        </div>
                                                                        <div class="nk-file-name">
                                                                            <div class="nk-file-name-text">
                                                                                {{ $upload->name ?: $upload->file_name }}
                                                                                {{--<div class="asterisk">
                                                                                    <a href="#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                                                </div>--}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <ul class="nk-file-desc">
                                                                        <li class="date">Created {{ relative_time($upload->created_at) }} ago</li>
                                                                        <?php if(isset($upload->size) && $upload->size > 0) : ?>
                                                                        <li class="size">{{ number_format($upload->size_mb, 2,'.',',') }} Mb</li>
                                                                        <?php endif; ?>
                                                                        <?php if(isset($upload->public) && $upload->public) : ?>
                                                                            <li class="members"><em class="icon ni ni-unlock"></em> Public</li>
                                                                        <?php else : ?>
                                                                            <li class="members"><em class="icon ni ni-lock-alt"></em> Private</li>
                                                                        <?php endif; ?>
                                                                    </ul>
                                                                </div>
                                                                <div class="nk-file-actions">
                                                                    <div class="dropdown">
                                                                        <a href="" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right" style="">
                                                                            <ul class="link-list-plain no-bdr">
                                                                                <li><a data-fancybox="gallery" href="{{ $upload->getUrl() }}"><em class="icon ni ni-eye"></em><span>Details</span></a></li>
                                                                                <li><a href="#" class="file-dl-toast"><em class="icon ni ni-download"></em><span>Download</span></a></li>
                                                                                {{--<li><a href="#"><em class="icon ni ni-pen"></em><span>Rename</span></a></li>--}}
                                                                                <li><a href="{{ url('upload/delete/'.$upload->id) }}" class="destroy-btn"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!-- .nk-file -->


                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else : ?>

                                                <div class="alert alert-warning">You have not uploaded any media yet</div>

                                                <?php endif; ?>

                                            </div><!-- end Media -->

                                            <!-- Build products -->
                                            <div id="tab-build-products" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>The following products will be used to create <strong class="text-primary">{{ $detail->title }}</strong>.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Product</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->children) && $detail->children->count()) : ?>

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
                                                        <?php foreach($detail->children as $item) : $uom = uom($item->unit_of_measure_id); ?>
                                                        <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                            <td class="tb-odr-info">
                                                                <span class="tb-odr-id"><a href="{{ url('products/'.$item->product_id) }}" target="_blank">{{ $item->product_title }}</a></span>
                                                            </td>
                                                            <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ $uom->id ? number_format($item->weight).' '.$uom->title : ' x '.$item->qty }}</span>
                                                                </span>
                                                                <span class="tb-odr-status">
                                                                    <span class="text-success">NA</span>
                                                                </span>
                                                            </td>
                                                            <td class="tb-odr-action">
                                                                <div class="tb-odr-btns d-none d-md-inline">
                                                                    <a href="{{ url('destroy-product-child/'.$item->id) }}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                                                                </div>
                                                                <div class="dropdown">
                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                        <ul class="link-list-plain">
                                                                            <li><a href="{{ url('products/'.$item->product_id) }}" target="_blank" class="text-primary">View</a></li>
                                                                            <li><a href="{{ url('destroy-product-child/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else : ?>

                                                <div class="alert alert-warning">You have not added any build products.</div>

                                                <?php endif; ?>

                                            </div><!-- end Build Products -->

                                            <!-- Suppliers -->
                                            <div id="tab-suppliers" style="display:none;" class="tab card">


                                                <div class="card card-bordered mb-3">
                                                    <div class="card-inner">
                                                        <?php if(isset($suppliers) && $suppliers->count() ) : ?>
                                                        <form method="post" action="{{ url('store-supplier-ref#tab-suppliers') }}" class="form-validate">
                                                            @csrf
                                                            <input type="hidden" name="product_id" value="{{ $detail->id }}">
                                                            <h4>Add supplier</h4>

                                                            <div class="form-group">
                                                                <label class="form-label">Supplier</label>
                                                                <div class="form-control-wrap">
                                                                    <select id="supplier-id" name="supplier_id" class="form-select form-control" data-search="on" required>
                                                                        <?php foreach($suppliers as $supplier) : ?>
                                                                            <option value="{{ $supplier->id }}" {{ is_selected($supplier->id, old('supplier_id')) }}>{{ $supplier->title }}</option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Cost to us</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" name="cost_to_us" class="form-control" value="{{ old('cost_to_us') }}" required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Supplier Ref</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                            </div>

                                                        </form>
                                                        <?php else : ?>
                                                            <div class="alert alert-warning">You cannot link this product to a supplier as you have not added any suppliers, please <a href="{{ url('suppliers') }}">add one now</a>.</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->suppliers) && $detail->suppliers->count()) : ?>

                                                <div class="card card-bordered card-preview">
                                                    <table id="suppliers" class="table table-suppliers">
                                                        <thead class="tb-odr-head">
                                                        <tr class="tb-odr-item">
                                                            <th class="tb-odr-info">
                                                                <span class="tb-odr-id">Supplier</span>
                                                            </th>
                                                            <th class="tb-odr-amount">
                                                                <span class="tb-odr-total">Cost</span>
                                                                <span class="d-none d-md-inline-block">Ref</span>
                                                            </th>
                                                            <th class="tb-odr-action">&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tb-odr-body">
                                                        <?php foreach($detail->suppliers as $item) : ?>
                                                        <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                            <td class="tb-odr-info">
                                                                <span class="tb-odr-id"><a href="{{ url('suppliers/'.$item->supplier_id) }}" target="_blank">{{ isset($item->supplier) ? $item->supplier->title : 'NA' }}</a></span>
                                                            </td>
                                                            <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">&pound;{{ number_format($item->cost_to_us,5,'.',',') }}</span>
                                                                </span>
                                                                <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $item->code }}</span>
                                                                </span>
                                                            </td>
                                                            <td class="tb-odr-action">
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
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <?php else : ?>

                                                <div class="alert alert-warning">No suppliers are linked to this product</div>

                                                <?php endif; ?>

                                            </div><!-- end Media -->

                                            <!-- STOCK -->
                                            <div id="tab-stock" style="display: none" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>View stock adjustments for this product.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalStockCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Stock</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->stock) && $detail->stock->count()) : ?>

                                                    <div class="card card-bordered">
                                                        <div class="card-inner">
                                                            <div class="justify-content-between">
                                                                <div class="mb-3"><h5>Qty instock : <span class="text-primary">{{ $detail->stock->stock }}</span></h5></div>
                                                                <?php if($detail->unit_of_measure_id) : ?>
                                                                <div>
                                                                    <h5>
                                                                        Unit Stock : <span class="text-primary">{{ $detail->stock->unit_level_stock ?: 'NA' }}</span>
                                                                        <?php if($uom = uom($detail->unit_of_measure_id)) : ?>
                                                                            {{ $uom->title }}
                                                                        <?php endif; ?>
                                                                    </h5>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <?php if(isset($detail->stock->movements) && $detail->stock->movements->count()) : ?>

                                                        <div class="card card-bordered card-preview mt-3">
                                                            <table id="suppliers" class="table table-suppliers">
                                                                <thead class="tb-odr-head">
                                                                <tr class="tb-odr-item">
                                                                    <th class="nk-tb-col"><span class="sub-text">Type</span></th>
                                                                    <th class="tb-odr-amount">
                                                                        <span class="tb-odr-total">Qty Adjustment</span>
                                                                        <span class="">Unit Adjustment</span>
                                                                    </th>
                                                                    <th class="tb-odr-action">&nbsp;</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="tb-odr-body">
                                                                <?php foreach($detail->stock->movements as $item) : ?>
                                                                <?php
                                                                    $type = 'Manually added';
                                                                    $link = '#';
                                                                    if($item->purcahse_order_item_id) { $type = 'Purchase order received'; $link = url('purchases/'.$item->purchase_order_item_id); }
                                                                    if($item->sales_order_item_id) { $type = 'Sales order dispatched'; $link = url('salesorders/'.$item->sales_order_item_id); }
                                                                    if($item->build_product_id || $item->production_order_id) { $type = 'Production order completed'; $link = url('productionorders/'.$item->production_order_id); }
                                                                ?>
                                                                <tr class="tb-odr-item">
                                                                    <td class="tb-odr-info">
                                                                        <div class="user-card">
                                                                            <div class="user-info">
                                                                                <span class="tb-lead"><a href="{{ $link }}">{{ $type }}</a></span>
                                                                                <?php if($item->created_by) : ?>
                                                                                <span>Added by {{ get_user($item->created_by)->getFullNameAttribute() }}</span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="tb-odr-amount">
                                                                        <span class="tb-odr-total">
                                                                            <span class="amount text-primary">{{ $item->movement }}</span>
                                                                        </span>
                                                                        <span class="tb-odr-status">
                                                                            <span class="text-primary">{{ $item->unit_movement }} {{ isset($uom) ? $uom->title : '' }}</span>
                                                                        </span>
                                                                    </td>
                                                                    <td class="tb-odr-action">
                                                                        <div class="dropdown">
                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                <ul class="link-list-plain">
                                                                                    <li><a href="{{ url('suppliers/'.$item->supplier_id) }}" target="_blank" class="text-primary">View supplier</a></li>
                                                                                    <li><a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    <?php endif; ?>

                                                <?php else : ?>

                                                <div class="alert alert-warning">Stock file not found, to add a stock file, you can use the add stock button. Dispatching a sale for this item will also add a stock file.</div>

                                                <?php endif; ?>

                                            </div><!-- end Stock -->

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
                                                                    <div class="card-inner">
                                                                        <div class="user-card">
                                                                            <a href="{{ url('products/create?id='.$detail->id) }}" class="btn btn-round btn-icon btn-outline-primary mr-1" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $detail->title }}"><em class="icon ni ni-copy"></em></a>
                                                                            <?php if($detail->is_available_online) : ?>
                                                                            <a href="{{ url('salesorders/create?product='.$detail->id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Sell {{ $detail->title }}"><em class="icon ni ni-cart"></em></a>
                                                                            <?php endif; ?>

                                                                            @can('delete-products')
                                                                            <div class="user-action">
                                                                                <div class="dropdown">
                                                                                    <a class="btn btn-icon btn-trigger mr-n2" data-toggle="dropdown" href="#"><em class="icon ni ni-more-v"></em></a>
                                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                                        <ul class="link-list-opt no-bdr">
                                                                                            <!--<li><a href="#"><em class="icon ni ni-camera-fill"></em><span>Change Photo</span></a></li>-->
                                                                                            <li>
                                                                                                <form action="{{ url()->current() }}" id="delete-resource" method="post">
                                                                                                    @method('delete') @csrf

                                                                                                </form>
                                                                                                <button type="submit" form="delete-resource" data-message="Please note, all related build products, suppliers, reviews and activity will be removed. If you still want to proceed hit yes, otherwise just disable the product from showing online." class="destroy-resource btn btn-block btn-sm btn-danger"><em class="icon ni ni-trash-fill"></em><span>Delete Product</span></button>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @endcan
                                                                        </div>

                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-account-info py-0">
                                                                            <h6 class="overline-title-alt">Pricing</h6>
                                                                            <div class="user-balance preview-gross-cost">&pound;{{ number_format($detail->gross_cost, 2, '.',',') }} </div>
                                                                            <div class="user-balance-sub">NET <span class="text-primary preview-net-cost">&pound;{{ number_format($detail->net_cost, 2, '.',',') }}</span></div>
                                                                            <div class="user-balance-sub">VAT {{ isset($detail->vatType) ? '@'.$detail->vatType->title : '' }} <span class="preview-vat-cost">&pound;{{ number_format($detail->vat_cost, 2, '.',',') }} </span></div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu large-list">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-categories"><em class="icon ni ni-view-list-fill"></em><span>Categories</span></a></li>
                                                                            <li><a href="#tab-attributes"><em class="icon ni ni-puzzle"></em><span>Attributes</span></a></li>
                                                                            <li><a href="#tab-media"><em class="icon ni ni-camera"></em><span>Media</span></a></li>
                                                                            <li><a href="#tab-build-products"><em class="icon ni ni-grid-plus"></em><span>Build Products</span></a></li>
                                                                            <li><a href="#tab-suppliers"><em class="icon ni ni-card-view"></em><span>Suppliers</span></a></li>
                                                                            <li><a href="#tab-stock"><em class="icon ni ni-list-check"></em><span>Stock</span></a></li>
                                                                            {{--<li><a href="#" data-toggle="tooltip" data-placement="right" title="Coming soon"><em class="icon ni ni-activity-round"></em><span>Product Activity</span></a></li>
                                                                            <li><a href="#" data-toggle="tooltip" data-placement="right" title="Coming soon"><em class="icon ni ni-star"></em><span>Reviews</span></a></li>--}}
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
                <form method="post" action="{{ url('store-product-child') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

                    <div class="form-group">
                        <label class="form-label" for="add-product-id">Product *</label>
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
                                <div class="form-control-wrap">
                                    <input class="form-control" type="number" name="qty" id="add-qty" min="0" value="0">
                                </div>
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
                    <div id="build-weight" class="form-group" style="display: none;">
                        <label class="form-label">Weight *</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="number" name="weight" id="add-weight" min="0" value="0" step="0.0001">
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

<div class="modal fade" tabindex="-1" id="modalStockCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>Stock</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('products/create-stock/'.$detail->id) }}" class="form-validate">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

                    <div class="form-group">
                        <label class="form-label">Qty instock *</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="number" name="stock" id="add-qty" min="1" value="1">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Weight of stock</label>
                            <div class="form-control-wrap">
                                <input class="form-control" type="number" name="unit_level_stock" id="add-weight" min="0" value="0" step="0.0001">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Unit</label>
                                <div class="form-control-wrap">
                                    <select class="form-select" {{ (isset($detail->unit_of_measure_id) && !is_null($detail->unit_of_measure_id)) ? 'disabled' : '' }} name="unit_of_measure_id" id="add-unit-of-measure-id" required>
                                        <option value="unit">Unit</option>
                                        <?php if($uoms = uoms()) : ?>
                                            <?php foreach($uoms as $uom) : ?>
                                                <option value="{{ $uom->id }}" {{ (isset($detail->unit_of_measure_id) && !is_null($detail->unit_of_measure_id)) ? is_selected($uom->id,$detail->unit_of_measure_id) : '' }}>{{ $uom->title }}</option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if(isset($detail->unit_of_measure_id) && !is_null($detail->unit_of_measure_id)) : ?>
                                        <input type="hidden" name="unit_of_measure_id" value="{{ $detail->unit_of_measure_id }}">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
            </div>
            {{--<div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>--}}
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="modalAttribute">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>product attribute</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <livewire:admin.add-product-attribute />
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="link-category">
    <div class="modal-dialog modal-md" role="document">
        {{ view('admin.templates.modals.link-category',['field_name' => 'product_id', 'field_id' => $detail->id, 'ignore' => $detail->categories()]) }}
    </div><!-- .modla-dialog -->
</div><!-- .modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="file-upload">
    <div class="modal-dialog modal-md" role="document">
        {{ view('admin.templates.modals.upload',['accepted_files' => 'image/*', 'upload_url' => 'products', 'related_id' => $detail->id]) }}
    </div><!-- .modla-dialog -->
</div><!-- .modal -->


@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/admin/product.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>


</html>
