<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }

    $sales_order = $detail->salesOrder;
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
                                        <h4 class="title nk-block-title">Invoice #{{ $detail->id }}</h4>
                                    </div>

                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                            <div class="toggle-expand-content" data-content="pageMenu">
                                                <ul class="nk-block-tools g-3">
                                                    <li><a href="{{ url('invoices/download/'.$detail->id) }}" class="btn btn-white btn-primary"><em class="icon ni ni-download"></em><span class="tb-col-lg">Download Invoice</span></a></li>
                                                    <li><a href="#" data-dismiss="modal" data-toggle="modal" data-target="#modalEmailInvoice" class="btn btn-white btn-outline-light"><em class="tb-col-lg icon ni ni-mail "></em><span>Email Invoice</span></a></li>
                                                    <li><a href="{{ url('invoices/'.$detail->id) }}" class="btn btn-white btn-outline-light"><span>View Invoice</span></a></li>

                                                    <li class="nk-block-tools-opt">
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-white btn-outline-light" data-toggle="dropdown"><em class="icon ni ni-printer"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="{{ url('invoices/print/'.$detail->id) }}" target="_blank"><span>Invoice</span></a></li>
                                                                    <li><a href="#"><span>Commercial Invoice</span></a></li>
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
                                                        <h4 class="nk-block-title">Invoice #{{$detail->id}}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                Invoice created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
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

                                            <?php if(isset($detail->vatReturn) && $detail->vatReturn->count()) : ?>
                                                <div class="alert alert-success">
                                                    This invoice has been filed in a VAT return
                                                </div>
                                            <?php endif; ?>

                                            <!-- Details -->
                                            <div id="tab-details" class="tab card">

                                                <?php if(isset($sales_order->orderType)) : ?>
                                                <div class="alert alert-{{$sales_order->orderType->classes}}">Order type : <b>{{ $sales_order->orderType->title }}</b> {{ $sales_order->customer_id ? '' : 'Checked out as GUEST' }}</div>
                                                <?php endif; ?>

                                                <h4><a href="{{ url('customers/'.$sales_order->customer_id) }}" target="_blank">{{ $sales_order->getFullNameAttribute() }}</a> </h4>
                                                <div class="row mb-3 mt-3">
                                                    <div class="col-md-6">
                                                        <b class="text-primary">Invoice Address : </b>
                                                        <ul>
                                                            <?php foreach ($detail->billing_address_data as $key => $line) : ?>
                                                            <li>{{ $line }}</li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <b class="text-primary">Delivery Address : </b>
                                                        <ul>
                                                            <?php foreach ($detail->delivery_address_data as $key => $line) : ?>
                                                            <li>{{ $line }}</li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    Current status : <span class="text-{{ $detail->status->classes }}">{{ $detail->status->title }}</span>
                                                </div>

                                                <div class="d-flex mt-3">
                                                    <a href="{{ url('salesorders/'.$sales_order->id) }}" class="btn btn-primary btn-lg" target="_blank">Go to sales order</a>
                                                </div>



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
                                                                    <td class="tb-odr-action">
                                                                        <div class="dropdown">
                                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                                <ul class="link-list-plain">
                                                                                    <li><a href="{{ url('products/'.$item->product_id) }}" target="_blank" class="text-primary">View</a></li>
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
                                                                    <th scope="row">Net cost (Inc Shipping)</th>
                                                                    <td>&pound; {{ number_format($detail->net_cost,2,'.',',') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Vat</th>
                                                                    <td>&pound; {{ number_format( ($detail->vat_cost),2,'.',',') }}</td>
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

                                                <div class="alert alert-warning">There are no items on invoice</div>

                                                <?php endif; ?>

                                            </div><!-- end Build Products -->

                                            <!-- Invoices -->
                                            <div id="tab-payments" style="display:none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>View payments against this invoice.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#modalPayment" {{ $detail->status->id != 3 ? '' : 'disabled' }} class="btn btn-primary {{ $detail->status->id != 3 ? '' : 'disabled' }}" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Payment</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if($detail->payments && $detail->payments->count() ) : ?>

                                                    <div class="card card-bordered card-preview">
                                                        <table class="table">
                                                            <thead class="thead-light">
                                                            <th>#</th>
                                                            <th>Amount</th>
                                                            <th>Payment Ref</th>
                                                            <th>Status</th>
                                                            <th></th>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach($detail->payments as $item) : ?>
                                                            <?php
                                                                $class = 'default';
                                                                if($item->status == 'complete') {
                                                                    $class = 'success';
                                                                }
                                                                if($item->status == 'error') {
                                                                    $class = 'danger';
                                                                }
                                                            ?>
                                                            <tr>
                                                                <td>#{{ $item->id }}</td>
                                                                <td>£{{ number_format($item->amount, 2, '.',',') }}</td>
                                                                <td>{{ $item->payment_ref }}</td>
                                                                <td><span class="badge badge-dot badge-{{ $class }} mr-3"><b>{{ ucfirst($item->status) }}</b></span></td>
                                                                <td class="tb-odr-action">
                                                                    <div class="dropdown">
                                                                        <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown" data-offset="-8,0"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                            <ul class="link-list-plain">
                                                                                <li><a href="#" data-dismiss="modal" data-invoice-id="{{ $detail->id }}" data-toggle="modal" data-target="#modalEmailInvoice" class="text-primary">Email invoice</a></li>
                                                                                <li><a href="{{ url('invoices/print/'.$detail->id) }}" target="_blank" class="text-primary">Print invoice</a></li>
                                                                                <li><a href="{{ url('invoices/download/'.$detail->id) }}" class="text-primary">Download invoice</a></li>
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

                                                    <div class="alert alert-warning">There are no payments to display</div>

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
                                                                    {{--<div class="card-inner">
                                                                        <!--<a href="#" class="btn btn-round btn-icon btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Duplicate {{ $detail->title }}"><em class="icon ni ni-copy"></em></a>-->

                                                                    </div>--}}<!-- .card-inner -->
                                                                    <div class="card-inner">
                                                                        <div class="user-account-info py-0">
                                                                            <h6 class="overline-title-alt">Pricing</h6>
                                                                            <div class="user-balance preview-gross-cost">&pound;{{ number_format($detail->gross_cost, 2, '.',',') }} </div>
                                                                            <div class="user-balance-sub">NET <span class="text-primary preview-net-cost">&pound;{{ number_format($detail->net_cost, 2, '.',',') }}</span></div>
                                                                            <div class="user-balance-sub">VAT {{ isset($detail->vatType) ? '@'.$detail->vatType->title : '' }} <span class="preview-vat-cost">&pound;{{ number_format($detail->vat_cost+$detail->shipping_vat, 2, '.',',') }} </span></div>
                                                                            {{--<div class="user-balance-sub">Shipping <span class="preview-vat-cost">&pound;{{ number_format($detail->shipping_cost, 2, '.',',') }} </span></div>
--}}
                                                                            <?php if($detail->discount_cost) : ?>
                                                                            <div class="user-balance-sub">Discount -<span class="preview-vat-cost">&pound;{{ number_format($detail->discount_cost, 2, '.',',') }} </span></div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Customer Details</span></a></li>
                                                                            <li><a href="#tab-order-items"><em class="icon ni ni-grid-plus"></em><span>Items on invoice</span></a></li>
                                                                            <li><a href="#tab-payments"><em class="icon ni ni-cc-alt"></em><span>Payments</span></a></li>
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

{{-- Email the invoice --}}
<div class="modal fade" tabindex="-1" id="modalEmailInvoice">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-invoice', ['invoice_id' => $detail->id,'email' => $sales_order->email]) }}
    </div>
</div>


<div class="modal fade" tabindex="-1" id="modalPayment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create payment<span></span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ url('invoices/manual-payment/'.$detail->id) }}" method="post">
                    @csrf

                    <?php
                        $paid = 0;
                        if(isset($detail->payments) && $detail->payments->count())
                        {
                            $paid = $detail->payments()->income()->complete()->sum('amount');
                        }
                    ?>

                    <div class="alert alert-info">
                        Mark a payment against this invoice. Only do this if you are sure the customer has paid.
                    </div>

                    <div class="form-group">
                        <label class="form-label">Payment Method</label>
                        <div class="form-control-wrap">
                            <select id="payment-method-id" name="payment_method_id" class="form-select form-control" data-search="on" required>
                                <?php if($payment_methods = payment_methods()) : ?>
                                <?php foreach($payment_methods as $method) : ?>
                                <option value="{{ $method->id }}">{{ $method->title }}</option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Amount *</label>
                        <div class="form-control-wrap">
                            <input id="add-amount" name="amount" type="number" step="any" class="form-control" value="{{ $detail->gross_cost-$paid }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{ view('admin.templates.footer') }}
</body>
<script type="text/javascript">
    // Trigger modal from another page so user can select what invoice to print etc
    var show_email_invoice_modal = {{ request()->exists('email-invoices') ? 'true' : 'false' }};
</script>
<script type="text/javascript" src="{{ asset('assets/js/admin/invoice-detail.js') }}"></script>
</html>
