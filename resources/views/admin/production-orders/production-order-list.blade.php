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
                                            <h4 class="nk-block-title">Production Orders</h4>
                                            <p>Manage your production orders</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                        @can('create-production-orders')
                                                            <li><a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Production Order</span></a></li>
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
                                        <table class="datatable-custom nk-tb-list nk-tb-ulist" data-auto-responsive="false">
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
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Qty</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Due</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($production_orders) && $production_orders->count()) : ?>

                                                    <?php foreach($production_orders as $item) : ?>
                                                    <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                        <!--<td class="nk-tb-col nk-tb-col-check">
                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input" id="uid1">
                                                                <label class="custom-control-label" for="uid1"></label>
                                                            </div>
                                                        </td>-->
                                                        <td class="nk-tb-col"><a href="{{ url('productionorders/'.$item->id) }}">{{ $item->id }}</a></td>
                                                        <td class="nk-tb-col">
                                                            <div class="user-info">
                                                                <span class="tb-lead">{{ $item->product->title }} <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                {{--<span>{{ $item->code }}</span>--}}
                                                            </div>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-mb">
                                                            <span class="tb-lead">
                                                                {{ $item->qty }}
                                                            </span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span>{{ $item->due_date ? date('dS M Y H:ia', strtotime($item->due_date)) : 'NA' }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status text-{{ $item->status == 'complete' ? 'success' : 'info' }}">{{ ucfirst($item->status) }}</span>
                                                            <?php if($item->is_urgent) : ?>
                                                               <span class="text-danger"><em class="icon ni ni-alert-fill"></em> URGENT</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="nk-tb-col nk-tb-col-tools">
                                                            <ul class="nk-tb-actions gx-1">
                                                                <li class="nk-tb-action-hidden">
                                                                    <a href="#" class="btn btn-icon" data-toggle="tooltip" data-placement="top" title="Print build sheet">
                                                                        <em class="icon ni ni-printer"></em>
                                                                    </a>
                                                                </li>
                                                                <?php /*if($item->status == 'active') : */?><!--
                                                                    <li class="nk-tb-action-hidden status-result">
                                                                        <a href="#" class="btn btn-icon" data-toggle="modal" data-target="#modalSuspend" data-toggle="tooltip" data-placement="top" title="Suspend Customer">
                                                                            <em class="icon ni ni-user-cross-fill"></em>
                                                                        </a>
                                                                    </li>
                                                                <?php /*else : */?>
                                                                    <li class="nk-tb-action-hidden status-result">
                                                                        <a href="{{ url('customer-status') }}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Re-activate Customer">
                                                                            <em class="icon ni ni-user-check-fill"></em>
                                                                        </a>
                                                                    </li>
                                                                --><?php /*endif; */?>
                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <ul class="link-list-opt no-bdr">
                                                                                <!--<li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>-->
                                                                                <li><a href="{{ url('productionorders/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                                                <!--<li><a href="#"><em class="icon ni ni-repeat"></em><span>Transaction</span></a></li>
                                                                                <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Activities</span></a></li>
                                                                                <li class="divider"></li>
                                                                                <li><a href="#"><em class="icon ni ni-shield-star"></em><span>Reset Pass</span></a></li>
                                                                                <li><a href="#"><em class="icon ni ni-shield-off"></em><span>Reset 2FA</span></a></li>
                                                                                <li><a href="#"><em class="icon ni ni-na"></em><span>Suspend User</span></a></li>-->
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr><!-- .nk-tb-item  -->
                                                    <?php endforeach; ?>

                                                <?php else : ?>

                                                    <div class="alert alert-warning m-3">No records found</div>


                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-inner">
                                        <?php if(isset($production_orders) && $production_orders->count()) : ?>
                                            {{ $production_orders->links() }}
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

{{-- Create Modal --}}
<div class="modal fade" tabindex="-1" id="modalCreate">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.create-production-order',['products'=>$products]) }}
    </div>
</div>


{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript" src="{{ asset('assets/js/admin/production-order-list.js') }}"></script>

</html>
