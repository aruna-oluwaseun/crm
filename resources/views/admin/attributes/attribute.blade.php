<?php

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
                                            <h4 class="nk-block-title">Attribute Detail</h4>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        {{--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>--}}

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
                                                                Created <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
                                                                <?php if($detail->updated_by) : ?>
                                                                    Last updated on <b>{{ date('dS F Y H:ia', strtotime($detail->updated_at)) }}</b>.
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

                                                <form method="post" action="{{ url('attribute/'.$detail->id) }}" id="caetgory-detail-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Category *</label>
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
                                                                <label class="form-label" for="notes">Category Code</label>
                                                                <div class="form-control-wrap">
                                                                    <input type="text" class="form-control" name="code" value="{{ old('slug', $detail->code) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{--<div class="form-group">
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
                                                    </div>--}}

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>


                                            </div><!-- end Details -->

                                            <!-- Build products -->
                                            <div id="tab-products" style="display: none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>The following products are linked.</p>
                                                        </div>

                                                    </div>
                                                </div>

                                                <?php if(isset($detail->products) && $detail->products->count()) : ?>

                                                <?php
                                                    $products = $detail->products()->paginate(30)->fragment('tab-products');;
                                                ?>
                                                <div class="card card-bordered card-preview">
                                                    <table id="build-products" class="table table-orders">
                                                        <thead class="tb-odr-head">
                                                        <tr class="tb-odr-item">
                                                            <th class="tb-odr-info">
                                                                <span class="tb-odr-id">Product</span>
                                                            </th>
                                                            {{--<th class="tb-odr-amount">
                                                                <span class="tb-odr-total">Available</span>
                                                            </th>--}}
                                                            <th class="tb-odr-action">&nbsp;</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="tb-odr-body">

                                                        <?php foreach($products as $item) : $uom = uom($item->unit_of_measure_id); ?>
                                                        <tr class="tb-odr-item remove-target" data-id="{{$item->id}}">
                                                            <td class="tb-odr-info">
                                                                <span class="tb-odr-id"><a href="{{ url('products/'.$item->id) }}" target="_blank">{{ $item->title }}</a></span>
                                                            </td>
                                                            {{--<td class="tb-odr-amount">
                                                                <span class="tb-odr-total">
                                                                    <span class="amount">{{ $uom->id ? number_format($item->weight).' '.$uom->title : ' x '.$item->qty }}</span>
                                                                </span>
                                                            </td>--}}
                                                            <td class="tb-odr-action">
                                                                <div class="dropdown">
                                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                        <ul class="link-list-plain">
                                                                            <li><a href="{{ url('products/'.$item->id) }}" target="_blank" class="text-primary">View</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>

                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="mt-3">{{ $products->links() }}</div>
                                                <?php else : ?>

                                                <div class="alert alert-warning">You have not linked any products.</div>

                                                <?php endif; ?>

                                            </div><!-- end Build Products -->


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
                                                                    {{--<div class="card-inner">
                                                                        --}}{{--<a href="{{ url('products/create?id='.$detail->id) }}" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $detail->title }}"><em class="icon ni ni-copy"></em></a>--}}{{--

                                                                    </div>--}}<!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-card">
                                                                            <div class="user-account-info py-0">
                                                                                <h6 class="overline-title-alt">Linked Products</h6>
                                                                                <div class="user-balance preview-gross-cost">
                                                                                    {{ $detail->products->count() }}
                                                                                </div>
                                                                            </div>

                                                                            @can('delete-attributes')
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
                                                                                                    <button type="submit" form="delete-resource" data-message="Please note, all linked products will be unlinked, values created against this attribute will be unlinked" class="destroy-resource btn btn-block btn-sm btn-danger"><em class="icon ni ni-trash-fill"></em><span>Delete Attribute</span></button>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endcan
                                                                        </div>

                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Details</span></a></li>
                                                                            <li><a href="#tab-products"><em class="icon ni ni-grid-plus"></em><span>Linked Products</span></a></li>
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


@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/admin/attribute.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>


</html>
