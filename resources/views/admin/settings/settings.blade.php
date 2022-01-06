<?php
    $form_locked = true;
    if(get_user()->hasRole('super-admin') || get_user()->hasRole('admin'))
    {
        $form_locked = false;
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

                            <div class="nk-block">
                                <div class="card card-bordered">
                                    <div class="card-aside-wrap">
                                        <div class="card-inner card-inner-lg">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">Settings</h4>
                                                        <div class="nk-block-des">
                                                        </div>
                                                    </div>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Details -->
                                            <div id="tab-details" class="tab card">

                                                <?php if($form_locked) : ?>
                                                    <div class="alert alert-info">Some fields require you to be an admin to edit them. Please speak to an admin to update fields blocked out.</div>
                                                <?php endif; ?>

                                                <form method="post" action="{{ url('settings/'.$detail->id) }}" id="detail-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Franchise title *</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" {{ $form_locked ? 'readonly' : '' }} class="form-control" name="title" value="{{ old('title', $detail->title) }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Code</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="code" value="{{ old('code', $detail->code) }}">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Contact name *</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $detail->contact_name) }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                           <div class="form-group">
                                                                <label class="form-label">General email *</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $detail->email) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">Contact number *</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="tel" class="form-control" name="contact_number" value="{{ old('contact_number', $detail->contact_number) }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">VAT Number</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="text" name="vat_number" {{ $form_locked ? 'readonly' : '' }} class="form-control" value="{{ old('vat_number', $detail->vat_number) }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">EORI Number (for exports)</label>
                                                                <div class="form-group-wrap">
                                                                    <input type="text" name="eori_number" {{ $form_locked ? 'readonly' : '' }} class="form-control" value="{{ old('eori_number', $detail->eori_number) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Company Registration Number *</label>
                                                        <div class="form-group-wrap">
                                                            <input type="number" name="company_number" {{ $form_locked ? 'readonly' : '' }} class="form-control" value="{{ old('company_number', $detail->company_number) }}" required>
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
                                                            <p>Main Address.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#" data-toggle="modal" data-target="#modalCreate" class="btn {{ (isset($detail->addresses) && $detail->addresses->count()) ? 'disabled' : '' }} btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Address</span></a>
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
                                                                                <form id="delete-adress-form-{{$address->id}}" action="{{ url('settings/address') }}" method="post">
                                                                                    @csrf
                                                                                    @method('delete')
                                                                                    <input type="hidden" name="address_id" value="{{$address->id}}">
                                                                                </form>
                                                                                <button form="delete-adress-form-{{$address->id}}" data-message="Are you sure you want to delete this address, this cant be undone" type="button" class="btn btn-block mt-3 btn-outline-danger destroy-resource"><span>Delete address</span></button>
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

                                            <!-- tab setting -->
                                            @role('super-admin')
                                            <div id="tab-settings" style="display:none;" class="tab card ">
                                                <livewire:admin.setting />
                                            </div>
                                            @endrole

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
                                                                                <span>JF</span>
                                                                            </div>
                                                                            <div class="user-info">
                                                                                <span class="lead-text">{{ $detail->title }}</span>
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
                                                                            <h6 class="overline-title-alt">Total Employees</h6>
                                                                            <div class="user-balance preview-gross-cost">{{ $detail->users->count() }}</div>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-addresses"><em class="icon ni ni-book"></em><span>Addresses</span></a></li>
                                                                            @role('super-admin')
                                                                                <li><a href="#tab-settings"><em class="icon ni ni-setting"></em><span>Settings</span></a></li>
                                                                            @endrole
                                                                            {{--<li><a href="#tab-orders"><em class="icon ni ni-cart"></em><span>Orders</span></a></li>
                                                                            <li><a href="#tab-media"><em class="icon ni ni-camera"></em><span>Media</span></a></li>--}}
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
                <form method="post" action="{{ url('settings/address') }}" id="create-form" class=" form-validate">
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
@endpush
{{ view('admin.templates.footer') }}
</body>


</html>
