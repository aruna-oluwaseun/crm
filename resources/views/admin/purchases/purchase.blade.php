<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }

    $purchase_locked = false;
    if($detail->vat_return_id) {
        $purchase_locked = true;
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
                                            <h4 class="nk-block-title">#{{ $detail->id }} Purchase Detail</h4>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        {{--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>--}}
                                                        <li><a href="{{ url('purchases/download/'.$detail->id) }}" class="btn btn-white btn-primary"><em class="icon ni ni-download"></em><span class="tb-col-lg">Download PO</span></a></li>
                                                        <li><a href="#" data-toggle="modal" data-target="#modalEmail" class="btn btn-white btn-outline-light"><em class="tb-col-lg icon ni ni-mail "></em><span>Email PO</span></a></li>
                                                        <li><a href="{{ url('purchases/view-purchase-order/'.$detail->id) }}" class="btn btn-white btn-outline-light"><span>View PO</span></a></li>
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
                                                                Purchase order created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
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

                                                <form method="post" action="{{ url('purchases/'.$detail->id) }}" id="product-detail-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label class="form-label">Supplier *</label>
                                                        <div class="form-control-wrap">
                                                            <select disabled class="form-control form-select" data-search="on">
                                                                <option value="0" selected>{{ $detail->supplier_title }}</option>
                                                            </select>
                                                        </div>
                                                        <input type="hidden" name="supplier_id" value="{{ $detail->supplier_id }}">
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Supplier invoice number</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="supplier_invoice_number" value="{{ old('supplier_invoice_number', $detail->supplier_invoice_number) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="form-label">Expected delivery date *</label>
                                                                <div class="form-control-wrap">
                                                                    <div class="form-icon form-icon-left">
                                                                        <em class="icon ni ni-calendar"></em>
                                                                    </div>
                                                                    <input type="text" class="form-control date-picker" name="expected_delivery_date" id="expected-delivery-date" data-date-format="yyyy-mm-dd" value="{{ old('expected_delivery_date',$detail->expected_delivery_date) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    $billing_address = $detail->billing_address_data;
                                                    $shipping_address = $detail->delivery_address_data;
                                                    ?>
                                                    <h5 class="card-title mt-5 mb-4">Your Billing and Shipping Address</h5>
                                                    <div class="row mb-4">
                                                        <div class="col-md-6 address">

                                                            <div class="mb-3">
                                                                <h6>Billing address <a href="#" class="copy-address-to-shipping" data-toggle="tooltip" data-placement="top" title="Copy to shipping"><em class="icon ni ni-swap"></em></a>
                                                                    <a href="#" id="change-billing-address" class="btn btn-sm btn-outline-primary float-right">Change billing address</a>
                                                                </h6>
                                                            </div>

                                                            <div class="address-container">

                                                                <div class="card card-bordered new-billing-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                    <div class="card-inner">

                                                                        <div class="form-group" style="{{ (isset($franchise->addresses) && $franchise->addresses->count()) ? 'display:block;' : 'display:none;' }}">
                                                                            <label class="form-label">Select a saved billing address</label>
                                                                            <select class="form-select">
                                                                                <option>Choose address</option>
                                                                                <?php if(isset($franchise->addresses)) : ?>
                                                                                <?php foreach($franchise->addresses as $address) : ?>
                                                                                <option value="{{ $address->id }}" data-organisation="{{ $address->title }}" data-line-1="{{ $address->line1 }}" data-line-2="{{ $address->line2 }}" data-line-3="{{ $address->line3 }}" data-county="{{ $address->county }}" data-city="{{ $address->city }}" data-country="{{ $address->country }}" data-lat="{{ $address->lat }}" data-lng="{{ $address->lng }}" data-postcode="{{ $address->postcode }}">
                                                                                    {{ $address->line1.' '.$address->line2.' '.$address->line3.' '.$address->city.' '.$address->postcode }}
                                                                                </option>
                                                                                <?php endforeach; ?>
                                                                                <?php endif;  ?>
                                                                            </select>

                                                                            <h5 class="mt-3">OR</h5>
                                                                        </div>


                                                                        <div class="form-group">
                                                                            <label class="form-label" for="notes">Enter postcode *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input id="find-postcode" class="find-postcode form-control" value="" placeholder="Enter postcode">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <button id="postcode-btn" type="button" class="submit-btn postcode-btn btn btn-outline-primary">Find addresses</button>
                                                                        </div>

                                                                        <div id="show-addresses" class="form-group show-addresses" style="display: none;">
                                                                            <label class="form-label" for="notes">Addresses found</label>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select">

                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="form-label">Your company / Address name</label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control title" type="text" name="billing_address_data[title]" value="{{ old('billing_address_data.title', isset($billing_address['title']) ? $billing_address['title'] : '') }}">
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 1 *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line1" type="text" name="billing_address_data[line1]" value="{{ old('billing_address_data.line1',isset($billing_address['line1']) ? $billing_address['line1'] : '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 2</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line2" type="text" name="billing_address_data[line2]" value="{{ old('billing_address_data.line2',isset($billing_address['line2']) ? $billing_address['line2'] : '') }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 3</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line3" type="text" name="billing_address_data[line3]" value="{{ old('billing_address_data.line3', isset($billing_address['line3']) ? $billing_address['line3'] : '') }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">City *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control city" type="text" name="billing_address_data[city]" value="{{ old('billing_address_data.city', isset($billing_address['city']) ? $billing_address['city'] : '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Postcode *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control postcode" type="text" name="billing_address_data[postcode]" value="{{ old('billing_address_data.postcode', isset($billing_address['postcode']) ? $billing_address['postcode'] : '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">County</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control county" type="text" name="billing_address_data[county]" value="{{ old('billing_address_data.county', isset($billing_address['county']) ? $billing_address['county'] : '') }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <?php
                                                                    if( !$ip_country = session('ip_location.country_name') ) {
                                                                        $ip_country = '';
                                                                    }
                                                                    ?>
                                                                    <label class="form-label" for="notes">Country *</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-select country" name="billing_address_data[country]" data-search="on" required>
                                                                            <?php if($countries = countries()) : ?>
                                                                            <?php foreach($countries as $country) : ?>
                                                                            <option value="{{ $country->title }}" data-country-code="{{$country->code}}" {{ is_selected($country->title, $ip_country) }}>{{$country->title}}</option>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input class="lat" type="hidden" name="billing_address_data[lat]" value="{{ old('billing_address_data.lat', isset($billing_address['lat']) ? $billing_address['lat'] : '') }}">
                                                                <input class="lng" type="hidden" name="billing_address_data[lng]" value="{{ old('billing_address_data.lng', isset($billing_address['lng']) ? $billing_address['lng'] :  '') }}">
                                                            </div>

                                                        </div>

                                                        <div class="col-md-6 address">

                                                            <div class="mb-3">
                                                                <h6>Delivery address <a href="#" class="copy-address-to-billing" data-toggle="tooltip" data-placement="top" title="Copy to billing"><em class="icon ni ni-swap"></em></a>
                                                                    <a href="#" id="change-delivery-address" class="btn btn-sm btn-outline-primary float-right">Change shipping address</a>
                                                                </h6>
                                                            </div>

                                                            <div class="address-container">

                                                                <div class="card card-bordered new-delivery-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                    <div class="card-inner">

                                                                        <div class="form-group" style="{{ (isset($franchise->addresses) && $franchise->addresses->count()) ? 'display:block;' : 'display:none;' }}">
                                                                            <label class="form-label">Select a saved delivery address</label>
                                                                            <select class="form-select">
                                                                                <option>Choose address</option>
                                                                                <?php if(isset($franchise->addresses)) : ?>
                                                                                <?php foreach($franchise->addresses as $address) : ?>
                                                                                <option value="{{ $address->id }}" data-organisation="{{ $address->title }}" data-line-1="{{ $address->line1 }}" data-line-2="{{ $address->line2 }}" data-line-3="{{ $address->line3 }}" data-county="{{ $address->county }}" data-city="{{ $address->city }}" data-country="{{ $address->country }}" data-lat="{{ $address->lat }}" data-lng="{{ $address->lng }}" data-postcode="{{ $address->postcode }}">
                                                                                    {{ $address->line1.' '.$address->line2.' '.$address->line3.' '.$address->city.' '.$address->postcode }}
                                                                                </option>
                                                                                <?php endforeach; ?>
                                                                                <?php endif;  ?>
                                                                            </select>

                                                                            <h5 class="mt-3">OR</h5>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="form-label" for="notes">Enter postcode *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input id="find-postcode" class="find-postcode form-control" value="" placeholder="Enter postcode">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <button id="postcode-btn" type="button" class="submit-btn postcode-btn btn btn-outline-primary">Find addresses</button>
                                                                        </div>

                                                                        <div id="show-addresses" class="form-group show-addresses" style="display: none;">
                                                                            <label class="form-label" for="notes">Addresses found</label>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select">

                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="form-label">Your company / Address name</label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control title" type="text" name="delivery_address_data[title]" value="{{ old('delivery_address_data.title', isset($shipping_address['title']) ? $shipping_address['title'] : '') }}">
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 1 *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line1" type="text" name="delivery_address_data[line1]" value="{{ old('delivery_address_data.line1',isset($shipping_address['line1']) ? $shipping_address['line1'] : '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 2</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line2" type="text" name="delivery_address_data[line2]" value="{{ old('delivery_address_data.line2',isset($shipping_address['line2']) ? $shipping_address['line2'] : '') }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 3</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line3" type="text" name="delivery_address_data[line3]" value="{{ old('delivery_address_data.line3',isset($shipping_address['line3']) ? $shipping_address['line3'] : '') }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">City *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control city" type="text" name="delivery_address_data[city]" value="{{ old('delivery_address_data.city',isset($shipping_address['city']) ? $shipping_address['city'] : '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Postcode *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control postcode" type="text" name="delivery_address_data[postcode]" value="{{ old('delivery_address_data.postcode',isset($shipping_address['postcode']) ? $shipping_address['postcode'] : '') }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">County</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control county" type="text" name="delivery_address_data[county]" value="{{ old('delivery_address_data.county',isset($shipping_address['county']) ? $shipping_address['county'] : '') }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <?php
                                                                    if( !$ip_country = session('ip_location.country_name') ) {
                                                                        $ip_country = '';
                                                                    }
                                                                    ?>
                                                                    <label class="form-label" for="notes">Country *</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-select country" name="delivery_address_data[country]" data-search="on" required>
                                                                            <?php if($countries = countries()) : ?>
                                                                            <?php foreach($countries as $country) : ?>
                                                                            <option value="{{ $country->title }}" data-country-code="{{$country->code}}" {{ is_selected($country->title, $ip_country) }}>{{$country->title}}</option>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <input class="lat" type="hidden" name="delivery_address_data[lat]" value="{{ old('delivery_address_data.lat',isset($shipping_address['lat']) ? $shipping_address['lat'] : '') }}">
                                                                <input class="lng" type="hidden" name="delivery_address_data[lng]" value="{{ old('delivery_address_data.lng',isset($shipping_address['lng']) ? $shipping_address['lng'] :  '') }}">
                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <?php if($order_statuses = purchase_order_statuses()) : ?>
                                                        <label class="form-label" for="notes">Status *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="purchase_order_status_id" id="purchase-order-status-id" data-search="on">
                                                                <?php foreach($order_statuses as $status) : ?><?php if($purchase_locked && in_array($status->id,[1,5,6])) { continue; } ?><!-- dont allow pending, cancelled or part-cancelled-->
                                                                    <option value="{{ $status->id }}" <?php echo is_selected($status->id, $detail->purchase_order_status_id) ?>>{{ $status->title }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="alert alert-warning">Please <a href="#">create a new purchase order status</a></div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div id="status-warning" style="display: none;" class="alert alert-info"><b><em class="icon ni ni-alert"></em> Warning :</b> no reason for this warning have a nice day.</div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>


                                            </div><!-- end Details -->

                                            <!-- Build products -->
                                            <div id="tab-products" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content tb-col-lg">
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-primary-dim"><em class="icon ni ni-package"></em> Not dispatched</span></p>
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-info"><em class="icon ni ni-package"></em> Part dispatched / collected</span></p>
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-success"><em class="icon ni ni-package"></em> Dispatched / Collected</span></p>
                                                        </div>


                                                        <?php if($purchase_locked) : ?>
                                                        <div class="nk-block-head-content">
                                                            <a href="#" disabled="" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add item</span></a>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add item</span></a>
                                                        </div>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>

                                                <?php if($purchase_locked) : ?>
                                                    <div class="alert alert-info">You cannot modify this purchase order as it has already been filed in a VAT Return</div>
                                                <?php endif; ?>

                                                <?php if(isset($detail->items) && $detail->items->count() ) : ?>
                                                <form id="dispatch-items-form" method="POST" action="{{ url('purchases/load-items-for-dispatch') }}">
                                                    <input type="hidden" name="purchase_order_id" value="{{ $detail->id }}">
                                                    <div class="card card-bordered card-preview">
                                                        <table class="table">
                                                            <thead class="thead-light">
                                                            <th>
                                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                    <input type="checkbox" class="custom-control-input checkbox-item-all" id="items-all">
                                                                    <label class="custom-control-label" for="items-all"></label>
                                                                </div>
                                                            </th>
                                                            <th>#</th>
                                                            <th>Product</th>
                                                            <th class="tb-col-lg">Weight</th>
                                                            <th>Price</th>
                                                            <th class="tb-col-lg">Status</th>
                                                            <th></th>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $total_qty = 0;
                                                            $total_qty_dispatched = 0;
                                                            $total_additional_shipping = 0;

                                                            $total_invoiced = 0;
                                                            ?>
                                                            <?php foreach($detail->items as $item) : $uom = uom($item->unit_of_measure_id); ?>
                                                            <?php

                                                            $total_qty+=$item->qty;

                                                            if($item->is_additional_shipping) {
                                                                $total_additional_shipping++;
                                                            }

                                                            $dispatched = false;
                                                            if(isset($item->dispatches) && $item->dispatches->count())
                                                            {
                                                                foreach ($item->dispatches as $dispatch)
                                                                {
                                                                    $dispatched += $dispatch->qty;
                                                                }
                                                            }

                                                            $total_qty_dispatched += $dispatched;

                                                            ?>
                                                            <tr>
                                                                <td class="nk-tb-col nk-tb-col-check sorting_1">
                                                                    <?php if($item->qty-$dispatched && !$item->is_additional_shipping) : ?>
                                                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                        <input type="checkbox" name="dispatch[{{$item->id}}]" value="{{ ($item->qty-$dispatched) }}" class="custom-control-input checkbox-item" id="item-{{$item->id}}">
                                                                        <label class="custom-control-label" for="item-{{$item->id}}"></label>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if($item->product_id) : ?>
                                                                    <a href="{{ url('products/'.$item->product_id) }}">{{ $item->product_id }}</a>
                                                                    <?php else : ?>
                                                                    <span class="text-light">NA</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><b>{{ $item->qty }} x</b> {{ $item->product_title }}</td>
                                                                <td class="tb-col-lg">{{ $uom->id ? number_format($item->weight).' '.$uom->title : ' NA ' }}</td>
                                                                <td>£{{ number_format($item->gross_cost, 2, '.',',') }}</td>
                                                                <td class="tb-col-lg"><?php if($dispatched) : ?>
                                                                    {!! ($dispatched >= $item->qty) ? '<em class="icon ni ni-package text-success" data-toggle="tooltip" data-placement="top" title="Dispatched / Collected"></em>' : '<em class="icon ni ni-package text-info" data-toggle="tooltip" data-placement="top" title="Part dispatched / collected"></em>' !!}
                                                                    <?php else : ?>
                                                                    <em class="icon ni ni-package text-primary-dim" data-toggle="tooltip" data-placement="top" title="Nothing dispatched / collected"></em>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="tb-odr-action">
                                                                    <div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                <li><a href="{{ url('products/'.$item->product_id) }}" target="_blank" class="text-primary">View</a></li>
                                                                                <?php if($item->qty-$dispatched) : ?>
                                                                                <li><a href="#" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                <?php endif; ?>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>

                                                    </div><!-- .card-preview -->

                                                    <div class="row nk-block-head">
                                                        <div class="col-md-8 mb-4">
                                                            <div class="nk-block-head-content">
                                                                <?php if(!in_array($detail->purchase_order_status_id,[1,5])) : ?>

                                                                <?php if($total_qty_dispatched >= ($total_qty-$total_additional_shipping)) : ?>
                                                                <a href="#" class="btn btn-white btn-success disabled" disabled><em class="icon ni ni-send"></em><span>All items dispatched</span></a>
                                                                <?php else : ?>
                                                                <a href="#" id="dispatch-btn" class="btn btn-white btn-primary disabled" data-toggle="modal" data-target="#modalDispatch" disabled><em class="icon ni ni-send"></em><span>Dispatch items</span></a>
                                                                <?php endif; ?>

                                                                <?php else : ?>

                                                                <a href="#" class="btn btn-white btn-primary disabled" disabled><span>Dispatch not possible with current status {{ $detail->status->title }}</span></a>

                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>The total weight of this order <span class="text-primary">{{ $detail->weight_kg }}Kg</span> </p>

                                                            <table class="table">
                                                                <thead class="thead-dark">
                                                                <tr>
                                                                    <th scope="col" colspan="2">Order Totals</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="card-bordered">
                                                                <tr>
                                                                    <th scope="row">Net cost</th>
                                                                    <td>&pound; {{ number_format($detail->net_cost,2,'.',',') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Net shipping</th>
                                                                    <td>&pound; {{ number_format($detail->shipping_cost,2,'.',',') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Vat</th>
                                                                    <td>&pound; {{ number_format( ($detail->vat_cost + $detail->shipping_vat),2,'.',',') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Gross</th>
                                                                    <td class="font-weight-bolder text-primary">&pound; {{ number_format($detail->gross_cost,2,'.',',') }} </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </form>

                                                <?php else : ?>

                                                <div class="alert alert-warning">There are no items on this pruchase order <a href="{{ url('products/create') }}" data-toggle="modal" data-target="#modalCreate">add item</a></div>

                                                <?php endif; ?>
                                            </div><!-- end Build Products -->

                                            <!-- Dispatches Tab -->
                                            <div id="tab-dispatches" style="display:none;" class="tab card">

                                                <?php if($detail->dispatches && $detail->dispatches->count() ) : ?>

                                                <?php foreach ($detail->dispatches as $dispatch) : ?>
                                                <?php
                                                if( !$dispatched_by = get_user($dispatch->created_by) ) {
                                                    $dispatched_by = 'NA';
                                                }
                                                if( !$dispatched_by = get_user($dispatch->updated_by) ) {
                                                    $dispatched_by = 'NA';
                                                }

                                                $courier_url = isset($dispatch->courier) ? $dispatch->courier->url : '#';
                                                if($courier_url != '' && $dispatch->tracking_number) {
                                                    $courier_url = str_replace('{CODE}',$dispatch->tracking_number,$courier_url);
                                                }
                                                ?>
                                                <div class="card card-bordered mb-3">
                                                    <div class="card-inner">
                                                        <h5 class="card-title">
                                                            Dispatch Ref #{{ $dispatch->id }}
                                                            <div class="float-right"><span class="badge badge-lg badge-primary">
                                                                            <?php if($dispatch->is_collection) : ?>
                                                                                <em class="icon ni ni-user-alt"></em> Collection
                                                                            <?php else : ?>
                                                                                <em class="icon ni ni-send"></em> Shipped
                                                                            <?php endif; ?>
                                                                        </span>
                                                            </div>
                                                        </h5>
                                                        <h6 class="card-subtitle text-primary mb-2">
                                                            <?php if($dispatch->is_collection) : ?>
                                                            {{ $dispatch->collected_by }}
                                                            <?php else : ?>
                                                            <?php if($dispatch->courier_title) : ?>
                                                            {{ $dispatch->courier_title }}
                                                            <?php else : ?>
                                                            {{ $dispatch->courier->title }}
                                                            <?php endif; ?>
                                                            <?php endif; ?>
                                                        </h6>
                                                        <p class="card-text">
                                                            Dispatched created by <a href="{{ url('users/'.$dispatched_by->id) }}">{{ $dispatched_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($dispatch->created_at)) }}</b>.
                                                            <?php if($dispatched_by->updated_by) : ?>
                                                            Dispatched updated by <a href="{{ url('users/'.$dispatched_by->id) }}">{{ $dispatched_by->getFullNameAttribute() }}</a> on <b>{{ date('dS F Y H:ia', strtotime($dispatch->updated_at)) }}</b>.
                                                            <?php endif; ?>
                                                        </p>

                                                        <hr>
                                                        <?php if($dispatch->tracking_number) : ?>
                                                        Tracking number : <span class="badge badge-dim badge-primary">
                                                                        <a href="{{ $courier_url }}" target="_blank">{{ $dispatch->tracking_number }}</a>
                                                                    </span>
                                                        <?php endif; ?>

                                                        <div class="mt-3 mb-3">
                                                            <form id="#" method="POST">
                                                                <div class="card card-bordered card-preview">
                                                                    <table class="table">
                                                                        <thead class="thead-light">
                                                                        <th>Product</th>
                                                                        <th>Qty</th>
                                                                        <th></th>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if(isset($dispatch->items) && $dispatch->items->count()) : ?>
                                                                        <?php foreach($dispatch->items as $item) : ?>
                                                                        <tr>
                                                                            <td>{{ $item->product_title }}</td>
                                                                            <td>{{ $item->qty }}</td>
                                                                            <td></td>
                                                                        </tr>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-8">

                                                            </div>
                                                            <div class="col-md-4">
                                                                <table class="table">
                                                                    <thead class="thead-dark">
                                                                    <tr>
                                                                        <th scope="col" colspan="2">Details</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="card-bordered">
                                                                    <tr>
                                                                        <th scope="row">Loaded by</th>
                                                                        <td>{{ $dispatch->loadedBy ? $dispatch->loadedBy->getFullNameAttribute() : '' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">Expected date</th>
                                                                        <td>{{ $dispatch->expected_delivery_date ? long_date($dispatch->expected_delivery_date) : 'NA' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">Delivered date</th>
                                                                        <td>{{ $dispatch->actual_delivery_date ? long_date($dispatch->actual_delivery_date) : 'NA' }}</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <?php endforeach; ?>



                                                <?php else : ?>

                                                <div class="alert alert-warning">There are no dispatches</div>

                                                <?php endif; ?>

                                            </div><!-- end Dispatches -->

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
                                                                            <div class="user-balance-sub">VAT {{ isset($detail->vatType) ? '@'.$detail->vatType->title : '' }} <span class="preview-vat-cost">&pound;{{ number_format($detail->vat_cost+$detail->shipping_vat, 2, '.',',') }} </span></div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-products"><em class="icon ni ni-grid-plus"></em><span>Item on order</span></a></li>
                                                                            <li><a href="#tab-dispatches"><em class="icon ni ni-package"></em><span>Dispatches</span></a></li>
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
                <h5 class="modal-title">Add <span>order item</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php if( isset($products) && $products->count() ) : ?>
                <form method="post" action="{{ url('purchases/add-item') }}#tab-order-items" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="purchase_order_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>


                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input name="none_stock_item" type="checkbox" class="custom-control-input" id="add-none-stock-item" value="1">
                            <label class="custom-control-label" for="add-none-stock-item"> Purchase none stock product</label>
                        </div>
                    </div>
{{--<option value="' + value.id + '" data-weight="' + value.weight + '" data-unit-of-measure-id="' + value.unit_of_measure_id + '" data-unit-of-measure-title="' + (value.unit_of_measure.title ? value.unit_of_measure.title : '') + '" data-weight-kg="' + value.weight_kg + '" data-cost="'+ value.pivot.cost_to_us +'" data-vat-type-id="' + value.pivot.vat_type_id + '">--}}
                    <div class="form-group" id="select-product">
                        <label class="form-label" for="notes">Product *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="product_id" id="add-product-id" data-search="on" required>
                                <option value="" selected="selected">Select a product</option>
                                <?php foreach($products as $product) : ?>
                                <option value="{{ $product->id }}" data-weight="{{ $product->weight }}" data-unit-of-measure-id="{{ $product->unit_of_measure_id }}" data-unit-of-measure-title="{{ $product->unitOfMeasure->title }}" data-weight-kg="{{ $product->weight_kg }}" data-cost="{{ $product->pivot->cost_to_us }}" data-vat-type-id="{{ $product->pivot->vat_type_id }}">{{ $product->title }} - ({{ $product->pivot->code }})</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3" id="typein-product" style="display: none">
                        <div class="col-md-8">
                            <div class="form-group" >
                                <label class="form-label" for="notes">Product *</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="product_title" id="add-product-title" class="form-control" placeholder="What are buying ?" disabled required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" >
                                <label class="form-label" for="notes">Code *</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="product_code" id="add-product-code" class="form-control" placeholder="Supplier code" disabled required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Qty</label>
                                <div class="form-control-wrap">
                                    <input type="number" id="add-qty" min="1" name="qty" id value="1" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Vat Type</label>
                                <div class="form-control-wrap">
                                    <select id="add-vat-type-id" name="vat_type_id" class="form-select form-control" data-search="on" required>
                                        <?php if($vat_types = vat_types()) : ?>
                                        <?php foreach($vat_types as $vats) : ?>
                                        <option value="{{ $vats->id }}" data-value="{{ $vats->value }}">{{ $vats->title }}</option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3" id="add-item-weight" style="display: none">

                        <div class="col-md-12 mb-3">
                            <div class="alert alert-info">If you are adding the item of <b>weight</b> please make sure you add the <b>unit of measure</b> too else the weight will be ignored.</div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Weight <small>Of 1 item</small></label>
                                <div class="form-control-wrap">
                                    <input name="weight" type="number" class="form-control" id="add-weight" value="" step="0.0001">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Unit</label>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="unit_of_measure_id" id="add-unit-of-measure-id">
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

                    <p id="show-weight-box">Total weight <span class="text-primary">NA</span></p>


                    <input type="hidden" id="add-original-weight-value" value="0">
                    <input type="hidden" id="add-original-unit-of-measure" value="">


                    <div class="form-group">
                        <label class="form-label">Item cost *</label>
                        <div class="form-control-wrap">
                            <input id="add-item-cost" name="item_cost" type="text" class="form-control" value="" required>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Net cost</label>
                                <div class="form-control-wrap">
                                    <input id="add-net-cost" readonly type="text" name="net_cost" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">VAT</label>
                                <div class="form-control-wrap">
                                    <input id="add-vat-cost" readonly type="text" name="vat_cost" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Gross</label>
                                <div class="form-control-wrap">
                                    <input id="add-gross-cost" readonly type="text" name="gross_cost" class="form-control" value="0">
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

<div class="modal fade" tabindex="-1" id="modalDispatch">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dispatch items</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php if( isset($products) && $products->count() ) : ?>
                <form method="post" action="{{ url('purchases/dispatches') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="purchase_order_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="dispatch-status" style="display: none"></div>

                    <?php
                    $is_collection = false;
                    if(isset($detail->shipping_option_id) && $detail->shipping_option_id == 1)
                    {
                        $is_collection = true;
                    }

                    $couriers = couriers();

                    $show_new_courier = false;
                    if(!count($couriers)) {
                        $show_new_courier = true;
                    }

                    if($is_collection) {
                        $show_new_courier = false;
                    }

                    ?>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input name="is_collection" type="checkbox" class="custom-control-input" id="dispatch-is-collection" value="1" {{ is_checked('1', intval($is_collection) ) }}>
                            <label class="custom-control-label" for="dispatch-is-collection"> We are collecting</label>
                        </div>
                    </div>

                    <div class="form-group shipping-details" id="select-product" style="display: {{ $is_collection ? 'none' : '' }}">
                        <label class="form-label" for="notes">Courier *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="courier_id" id="dispatch-courier-id" data-search="on" required>
                                <option value="new">Add new</option>
                                <?php foreach($couriers as $courier) : ?>
                                <option value="{{ $courier->id }}">{{ $courier->title }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group shipping-details" id="dispatch-new-courier" style="display: {{ $show_new_courier ? '' : 'none' }}">
                        <label class="form-label" for="notes">New courier</label>
                        <div class="form-control-wrap">
                            <input type="text" name="courier_title" id="dispatch-courier-title" class="form-control" placeholder="Eg: TNT" {{ $show_new_courier ? '' : 'disabled' }} required>
                        </div>
                    </div>

                    <div class="form-group shipping-details" style="display: {{ $is_collection ? 'none' : '' }}">
                        <label class="form-label" for="notes">Tracking number</label>
                        <div class="form-control-wrap">
                            <input type="text" name="tracking_number" id="dispatch-tracking-number" class="form-control" value="">
                        </div>
                    </div>

                    <div class="form-group collection-details" style="display: {{ $is_collection ? '' : 'none' }}">
                        <label class="form-label" for="notes">Name of person collecting</label>
                        <div class="form-control-wrap">
                            <input type="text" name="collected_by" id="dispatch-collecting-by" class="form-control" value="">
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Expected delivery date</label>
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>
                                    <input type="text" class="form-control date-picker" name="expected_delivery_date" id="dispatch-expected-delivery-date" data-date-format="yyyy-mm-dd">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="loading text-center mt-3">
                        <img src="{{ asset('assets/files/img/loading.gif') }}">
                    </div>
                    <!-- Load dispatch via ajax -->
                    <div class="mt-3 mb-3" id="load-dispatch-items" >
                        <table class="table card-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th>Product</th>
                                <th>Sent</th>
                                <th>Ship</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info">Please note : Stock will only be updated when the dispatch is marked as completed.</div>

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

<div class="modal fade" tabindex="-1" id="modalEmail">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-purchase', ['email' => $detail->supplier->email,'id' => $detail->id]) }}
    </div>
</div>


@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/admin/purchase-order.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>


</html>
