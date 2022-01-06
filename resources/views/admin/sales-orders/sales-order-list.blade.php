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
                                            <h4 class="nk-block-title">Sales Orders</h4>
                                            <p>Manage your sales orders</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                        @can('create-sale-orders')
                                                            <li><a href="{{ url('salesorders/create') }}" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Sales Order</span></a></li>
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
                                            <div class="card-title"><h5 class="title">All Orders</h5></div>
                                            <div class="card-tools mr-n1" data-select2-id="17">
                                                <ul class="btn-toolbar gx-1" data-select2-id="16">
                                                    {{--<li>
                                                        <a href="#" class="search-toggle toggle-search btn btn-icon" data-target="search"><em class="icon ni ni-search"></em></a>
                                                    </li>
                                                    <li class="btn-toolbar-sep"></li>--}}
                                                    <li data-select2-id="15">
                                                        <div class="dropdown" data-select2-id="14">
                                                            <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                                <div class="badge badge-circle badge-primary">4</div><!-- Filters -->
                                                                <em class="icon ni ni-filter-alt"></em><!-- Filter icon -->
                                                            </a>
                                                            <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                                <div class="dropdown-head">
                                                                    <span class="sub-title dropdown-title">Filters</span>
                                                                </div>
                                                                <div class="dropdown-body dropdown-body-rg">
                                                                    <div class="row gx-6 gy-4">
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">Type</label>
                                                                                <select name="type" class="form-select form-select-sm">
                                                                                    <option value="all">All</option>
                                                                                    <option value="2" {{ is_selected(2, request()->input('type')) }}>Phone order</option>
                                                                                    <option value="1" {{ is_selected(1, request()->input('type')) }}>Online order</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <?php if($order_statuses = sales_order_statuses()) : ?>
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">Status</label>
                                                                                <select name="status" class="form-select form-select-sm">
                                                                                    <option value="all">All</option>
                                                                                    <?php foreach($order_statuses as $status) : ?>
                                                                                        <option value="{{ $status->id }}" {{ is_selected($status->id, request()->input('status')) }}>{{ $status->title }}</option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        <?php if($customers = customers()) : ?>
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">Customer</label>
                                                                                <select name="customer" class="form-select form-select-sm" data-search="on">
                                                                                    <option value="all">All customers</option>
                                                                                    <?php foreach($customers as $customer) : ?>
                                                                                    <option value="{{ $customer->id }}" {{ is_selected($customer->id, request()->input('customer')) }}>{{ $customer->getFullNameAttribute() }}</option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <?php endif; ?>
                                                                        <div class="col-6">
                                                                            <div class="form-group">
                                                                                <label class="overline-title overline-title-alt">Created date</label>
                                                                                <input type="text" name="created" class="form-control date-picker" data-date-format="yyyy-mm-dd" value="{{ request()->input('created') }}">
                                                                            </div>
                                                                        </div>
                                                                        {{--<div class="col-6">
                                                                            <div class="form-group">
                                                                                <div class="custom-control custom-control-sm custom-checkbox">
                                                                                    <input type="checkbox" class="custom-control-input" id="includeDel">
                                                                                    <label class="custom-control-label" for="includeDel"> Including Deleted</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>--}}
                                                                        <input type="hidden" name="rows" value="{{ request()->input('rows') ?: 50  }}">
                                                                        <input type="hidden" name="sort" value="{{ request()->input('sort') ?: 'desc'  }}">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="dropdown-foot between">
                                                                    <a class="clickable" href="{{ url('salesorders') }}">Reset Filters</a>
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
                                                                    <li {!! request()->input('rows') == 10 ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?rows=10&{{ request()->getQueryString() }}">10</a></li>
                                                                    <li {!! request()->input('rows') == 20 ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?rows=20&{{ request()->getQueryString() }}">20</a></li>
                                                                    <li {!! (request()->input('rows') == 50 || !request()->exists('rows')) ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?rows=50&{{ request()->getQueryString() }}">50</a></li>
                                                                    <li {!! request()->input('rows') == 100 ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?rows=100&{{ request()->getQueryString() }}">100</a></li>
                                                                    <li {!! request()->input('rows') == 200 ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?rows=200&{{ request()->getQueryString() }}">200</a></li>
                                                                </ul>
                                                                <ul class="link-check">
                                                                    <li><span>Order</span></li>
                                                                    <li {!! (request()->input('sort') == 'desc' || !request()->exists('sort')) ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?sort=desc&{{ request()->getQueryString() }}">DESC</a></li>
                                                                    <li {!! request()->input('sort') == 'asc' ? 'class="active"' : '' !!}><a href="{{ url('salesorders') }}?sort=asc&{{ request()->getQueryString() }}">ASC</a></li>
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
                                        <table class="nk-tb-list nk-tb-ulist" data-auto-responsive="false"><!-- datatable-init -->
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <!--<th class="nk-tb-col nk-tb-col-check">
                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                            <input type="checkbox" class="custom-control-input" id="uid">
                                                            <label class="custom-control-label" for="uid"></label>
                                                        </div>
                                                    </th>-->
                                                    <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Customer</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Invoiced</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Type</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Date</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($sales_orders) && $sales_orders->count()) : ?>

                                                    <?php foreach($sales_orders as $item) :  ?>

                                                        <tr class="nk-tb-item">
                                                        <!--<td class="nk-tb-col nk-tb-col-check">
                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input" id="uid1">
                                                                <label class="custom-control-label" for="uid1"></label>
                                                            </div>
                                                        </td>-->
                                                            <td class="nk-tb-col"><a href="{{ url('salesorders/'.$item->id) }}">{{ $item->id }}</a></td>
                                                            <td class="nk-tb-col">
                                                                <div class="user-card">
                                                                    <?php if($customer = $item->customer) : ?>
                                                                        <div class="user-avatar hidden-800-up bg-dim-primary d-none d-sm-flex">
                                                                            <span>{{ $customer->getInitialsAttribute() }}</span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <div class="user-info">
                                                                        <span class="tb-lead"><a href="{{ url('salesorders/'.$item->id) }}">{{ $item->first_name.' '.$item->last_name }}</a>  <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                        <span>{{ $item->email }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-mb">
                                                                <span class="tb-lead">
                                                                    <?php if(isset($item->invoices) && count($item->invoices)) : ?>
                                                                        <?php if($item->invoices->count() == 1) : ?>
                                                                            <a href="{{ url('invoices/'.$item->invoices[0]->id) }}">#{{ $item->invoices[0]->id }}</a>
                                                                        <?php else : ?>
                                                                            Multiple invoices
                                                                        <?php endif; ?>
                                                                    <?php else : ?>
                                                                        Not invoiced
                                                                    <?php endif; ?>
                                                                </span>
                                                                <span class="visible-800-up">
                                                                    <!--<span class="tb-status text-warning">Dispatched</span>-->
                                                                    <?php if($item->status) : ?>
                                                                        <span class="badge badge-dot badge-{{ $item->status->classes }}">{{ $item->status->title }}</span>
                                                                    <?php else :  ?>
                                                                        <span class="badge badge-dot badge-default">Draft</span>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-md">
                                                                <?php if($item->orderType) : ?>
                                                                <span class="badge badge-{{ $item->orderType->classes }}">{{ $item->orderType->title }}</span>
                                                                <?php else : ?>
                                                                NA
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-lg">
                                                                <span>{{ date('dS M Y H:ia', strtotime($item->created_at)) }}</span>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-md">
                                                                <?php if($item->status) : ?>
                                                                <span class="tb-status text-{{ $item->status->classes }}">{{ $item->status->title }}</span>
                                                                <?php else :  ?>
                                                                <span class="tb-status text-default">Draft</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="nk-tb-col nk-tb-col-tools">
                                                                <ul class="nk-tb-actions gx-1">
                                                                    <?php if(isset($item->invoices) && count($item->invoices)) : ?>
                                                                        <li class="nk-tb-action-hidden">
                                                                            <?php if( $item->invoices->count() > 1 ) : ?>
                                                                                <a href="{{ url('salesorders/'.$item->id.'?email-invoices=yes') }}" target="_blank" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Email invoices to {{ $item->email }}">
                                                                                    <em class="icon ni ni-mail-fill"></em>
                                                                                </a>
                                                                            <?php else : ?>

                                                                                <span data-toggle="modal" data-target="#modalEmailInvoice" data-invoice-id="{{ isset($item->invoices[0]) ? $item->invoices[0]->id : '' }}" data-email-address="{{ $item->email }}">
                                                                                    <a class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Email invoice to {{ $item->email }}">
                                                                                        <em class="icon ni ni-mail-fill"></em>
                                                                                    </a>
                                                                                </span>

                                                                            <?php endif; ?>
                                                                        </li>
                                                                    <?php else : ?>
                                                                        <?php if( in_array($item->sales_order_status_id,[1,2]) ) : ?>
                                                                            <span data-toggle="modal" data-target="#modalEmailQuote" data-id="{{ $item->id }}" data-email-address="{{ $item->email }}">
                                                                                <a class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Email quote to {{ $item->email }}">
                                                                                    <em class="icon ni ni-mail-fill"></em>
                                                                                </a>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <!--<li class="nk-tb-action-hidden">
                                                                        <a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Suspend">
                                                                            <em class="icon ni ni-user-cross-fill"></em>
                                                                        </a>
                                                                    </li>-->
                                                                    <li>
                                                                        <div class="drodown">
                                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <ul class="link-list-opt no-bdr">
                                                                                    <!--<li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>-->
                                                                                    <li><a href="{{ url('salesorders/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
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

                                                    <tr>
                                                        <div class="alert alert-warning m-3">No records found</div>
                                                    </tr>

                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="card-inner">
                                        <?php if(isset($sales_orders) && $sales_orders->count()) : ?>
                                            {{ $sales_orders->appends(request()->except('page'))->links() }}
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


<div class="modal fade" tabindex="-1" id="modalEmailInvoice">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-invoice', ['invoice_id' => null,'email' => null]) }}
    </div>
</div>
<div class="modal fade" tabindex="-1" id="modalEmailQuote">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-quote', ['id' => null,'email' => null]) }}
    </div>
</div>

{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">

    $(document).ready(function() {

        $('#modalEmailInvoice').on('shown.bs.modal', function (e) {
            var button = e.relatedTarget;
            var invoice_id = $(button).data('invoice-id');
            var email = $(button).data('email-address');

            $('#modalEmailInvoice [name="invoice_id"]').val(invoice_id);
            $('#modalEmailInvoice [name="email"]').val(email);
        });

        $('#modalEmailQuote').on('shown.bs.modal', function (e) {
            var button = e.relatedTarget;
            var id = $(button).data('id');
            var email = $(button).data('email-address');

            $('#modalEmailQuote [name="id"]').val(id);
            $('#modalEmailQuote [name="email"]').val(email);
        });

    });

</script>


</html>
