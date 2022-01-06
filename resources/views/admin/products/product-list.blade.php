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
                                            <h4 class="nk-block-title">Products</h4>
                                            <p>Manage your products</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <p class="mb-1" style="font-size: 14px;" data-toggle="tooltip" data-placement="top" title="Actual stock regardless of pending changes"><span class="badge badge-dot badge-success">Actual stock</span></p>
                                            <p class="mb-1" style="font-size: 14px;" data-toggle="tooltip" data-placement="top" title="Actual stock -/+ pending changes i.e paid sales that are not dispatched / returns received but not placed back on the shelf"><span class="badge badge-dot badge-warning">Provisionally available stock</span></p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                        @can('create-products')
                                                            <li><a href="{{ url('products/create') }}" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Product</span></a></li>
                                                        @endcan
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

                                <div class="card card-preview">
                                    <div class="card-inner" data-select2-id="19">
                                        <form action="" method="get">
                                            <div class="card-title-group" data-select2-id="18">
                                                <div class="card-title">
                                                    <h5 class="title">All Orders</h5>
                                                    <?php if(isset($products) && $products->count()) : ?>
                                                        {!! '<span class="text-primary">'.$products->total().'</span> products found'  !!}
                                                    <?php else : ?>
                                                        0 Products found
                                                    <?php endif;?>
                                                </div>
                                                <div class="card-tools mr-n1" data-select2-id="17">
                                                    <ul class="btn-toolbar gx-1" data-select2-id="16">
                                                        {{--<li>
                                                            <a href="#" class="search-toggle toggle-search btn btn-icon" data-target="search"><em class="icon ni ni-search"></em></a>
                                                        </li>
                                                        <li class="btn-toolbar-sep"></li>--}}
                                                        <li data-select2-id="15">
                                                            <div class="dropdown" data-select2-id="14">
                                                                <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                                    <div class="badge badge-circle badge-primary">7</div><!-- Filters -->
                                                                    <em class="icon ni ni-filter-alt"></em><!-- Filter icon -->
                                                                </a>
                                                                <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                                    <div class="dropdown-head">
                                                                        <span class="sub-title dropdown-title">Filters</span>
                                                                    </div>
                                                                    <div class="dropdown-body dropdown-body-rg">
                                                                        <div class="row gx-6 gy-4">
                                                                            <?php if($categories = categories()) : ?>
                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label class="overline-title overline-title-alt">Category</label>
                                                                                    <select name="category" class="form-select form-select-sm" data-search="on">
                                                                                        <option value="all">All categories</option>
                                                                                        <?php foreach($categories as $category) : ?>
                                                                                        <option value="{{ $category->id }}" {{ is_selected($category->id, request()->input('category')) }}>{{ $category->title }}</option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <?php endif; ?>

                                                                            <?php if($product_types = product_types()) : ?>
                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <label class="overline-title overline-title-alt">Product type</label>
                                                                                    <select name="product_type_id" class="form-select form-select-sm" data-search="on">
                                                                                        <option value="all">All product types</option>
                                                                                        <?php foreach($product_types as $type) : ?>
                                                                                        <option value="{{ $type->id }}" {{ is_selected($type->id, request()->input('product_type_id')) }}>{{ $type->title }}</option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <?php endif; ?>

                                                                            <?php if($staff = users()) : ?>
                                                                                <div class="col-6">
                                                                                    <div class="form-group">
                                                                                        <label class="overline-title overline-title-alt">Created by</label>
                                                                                        <select name="created_by" class="form-select form-select-sm" data-search="on">
                                                                                            <option value="all">All staff</option>
                                                                                            <?php foreach($staff as $user) : ?>
                                                                                            <option value="{{ $user->id }}" {{ is_selected($user->id, request()->input('created_by')) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                                            <?php endforeach; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="form-group">
                                                                                        <label class="overline-title overline-title-alt">Last updated by</label>
                                                                                        <select name="updated_by" class="form-select form-select-sm" data-search="on">
                                                                                            <option value="all">All staff</option>
                                                                                            <?php foreach($staff as $user) : ?>
                                                                                            <option value="{{ $user->id }}" {{ is_selected($user->id, request()->input('updated_by')) }}>{{ $user->getFullNameAttribute() }}</option>
                                                                                            <?php endforeach; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label class="overline-title overline-title-alt">Status</label>
                                                                                    <select name="status" class="form-select form-select-sm">
                                                                                        <option value="all">All</option>
                                                                                        <option value="active" {{ is_selected('active', request()->input('type')) }}>Active</option>
                                                                                        <option value="disabled" {{ is_selected('disabled', request()->input('type')) }}>Disabled</option>

                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <div class="custom-control custom-control-sm custom-checkbox">
                                                                                        <input name="is_available_online" type="checkbox" class="custom-control-input" id="is-available-online" value="1" {{ is_checked(1,request()->input('is_available_online')) }}>
                                                                                        <label class="custom-control-label" for="is-available-online"> Is available online</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-6">
                                                                                <div class="form-group">
                                                                                    <div class="custom-control custom-control-sm custom-checkbox">
                                                                                        <input name="is_training" type="checkbox" class="custom-control-input" id="is-training" value="1" {{ is_checked(1,request()->input('is_training')) }}>
                                                                                        <label class="custom-control-label" for="is-training"> Is training product</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <input type="hidden" name="rows" value="{{ request()->input('rows') ?: 100  }}">
                                                                            <input type="hidden" name="sort" value="{{ request()->input('sort') ?: 'desc'  }}">
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dropdown-foot between">
                                                                        <a class="clickable" href="{{ url('products') }}">Reset Filters</a>
                                                                        {{--<a href="#savedFilter" data-toggle="modal">Save Filter</a>--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="dropdown">
                                                                <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                                                    <em class="icon ni ni-setting"></em> <!-- Settings icon -->
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                                                    <ul class="link-check">
                                                                        <li><span>Show</span></li>
                                                                        <li {!! request()->input('rows') == 10 ? 'class="active"' : '' !!}><a href="{{ url('products') }}?rows=10&{{ request()->getQueryString() }}">10</a></li>
                                                                        <li {!! request()->input('rows') == 20 ? 'class="active"' : '' !!}><a href="{{ url('products') }}?rows=20&{{ request()->getQueryString() }}">20</a></li>
                                                                        <li {!! request()->input('rows') == 50 ? 'class="active"' : '' !!}><a href="{{ url('products') }}?rows=50&{{ request()->getQueryString() }}">50</a></li>
                                                                        <li {!! (request()->input('rows') == 100 || !request()->exists('rows')) ? 'class="active"' : '' !!}><a href="{{ url('products') }}?rows=100&{{ request()->getQueryString() }}">100</a></li>
                                                                        <li {!! request()->input('rows') == 200 ? 'class="active"' : '' !!}><a href="{{ url('products') }}?rows=200&{{ request()->getQueryString() }}">200</a></li>
                                                                    </ul>
                                                                    <ul class="link-check">
                                                                        <li><span>Title</span></li>
                                                                        <li {!! (request()->input('sort') == 'desc' || !request()->exists('sort')) ? 'class="active"' : '' !!}><a href="{{ url('products') }}?sort=desc&{{ request()->getQueryString() }}">DESC</a></li>
                                                                        <li {!! request()->input('sort') == 'asc' ? 'class="active"' : '' !!}><a href="{{ url('products') }}?sort=asc&{{ request()->getQueryString() }}">ASC</a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="card-search search-wrap" data-search="search">
                                                    <div class="search-content">
                                                        <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                                        <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search orders...">
                                                        <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-inner p-0">
                                        <table class=" nk-tb-list nk-tb-ulist" data-auto-responsive="false"><!-- datatable-init add to class -->
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <!--<th class="nk-tb-col nk-tb-col-check">
                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                            <input type="checkbox" class="custom-control-input" id="uid">
                                                            <label class="custom-control-label" for="uid"></label>
                                                        </div>
                                                    </th>-->
                                                    <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Product</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Stock</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Weight</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Online</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Date</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($products) && $products->count()) : ?>

                                                    <?php foreach($products as $item) : ?>
                                                        <?php
                                                            $status = 'success';
                                                            if($item->status == 'disabled') {
                                                                $status = 'warning';
                                                            }

                                                            $stock = stock_level($item->product_id);
                                                        ?>
                                                        <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                        <!--<td class="nk-tb-col nk-tb-col-check">
                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input" id="uid1">
                                                                <label class="custom-control-label" for="uid1"></label>
                                                            </div>
                                                        </td>-->
                                                        <td class="nk-tb-col"><a href="{{ url('products/'.$item->id) }}">{{ $item->id }}</a></td>
                                                        <td class="nk-tb-col">
                                                            <div class="user-card">
                                                                <div class="user-info">
                                                                    <span class="tb-lead"><a href="{{ url('products/'.$item->id) }}">{{ $item->title }}</a> <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                    <span>{{ $item->code }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span class="badge badge-dot badge-success mr-3"><b>{{ $stock->actual }}</b></span>
                                                            <span class="badge badge-dot badge-warning"><b>{{ $stock->pending }}</b></span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span class="tb-lead">
                                                                <?php if($item->weight) : ?>
                                                                    {{ number_format($item->weight) }} {{ $item->unitOfMeasure->code }}
                                                                <?php else : ?>
                                                                    NA
                                                                <?php endif; ?>
                                                            </span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <?php if($item->is_available_online) : ?>
                                                                <span class="text-success"><em class="icon ni ni-check-thick"></em></span>
                                                            <?php else : ?>
                                                                <span class="text-warning"><em class="icon ni ni-cross"></em></span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span>{{ date('dS M Y H:ia', strtotime($item->created_at)) }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status text-{{ $status }}">{{ ucfirst($item->status) }}</span>
                                                        </td>
                                                        <td class="nk-tb-col nk-tb-col-tools">
                                                            <ul class="nk-tb-actions gx-1">
                                                                <?php if($item->status == 'active') : ?>
                                                                    <li class="nk-tb-action-hidden status-result">
                                                                        <a href="{{ url('product-status') }}" class="btn btn-trigger btn-action btn-icon" data-action="disabled" data-toggle="tooltip" data-placement="top" title="Disable product">
                                                                            <em class="icon ni ni-cross"></em>
                                                                        </a>
                                                                    </li>
                                                                <?php else : ?>
                                                                    <li class="nk-tb-action-hidden status-result">
                                                                        <a href="{{ url('product-status') }}" class="btn btn-trigger btn-action btn-icon" data-action="active" data-toggle="tooltip" data-placement="top" title="Enable product">
                                                                            <em class="icon ni ni-check-thick"></em>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>

                                                                <?php if($item->is_available_online) : ?>
                                                                    <li class="nk-tb-action-hidden">
                                                                        <a href="{{ url('salesorders/create?product='.$item->id) }}" target="_blank" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Sell {{ $item->title }}">
                                                                            <em class="icon ni ni-cart"></em>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>

                                                                <li class="nk-tb-action-hidden">
                                                                    <a href="{{ url('products/create?id='.$item->id) }}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $item->title }}">
                                                                        <em class="icon ni ni-copy"></em>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <ul class="link-list-opt no-bdr">
                                                                                <!--<li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>-->
                                                                                <li><a href="{{ url('products/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr><!-- .nk-tb-item  -->
                                                    <?php endforeach; ?>

                                                <?php else : ?>

                                                    <tr>
                                                        <div class="alert alert-warning m-3">No records found</div>
                                                    </tr>

                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-inner">
                                        <?php if(isset($products) && $products->count()) : ?>
                                        {{ $products->appends(request()->except('page'))->links() }}
                                        <?php endif; ?>
                                    </div>
                                </div><!-- .card-preview -->


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
<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript" src="{{ asset('assets/js/admin/product-list.js') }}"></script>


</html>
