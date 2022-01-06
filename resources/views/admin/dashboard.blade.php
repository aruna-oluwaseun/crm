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
                            <div class="nk-block-head nk-block-head-sm">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h3 class="nk-block-title page-title">Overview Dashboard</h3>
                                        <div class="nk-block-des text-soft">
                                            <p>Welcome back {{ get_user()->first_name }}.</p>
                                        </div>
                                    </div><!-- .nk-block-head-content -->
                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                            <div class="toggle-expand-content" data-content="pageMenu">
                                                <ul class="nk-block-tools g-3">
                                                    {{--<li><a href="#" class="btn btn-white btn-dim btn-outline-primary"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                    <li><a href="#" class="btn btn-white btn-dim btn-outline-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                                    <li class="nk-block-tools-opt">
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-primary" data-toggle="dropdown"><em class="icon ni ni-plus"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <l00 mi><a href="#"><em class="icon ni ni-user-add-fill"></em><span>Add User</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-coin-alt-fill"></em><span>Add Order</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-note-add-fill-c"></em><span>Add Page</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>--}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head-content -->
                                </div><!-- .nk-block-between -->
                            </div><!-- .nk-block-head -->
                            <div class="nk-block">
                                <div class="row g-gs">
                                    <div class="col-lg-8">
                                        <div class="card card-bordered h-100">
                                            <div class="card-inner">
                                                <div class="card-title-group align-start mb-3">
                                                    <div class="card-title">
                                                        <h6 class="title">Orders Overview</h6>
                                                        <p>In last 30 days. <a href="#" class="link link-sm">Detailed Stats</a></p>
                                                    </div>
                                                    {{--<div class="card-tools mt-n1 mr-n1">
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#" class="active"><span>15 Days</span></a></li>
                                                                    <li><a href="#"><span>30 Days</span></a></li>
                                                                    <li><a href="#"><span>3 Months</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>--}}
                                                </div><!-- .card-title-group -->
                                                <div class="nk-order-ovwg">
                                                    <div class="row g-4 align-end">
                                                        <div class="col-xxl-8">
                                                            <div class="nk-order-ovwg-ck">
                                                                <canvas class="order-overview-chart" id="orderOverview"></canvas>
                                                            </div>
                                                        </div><!-- .col -->
                                                        <div class="col-xxl-4">
                                                            <div class="row g-4">
                                                                <div class="col-sm-6 col-xxl-12">
                                                                    <div class="nk-order-ovwg-data buy">
                                                                        <div class="amount">{{ !empty($overview_chart) ? number_format(array_sum($overview_chart),2,'.',',') : 0 }} <small class="currenct currency-GBP">GBP</small></div>
                                                                        <div class="info">This year {{ date('Y') }} <strong>{{ !empty($overview_chart) ? number_format(array_sum($overview_chart),2,'.',',') : 0 }} <span class="currenct currency-GBP">GBP</span></strong></div>
                                                                        <div class="title"><em class="icon ni ni-arrow-down-left"></em> Paid invoices</div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6 col-xxl-12">
                                                                    <div class="nk-order-ovwg-data sell">
                                                                        <div class="amount">{{ !empty($overview_chart_last_year) ? number_format(array_sum($overview_chart_last_year),2,'.',',') : 0 }} <small class="currenct currency-GBP">GBP</small></div>
                                                                        <div class="info">Last year {{ date('Y', strtotime('-1 year')) }} <strong>{{ !empty($overview_chart_last_year) ? number_format(array_sum($overview_chart_last_year),2,'.',',') : 0 }} <span class="currenct currency-GBP">GBP</span></strong></div>
                                                                        <div class="title"><em class="icon ni ni-arrow-up-left"></em> Paid invoices last year</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- .col -->
                                                    </div>
                                                </div><!-- .nk-order-ovwg -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card -->
                                    </div><!-- .col -->
                                    <div class="col-lg-4">
                                        <div class="card card-bordered h-100">
                                            <div class="card-inner-group">
                                                <div class="card-inner card-inner-md">
                                                    <div class="card-title-group">
                                                        <div class="card-title">
                                                            <h6 class="title">Action Center</h6>
                                                        </div>
                                                        <div class="card-tools mr-n1">
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li><a href="#"><em class="icon ni ni-setting"></em><span>Action Settings</span></a></li>
                                                                        <li><a href="#"><em class="icon ni ni-notify"></em><span>Push Notification</span></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!-- .card-inner -->
                                                <div class="card-inner">
                                                    <div class="nk-wg-action">
                                                        <div class="nk-wg-action-content">
                                                            <em class="icon ni ni-alert-fill text-danger"></em>
                                                            <div class="title">OVERDUE Sales Order(s)</div>
                                                            <p>We have <strong>5 OVERDUE sales orders</strong> that need dispatching.</p>
                                                        </div>
                                                        <a href="#" class="btn btn-icon btn-trigger mr-n2"><em class="icon ni ni-forward-ios"></em></a>
                                                    </div>
                                                </div><!-- .card-inner -->
                                                <div class="card-inner">
                                                    <div class="nk-wg-action">
                                                        <div class="nk-wg-action-content">
                                                            <em class="icon ni ni-help-fill"></em>
                                                            <div class="title">Urgent Sales Order(s)</div>
                                                            <p>We have <strong>5 urgent sales orders</strong> that need dispatching.</p>
                                                        </div>
                                                        <a href="#" class="btn btn-icon btn-trigger mr-n2"><em class="icon ni ni-forward-ios"></em></a>
                                                    </div>
                                                </div><!-- .card-inner -->
                                                <div class="card-inner">
                                                    <div class="nk-wg-action">
                                                        <div class="nk-wg-action-content">
                                                            <em class="icon ni ni-help-fill"></em>
                                                            <div class="title">Urgent Production Order(s)</div>
                                                            <p>We have <strong>5 urgent production orders</strong> that need urgent attention.</p>
                                                        </div>
                                                        <a href="#" class="btn btn-icon btn-trigger mr-n2"><em class="icon ni ni-forward-ios"></em></a>
                                                    </div>
                                                </div><!-- .card-inner -->
                                            </div><!-- .card-inner-group -->
                                        </div><!-- .card -->
                                    </div><!-- .col -->
                                    <div class="col-lg-8">
                                        <div class="card card-bordered card-full">
                                            <div class="card-inner">
                                                <div class="card-title-group">
                                                    <div class="card-title">
                                                        <h6 class="title"><span class="mr-2">Orders</span> <a href="#" class="link d-none d-sm-inline">See History</a></h6>
                                                    </div>
                                                    {{--<div class="card-tools">
                                                        <ul class="card-tools-nav">
                                                            <li><a href="#"><span>Online</span></a></li>
                                                            <li><a href="#"><span>Phone</span></a></li>
                                                            <li class="active"><a href="#"><span>All</span></a></li>
                                                        </ul>
                                                    </div>--}}
                                                </div>
                                            </div>
                                            <?php if(isset($sales_orders) && $sales_orders->count()) : ?>
                                            <div class="card-inner p-0 border-top">
                                                <div class="nk-tb-list nk-tb-orders">
                                                    <div class="nk-tb-item nk-tb-head">
                                                        <div class="nk-tb-col"><span>Order No.</span></div>
                                                        <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                                                        <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                                                        <div class="nk-tb-col tb-col-lg"><span>Ref</span></div>
                                                        <div class="nk-tb-col"><span>Amount</span></div>
                                                        <div class="nk-tb-col"><span class="d-none d-sm-inline">Status</span></div>
                                                        <div class="nk-tb-col"><span>&nbsp;</span></div>
                                                    </div>

                                                    <?php foreach ($sales_orders as $order) : ?>
                                                    <div class="nk-tb-item">
                                                        <div class="nk-tb-col">
                                                            <span class="tb-lead"><a href="{{ url('salesorders/'.$order->id) }}">#{{ $order->id }}</a></span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-sm">
                                                            <div class="user-card">
                                                                <div class="user-avatar user-avatar-sm bg-purple">
                                                                    <span>{{ $order->getInitialsAttribute() }}</span>
                                                                </div>
                                                                <div class="user-name">
                                                                    <span class="tb-lead">{{ $order->getFullNameAttribute() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-md">
                                                            <span class="tb-sub">{{ format_date($order->created_at) }}</span>
                                                        </div>
                                                        <div class="nk-tb-col tb-col-lg">
                                                            <span class="tb-sub text-primary">NA</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span class="tb-sub tb-amount">{{ number_format($order->gross_cost, 2,'.',',') }} <span>GBP</span></span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <?php if($order->status) : ?>
                                                                <span class="badge badge-dot badge-dot-xs badge-{{ $order->status->classes }}">
                                                                    {{ $order->status->title }}
                                                                </span>
                                                            <?php else :  ?>
                                                                <span class="badge badge-dot badge-dot-xs badge-default">
                                                                    NA
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="nk-tb-col nk-tb-col-action">
                                                            <div class="dropdown">
                                                                <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                    <ul class="link-list-plain">
                                                                        <li><a href="{{ url('salesorders/'.$order->id) }}">View</a></li>
                                                                        {{--<li><a href="#">Invoice</a></li>
                                                                        <li><a href="#">Print</a></li>--}}
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <?php else : ?>
                                            <div class="alert alert-warning">No sales orders from the last 30 days</div>
                                            <?php endif; ?>
                                            <div class="card-inner-sm border-top text-center d-sm-none">
                                                <a href="#" class="btn btn-link btn-block">See History</a>
                                            </div>
                                        </div><!-- .card -->
                                    </div><!-- .col -->
                                    <div class="col-lg-4">
                                        <div class="row g-gs">
                                            {{--<div class="col-md-6 col-lg-12">
                                                <div class="card card-bordered card-full">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-2">
                                                            <div class="card-title">
                                                                <h6 class="title">Top sales by product type</h6>
                                                                <p>In last 15 days buy and sells overview.</p>
                                                            </div>
                                                            <div class="card-tools mt-n1 mr-n1">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="#" class="active"><span>15 Days</span></a></li>
                                                                            <li><a href="#"><span>30 Days</span></a></li>
                                                                            <li><a href="#"><span>3 Months</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!-- .card-title-group -->
                                                        <div class="nk-coin-ovwg">
                                                            <div class="nk-coin-ovwg-ck">
                                                                <canvas class="coin-overview-chart" id="coinOverview"></canvas>
                                                            </div>
                                                            <ul class="nk-coin-ovwg-legends">
                                                                <li><span class="dot dot-lg sq" data-bg="#f98c45"></span><span>Pigments</span></li>
                                                                <li><span class="dot dot-lg sq" data-bg="#9cabff"></span><span>Resins</span></li>
                                                                <li><span class="dot dot-lg sq" data-bg="#8feac5"></span><span>Starter Kits</span></li>
                                                                <li><span class="dot dot-lg sq" data-bg="#6b79c8"></span><span>Jen-prime</span></li>
                                                                <li><span class="dot dot-lg sq" data-bg="#79f1dc"></span><span>Solvents</span></li>
                                                            </ul>
                                                        </div><!-- .nk-coin-ovwg -->
                                                    </div><!-- .card-inner -->
                                                </div><!-- .card -->
                                            </div>--}}
                                            <!-- .col -->
                                            {{--<div class="col-md-6 col-lg-12">
                                                <div class="card card-bordered card-full">
                                                    <div class="card-inner">
                                                        <div class="card-title-group align-start mb-3">
                                                            <div class="card-title">
                                                                <h6 class="title">User Activities</h6>
                                                                <p>In last 30 days <em class="icon ni ni-info" data-toggle="tooltip" data-placement="right" title="Referral Informations"></em></p>
                                                            </div>
                                                            <div class="card-tools mt-n1 mr-n1">
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="#"><span>15 Days</span></a></li>
                                                                            <li><a href="#" class="active"><span>30 Days</span></a></li>
                                                                            <li><a href="#"><span>3 Months</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="user-activity-group g-4">
                                                            <div class="user-activity">
                                                                <em class="icon ni ni-users"></em>
                                                                <div class="info">
                                                                    <span class="amount">345</span>
                                                                    <span class="title">Direct Join</span>
                                                                </div>
                                                                <div class="gfx" data-color="#9cabff">
                                                                    <svg enable-background="new 0 0 48 17.5" version="1.1" viewBox="0 0 48 17.5" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill="currentColor" d="m1.2 17.4h-0.3c-0.5-0.1-0.8-0.7-0.7-1.2 2-7.2 5-12.2 8.8-14.7 1.5-1 3-1.5 4.5-1.4 4.9 0.3 7.2 4.9 9 8.5 0.3 0.4 0.5 0.8 0.7 1.2 1 1.8 2.7 3.9 4.5 4.3 0.9 0.2 1.7-0.1 2.6-0.8 1.8-1.4 3-3.7 4.1-5.9 0.5-1 1-1.9 1.5-2.9 1-1.5 2.8-3.5 4.9-3.8 1.1-0.1 2.2 0.3 3.1 1 1.3 1.1 1.9 2.6 2.4 4.1 0.4 1 0.7 1.9 1.2 2.6 0.3 0.4 0.2 1.1-0.2 1.4s-1.1 0.2-1.4-0.2c-0.7-0.9-1.1-2-1.5-3-0.5-1.3-1-2.5-1.9-3.3-0.5-0.4-1-0.6-1.5-0.5-1.3 0.2-2.7 1.6-3.5 2.8-0.5 0.8-1 1.8-1.4 2.7-1.2 2.4-2.4 4.9-4.6 6.5-1.3 1.1-2.8 1.5-4.2 1.2-3.1-0.6-5.1-3.9-5.9-5.3-0.2-0.4-0.4-0.8-0.6-1.3-1.7-3.4-3.5-7.2-7.3-7.4-1.1-0.1-2.1 0.3-3.3 1-3.5 2.4-6.2 7-8 13.7-0.2 0.4-0.6 0.7-1 0.7z" />
                                                                        </svg>
                                                                </div>
                                                            </div>
                                                            <div class="user-activity">
                                                                <em class="icon ni ni-users"></em>
                                                                <div class="info">
                                                                    <span class="amount">49</span>
                                                                    <span class="title">Referral Join</span>
                                                                </div>
                                                                <div class="gfx" data-color="#ccd4ff">
                                                                    <svg enable-background="new 0 0 48 17.5" version="1.1" viewBox="0 0 48 17.5" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill="currentColor" d="m1.2 17.4h-0.3c-0.5-0.1-0.8-0.7-0.7-1.2 2-7.2 5-12.2 8.8-14.7 1.5-1 3-1.5 4.5-1.4 4.9 0.3 7.2 4.9 9 8.5 0.3 0.4 0.5 0.8 0.7 1.2 1 1.8 2.7 3.9 4.5 4.3 0.9 0.2 1.7-0.1 2.6-0.8 1.8-1.4 3-3.7 4.1-5.9 0.5-1 1-1.9 1.5-2.9 1-1.5 2.8-3.5 4.9-3.8 1.1-0.1 2.2 0.3 3.1 1 1.3 1.1 1.9 2.6 2.4 4.1 0.4 1 0.7 1.9 1.2 2.6 0.3 0.4 0.2 1.1-0.2 1.4s-1.1 0.2-1.4-0.2c-0.7-0.9-1.1-2-1.5-3-0.5-1.3-1-2.5-1.9-3.3-0.5-0.4-1-0.6-1.5-0.5-1.3 0.2-2.7 1.6-3.5 2.8-0.5 0.8-1 1.8-1.4 2.7-1.2 2.4-2.4 4.9-4.6 6.5-1.3 1.1-2.8 1.5-4.2 1.2-3.1-0.6-5.1-3.9-5.9-5.3-0.2-0.4-0.4-0.8-0.6-1.3-1.7-3.4-3.5-7.2-7.3-7.4-1.1-0.1-2.1 0.3-3.3 1-3.5 2.4-6.2 7-8 13.7-0.2 0.4-0.6 0.7-1 0.7z" />
                                                                        </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="user-activity-ck">
                                                        <canvas class="usera-activity-chart" id="userActivity"></canvas>
                                                    </div>
                                                </div><!-- .card -->
                                            </div>--}}<!-- .col -->
                                        </div><!-- .row -->
                                    </div><!-- .col -->
                                </div><!-- .row -->
                            </div><!-- .nk-block -->
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
<!-- JavaScript -->

{{ view('admin.templates.footer') }}
<script type="text/javascript">
    "use strict";

    var this_year_labels = [];
    var this_year_values = [];

    var last_year_labels = [];
    var last_year_values = [];

    <?php if(!empty($overview_chart)) : ?>
        <?php foreach($overview_chart as $date => $value) : ?>
            this_year_labels.push('{{ $date }}');
            this_year_values.push({{ $value }});
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(!empty($overview_chart_last_year)) : ?>
        <?php foreach($overview_chart_last_year as $date => $value) : ?>
            last_year_labels.push('{{ $date }}');
            last_year_values.push({{ $value }});
        <?php endforeach; ?>
    <?php endif; ?>


    !function (NioApp, $) {
        "use strict"; //////// for developer - User Balance ////////
        // Avilable options to pass from outside
        // labels: array,
        // legend: false - boolean,
        // dataUnit: string, (Used in tooltip or other section for display)
        // datasets: [{label : string, color: string (color code with # or other format), data: array}]

        function lineProfileBalance(selector, set_data) {
            var $selector = selector ? $(selector) : $('.profile-balance-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        tension: _get_data.lineTension,
                        backgroundColor: _get_data.datasets[i].background,
                        borderWidth: 2,
                        borderColor: _get_data.datasets[i].color,
                        pointBorderColor: "transparent",
                        pointBackgroundColor: "transparent",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: _get_data.datasets[i].color,
                        pointBorderWidth: 2,
                        pointHoverRadius: 3,
                        pointHoverBorderWidth: 2,
                        pointRadius: 3,
                        pointHitRadius: 3,
                        data: _get_data.datasets[i].data
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'line',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: false
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return false;
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                                }
                            },
                            backgroundColor: '#fde3da',
                            titleFontSize: 11,
                            titleFontColor: '#fd4c1c',
                            titleMarginBottom: 4,
                            bodyFontColor: '#ffb69e',
                            bodyFontSize: 10,
                            bodySpacing: 3,
                            yPadding: 8,
                            xPadding: 8,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: false
                            }],
                            xAxes: [{
                                display: false,
                                ticks: {
                                    reverse: NioApp.State.isRTL
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            lineProfileBalance();
        });
        var orderOverview = {
            labels: this_year_labels,
            dataUnit: 'GBP',
            datasets: [{
                label: "{{ date('Y') }}",
                color: "#8feac5",
                data: this_year_values
            }, {
                label: "{{ date('Y',strtotime('-1 year')) }}",
                color: "#fd4c1c",
                data: last_year_values
            }]
        };

        function orderOverviewChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.order-overview-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
                    _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        // Styles
                        backgroundColor: _get_data.datasets[i].color,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        hoverBorderColor: 'transparent',
                        borderSkipped: 'bottom',
                        barPercentage: .8,
                        categoryPercentage: .6
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'bar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#fd4c1c'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return data.datasets[tooltipItem[0].datasetIndex].label;
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                                }
                            },
                            backgroundColor: '#fde3da',
                            titleFontSize: 11,
                            titleFontColor: '#fd4c1c',
                            titleMarginBottom: 4,
                            bodyFontColor: '#ffb69e',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                position: NioApp.State.isRTL ? "right" : "left",
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 11,
                                    fontColor: '#ffb69e',
                                    padding: 10,
                                    callback: function callback(value, index, values) {
                                        return '£ ' + value;
                                    },
                                    min: 100,
                                    max: 5000,
                                    stepSize: 1200
                                },
                                gridLines: {
                                    color: NioApp.hexRGB("#526484", .2),
                                    tickMarkLength: 0,
                                    zeroLineColor: NioApp.hexRGB("#526484", .2)
                                }
                            }],
                            xAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    fontSize: 9,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                    padding: 10,
                                    reverse: NioApp.State.isRTL
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 0,
                                    zeroLineColor: 'transparent'
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            orderOverviewChart();
        });
        var userActivity = {
            labels: ["01 Nov", "02 Nov", "03 Nov", "04 Nov", "05 Nov", "06 Nov", "07 Nov", "08 Nov", "09 Nov", "10 Nov", "11 Nov", "12 Nov", "13 Nov", "14 Nov", "15 Nov", "16 Nov", "17 Nov", "18 Nov", "19 Nov", "20 Nov", "21 Nov"],
            dataUnit: 'GBP',
            stacked: true,
            datasets: [{
                label: "Direct Join",
                color: "#9cabff",
                data: [110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90]
            }, {
                label: "Referral Join",
                color: NioApp.hexRGB("#9cabff", .4),
                data: [125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 110, 80, 125, 55, 95, 75, 90, 75, 90]
            }]
        };

        function userActivityChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.usera-activity-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
                    _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        // Styles
                        backgroundColor: _get_data.datasets[i].color,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        hoverBorderColor: 'transparent',
                        borderSkipped: 'bottom',
                        barPercentage: .7,
                        categoryPercentage: .7
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'bar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#6783b8'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return data.datasets[tooltipItem[0].datasetIndex].label;
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 6,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                            xAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    reverse: NioApp.State.isRTL
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            userActivityChart();
        });
        var coinOverview = {
            labels: ["Bitcoin", "Ethereum", "NioCoin", "Litecoin", "Bitcoin"],
            stacked: true,
            datasets: [{
                label: "Buy Orders",
                color: ["#f98c45", "#9cabff", "#8feac5", "#6b79c8", "#79f1dc"],
                data: [1740, 2500, 1820, 1200, 1600, 2500]
            }, {
                label: "Sell Orders",
                color: [NioApp.hexRGB('#f98c45', .2), NioApp.hexRGB('#9cabff', .4), NioApp.hexRGB('#8feac5', .4), NioApp.hexRGB('#6b79c8', .4), NioApp.hexRGB('#79f1dc', .4)],
                data: [2420, 1820, 3000, 5000, 2450, 1820]
            }]
        };

        function coinOverviewChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.coin-overview-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
                    _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        // Styles
                        backgroundColor: _get_data.datasets[i].color,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        hoverBorderColor: 'transparent',
                        borderSkipped: 'bottom',
                        barThickness: '8',
                        categoryPercentage: 0.5,
                        barPercentage: 1.0
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'horizontalBar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#6783b8'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return data['labels'][tooltipItem[0]['index']];
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + data.datasets[tooltipItem.datasetIndex]['label'];
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 6,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    beginAtZero: true,
                                    padding: 0
                                },
                                gridLines: {
                                    color: NioApp.hexRGB("#526484", .2),
                                    tickMarkLength: 0,
                                    zeroLineColor: NioApp.hexRGB("#526484", .2)
                                }
                            }],
                            xAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    fontSize: 9,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                    padding: 0,
                                    reverse: NioApp.State.isRTL
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 0,
                                    zeroLineColor: 'transparent'
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            coinOverviewChart();
        });
        var salesRevenue = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dataUnit: 'GBP',
            stacked: true,
            datasets: [{
                label: "Sales Revenue",
                color: [NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), "#6576ff"],
                data: [11000, 8000, 12500, 5500, 9500, 14299, 11000, 8000, 12500, 5500, 9500, 14299]
            }]
        };
        var activeSubscription = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            dataUnit: 'GBP',
            stacked: true,
            datasets: [{
                label: "Active User",
                color: [NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), NioApp.hexRGB("#6576ff", .2), "#6576ff"],
                data: [8200, 7800, 9500, 5500, 9200, 9690]
            }]
        };
        var totalSubscription = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            dataUnit: 'GBP',
            stacked: true,
            datasets: [{
                label: "Active User",
                color: [NioApp.hexRGB("#aea1ff", .2), NioApp.hexRGB("#aea1ff", .2), NioApp.hexRGB("#aea1ff", .2), NioApp.hexRGB("#aea1ff", .2), NioApp.hexRGB("#aea1ff", .2), "#aea1ff"],
                data: [8200, 7800, 9500, 5500, 9200, 9690]
            }]
        };

        function salesBarChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.sales-bar-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
                    _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        // Styles
                        backgroundColor: _get_data.datasets[i].color,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        hoverBorderColor: 'transparent',
                        borderSkipped: 'bottom',
                        barPercentage: .7,
                        categoryPercentage: .7
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'bar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#6783b8'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return false;
                                },
                                label: function label(tooltipItem, data) {
                                    return data['labels'][tooltipItem['index']] + ' ' + data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']];
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 11,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 4,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 10,
                            bodySpacing: 3,
                            yPadding: 8,
                            xPadding: 8,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                            xAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    reverse: NioApp.State.isRTL
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            salesBarChart();
        });
        var salesOverview = {
            labels: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30"],
            dataUnit: 'BTC',
            lineTension: 0.1,
            datasets: [{
                label: "Sales Overview",
                color: "#fd4c1c",
                background: NioApp.hexRGB('#fd4c1c', .3),
                data: [8200, 7800, 9500, 5500, 9200, 9690, 8200, 7800, 9500, 5500, 9200, 9690, 8200, 7800, 9500, 5500, 9200, 9690, 8200, 7800, 9500, 5500, 9200, 9690, 8200, 7800, 9500, 5500, 9200, 9690]
            }]
        };

        function lineSalesOverview(selector, set_data) {
            var $selector = selector ? $(selector) : $('.sales-overview-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        tension: _get_data.lineTension,
                        backgroundColor: _get_data.datasets[i].background,
                        borderWidth: 2,
                        borderColor: _get_data.datasets[i].color,
                        pointBorderColor: "transparent",
                        pointBackgroundColor: "transparent",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: _get_data.datasets[i].color,
                        pointBorderWidth: 2,
                        pointHoverRadius: 3,
                        pointHoverBorderWidth: 2,
                        pointRadius: 3,
                        pointHitRadius: 3,
                        data: _get_data.datasets[i].data
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'line',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#6783b8'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return data['labels'][tooltipItem[0]['index']];
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 6,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                position: NioApp.State.isRTL ? "right" : "left",
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 11,
                                    fontColor: '#9eaecf',
                                    padding: 10,
                                    callback: function callback(value, index, values) {
                                        return '$ ' + value;
                                    },
                                    min: 100,
                                    stepSize: 3000
                                },
                                gridLines: {
                                    color: NioApp.hexRGB("#526484", .2),
                                    tickMarkLength: 0,
                                    zeroLineColor: NioApp.hexRGB("#526484", .2)
                                }
                            }],
                            xAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    fontSize: 9,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                    padding: 10,
                                    reverse: NioApp.State.isRTL
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 0,
                                    zeroLineColor: 'transparent'
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            lineSalesOverview();
        });
        var supportStatus = {
            labels: ["Bitcoin", "Ethereum", "NioCoin", "Feature Request", "Bug Fix"],
            stacked: true,
            datasets: [{
                label: "Solved",
                color: ["#f98c45", "#9cabff", "#8feac5", "#6b79c8", "#79f1dc"],
                data: [66, 74, 92, 142, 189]
            }, {
                label: "Open",
                color: [NioApp.hexRGB('#f98c45', .4), NioApp.hexRGB('#9cabff', .4), NioApp.hexRGB('#8feac5', .4), NioApp.hexRGB('#6b79c8', .4), NioApp.hexRGB('#79f1dc', .4)],
                data: [66, 74, 92, 32, 26]
            }, {
                label: "Pending",
                color: [NioApp.hexRGB('#f98c45', .2), NioApp.hexRGB('#9cabff', .2), NioApp.hexRGB('#8feac5', .2), NioApp.hexRGB('#6b79c8', .2), NioApp.hexRGB('#79f1dc', .2)],
                data: [66, 74, 92, 21, 9]
            }]
        };

        function supportStatusChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.support-status-chart');
            $selector.each(function () {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
                    _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        // Styles
                        backgroundColor: _get_data.datasets[i].color,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        hoverBorderColor: 'transparent',
                        borderSkipped: 'bottom',
                        barThickness: '8',
                        categoryPercentage: 0.5,
                        barPercentage: 1.0
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'horizontalBar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#6783b8'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return data['labels'][tooltipItem[0]['index']];
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + data.datasets[tooltipItem.datasetIndex]['label'];
                                }
                            },
                            backgroundColor: '#eff6ff',
                            titleFontSize: 13,
                            titleFontColor: '#6783b8',
                            titleMarginBottom: 6,
                            bodyFontColor: '#9eaecf',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                position: NioApp.State.isRTL ? "right" : "left",
                                ticks: {
                                    beginAtZero: true,
                                    padding: 16,
                                    fontColor: "#8094ae"
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 0,
                                    zeroLineColor: 'transparent'
                                }
                            }],
                            xAxes: [{
                                display: false,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    fontSize: 9,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                    padding: 0,
                                    reverse: NioApp.State.isRTL
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 0,
                                    zeroLineColor: 'transparent'
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function () {
            supportStatusChart();
        });
    }(NioApp, jQuery);

</script>
</body>

</html>
