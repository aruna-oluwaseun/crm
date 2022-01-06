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
                                                        <h4 class="nk-block-title">{{ $detail->getFullNameAttribute() }}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                Customer created at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
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

                                                <form method="post" action="{{ url('customers/'.$detail->id) }}" id="detail-form" class=" form-validate">
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

                                                   <div class="form-group">
                                                        <label class="form-label">Email (shop(s) username)</label>
                                                        <div class="form-group-wrap">
                                                            <input type="email" name="email" class="form-control" value="{{ old('email', $detail->email) }}" required>
                                                        </div>
                                                       <!--<p class="mt-2">
                                                           <?php if($detail->email_verified_at) : ?>
                                                           Last on <b>{{ date('dS F Y H:ia', strtotime($detail->email_verified_at)) }}</b>.
                                                           <?php else : ?>
                                                           Email not verified : <a href="#">Send confirmation link</a>
                                                           <?php endif; ?>
                                                       </p>-->

                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Contact number 1</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="number" name="contact_number" class="form-control" value="{{ old('contact_number', $detail->contact_number) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Contact number 2</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="number" name="contact_number2" class="form-control" value="{{ old('contact_number2', $detail->contact_number2) }}">
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

                                                    <div id="customer-notes" class="form-group" style="display: none;">
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

                                            <!-- Addresses -->
                                            <div id="tab-addresses" style="display:none;" class="tab card ">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>Customers addresses.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Address</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(isset($detail->addresses) && $detail->addresses->count()) : ?>

                                                    <div class="row g-gs">
                                                    <?php foreach($detail->addresses as $address) : ?>
                                                        <div class="col-md-6 col-lg-4 mb-3">
                                                                <div class="card card-bordered">
                                                                    <div class="card-inner">
                                                                        <div class="team">
                                                                            <ul class="team-info mb-1">
                                                                                <div class="user-info">
                                                                                    <?php if($address->title) : ?>
                                                                                        <h6>{{$address->title}}</h6>
                                                                                    <?php endif; ?>
                                                                                    <span class="sub-text">Address</span>
                                                                                </div>
                                                                                <?php if($address->line1) : ?><li>{{$address->line1}}</li><?php endif; ?>
                                                                                <?php if($address->line2) : ?><li>{{$address->line2}}</li><?php endif; ?>
                                                                                <?php if($address->line3) : ?><li>{{$address->line3}}</li><?php endif; ?>
                                                                                <?php if($address->city) : ?><li>{{$address->city}}</li><?php endif; ?>
                                                                                <?php if($address->postcode) : ?><li>{{$address->postcode}}</li><?php endif; ?>
                                                                                <?php if($address->county) : ?><li>{{$address->county}}</li><?php endif; ?>
                                                                                <?php if($address->country) : ?><li>{{$address->country}}</li><?php endif; ?>
                                                                            </ul>
                                                                            <div class="team-view">
                                                                                <?php if(!$address->default_billing_address) : ?>
                                                                                    <a href="{{ url('customers/default-address/'.$detail->id.'/'.$address->id.'/billing#tab-addresses') }}" class="btn btn-block mt-3 btn-dim btn-primary"><span>Set default billing address</span></a>
                                                                                <?php else :  ?>
                                                                                    <p class="text-primary pt-2">Default billing address</p>
                                                                                <?php endif; ?>

                                                                                <?php if(!$address->default_shipping_address) : ?>
                                                                                    <a href="{{ url('customers/default-address/'.$detail->id.'/'.$address->id.'/shipping#tab-addresses') }}" class="btn btn-block mt-3 btn-dim btn-primary"><span>Set default shipping address</span></a>
                                                                                <?php else :  ?>
                                                                                    <p class="text-primary">Default shipping address</p>
                                                                                <?php endif; ?>

                                                                                <a href="{{ url('customers/destroy-address/'.$address->id.'#tab-addresses') }}" class="btn btn-block mt-3 btn-outline-danger destroy-btn"><span>Delete address</span></a>
                                                                            </div>
                                                                        </div><!-- .team -->
                                                                    </div><!-- .card-inner -->
                                                                </div><!-- .card -->
                                                            </div>

                                                    <?php endforeach; ?>
                                                    </div>

                                                <?php else : ?>

                                                    <div class="alert alert-warning">No addresses found. The customer can add these or you can add them on their behalf.</div>

                                                <?php endif; ?>
                                            </div>

                                            <!-- Orders -->
                                            <div id="tab-orders" style="display:none;" class="tab card">

                                                <?php if(isset($detail->orders) && $detail->orders->count()) : ?>

                                                    <div class="card card-bordered">
                                                        <table id="build-products" class="table table-orders">
                                                            <thead class="tb-odr-head">
                                                            <tr class="tb-odr-item">
                                                                <th class="tb-odr-info">
                                                                    <span class="tb-odr-id">#</span>
                                                                </th>
                                                                <th class="tb-odr-amount">
                                                                    <span class="tb-odr-total">Gross Cost</span>
                                                                    <span class="d-none d-md-inline-block">Invoiced</span>
                                                                </th>
                                                                <th class="tb-status">
                                                                    <span class="tb-odr-id">Status</span>
                                                                </th>
                                                                <th class="tb-odr-action">&nbsp;</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="tb-odr-body">
                                                            <?php foreach($detail->orders as $item) : ?>
                                                            <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                                <td class="tb-odr-info">
                                                                    <span class="tb-odr-id"><a href="{{ url('salesorders/'.$item->id) }}" target="_blank">{{ $item->id }}</a></span>
                                                                </td>
                                                                <td class="tb-odr-amount">
                                                                    <span class="tb-odr-total">
                                                                        <span class="amount">&pound;{{ number_format($item->gross_cost,2,'.',',') }}</span>
                                                                    </span>
                                                                    <span class="tb-odr-status">
                                                                        <?php if(isset($item->invoices) && count($item->invoices)) : ?>
                                                                            <?php if(count($item->invoices) > 1) : ?>
                                                                                {{ count($item->invoices) }} Invoices linked
                                                                            <?php else : ?>
                                                                                <a href="{{ url('invoices/'.$item->invoices[0]->id) }}" target="_blank">Invoice #{{$item->invoices[0]->id}}</a>
                                                                            <?php endif; ?>
                                                                        <?php else : ?>
                                                                            <span class="tb-status text-default">Not invoiced</span>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </td>
                                                                <td class="tb-status">
                                                                    <?php if($item->status) : ?>
                                                                        <span class="tb-status text-{{ $item->status->classes }}">{{ $item->status->title }}</span>
                                                                    <?php else :  ?>
                                                                        <span class="tb-status text-default">Draft</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="tb-odr-action">
                                                                    <div class="tb-odr-btns d-none d-md-inline">
                                                                        <a href="{{ url('salesorders/'.$item->id) }}" target="_blank" class="btn btn-sm btn-primary">View</a>
                                                                    </div>
                                                                    {{--<div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                <li><a href="{{ url('suppliers/'.$item->supplier_id) }}" target="_blank" class="text-primary">View supplier</a></li>
                                                                                <li><a href="{{ url('destroy-supplier-ref/'.$item->id) }}" data-async="true" class="text-danger destroy-btn">Remove</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>--}}
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>


                                                <?php else : ?>
                                                    <div class="alert alert-warning">Customers has not placed any orders. If you are looking for legacy orders <a href="{{ url('customers/legacy-orders') }}" class="text-primary">click here</a> </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Media -->
                                            <div id="tab-media" style="display:none;" class="tab card">

                                                <?php if(isset($detail->media)) : ?>

                                                <?php else : ?>

                                                <div class="alert alert-warning">You have not uploaded any media yet</div>

                                                <?php endif; ?>

                                            </div><!-- end Media -->

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
                                                                            <div class="user-avatar bg-primary">
                                                                                <span>{{ $detail->getInitialsAttribute() }}</span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="lead-text">{{ $detail->getFullNameAttribute() }}</span>
                                                                                <span class="sub-text">{{ $detail->email }}</span>
                                                                            </div>

                                                                            @can('delete-customers')
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
                                                                            @endcan
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-account-info py-0">
                                                                            <h6 class="overline-title-alt">Customer Spend</h6>
                                                                            <div class="user-balance preview-gross-cost">&pound;{{ number_format($detail->gross_sales, 2, '.',',') }} </div>
                                                                            <div class="user-balance-sub">NET <span class="text-primary preview-net-cost">&pound;{{ number_format($detail->net_sales, 2, '.',',') }}</span></div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-addresses"><em class="icon ni ni-book"></em><span>Addresses</span></a></li>
                                                                            <li><a href="#tab-orders"><em class="icon ni ni-cart"></em><span>Orders</span></a></li>
                                                                            <li><a href="#tab-media"><em class="icon ni ni-camera"></em><span>Media</span></a></li>
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

<table style="display: none" id="build-product-prototype">
    <tbody>
        <tr class="tb-odr-item remove-target" data-id="{ID}">
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
                    <a href="{{ url('destroy-product-child') }}/{ID}" data-async="true" class="btn btn-sm btn-danger destroy-btn">Remove</a>
                </div>
                <div class="dropdown">
                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                        <ul class="link-list-plain">
                            <li><a href="{{ url('products') }}/{PRODUCT_ID}" target="_blank" class="text-primary">View</a></li>
                            <li><a href="{{ url('destroy-product-child') }}/{ID}" class="text-danger destroy-btn">Remove</a></li>
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>

</table>

@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/admin/customer.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>


</html>
