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
                                            <h4 class="nk-block-title">Purchases</h4>
                                            <p>Manage your purchases</p>
                                        </div>


                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                        @can('create-purchase-orders')
                                                            <li><a href="{{ url('purchases/create') }}" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Purchase Order</span></a></li>
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
                                                    <th class="nk-tb-col"><span class="sub-text">Supplier</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Items</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Total Cost</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Due Date</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($purchases) && $purchases->count()) : ?>

                                                    <?php foreach($purchases as $item) : ?>
                                                        <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                        <!--<td class="nk-tb-col nk-tb-col-check">
                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input" id="uid1">
                                                                <label class="custom-control-label" for="uid1"></label>
                                                            </div>
                                                        </td>-->
                                                        <td class="nk-tb-col"><a href="{{ url('purchases/'.$item->id) }}">{{ $item->id }}</a></td>
                                                        <td class="nk-tb-col">
                                                            <div class="user-card">
                                                                <div class="user-info">
                                                                    <span class="tb-lead"><a href="{{ url('purchases/'.$item->id) }}">{{ $item->supplier_title }}</a> <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                    <span>{{ format_date($item->created_at) }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span>{{ $item->items->count() }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span class="tb-lead">£{{ number_format($item->gross_cost,2,'.',',') }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                           <span>{{ format_date($item->expected_delivery_date) }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status text-{{ $item->status->classes }}">{{ ucfirst($item->status->title) }}</span>
                                                        </td>
                                                        <td class="nk-tb-col nk-tb-col-tools">
                                                            <ul class="nk-tb-actions gx-1">
                                                                <li class="nk-tb-action-hidden">
                                                                    <a href="{{ url('purchases/create?id='.$item->id) }}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $item->title }}">
                                                                        <em class="icon ni ni-copy"></em>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <ul class="link-list-opt no-bdr">
                                                                                <li><a href="{{ url('purchases/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
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
                                        <?php if(isset($purchases) && $purchases->count()) : ?>
                                         {{ $purchases->links() }}
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

{{--<script type="text/javascript" src="{{ asset('assets/js/admin/product-list.js') }}"></script>--}}

</html>
