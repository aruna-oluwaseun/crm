<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }

    $customer_locked = false;

    // online order
    if( $detail->order_type_id === 1 ) {
        $customer_locked = true;
    }

    // lock customer by status
    if( !in_array($detail->sales_order_status_id,[1,2]) ) {
        $customer_locked = true;
    }

    // If any invoice associated has been filed in a VAT return lock customer again
    if(isset($detail->invoices) && $detail->invoices->count())
    {
        $vat_returns = $detail->invoices()->vatFiled()->count();

        if($vat_returns)
        {
           $customer_locked = true;
        }
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


                <div id="sales-order-urgent" class="col-md-12 mb-3" style="display: {{ $detail->is_urgent ? 'block' : 'none' }};"><div class="alert alert-danger">This sales order is <b>URGENT</b>, please prioritise it.</div></div>

                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">

                            <div class="nk-block-head " style="padding-bottom: 1.5rem">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h4 class="title nk-block-title">#{{ $detail->id }} Sales order</h4>
                                    </div>

                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                            <div class="toggle-expand-content" data-content="pageMenu">
                                                <ul class="nk-block-tools g-3">

                                                    <?php if($detail->invoices->count()) : ?>
                                                        <li><a href="#" data-toggle="modal" data-target="#modalInvoiceAction" class="btn btn-white btn-primary"><em class="icon ni ni-download"></em><span class="tb-col-lg">Download Invoice</span></a></li>
                                                        <li><a href="#" data-toggle="modal" data-target="#modalInvoiceAction" class="btn btn-white btn-outline-light"><em class="tb-col-lg icon ni ni-mail "></em><span>Email Invoice</span></a></li>
                                                    <?php else : ?>
                                                        <li><a href="{{ url('quote/download/'.$detail->id) }}" class="btn btn-white btn-primary"><em class="icon ni ni-download"></em><span class="tb-col-lg">Download Quote</span></a></li>
                                                        <li><a href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalEmailQuote" class="btn btn-white btn-outline-light"><em class="tb-col-lg icon ni ni-mail "></em><span>Email Quote</span></a></li>
                                                        <li><a href="{{ url('quote/'.$detail->id) }}" class="btn btn-white btn-outline-light"><span>View Quote</span></a></li>
                                                    <?php endif; ?>
                                                        <li class="nk-block-tools-opt">
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-white btn-outline-light" data-toggle="dropdown"><em class="icon ni ni-printer"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">

                                                                    <?php if(!in_array($detail->sales_order_status_id,[1,7])) : ?>
                                                                        <li><a href="#"><span>Packing List</span></a></li>
                                                                        <li><a href="#"><span>Picking List</span></a></li>
                                                                    <?php endif; ?>
                                                                    <?php if($detail->invoices->count()) : ?>
                                                                        <li><a href="#" data-toggle="modal" data-target="#modalInvoiceAction"><span>Invoice</span></a></li>
                                                                    <?php else : ?>
                                                                        <li><a href="{{ url('quote/print/'.$detail->id) }}" target="_blank"><span>Quote</span></a></li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div><!-- .toggle-wrap -->
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
                                                        <h4 class="nk-block-title">Sales order #{{$detail->id}}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                Sales order created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
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

                                                <?php if(isset($detail->orderType)) : ?>
                                                <div class="alert alert-{{$detail->orderType->classes}}">Order type : <b>{{ $detail->orderType->title }}</b> {{ $detail->customer_id ? '' : 'Checked out as GUEST' }}</div>
                                                <?php endif; ?>

                                                <form method="post" action="{{ url('salesorders/'.$detail->id) }}" id="edit-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Customer *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="customer_id" data-search="on" <?php echo $customer_locked ? 'disabled="disabled"' : '' ?>>
                                                                <?php if($customers->count()) : ?>
                                                                <?php foreach($customers as $customer) : ?>
                                                                <option value="{{ $customer->id }}" {{ is_selected($customer->id, old('customer_id', $detail->customer_id)) }}>{{ $customer->getFullNameAttribute() }} - {{ $customer->email }}</option>
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
                                                                        <input class="form-control" type="text" name="first_name" value="{{ old('first_name', $detail->first_name) }}" <?php echo $customer_locked ? 'readonly="readonly"' : '' ?>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Last name *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control" type="text" name="last_name" value="{{ old('last_name', $detail->last_name) }}" <?php echo $customer_locked ? 'readonly="readonly"' : '' ?>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Contact number</label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control" type="tel" name="contact_number" value="{{ old('contact_number', $detail->contact_number) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Email *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input class="form-control" type="email" name="email" value="{{ old('email', $detail->email) }}" <?php echo $customer_locked ? 'readonly="readonly"' : '' ?>>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Order number</label>
                                                                <input class="form-control" type="text" name="order_number" value="{{ old('order_number', $detail->order_number) }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Order notes</label>
                                                                <div class="form-control-wrap">
                                                                    <textarea class="form-control" name="notes" id="notes">{{ old('notes', $detail->notes) }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php if($users = get_users()) : ?>
                                                                <label class="form-label" for="notes">Picked by </label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="picked_by_id" id="picked-by-id" data-search="on">
                                                                        <option value="" selected="selected">Select staff member</option>
                                                                        <?php foreach($users as $user) : ?>
                                                                        <option value="{{ $user->id }}" {{ is_selected($user->id, old('picked_by_id', $detail->picked_by_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <?php else : ?>
                                                                <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php if($users = get_users()) : ?>
                                                                <label class="form-label" for="notes">Packaged by </label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-select" name="packed_by_id" id="packed-by-id" data-search="on">
                                                                        <option value="" selected="selected">Select staff member</option>
                                                                        <?php foreach($users as $user) : ?>
                                                                        <option value="{{ $user->id }}" {{ is_selected($user->id, old('packed_by_id', $detail->packed_by_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <?php else : ?>
                                                                <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <?php if($users = get_users()) : ?>
                                                        <label class="form-label" for="notes">Approved by </label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="packed_approved_id" id="packed-approved-id" data-search="on">
                                                                <option value="" selected="selected">Select staff member</option>
                                                                <?php foreach($users as $user) : ?>
                                                                <option value="{{ $user->id }}" {{ is_selected($user->id, old('packed_approved_id', $detail->packed_approved_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php
                                                        if($detail->customer_id)
                                                        {
                                                            // load customer addresses
                                                            $customer = get_customer($detail->customer_id);
                                                            $customer_addresses = $customer->addresses;
                                                        }
                                                    ?>

                                                    <div class="row mt-5 mb-5">
                                                        <div id="billing-address-details" class="col-md-6 address">

                                                            <div class="mb-3">
                                                                <h6>Billing address <a href="#" class="copy-address-to-shipping" data-toggle="tooltip" data-placement="top" title="Copy to shipping"><em class="icon ni ni-swap"></em></a>
                                                                    <a href="#" id="change-billing-address" class="btn btn-sm btn-outline-primary float-right">Change billing address</a>
                                                                </h6>
                                                            </div>

                                                            <div class="address-container">

                                                                <div class="card card-bordered new-billing-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                    <div class="card-inner">
                                                                        <?php if(isset($customer_addresses) && count($customer_addresses)) : ?>
                                                                            <div class="form-group">
                                                                                <label class="form-label">Select a saved billing address</label>
                                                                                <select class="form-select" id="billing_id">
                                                                                    <option>Choose address</option>
                                                                                    <?php foreach($customer_addresses as $customer_address) : if($customer_address->status != 'active') { continue; } ?>
                                                                                        <option value="{{ $customer_address->id }}" data-organisation="{{ $customer_address->title }}" data-line-1="{{ $customer_address->line1 }}" data-line-2="{{ $customer_address->line2 }}" data-line-3="{{ $customer_address->line3 }}" data-county="{{ $customer_address->county }}" data-city="{{ $customer_address->city }}" data-country="{{ $customer_address->country }}" data-lat="{{ $customer_address->lat }}" data-lng="{{ $customer_address->lng }}" data-postcode="{{ $customer_address->postcode }}">
                                                                                            {{ $customer_address->line1.' '.$customer_address->line2.' '.$customer_address->line3.' '.$customer_address->city.' '.$customer_address->postcode }}
                                                                                        </option>
                                                                                    <?php endforeach; ?>
                                                                                </select>

                                                                                <h5 class="mt-3">OR</h5>
                                                                            </div>

                                                                        <?php endif;  ?>


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
                                                                        <input class="form-control title" type="text" name="billing_address_data[title]" value="{{ isset($detail->billing_address_data['title']) ? $detail->billing_address_data['title'] : ''}}">
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 1 *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line1" type="text" name="billing_address_data[line1]" value="{{ isset($detail->billing_address_data['line1']) ? $detail->billing_address_data['line1'] : ''}}" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 2</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line2" type="text" name="billing_address_data[line2]" value="{{ isset($detail->billing_address_data['line2']) ? $detail->billing_address_data['line2'] : ''}}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 3</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line3" type="text" name="billing_address_data[line3]" value="{{ isset($detail->billing_address_data['line3']) ? $detail->billing_address_data['line3'] : ''}}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">City *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control city" type="text" name="billing_address_data[city]" value="{{ isset($detail->billing_address_data['city']) ? $detail->billing_address_data['city'] : '' }}" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Postcode *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control postcode" type="text" name="billing_address_data[postcode]" value="{{ isset($detail->billing_address_data['postcode']) ? $detail->billing_address_data['postcode'] : '' }}" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">County</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control county" type="text" name="billing_address_data[county]" value="{{ isset($detail->billing_address_data['county']) ? $detail->billing_address_data['county'] : '' }}" >
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
                                                                <input class="lat" type="hidden" name="billing_address_data[lat]" value="{{ isset($detail->billing_address_data['lat']) ? $detail->billing_address_data['lat'] : ''}}">
                                                                <input class="lng" type="hidden" name="billing_address_data[lng]" value="{{ isset($detail->billing_address_data['lng']) ? $detail->billing_address_data['lng'] :  ''}}">
                                                            </div>

                                                        </div>

                                                        <div id="shipping-address-details" class="col-md-6 address">

                                                            <div class="mb-3">
                                                                <h6>Delivery address <a href="#" class="copy-address-to-billing" data-toggle="tooltip" data-placement="top" title="Copy to billing"><em class="icon ni ni-swap"></em></a>
                                                                    <a href="#" id="change-delivery-address" class="btn btn-sm btn-outline-primary float-right">Change shipping address</a>
                                                                </h6>
                                                            </div>

                                                            <div class="address-container">

                                                                <div class="card card-bordered new-delivery-address mt-4 mb-3 bg-light-grey" style="display: none">
                                                                    <div class="card-inner">
                                                                        <?php if(isset($customer_addresses)) : ?>
                                                                        <div class="form-group">
                                                                            <label class="form-label">Select a saved delivery address</label>
                                                                            <select class="form-select" id="delivery_id">
                                                                                <option>Choose address</option>
                                                                                <?php foreach($customer_addresses as $customer_address) : if($customer_address->status != 'active') { continue; } ?>
                                                                                <option value="{{ $customer_address->id }}" data-organisation="{{ $customer_address->title }}" data-line-1="{{ $customer_address->line1 }}" data-line-2="{{ $customer_address->line2 }}" data-line-3="{{ $customer_address->line3 }}" data-county="{{ $customer_address->county }}" data-city="{{ $customer_address->city }}" data-country="{{ $customer_address->country }}" data-lat="{{ $customer_address->lat }}" data-lng="{{ $customer_address->lng }}" data-postcode="{{ $customer_address->postcode }}">
                                                                                    {{ $customer_address->line1.' '.$customer_address->line2.' '.$customer_address->line3.' '.$customer_address->city.' '.$customer_address->postcode }}
                                                                                </option>
                                                                                <?php endforeach; ?>
                                                                            </select>

                                                                            <h5 class="mt-3">OR</h5>
                                                                        </div>

                                                                        <?php endif;  ?>


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
                                                                        <input class="form-control title" type="text" name="delivery_address_data[title]" value="{{ isset($detail->delivery_address_data['title']) ? $detail->delivery_address_data['title'] : ''}}">
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 1 *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line1" type="text" name="delivery_address_data[line1]" value="{{ isset($detail->delivery_address_data['line1']) ? $detail->delivery_address_data['line1'] : ''}}"  required>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 2</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line2" type="text" name="delivery_address_data[line2]" value="{{ isset($detail->delivery_address_data['line2']) ? $detail->delivery_address_data['line2'] : ''}}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Line 3</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control line3" type="text" name="delivery_address_data[line3]" value="{{ isset($detail->delivery_address_data['line3']) ? $detail->delivery_address_data['line3'] : ''}}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">City *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control city" type="text" name="delivery_address_data[city]" value="{{ isset($detail->delivery_address_data['city']) ? $detail->delivery_address_data['city'] : '' }}" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Postcode *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control postcode" type="text" name="delivery_address_data[postcode]" value="{{ isset($detail->delivery_address_data['postcode']) ? $detail->delivery_address_data['postcode'] : '' }}"  required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">County</label>
                                                                            <div class="form-control-wrap">
                                                                                <input class="form-control county" type="text" name="delivery_address_data[county]" value="{{ isset($detail->delivery_address_data['county']) ? $detail->delivery_address_data['county'] : '' }}">
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
                                                                <input class="lat" type="hidden" name="delivery_address_data[lat]" value="{{ isset($detail->delivery_address_data['lat']) ? $detail->delivery_address_data['lat'] : ''}}">
                                                                <input class="lng" type="hidden" name="delivery_address_data[lng]" value="{{ isset($detail->delivery_address_data['lng']) ? $detail->delivery_address_data['lng'] :  ''}}">
                                                            </div>

                                                        </div>

                                                    </div>


                                                    <div class="form-group">
                                                        <?php if($order_statuses = sales_order_statuses()) : ?>
                                                        <label class="form-label" for="notes">Status *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="sales_order_status_id" id="sales-order-status-id" data-search="on">
                                                                <?php foreach($order_statuses as $status) : ?>
                                                                <?php
                                                                    if($status->id == 1 && $detail->invoices->count()) { continue; } // quote
                                                                    if($status->id == 9 && $detail->invoices->count()) { continue; } // expired
                                                                ?>
                                                                <option value="{{ $status->id }}" {{ is_selected($status->id, old('sales_order_status_id', $detail->sales_order_status_id)) }}>{{ $status->title }}</option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <?php else : ?>
                                                        <div class="alert alert-warning">Please <a href="#">create a new order status</a></div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="form-group" id="cancellation-reason" style="display: {{ $detail->sales_order_status_id == 8 ? 'block' : 'none' }}">
                                                        <label class="form-label" for="notes">Cancellation reason *</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control" name="cancelled_reason" id="cancelled-reason" {{ $detail->sales_order_status_id == 8 ? 'required' : '' }}>{{ old('cancelled_reason', $detail->cancelled_reason) }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div id="status-warning" style="display:{{ !in_array($detail->sales_order_status_id,[1,2]) ? 'block' : 'none' }}" class="alert alert-info"><b><em class="icon ni ni-alert"></em> Warning :</b> customer details can only be changed if the order status is Quote / Pending Payment and the order type is not Online.</div>

                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" name="is_urgent" id="is-urgent" value="1" {{ is_checked('1', old('is_urgent', $detail->is_urgent)) }}>
                                                            <label class="custom-control-label" for="is-urgent">Is urgent</label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div><!-- end Details -->

                                            <!-- Build products -->
                                            <div id="tab-order-items" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content tb-col-lg">
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-primary-dim"><em class="icon ni ni-package"></em> Not dispatched</span></p>
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-info"><em class="icon ni ni-package"></em> Part dispatched / collected</span></p>
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-success"><em class="icon ni ni-package"></em> Dispatched / Collected</span></p>
                                                        </div>

                                                        <div class="nk-block-head-content tb-col-lg">
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-primary-dim"><em class="icon ni ni-cc-alt"></em> Not invoiced</span></p>
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-info"><em class="icon ni ni-cc-alt"></em> Part invoiced</span></p>
                                                            <p class="mb-1"><span style="font-size: 14px;" class="text-success"><em class="icon ni ni-cc-alt"></em> Invoiced</span></p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <p class="mb-1" style="font-size: 14px;" data-toggle="tooltip" data-placement="top" title="Actual stock regardless of pending changes"><span class="badge badge-dot badge-success">Actual stock</span></p>
                                                            <p class="mb-1" style="font-size: 14px;" data-toggle="tooltip" data-placement="top" title="Actual stock -/+ pending changes i.e paid sales that are not dispatched / returns received but not placed back on the shelf"><span class="badge badge-dot badge-warning">Provisionally available stock</span></p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add item</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->items) && $detail->items->count() ) : ?>
                                                <form id="dispatch-items-form" method="POST" action="{{ url('load-items-for-dispatch') }}">
                                                    <input type="hidden" name="sales_order_id" value="{{ $detail->id }}">
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
                                                                <th>Options</th>
                                                                <th class="tb-col-lg">Weight</th>
                                                                <th>Price</th>
                                                                <th class="tb-col-lg">Status</th>
                                                                <th>Stock</th>
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

                                                                    // for now items can only be invoiced the total of their qty
                                                                    $invoiced = false;
                                                                    if(isset($item->invoice) && $item->invoice->count())
                                                                    {
                                                                        $invoiced = true;
                                                                        $total_invoiced += $item->qty;
                                                                    }

                                                                    $stock = false;
                                                                    $production_order_link = false;
                                                                    if(isset($item->product) && $item->product->count())
                                                                    {
                                                                        if($item->product->is_manufactured) {
                                                                            $production_order_link = true;
                                                                        }

                                                                        $stock = stock_level($item->product_id);
                                                                    }

                                                                    $vat_returned = false;
                                                                    if(isset($item->invoice) && $item->invoice->vat_return_id) {
                                                                        $vat_returned = true;
                                                                    }
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
                                                                    <td>Option here</td>
                                                                    <td class="tb-col-lg">{{ $uom->id ? number_format($item->weight).' '.$uom->title : ' NA ' }}</td>
                                                                    <td>£{{ number_format($item->gross_cost, 2, '.',',') }}</td>
                                                                    <td class="tb-col-lg"><?php if($dispatched) : ?>
                                                                        {!! ($dispatched >= $item->qty) ? '<em class="icon ni ni-package text-success" data-toggle="tooltip" data-placement="top" title="Dispatched / Collected"></em>' : '<em class="icon ni ni-package text-info" data-toggle="tooltip" data-placement="top" title="Part dispatched / collected"></em>' !!}
                                                                        <?php else : ?>
                                                                        <em class="icon ni ni-package text-primary-dim" data-toggle="tooltip" data-placement="top" title="Nothing dispatched / collected"></em>
                                                                        <?php endif; ?>
                                                                        | {!! $invoiced ? '<em class="icon ni ni-cc-alt text-success" data-toggle="tooltip" data-placement="top" title="Invoiced"></em>' : '<em class="icon ni ni-cc-alt text-primary-dim" data-toggle="tooltip" data-placement="top" title="Not invoiced"></em>' !!}
                                                                    </td>
                                                                    <td>
                                                                        <?php if(!$item->is_additional_shipping) : ?>
                                                                            <?php if($stock === false) : ?>
                                                                                None stock item
                                                                            <?php else : ?>
                                                                                <?php

                                                                                    if($stock->actual === 0)
                                                                                    {
                                                                                        if($item->product->is_manufactured)
                                                                                        {
                                                                                            echo '<a href="#" data-toggle="modal" data-target="#modalCreateProductionOrder" class="text-primary">Create Production Order</a>';
                                                                                        }
                                                                                        else {
                                                                                            echo '<a href="#" data-toggle="modal" class="text-primary">Create Purchase Order</a>';
                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        echo '<span class="badge badge-dot badge-success mr-3"><b>'.$stock->actual.'</b></span>';
                                                                                        echo '<span class="badge badge-dot badge-warning"><b>'.$stock->pending.'</b></span>';
                                                                                    }

                                                                                ?>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="tb-odr-action">
                                                                        <div class="dropdown">
                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                <ul class="link-list-plain">
                                                                                    <li><a href="{{ url('products/'.$item->product_id) }}" target="_blank" class="text-primary">View</a></li>
                                                                                    <?php if(!$detail->is_paid && ($item->qty-$dispatched) && !$vat_returned) : ?>
                                                                                    <li><a href="#" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                                    <?php endif; ?>
                                                                                    <?php if($production_order_link) : ?>
                                                                                        <?php if($stock && $stock->actual <= 1) : ?>
                                                                                            <li><a href="#" data-toggle="modal" data-target="#modalCreateProductionOrder" class="text-primary">Create Production Order</a></li>
                                                                                        <?php endif; ?>
                                                                                    <?php else : ?>
                                                                                        <li><a href="#" data-toggle="modal" class="text-primary">Create Purchase Order</a></li>
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
                                                                <?php if(!in_array($detail->sales_order_status_id,[1,7,8])) : ?>

                                                                    <?php if($total_qty_dispatched >= ($total_qty-$total_additional_shipping)) : ?>
                                                                        <a href="#" class="btn btn-white btn-success disabled" disabled><em class="icon ni ni-send"></em><span>All items dispatched</span></a>
                                                                    <?php else : ?>
                                                                        <a href="#" id="dispatch-btn" class="btn btn-white btn-primary disabled" data-toggle="modal" data-target="#modalDispatch" disabled><em class="icon ni ni-send"></em><span>Dispatch items</span></a>
                                                                    <?php endif; ?>

                                                                    <?php if($total_invoiced >= $total_qty) : ?>
                                                                        <a href="#" class="btn btn-white btn-success disabled" disabled><em class="icon ni ni-cc-alt"></em><span>All items invoiced</span></a>
                                                                    <?php elseif($total_invoiced > 0) : ?>
                                                                        <a href="#" class="btn btn-white btn-primary" data-toggle="modal" data-target="#modalInvoice"><em class="icon ni ni-cc-alt"></em><span>Invoice remaining items</span></a>
                                                                    <?php else : ?>
                                                                        <a href="#" class="btn btn-white btn-primary" data-toggle="modal" data-target="#modalInvoice"><em class="icon ni ni-cc-alt"></em><span>Invoice items</span></a>
                                                                    <?php endif; ?>

                                                                <?php else : ?>

                                                                    <a href="#" class="btn btn-white btn-primary disabled" disabled><span>Invoice and Dispatch not possible with current status {{ $detail->status->title }}</span></a>

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

                                                <div class="alert alert-warning">There are no items on this sales order <a href="{{ url('products/create') }}" data-toggle="modal" data-target="#modalCreate">add item</a></div>

                                                <?php endif; ?>

                                            </div><!-- end Build Products -->

                                            <!-- Invoices -->
                                            <div id="tab-invoices" style="display:none;" class="tab card">

                                                <?php if($detail->invoices && $detail->invoices->count() ) : ?>

                                                    <div class="card card-bordered card-preview">
                                                        <table class="table">
                                                            <thead class="thead-light">
                                                            <th>Invoice #</th>
                                                            <th>Items</th>
                                                            <th>Net</th>
                                                            <th>VAT</th>
                                                            <th>Gross</th>
                                                            <th>Status</th>
                                                            <th></th>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach($detail->invoices as $item) : ?>
                                                            <tr>
                                                                <td><a href="{{ url('invoices/detail/'.$item->id) }}" target="_blank">#{{ $item->id }}</a></td>
                                                                <td><b><a href="#" data-toggle="modal" data-target="#modalInvoiceItems" data-invoice-id="{{ $item->id }}">{{ $item->items->count() }}</a></b></td>
                                                                <td>£{{ number_format($item->net_cost, 2, '.',',') }}</td>
                                                                <td>£{{ number_format($item->vat_cost, 2, '.',',') }}</td>
                                                                <td>£{{ number_format($item->gross_cost, 2, '.',',') }}</td>
                                                                <td>
                                                                    <?php if($item->status) : ?>
                                                                        <span class="badge badge-dot badge-{{$item->status->classes}} mr-3"><b>{{$item->status->title}}</b></span>
                                                                    <?php else : ?>
                                                                        <span class="badge badge-dot badge-default"><b>NA</b></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="tb-odr-action">
                                                                    <div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                <li><a href="{{ url('invoices/detail/'.$item->id) }}" target="_blank" class="text-primary">View</a></li>
                                                                                <li><a href="#" data-dismiss="modal" data-invoice-id="{{ $item->id }}" data-toggle="modal" data-target="#modalEmailInvoice" class="text-primary">Email invoice</a></li>
                                                                                <li><a href="{{ url('invoices/print/'.$item->id) }}" target="_blank" class="text-primary">Print invoice</a></li>
                                                                                <li><a href="{{ url('invoices/download/'.$item->id) }}" class="text-primary">Download invoice</a></li>
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

                                                    <div class="alert alert-warning">There are no invoices</div>

                                                <?php endif; ?>

                                            </div><!-- end Invoices -->

                                            <!-- Invoices -->
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
                                                                        <a href="{{ url('salesorders/commercial-invoice/'.$dispatch->id) }}" class="btn btn-white btn-outline-light" target="_blank">
                                                                            <em class="icon ni ni-printer"></em> Commercial Invoice
                                                                        </a>
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

                                            </div><!-- end Invoices -->

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
                                                                        <a href="#" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $detail->title }}"><em class="icon ni ni-copy"></em></a>

                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-account-info py-0">
                                                                            <h6 class="overline-title-alt">Pricing</h6>
                                                                            <div class="user-balance preview-gross-cost">&pound;{{ number_format($detail->gross_cost, 2, '.',',') }} </div>
                                                                            <div class="user-balance-sub">NET <span class="text-primary preview-net-cost">&pound;{{ number_format($detail->net_cost, 2, '.',',') }}</span></div>
                                                                            <div class="user-balance-sub">VAT {{ isset($detail->vatType) ? '@'.$detail->vatType->title : '' }} <span class="preview-vat-cost">&pound;{{ number_format($detail->vat_cost+$detail->shipping_vat, 2, '.',',') }} </span></div>
                                                                            <div class="user-balance-sub">Shipping <span class="preview-vat-cost">&pound;{{ number_format($detail->shipping_cost, 2, '.',',') }} </span></div>

                                                                            <?php if($detail->discount_cost) : ?>
                                                                            <div class="user-balance-sub">Discount -<span class="preview-vat-cost">&pound;{{ number_format($detail->discount_cost, 2, '.',',') }} </span></div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-order-items"><em class="icon ni ni-grid-plus"></em><span>Items on order</span></a></li>
                                                                            <li><a href="#tab-invoices"><em class="icon ni ni-cc-alt"></em><span>Invoices</span></a></li>
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
                <form method="post" action="{{ url('store-sales-item') }}#tab-order-items" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="sales_order_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>


                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input name="none_stock_item" type="checkbox" class="custom-control-input" id="add-none-stock-item" value="1">
                            <label class="custom-control-label" for="add-none-stock-item"> Check to sell none stock product</label>
                        </div>
                    </div>

                    <div class="form-group" id="select-product">
                        <label class="form-label" for="notes">Product *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="product_id" id="add-product-id" data-search="on" required>
                                <option value="" selected="selected">Select a product</option>
                                <?php foreach($products as $product) : ?>
                                <option value="{{ $product->id }}" data-is-training="{{ $product->is_training }}">{{ $product->title }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="typein-product" style="display: none">
                        <label class="form-label" for="notes">Product *</label>
                        <div class="form-control-wrap">
                            <input type="text" name="product_title" id="add-product-title" class="form-control" placeholder="What is it your selling?" disabled required>
                        </div>
                    </div>

                    <div id="add-training-box" class="mb-4" style="display: none">

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

                    <p id="original-cost" style="display: none">Original item cost <mark>£0.00</mark></p>


                    <input type="hidden" id="add-original-net-cost" value="0">
                    <input type="hidden" id="add-original-sale-cost" value="0">

                    <div id="add-paying-deposit-box" class="form-group" style="display: none">

                    </div>

                    <div class="form-group">
                        <label class="form-label">Item cost *</label>
                        <div class="form-control-wrap">
                            <input id="add-item-cost" name="item_cost" type="text" class="form-control" value="" required>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Net cost</label>
                                <div class="form-control-wrap">
                                    <input id="add-net-cost" readonly type="text" name="net_cost" class="form-control" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Net saving value</label>
                                <div class="form-control-wrap">
                                    <input id="add-discount-cost" readonly type="text" name="discount_cost" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <p id="sale-saving" style="display: none">Net saving of <mark>0%</mark></p>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">VAT</label>
                                <div class="form-control-wrap">
                                    <input id="add-vat-cost" readonly type="text" name="vat_cost" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
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

{{-- Create Production Order Modal --}}
<div class="modal fade" tabindex="-1" id="modalCreateProductionOrder">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.create-production-order',['products'=>$products]) }}
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
                <form method="post" action="{{ url('dispatches') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="sales_order_id" value="{{ $detail->id }}">
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
                            <label class="custom-control-label" for="dispatch-is-collection"> Customer is collecting</label>
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

                    <?php if($boxes = get_boxes()) : ?>
                    <div class="card card-bordered">
                        <div class="card-header bg-primary px-3 py-1 text-light">Boxes used</div>
                        <div class="px-3 py-2">
                            <div class="dispatch-block">
                                <div class="row box-row mb-2">
                                    <div class="col-md-8">
                                        <div class="form-control-wrap">
                                            <select class="form-select" name="box_ids[]" id="0-add-boxes" data-search="on" required>
                                                <?php foreach($boxes as $box) : ?>
                                                <option value="{{ $box->id }}">{{ $box->title }}</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-control-wrap">
                                            <input type="number" name="box_qtys[]" class="form-control" value="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-0 pt-1">
                                <a href="#" class="btn btn-add-dispatch-row btn-primary btn-sm">Add row</a>
                            </div>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="alert alert-warning">No boxes added, please create a new product and mark it as a box</div>
                    <?php endif; ?>

                    <?php if($pallets = get_pallets()) : ?>
                    <div class="card card-bordered mt-3 mb-3">
                        <div class="card-header bg-primary px-3 py-1 text-light">Pallets used</div>
                        <div class="px-3 py-2">
                            <div class="dispatch-block">
                                <div class="row pallet-row mb-2">
                                    <div class="col-md-8">
                                        <div class="form-control-wrap">
                                            <select class="form-select" name="box_ids[]" id="0-add-pallets" data-search="on" required>
                                                <?php foreach($pallets as $pallet) : ?>
                                                <option value="{{ $pallet->id }}">{{ $pallet->title }}</option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-control-wrap">
                                            <input type="number" name="box_qtys[]" class="form-control" value="0" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-0 pt-1">
                                <a href="#" class="btn btn-add-dispatch-row btn-primary btn-sm">Add row</a>
                            </div>
                        </div>
                    </div>
                    <?php else : ?>
                    <div class="alert alert-warning">No pallets added, please create a new product and mark it as a pallet</div>
                    <?php endif; ?>

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

                        <div class="col-md-6">
                            <div class="form-group">
                                <?php if($users = get_users()) : ?>
                                <label class="form-label" for="notes">Loaded by</label>
                                <div class="form-control-wrap">
                                    <select class="form-select" name="loaded_by" id="dispatch-loaded-by" data-search="on">
                                        <option value="" selected="selected">Select staff member</option>
                                        <?php foreach($users as $user) : ?>
                                        <option value="{{ $user->id }}" {{ is_selected($user->id, old('packed_approved_id', $detail->packed_approved_id)) }}>{{ $user->getFullNameAttribute() }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php else : ?>
                                <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                                <?php endif; ?>
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

                    <div class="alert alert-info">Please note : once you click save stock will be removed from the system.</div>

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

<div class="modal fade" tabindex="-1" id="modalInvoice">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice items</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php if( $detail->items ) : ?>
                <form method="post" action="{{ url('invoices') }}" id="create-form" class=" form-validate">
                    @csrf
                    <input type="hidden" name="sales_order_id" value="{{ $detail->id }}">
                    <div class="alert alert-warning" id="invoice-status" style="display: none"></div>


                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input name="email_invoice" type="checkbox" class="custom-control-input" id="email-invoice" value="1" checked>
                            <label class="custom-control-label" for="email-invoice"> Email invoice immediately</label>
                        </div>
                    </div>

                    <div class="form-group" id="email-for-invoice">
                        <label class="form-label" for="notes">Email address for invoice</label>
                        <div class="form-control-wrap">
                            <input type="text" name="invoice_email" class="form-control" value="{{ $detail->email }}" required>
                        </div>
                    </div>

                    <!-- offer user to add additional shipping line item -->
                    <?php if($detail->shipping_invoice) : ?>
                        <div class="alert alert-info">Original shipping for this order has been invoiced on invoice #{{$detail->shipping_invoice}}, you can add additional shipping to this invoice.</div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input name="additional_shipping" type="checkbox" class="custom-control-input" id="additional-shipping" value="1">
                                <label class="custom-control-label" for="additional-shipping"> Add additional shipping</label>
                            </div>
                        </div>

                        <div id="additional-shipping-details" class="row" style="display: none">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Additional shipping</label>
                                    <div class="form-control-wrap">
                                        <input id="add-additional-shipping-cost" disabled type="text" name="additional_shipping_cost" class="form-control" value="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">VAT</label>
                                    <div class="form-control-wrap">
                                        <select id="add-additional-shipping-vat" name="shipping_vat_type_id" class="form-select form-control" data-search="on" required>
                                            <?php if($vat_types = vat_types()) : ?>
                                            <?php foreach($vat_types as $vats) : ?>
                                            <option value="{{ $vats->id }}" data-value="{{ $vats->value }}">{{ $vats->title }}</option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Gross</label>
                                    <div class="form-control-wrap">
                                        <input id="add-additional-shipping-gross-cost" readonly type="text"class="form-control" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>

                        {!! $detail->order_type_id === 1 ? '<div class="alert alert-warning">Online order can\'t modify original shipping</div>' : '' !!}

                         <div class="row">
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label class="form-label">Shipping cost</label>
                                     <div class="form-control-wrap">
                                         <input id="add-additional-shipping-cost" name="shipping_cost" {{ $detail->order_type_id === 1 ? 'readonly' : '' }} type="text" class="form-control" value="{{ $detail->shipping_cost }}">
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label class="form-label">VAT</label>
                                     <div class="form-control-wrap">
                                         <select id="add-additional-shipping-vat" name="shipping_vat_type_id" class="form-select form-control" data-search="on" required>
                                             <?php if($vat_types = vat_types()) : ?>
                                             <?php foreach($vat_types as $vats) : ?>
                                             <option value="{{ $vats->id }}" data-value="{{ $vats->value }}">{{ $vats->title }}</option>
                                             <?php endforeach; ?>
                                             <?php endif; ?>
                                         </select>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label class="form-label">Gross</label>
                                     <div class="form-control-wrap">
                                         <input id="add-additional-shipping-gross-cost" readonly type="text" class="form-control" value="{{ $detail->shipping_gross }}">
                                     </div>
                                 </div>
                             </div>
                         </div>

                    <?php endif; ?>

                    <div class="mt-3 mb-3">
                        <table class="table card-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach($detail->items as $item) : if($item->invoice_id) { continue; } ?>
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" name="invoice[{{$item->id}}]" value="1" class="custom-control-input checkbox-item" id="dispatch-item-{{$item->id}}" checked>
                                                <label class="custom-control-label" for="dispatch-item-{{$item->id}}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $item->product_title }}</td>
                                        <td>£{{ number_format($item->gross_cost, 2, '.',',') }}</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
                <?php else : ?>
                <div class="alert alert-warning">You dont have any items on this order, <a data-toggle="modal" data-target="#modalCreate" href="{{ url('products/create') }}">add an item</a></div>
                <?php endif; ?>
            </div>
            {{--<div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>--}}
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="modalEmailInvoice">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-invoice', ['invoice_id' => null,'email' => $detail->email]) }}
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modalEmailQuote">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-quote', ['id' => $detail->id,'email' => $detail->email]) }}
    </div>
</div>

<?php if( $detail->invoices->count() ) : ?>
<div class="modal fade" tabindex="-1" id="modalInvoiceItems">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Items on invoice #<span></span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php foreach($detail->invoices as $invoice) : ?>
                    <div id="items-on-invoice-{{ $invoice->id }}" class="items-on-invoice" style="display: none">
                        <table class="table card-bordered">
                            <thead class="thead-dark">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invoice->items as $item) : ?>
                                <tr>
                                    <td>{{ $item->product_title }}</td>
                                    <td>{{ $item->qty }}</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if( $detail->invoices->count() ) : ?>
<div class="modal fade" tabindex="-1" id="modalInvoiceAction">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoices</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <table class="table card-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th>Invoice</th>
                        <th>Download</th>
                        <th>Email</th>
                        <th>Print</th>
                    </tr>
                    </thead>
                        <?php foreach($detail->invoices as $invoice) : ?>
                            <tr>
                                <td><a href="{{ url('') }}">#{{ $invoice->id }}</a></td>
                                <td><a href="{{ url('invoices/download/'.$invoice->id) }}" target="_blank" class="btn btn-sm btn-white btn-primary"><em class="icon ni ni-download"></em><span>Download</span></a></td>
                                <td><a href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalEmailInvoice" data-invoice-id="{{ $invoice->id }}" class="btn btn-sm btn-white btn-outline-light"><em class="icon ni ni-mail"></em><span>Email</span></a></td>
                                <td><a href="{{ url('invoices/print/'.$invoice->id) }}" target="_blank" class="btn btn-sm btn-white btn-outline-light"><em class="icon ni ni-printer"></em><span>Print</span></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

{{ view('admin.templates.footer') }}
</body>
<script type="text/javascript">
    // Trigger modal from another page so user can select what invoice to print etc
    var show_email_invoice_modal = {{ request()->exists('email-invoices') ? 'true' : 'false' }};

    var boxes = false;
    var pallets = false;

    <?php if(isset($boxes)) : ?>
        boxes = [];
        <?php foreach($boxes as $box) : ?>
            boxes.push({id:<?=$box->id?>, title:'<?=$box->title?>'})
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(isset($pallets)) : ?>
        pallets = [];
        <?php foreach($pallets as $pallet) : ?>
            pallets.push({id:<?=$pallet->id?>, title:'<?=$pallet->title?>'})
        <?php endforeach; ?>
    <?php endif; ?>
</script>
<script type="text/javascript" src="{{ asset('assets/js/admin/sales-order.js') }}"></script>
</html>
