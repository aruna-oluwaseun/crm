<?php

    if($duplicate_salesorder) {
        $detail = $duplicate_salesorder;
    }

?>
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

                            <div class="nk-block nk-block-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="title nk-block-title">Create new sales order</h4>
                                            <div class="nk-block-des">
                                                <p>
                                                    Fill in as much detail as you can, more options will be available once you have added the sales order
                                                </p>
                                            </div>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="{{ url('salesorders') }}" class="btn btn-danger"><span>Cancel</span></a></li>
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
                                <form method="post" action="{{ url('salesorders') }}" id="create-saleorder-form" class="form-validate">
                                    <div class="row g-gs">

                                       @csrf
                                        <div class="col-lg-6">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner">
                                                    <div class="card-head">
                                                        <?php if(isset($detail->id)) : ?>
                                                            <h5 class="card-title">You are duplicating : <a href="{{ url('salesorder/'.$detail->id) }}" target="_blank">{{ $detail->id }}</a></h5>
                                                        <?php else : ?>
                                                            <h5 class="card-title">Customer details</h5>
                                                        <?php endif; ?>
                                                    </div>

                                                        <div class="form-group">
                                                            <label class="form-label" for="notes">Customer</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select" name="customer_id" data-search="on">
                                                                    <?php if($customers->count()) : ?>
                                                                    <option>Select customer</option>
                                                                    <?php foreach($customers as $customer) : ?>
                                                                    <option value="{{ $customer->id }}" data-first-name="{{ $customer->first_name }}" data-last-name="{{ $customer->last_name }}" data-email="{{ $customer->email }}" {{ is_selected($customer->id, old('customer_id', isset($detail->customer_id) ? $detail->customer_id : '')) }}>{{ $customer->getFullNameAttribute() }} - {{ $customer->email }}</option>
                                                                    <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- Customer detail -->
                                                        <div id="customer-details">
                                                            <!-- Addresses -->

                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Last name *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input class="form-control" type="text" name="first_name" value="{{ old('first_name', isset($detail->first_name) ? $detail->first_name : '') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Last name *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input class="form-control" type="text" name="last_name" value="{{ old('last_name', isset($detail->last_name) ? $detail->last_name : '') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Contact number</label>
                                                                        <div class="form-control-wrap">
                                                                            <input class="form-control" type="tel" name="contact_number" value="{{ old('contact_number', isset($detail->contact_number) ? $detail->contact_number : '') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Email *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input class="form-control" type="email" name="email" value="{{ old('email', isset($detail->email) ? $detail->email : '') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Order number</label>
                                                                    <input class="form-control" type="text" name="order_number" value="{{ old('order_number', isset($detail->order_number) ? $detail->order_number : '') }}">
                                                                </div>

                                                                <div class="form-group mt-3">
                                                                    <label class="form-label">Quote valid until</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="form-icon form-icon-left">
                                                                            <em class="icon ni ni-calendar"></em>
                                                                        </div>
                                                                        <input type="text" class="form-control date-picker" name="quote_valid_until" id="quote_valid_until" value="{{ old('quote_valid_until',date('Y-m-d', strtotime('+ 30 days'))) }}" data-date-format="yyyy-mm-dd">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="notes">Order notes</label>
                                                                    <div class="form-control-wrap">
                                                                        <textarea class="form-control" name="notes" id="notes">{{ old('notes', isset($detail->notes) ? $detail->notes : '') }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <?php
                                                        if(isset($detail->customer_id))
                                                        {
                                                            // load customer addresses
                                                            $customer = get_customer($detail->customer_id);
                                                            $customer_addresses = $customer->addresses;
                                                        }
                                                        ?>

                                                        <div class="row mt-5 mb-5">
                                                            <div class="col-md-6 address">

                                                                <div class="mb-3">
                                                                    <h6>Billing address <a href="#" class="copy-address-to-shipping" data-toggle="tooltip" data-placement="top" title="Copy to shipping"><em class="icon ni ni-swap"></em></a>
                                                                        <a href="#" id="change-billing-address" class="btn btn-sm btn-outline-primary float-right">Change billing address</a>
                                                                    </h6>
                                                                </div>

                                                                <div class="address-container">

                                                                    <div class="card card-bordered new-billing-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                        <div class="card-inner">

                                                                            <div class="form-group" style="{{ isset($customer_addresses) ? 'display:block;' : 'display:none;' }}">
                                                                                <label class="form-label">Select a saved billing address</label>
                                                                                <select class="form-select" id="billing_id">
                                                                                    <option>Choose address</option>
                                                                                    <?php if(isset($customer_addresses)) : ?>
                                                                                        <?php foreach($customer_addresses as $customer_address) : ?>
                                                                                        <option value="{{ $customer_address->address_id }}" data-organisation="{{ $customer_address->detail->title }}" data-line-1="{{ $customer_address->detail->line1 }}" data-line-2="{{ $customer_address->detail->line2 }}" data-line-3="{{ $customer_address->detail->line3 }}" data-county="{{ $customer_address->detail->county }}" data-city="{{ $customer_address->detail->city }}" data-country="{{ $customer_address->detail->country }}" data-lat="{{ $customer_address->detail->lat }}" data-lng="{{ $customer_address->detail->lng }}" data-postcode="{{ $customer_address->detail->postcode }}">
                                                                                            {{ $customer_address->detail->line1.' '.$customer_address->detail->line2.' '.$customer_address->detail->line3.' '.$customer_address->detail->city.' '.$customer_address->detail->postcode }}
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
                                                                        <label class="form-label">Organisation / Address name</label>
                                                                        <div class="form-control-wrap">
                                                                            <input class="form-control title" type="text" name="billing_address_data[title]" value="{{ old('billing_address_data.title', isset($detail->billing_address_data['title']) ? $detail->billing_address_data['title'] : '') }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Line 1 *</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control line1" type="text" name="billing_address_data[line1]" value="{{ old('billing_address_data.line1',isset($detail->billing_address_data['line1']) ? $detail->billing_address_data['line1'] : '') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Line 2</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control line2" type="text" name="billing_address_data[line2]" value="{{ old('billing_address_data.line2',isset($detail->billing_address_data['line2']) ? $detail->billing_address_data['line2'] : '') }}" >
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Line 3</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control line3" type="text" name="billing_address_data[line3]" value="{{ old('billing_address_data.line3', isset($detail->billing_address_data['line3']) ? $detail->billing_address_data['line3'] : '') }}" >
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">City *</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control city" type="text" name="billing_address_data[city]" value="{{ old('billing_address_data.city', isset($detail->billing_address_data['city']) ? $detail->billing_address_data['city'] : '') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Postcode *</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control postcode" type="text" name="billing_address_data[postcode]" value="{{ old('billing_address_data.postcode', isset($detail->billing_address_data['postcode']) ? $detail->billing_address_data['postcode'] : '') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">County</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control county" type="text" name="billing_address_data[county]" value="{{ old('billing_address_data.county', isset($detail->billing_address_data['county']) ? $detail->billing_address_data['county'] : '') }}" >
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
                                                                    <input class="lat" type="hidden" name="billing_address_data[lat]" value="{{ old('billing_address_data.lat', isset($detail->billing_address_data['lat']) ? $detail->billing_address_data['lat'] : '') }}">
                                                                    <input class="lng" type="hidden" name="billing_address_data[lng]" value="{{ old('billing_address_data.lng', isset($detail->billing_address_data['lng']) ? $detail->billing_address_data['lng'] :  '') }}">
                                                                </div>

                                                            </div>

                                                            <div class="col-md-6">

                                                                <div class="mb-3">
                                                                    <h6>Delivery address <a href="#" class="copy-address-to-billing" data-toggle="tooltip" data-placement="top" title="Copy to billing"><em class="icon ni ni-swap"></em></a>
                                                                        <a href="#" id="change-delivery-address" class="btn btn-sm btn-outline-primary float-right">Change shipping address</a>
                                                                    </h6>
                                                                </div>

                                                                <div class="address-container">

                                                                    <div class="card card-bordered new-delivery-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                        <div class="card-inner">

                                                                            <div class="form-group" style="{{ isset($customer_addresses) ? 'display:block;' : 'display:none;' }}">
                                                                                <label class="form-label">Select a saved delivery address</label>
                                                                                <select class="form-select" id="delivery_id">
                                                                                    <option>Choose address</option>
                                                                                    <?php if(isset($customer_addresses)) : ?>
                                                                                        <?php foreach($customer_addresses as $customer_address) : ?>
                                                                                        <option value="{{ $customer_address->address_id }}" data-organisation="{{ $customer_address->detail->title }}" data-line-1="{{ $customer_address->detail->line1 }}" data-line-2="{{ $customer_address->detail->line2 }}" data-line-3="{{ $customer_address->detail->line3 }}" data-county="{{ $customer_address->detail->county }}" data-city="{{ $customer_address->detail->city }}" data-country="{{ $customer_address->detail->country }}" data-lat="{{ $customer_address->detail->lat }}" data-lng="{{ $customer_address->detail->lng }}" data-postcode="{{ $customer_address->detail->postcode }}">
                                                                                            {{ $customer_address->detail->line1.' '.$customer_address->detail->line2.' '.$customer_address->detail->line3.' '.$customer_address->detail->city.' '.$customer_address->detail->postcode }}
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
                                                                        <label class="form-label">Organisation / Address name</label>
                                                                        <div class="form-control-wrap">
                                                                            <input class="form-control title" type="text" name="delivery_address_data[title]" value="{{ old('delivery_address_data.title', isset($detail->delivery_address_data['title']) ? $detail->delivery_address_data['title'] : '') }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Line 1 *</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control line1" type="text" name="delivery_address_data[line1]" value="{{ old('delivery_address_data.line1',isset($detail->delivery_address_data['line1']) ? $detail->delivery_address_data['line1'] : '') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Line 2</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control line2" type="text" name="delivery_address_data[line2]" value="{{ old('delivery_address_data.line2',isset($detail->delivery_address_data['line2']) ? $detail->delivery_address_data['line2'] : '') }}" >
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Line 3</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control line3" type="text" name="delivery_address_data[line3]" value="{{ old('delivery_address_data.line3',isset($detail->delivery_address_data['line3']) ? $detail->delivery_address_data['line3'] : '') }}" >
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">City *</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control city" type="text" name="delivery_address_data[city]" value="{{ old('delivery_address_data.city',isset($detail->delivery_address_data['city']) ? $detail->delivery_address_data['city'] : '') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Postcode *</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control postcode" type="text" name="delivery_address_data[postcode]" value="{{ old('delivery_address_data.postcode',isset($detail->delivery_address_data['postcode']) ? $detail->delivery_address_data['postcode'] : '') }}" >
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">County</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input class="form-control county" type="text" name="delivery_address_data[county]" value="{{ old('delivery_address_data.county',isset($detail->delivery_address_data['county']) ? $detail->delivery_address_data['county'] : '') }}">
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
                                                                    <input class="lat" type="hidden" name="delivery_address_data[lat]" value="{{ old('delivery_address_data.lat',isset($detail->delivery_address_data['lat']) ? $detail->delivery_address_data['lat'] : '') }}">
                                                                    <input class="lng" type="hidden" name="delivery_address_data[lng]" value="{{ old('delivery_address_data.lng',isset($detail->delivery_address_data['lng']) ? $detail->delivery_address_data['lng'] :  '') }}">
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="form-group">
                                                            <div class="custom-control custom-checkbox">
                                                                <input name="send_quote_email" type="checkbox" class="custom-control-input" id="send-quote-email" checked="checked" value="1">
                                                                <label class="custom-control-label" for="send-quote-email"> Upon save email quote to <span class="text-primary" id="quote-email"></span></label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <?php if($order_statuses = sales_order_statuses()) : ?>
                                                            <label class="form-label" for="notes">Status *</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select" name="sales_order_status_id" id="sales-order-status-id" data-search="on">
                                                                    <?php foreach($order_statuses as $status) : if($status->title != 'Quote') { continue; } ?>
                                                                    <option value="{{ $status->id }}">{{ $status->title }}</option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <?php else : ?>
                                                            <div class="alert alert-warning">Please <a href="#">create a new order status</a></div>
                                                            <?php endif; ?>
                                                        </div>


                                                    </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner">
                                                    <div class="card-head">
                                                        <h5 class="card-title">Products</h5>
                                                    </div>

                                                    <div class="card card-bordered card-preview">
                                                        <table id="products-list" class="table table-tranx">
                                                            <thead>
                                                                <tr class="tb-tnx-head">
                                                                    <th width="5%">
                                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                            <input type="checkbox" class="custom-control-input checkbox-item-all" id="items-all">
                                                                            <label class="custom-control-label" for="items-all"></label>
                                                                        </div>
                                                                    </th>
                                                                    <th width="40%">Product</th>
                                                                    <th width="10%">Qty</th>
                                                                    <th width="15%">Vat</th>
                                                                    <th width="15%">Item Cost</th>
                                                                    <th width="15%" class="text-right">Gross</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php if(!empty(old('items'))) : ?>
                                                                <div id="update-costings"></div>
                                                                <!-- repopulate list if error -->
                                                               <?php foreach(old('items') as $key => $item) : ?>
                                                                    <tr class="tb-tnx-item">
                                                                        <td class="nk-tb-col nk-tb-col-check sorting_1">
                                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                                <input type="checkbox" value="1" class="custom-control-input checkbox-item" id="item-{{ $key }}">
                                                                                <label class="custom-control-label" for="item-{{ $key }}"></label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select select-product" name="items[{{ $key }}][product_id]" data-search="on" required>
                                                                                    <option>Select product</option>
                                                                                    <?php foreach($products as $product) : ?>
                                                                                    <option value="{{ $product->id }}" {{ is_selected($item['product_id'], $product->id)}}>{{ $product->title }}</option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="items[{{ $key }}][qty]" value="{{ $item['qty'] }}" class="qty form-control" min="1" required>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-control-wrap">
                                                                                <select name="items[{{ $key }}][vat_type_id]" class="vat-type form-select form-control" data-search="on" required>
                                                                                    <?php if($vat_types = vat_types()) : ?>
                                                                                    <?php foreach($vat_types as $vats) : ?>
                                                                                    <option value="{{ $vats->id }}" data-value="{{ $vats->value }}" {{ is_selected($item['vat_type_id'], $vats->id) }}>{{ $vats->title }}</option>
                                                                                    <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </div>
                                                                            <input type="hidden" class="vat-cost form-control" value="0">
                                                                        </td>
                                                                        <td>
                                                                            <input name="items[{{ $key }}][item_cost]" type="text" class="item-cost form-control" value="{{ $item['item_cost'] }}" required>
                                                                        </td>
                                                                        <td class="text-right gross-cost">
                                                                            <input name="items[{{ $key }}][gross_cost]" readonly type="text" class="gross-cost form-control" value="{{ $item['gross_cost'] }}">
                                                                            <input type="hidden" class="net-cost form-control" value="">
                                                                        </td>
                                                                    </tr>
                                                               <?php endforeach; ?>

                                                            <?php else : ?>

                                                                {{ view('admin.sales-orders.template.product-row', ['products' => $products, 'id' => 0]) }}


                                                            <?php endif; ?>


                                                            </tbody>

                                                        </table>

                                                        <div class="pl-3 pr-3 pb-3">
                                                            <a id="add-product" href="#" class="btn btn-white btn-primary"><span> Add row </span></a>
                                                        </div>

                                                    </div><!-- .card-preview -->

                                                    <div class="row mt-4">
                                                        <div class="col-md-6">
                                                            <a href="#" id="remove-items-btn" class="btn btn-danger disabled" disabled><span> Remove items </span></a>
                                                        </div>

                                                        <div class="col-md-6">

                                                            <table class="table">
                                                                <thead class="thead-dark">
                                                                <tr>
                                                                    <th scope="col" colspan="2">Order Totals</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="card-bordered">
                                                                <tr>
                                                                    <th scope="row">Net cost</th>
                                                                    <td id="show-total-net">&pound; <span>{{ number_format(0.00,2,'.',',') }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Net shipping</th>
                                                                    <td><input id="add-shipping-cost" type="text" value="{{ old('shipping_cost') }}" min="0" class="form-control" name="shipping_cost"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Shipping vat</th>
                                                                    <td>
                                                                        <select id="add-shipping-vat-id" name="shipping_vat_type_id" class="form-select form-control" data-search="on" required>
                                                                            <?php if($vat_types = vat_types()) : ?>
                                                                            <?php foreach($vat_types as $vats) : ?>
                                                                            <option value="{{ $vats->id }}" data-value="{{ $vats->value }}" {{ is_selected($vats->id,old('shipping_vat_type_id')) }}>{{ $vats->title }}</option>
                                                                            <?php endforeach; ?>
                                                                            <?php endif; ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Vat</th>
                                                                    <td id="show-total-vat">&pound; <span>{{ number_format( (0.00),2,'.',',') }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Gross</th>
                                                                    <td id="show-total-gross" class="font-weight-bolder text-primary">&pound; <span>{{ number_format(0,2,'.',',') }}</span> </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

<table style="display: none;" id="product-prototype">
    <tbody>
        {{ view('admin.sales-orders.template.product-row', ['prototype' => true, 'products' => $products]) }}
    </tbody>
</table>

@push('scripts')

@endpush
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">
    var pre_fetch_product = {{ $sell_product ? $sell_product : 'false' }};
</script>
<script type="text/javascript" src="{{ asset('assets/js/admin/sales-order-create.js') }}"></script>

</html>
