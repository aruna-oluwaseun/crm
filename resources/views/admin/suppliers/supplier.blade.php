<?php
    /*if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }*/
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
                                                                Supplier created at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
                                                                <?php if($detail->updated_by) : ?>
                                                                    Last on <b>{{ date('dS F Y H:ia', strtotime($detail->updated_at)) }}</b>.
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

                                                <form method="post" action="{{ url('suppliers/'.$detail->id) }}" id="detail-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')


                                                   <div class="form-group">
                                                        <label class="form-label" for="notes">Supplier *</label>
                                                        <div class="form-control-wrap">
                                                            <input name="title" class="form-control" type="text" value="{{ old('title', $detail->title) }}" placeholder="Supplier name" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Supplier Code</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="code" value="{{ old('code', $detail->code) }}" placeholder="A reference code">
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Contact name *</label>
                                                        <div class="form-control-wrap">
                                                            <input name="contact_name" class="form-control" type="text" value="{{ old('contact_name', $detail->contact_name) }}" placeholder="Contact name" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Email *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="email" class="form-control" name="email" value="{{ old('email', $detail->email) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Phone *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="number" class="form-control" name="contact_number" value="{{ old('contact_number', $detail->contact_number) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>

                                                </form>


                                            </div><!-- end Details -->

                                            <!-- Addresses -->
                                            <div id="tab-addresses" style="display:none;" class="tab card ">

                                                <form method="post" action="{{ url('suppliers/addresses/'.$detail->id) }}">
                                                    @csrf
                                                    <?php if($detail->billing_id) : ?>
                                                        <input type="hidden" name="billing_id" value="{{ $detail->billing_id }}">
                                                    <?php endif; ?>

                                                    <?php if($detail->head_office_id) : ?>
                                                        <input type="hidden" name="head_office_id" value="{{ $detail->head_office_id }}">
                                                    <?php endif; ?>


                                                    <div class="row g-gs">
                                                        <!-- Start Billing Address -->
                                                        <div id="billing-address-details" class="col-md-6 address">
                                                            <div class="card card-bordered card-preview">
                                                                <div class="card-inner">

                                                                    <div class="mb-3">
                                                                        <h6>Billing address <a href="#" class="copy-address-to-shipping" data-toggle="tooltip" data-placement="top" title="Copy to shipping"><em class="icon ni ni-swap"></em></a>
                                                                            <a href="#" id="change-billing-address" class="btn btn-sm btn-outline-primary float-right">Change billing address</a>
                                                                        </h6>
                                                                    </div>

                                                                    <div class="address-container">

                                                                        <div class="card card-bordered new-billing-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                            <div class="card-inner">

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

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Line 1 *</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control line1" type="text" name="billing_address_data[line1]" value="{{ isset($detail->billingAddress->line1) ? $detail->billingAddress->line1 : ''}}" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Line 2</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control line2" type="text" name="billing_address_data[line2]" value="{{ isset($detail->billingAddress->line2) ? $detail->billingAddress->line2 : ''}}" >
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Line 3</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control line3" type="text" name="billing_address_data[line3]" value="{{ isset($detail->billingAddress->line3) ? $detail->billingAddress->line3 : ''}}" >
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">City *</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control city" type="text" name="billing_address_data[city]" value="{{ isset($detail->billingAddress->city) ? $detail->billingAddress->city : '' }}" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Postcode *</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control postcode" type="text" name="billing_address_data[postcode]" value="{{ isset($detail->billingAddress->postcode) ? $detail->billingAddress->postcode : '' }}" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">County</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control county" type="text" name="billing_address_data[county]" value="{{ isset($detail->billingAddress->county) ? $detail->billingAddress->county : '' }}" >
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
                                                                        <input class="lat" type="hidden" name="billing_address_data[lat]" value="{{ isset($detail->billingAddress->lat) ? $detail->billingAddress->lat : ''}}">
                                                                        <input class="lng" type="hidden" name="billing_address_data[lng]" value="{{ isset($detail->billingAddress->lng) ? $detail->billingAddress->lng :  ''}}">
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Start Head Office Address -->
                                                        <div id="shipping-address-details" class="col-md-6 address">

                                                            <div class="card card-bordered card-preview">
                                                                <div class="card-inner">
                                                                    <div class="mb-3">
                                                                        <h6>Head Office Address <a href="#" class="copy-address-to-billing" data-toggle="tooltip" data-placement="top" title="Copy to billing"><em class="icon ni ni-swap"></em></a>
                                                                            <a href="#" id="change-delivery-address" class="btn btn-sm btn-outline-primary float-right">Change shipping address</a>
                                                                        </h6>
                                                                    </div>

                                                                    <div class="address-container">

                                                                        <div class="card card-bordered new-delivery-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                            <div class="card-inner">

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

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Line 1 *</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control line1" type="text" name="head_office_address_data[line1]" value="{{ isset($detail->headOfficeAddress->line1) ? $detail->headOfficeAddress->line1 : ''}}"  required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Line 2</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control line2" type="text" name="head_office_address_data[line2]" value="{{ isset($detail->headOfficeAddress->line2) ? $detail->headOfficeAddress->line2 : ''}}" >
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Line 3</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control line3" type="text" name="head_office_address_data[line3]" value="{{ isset($detail->headOfficeAddress->line3) ? $detail->headOfficeAddress->line3 : ''}}" >
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">City *</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control city" type="text" name="head_office_address_data[city]" value="{{ isset($detail->headOfficeAddress->city) ? $detail->headOfficeAddress->city : '' }}" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Postcode *</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control postcode" type="text" name="head_office_address_data[postcode]" value="{{ isset($detail->headOfficeAddress->postcode) ? $detail->headOfficeAddress->postcode : '' }}"  required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">County</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input class="form-control county" type="text" name="head_office_address_data[county]" value="{{ isset($detail->headOfficeAddress->county) ? $detail->headOfficeAddress->county : '' }}">
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
                                                                                <select class="form-select country" name="head_office_address_data[country]" data-search="on" required>
                                                                                    <?php if($countries = countries()) : ?>
                                                                                    <?php foreach($countries as $country) : ?>
                                                                                    <option value="{{ $country->title }}" data-country-code="{{$country->code}}" {{ is_selected($country->title, $ip_country) }}>{{$country->title}}</option>
                                                                                    <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <input class="lat" type="hidden" name="head_office_address_data[lat]" value="{{ isset($detail->headOfficeAddress->lat) ? $detail->headOfficeAddress->lat : ''}}">
                                                                        <input class="lng" type="hidden" name="head_office_address_data[lng]" value="{{ isset($detail->headOfficeAddress->lng) ? $detail->headOfficeAddress->lng :  ''}}">
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div><!-- end row -->

                                                    <div class="mt-4">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>

                                            </div>

                                            <!-- Products -->
                                            <div id="tab-products" style="display:none;" class="tab card">

                                                <div class="card card-bordered mb-3">
                                                    <div class="card-inner">
                                                        <?php if(isset($products) && $products->count() ) : ?>
                                                        <form method="post" action="{{ url('store-supplier-ref#tab-suppliers') }}" class="form-validate">
                                                            @csrf
                                                            <input type="hidden" name="supplier_id" value="{{ $detail->id }}">
                                                            <h4>Add Product</h4>

                                                            <div class="form-group">
                                                                <label class="form-label">Product</label>
                                                                <div class="form-control-wrap">
                                                                    <select id="product-id" name="product_id" class="form-select form-control" data-search="on" required>
                                                                        <?php foreach($products as $product) : ?>
                                                                        <option value="{{ $product->id }}" {{ is_selected($product->id, old('product_id')) }}>{{ $product->title }}</option>
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

                                                <?php if(isset($detail->products) && $detail->products->count()) : ?>
                                                <?php
                                                    $products = $detail->products()->paginate(50)->fragment('tab-products');
                                                ?>
                                                <div class="card card-bordered card-preview">
                                                    <table id="suppliers" class="table table-suppliers">
                                                        <thead class="tb-odr-head">
                                                        <tr class="tb-odr-item">
                                                            <th class="tb-odr-info">
                                                                <span class="tb-odr-id">Product</span>
                                                            </th>
                                                            <th class="tb-odr-amount">
                                                                <span class="tb-odr-total">Cost</span>
                                                                <span class="d-none d-md-inline-block">Ref</span>
                                                            </th>
                                                            <th class="tb-odr-action">&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tb-odr-body">
                                                        <?php foreach($products as $product_item) : ?>
                                                        <?php
                                                            $item = $product_item;
                                                        ?>
                                                        <tr class="tb-odr-item remove-target" data-id="{{$product_item->id}}">
                                                            <td class="tb-odr-info">
                                                                <span class="tb-odr-id"><a href="{{ url('products/'.$item->id) }}" target="_blank">{{ $item->title }}</a></span>
                                                            </td>
                                                            <td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">&pound;{{ number_format($product_item->pivot->cost_to_us,5,'.',',') }}</span>
                                                                </span>
                                                                <span class="tb-odr-status">
                                                                    <span class="text-success">{{ $product_item->pivot->code }}</span>
                                                                </span>
                                                            </td>
                                                            <td class="tb-odr-action">
                                                                <div class="tb-odr-btns d-none d-md-inline">
                                                                    <a href="{{ url('destroy-supplier-ref/'.$product_item->id) }}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                                                                </div>
                                                                <div class="dropdown">
                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                        <ul class="link-list-plain">
                                                                            <li><a href="{{ url('products/'.$item->id) }}" target="_blank" class="text-primary">View Product</a></li>
                                                                            <li><a href="{{ url('destroy-supplier-ref/'.$product_item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                 <div class="mt-5">
                                                     {{ $products->links() }}
                                                 </div>

                                                <?php else : ?>

                                                <div class="alert alert-warning">No products linked to this product</div>

                                                <?php endif; ?>

                                            </div>

                                            <!-- Purchases -->
                                            <div id="tab-purchases" style="display:none;" class="tab card">

                                            </div>

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
                                                                            <a href="{{ url('purchases/create?supplier='.$detail->id) }}" target="_blank" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Raise purchase order for {{ $detail->title }}"><em class="icon ni ni-wallet-out"></em></a>

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
                                                                                                <button type="submit" form="delete-resource" class="destroy-resource btn btn-block btn-sm btn-danger"><em class="icon ni ni-trash-fill"></em><span>Delete Customer</span></button>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-account-info py-0">
                                                                            <?php
                                                                                $net_spend = 0;
                                                                                $gross_spend = 0;
                                                                                if(isset($detail->purchases->items))
                                                                                {
                                                                                    $net_spend = $detail->purchases->items()->whereNull('cancelled')->sum('net_cost');
                                                                                    $gross_spend = $detail->purchases->items()->whereNull('cancelled')->sum('gross_cost');
                                                                                }
                                                                            ?>

                                                                            <h6 class="overline-title-alt">Your Spend</h6>
                                                                            <div class="user-balance preview-gross-cost">&pound;{{ number_format($gross_spend, 2, '.',',') }} </div>
                                                                            <div class="user-balance-sub">NET <span class="text-primary preview-net-cost">&pound;{{ number_format($detail->net_spend, 2, '.',',') }}</span></div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-addresses"><em class="icon ni ni-book"></em><span>Addresses</span></a></li>
                                                                            <li><a href="#tab-products"><em class="icon ni ni-layers"></em><span>Products</span></a></li>
                                                                            <li><a href="#tab-purchases"><em class="icon ni ni-wallet-in"></em><span>Purchases</span></a></li>
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
                <h5 class="modal-title">Add <span>Address</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('customers/store-address#tab-addresses') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-status" style="display: none"></div>

                    <div class="alert alert-info mb-2"><b>Please note :</b> The address finder will only locate addresses in Great Britain</div>

                    {{ view('admin.templates.address-finder') }}

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


{{ view('admin.templates.footer') }}
</body>
<script type="text/javascript" src="{{ asset('assets/js/admin/suppliers.js') }}"></script>

</html>
