<?php
    if( !$created_by = get_user($detail->created_by) ) {
        $created_by = 'NA';
    }
    if( !$updated_by = get_user($detail->updated_by) ) {
        $updated_by = 'NA';
    }

    $customer = $detail->customer;
    $customer_locked = true;
    $refund_locked = false;
    if($detail->vatReturn) {
        $refund_locked = true;
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

                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">

                            <div class="nk-block-head " style="padding-bottom: 1.5rem">
                                <div class="nk-block-between">
                                    <div class="nk-block-head-content">
                                        <h4 class="title nk-block-title">Refund #{{ $detail->id }}</h4>
                                    </div>

                                    <div class="nk-block-head-content">
                                        <div class="toggle-wrap nk-block-tools-toggle">
                                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                            <div class="toggle-expand-content" data-content="pageMenu">
                                                <ul class="nk-block-tools g-3">
                                                    <li><a href="{{ url('refunds/download/'.$detail->id) }}" class="btn btn-white btn-primary"><em class="icon ni ni-download"></em><span class="tb-col-lg">Download Refund</span></a></li>
                                                    <li><a href="#modalEmailRefund" data-dismiss="modal" data-toggle="modal" class="btn btn-white btn-outline-light"><em class="tb-col-lg icon ni ni-mail "></em><span>Email Refund</span></a></li>
                                                    <li><a href="{{ url('refunds/'.$detail->id) }}" class="btn btn-white btn-outline-light"><span>View Refund</span></a></li>
                                                    <li><a href="{{ url('refunds/print/'.$detail->id) }}" class="btn btn-white btn-outline-light" target="_blank"><em class="icon ni ni-printer"></em></a></li>
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
                                                        <h4 class="nk-block-title">Refund #{{$detail->id}}</h4>
                                                        <div class="nk-block-des">
                                                            <p>
                                                                Refund created by <a href="{{ url('users/'.$created_by->id) }}">{{ $created_by->getFullNameAttribute() }}</a> at <b>{{ date('dS F Y H:ia', strtotime($detail->created_at)) }}</b>.
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
                                                    This refund has been filed in a VAT return
                                                </div>
                                            <?php endif; ?>

                                            <!-- Details -->
                                            <div id="tab-details" class="tab card">

                                                <form method="post" action="{{ url('refunds/'.$detail->id) }}" id="edit-form" class=" form-validate">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Customer *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select" name="customer_id" data-search="on" <?php echo $customer_locked ? 'disabled="disabled"' : '' ?>>
                                                                <?php if($customers = customers(true)) : ?>
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

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Reason for refund *</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control" name="reason" {{ $detail->sales_order_status_id == 8 ? 'required' : '' }}>{{ old('reason', $detail->reason) }}</textarea>
                                                        </div>
                                                    </div>

                                                    {{--<div id="status-warning" style="display:{{ !in_array($detail->sales_order_status_id,[1,2]) ? 'block' : 'none' }}" class="alert alert-info"><b><em class="icon ni ni-alert"></em> Warning :</b> customer details can only be changed if the order status is Quote / Pending Payment and the order type is not Online.</div>
--}}
                                                    <div class="form-group">
                                                        <?php

                                                            switch ($detail->status)
                                                            {
                                                                case 'processing' :
                                                                    $status_class = 'info';
                                                                    break;
                                                                case 'complete' :
                                                                    $status_class = 'success';
                                                                    break;
                                                                case 'error' :
                                                                    $status_class = 'danger';
                                                                    break;
                                                                default :
                                                                    $status_class = 'default';
                                                            }
                                                        ?>
                                                        Current status : <span class="text-{{  $status_class }}">{{ ucfirst($detail->status) }}</span>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>

                                            </div><!-- end Details -->

                                            <!-- Build products -->
                                            <div id="tab-order-items" style="display: none;" class="tab card">
                                                <?php
                                                    $can_add_refund_items = true;
                                                    if($detail->invoice_id && $detail->gross_cost >= $detail->invoice->gross_cost)
                                                    {
                                                        $can_add_refund_items = false;
                                                    }
                                                ?>
                                                <div class="card card-bordered mb-2" style="display: {{ $can_add_refund_items ? 'block' : 'none' }}">
                                                    <div class="card-inner">
                                                        <h6>Add refund items</h6>


                                                        <!-- Start 4 col -->
                                                        <div class="mb-3">

                                                            <?php if(isset($detail->customer->invoices) && $detail->customer->invoices->count()) : ?>
                                                                <div class="form-group">
                                                                    <label class="form-label">Select invoice</label>
                                                                    <select name="invoice_id" {{ $detail->invoice_id ? 'disabled' : '' }} class="form-select">
                                                                        <option value="0">Select invoice to view items</option>
                                                                        <?php foreach($detail->customer->invoices as $invoice) : ?>
                                                                            <option value="{{ $invoice->id }}" {{ is_selected($invoice->id, (!is_null($selected_invoice) ? $selected_invoice->id : '')) }} data-url="{{ url('refunds/detail/'.$detail->id.'?invoice='.$invoice->id.'#tab-order-items') }}">#{{ $invoice->id }} {{ format_date($invoice->created_at) }} - £{{ number_format($invoice->gross_cost,2,',','.') }} | {{ $invoice->items->count() }} items</option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            <?php else : ?>

                                                                <?php if($detail->customer->orders->count()) : ?>
                                                                    <div class="alert alert-info">This customer has no invoices against their account. They do have <a href="{{ url('customers/'.$detail->customer->id.'#tab-orders') }}">sales orders</a>  but none have been invoiced, therefore there is no need to issue a refund, you can just cancel the items on the sales order.</div>
                                                                <?php else : ?>
                                                                    <div class="alert alert-warning">This customer has no invoices against their account.</div>
                                                                <?php endif; ?>

                                                            <?php endif; ?>
                                                        </div><!-- end 4 col -->

                                                        <?php if(!$refund_locked) : ?>
                                                            <!-- start 8 col -->
                                                            <div id="refund-items">
                                                                <?php if(!is_null($selected_invoice)) : ?>
                                                                    <?php
                                                                        // Check if the sales order has original shipping invoiced
                                                                        $salesOrder = $selected_invoice->salesOrder;
                                                                        $orig_shipping_cost = $salesOrder->shipping_cost;
                                                                        $orig_shipping_vat = vat_type($salesOrder->shipping_vat_type_id);
                                                                        $orig_shipping_gross = $salesOrder->shipping_gross;
                                                                    ?>

                                                                    <div class="alert alert-info mb-3">Each item can be refunded up to the max value of what it was invoiced at, you can optionally refund an item at a smaller value.</div>

                                                                    <form id="refund-items-form" method="post" action="{{ url('refunds/refund-items/'. $detail->id) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="invoice_id" value="{{ $selected_invoice->id }}">
                                                                        <div class="card card-bordered card-preview">
                                                                            <table class="table">
                                                                                <thead class="thead-light">
                                                                                    <th>
                                                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                                            <input type="checkbox" class="custom-control-input checkbox-item-all" id="items-all">
                                                                                            <label class="custom-control-label" for="items-all"></label>
                                                                                        </div>
                                                                                    </th>
                                                                                    <th>Qty</th>
                                                                                    <th>Product</th>
                                                                                    <th>Item Cost</th>
                                                                                    <th>Vat</th>
                                                                                    <th>Gross</th>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                    $total_qty = 0;
                                                                                    $total_qty_dispatched = 0;
                                                                                    $total_additional_shipping = 0;

                                                                                    $total_invoiced = 0;
                                                                                    ?>
                                                                                <?php foreach($selected_invoice->items as $item) : ?>
                                                                                    <?php

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

                                                                                        $item_locked = false;
                                                                                        $refunded_item_amount = 0;
                                                                                        $refunded_gross_amount = 0;
                                                                                        // check if this item exists in whats already being processed.
                                                                                        if(isset($detail->items) && $detail->items->count() && $item->id)
                                                                                        {
                                                                                            if($refund_item = $detail->items()->where('sales_order_item_id', $item->id)->first())
                                                                                            {
                                                                                                $refunded_item_amount = $refund_item->refund_item_cost;
                                                                                                $refunded_gross_amount = $refund_item->refund_gross_cost;

                                                                                                if($refunded_gross_amount == $item->gross_cost)
                                                                                                {
                                                                                                    $item_locked = true;
                                                                                                }
                                                                                            }
                                                                                        }

                                                                                    ?>
                                                                                    <tr data-vat-percentage="{{ $item->vat_percentage }}">
                                                                                        <td class="nk-tb-col nk-tb-col-check sorting_1">
                                                                                            <?php if($item->qty-$dispatched && !$item->is_additional_shipping) : ?>
                                                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                                                <input {{ $item_locked ? 'disabled' : '' }} type="checkbox" name="refund[{{$item->id}}]" value="1" class="custom-control-input checkbox-item" id="item-{{$item->id}}">
                                                                                                <label class="custom-control-label" for="item-{{$item->id}}"></label>
                                                                                            </div>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input {{ $item_locked ? 'disabled' : '' }} type="number" class="form-control qty" name="refund[{{$item->id}}][qty]" max="{{ $item->qty }}" value="{{ $item->qty }}">
                                                                                        </td>
                                                                                        <td><b>{{ $item->qty }} x</b> {{ $item->product_title }}</td>
                                                                                        <td>
                                                                                            <?php if($item_locked) : ?>
                                                                                                <span class="text-danger">Refunded £{{ number_format($refunded_item_amount,2,'.',',') }}</span>
                                                                                            <?php else : ?>
                                                                                                <input type="number" step="any" class="form-control item-cost" name="refund[{{$item->id}}][item_cost]" value="{{ $item->item_cost-$refunded_item_amount }}" max="{{ $item->item_cost-$refunded_item_amount }}">
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ '@'.$item->vat_percentage.'%' }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <?php
                                                                                                $vat = ($item->vat_percentage / 100) * ($item->item_cost*$item->qty);
                                                                                                $gross =  $item->item_cost*$item->qty + $vat;
                                                                                            ?>
                                                                                            <input type="number" readonly step="any" class="form-control gross-cost" name="refund[{{$item->id}}][gross_cost]" value="{{ $gross }}" max="{{ $gross }}">
                                                                                        </td>
                                                                                        <input type="hidden" name="refund[{{$item->id}}][vat_type_id]" value="{{ $item->vat_type_id }}">
                                                                                    </tr>
                                                                                <?php endforeach; ?>

                                                                                <!-- -->
                                                                                <?php if($salesOrder->shipping_invoice && $salesOrder->shipping_invoice == $selected_invoice->id) : ?>
                                                                                    <?php
                                                                                        $item_locked = false;
                                                                                        $refunded_item_amount = 0;
                                                                                        $refunded_gross_amount = 0;
                                                                                        $vat = ($orig_shipping_vat->value / 100) * $orig_shipping_cost;
                                                                                        $gross = $orig_shipping_cost + $vat;
                                                                                        // check if this item exists in whats already being processed.
                                                                                        if(isset($detail->items) && $detail->items->count())
                                                                                        {

                                                                                            if($refund_item = $detail->items()->where('title', 'Shipping')->first())
                                                                                            {
                                                                                                $refunded_item_amount = $refund_item->refund_item_cost;
                                                                                                $refunded_gross_amount = $refund_item->refund_gross_cost;

                                                                                                if($refunded_gross_amount == $gross)
                                                                                                {
                                                                                                    $item_locked = true;
                                                                                                }
                                                                                            }

                                                                                        }
                                                                                    ?>
                                                                                    <tr data-vat-percentage="{{ $orig_shipping_vat->value }}">
                                                                                        <td class="nk-tb-col nk-tb-col-check sorting_1">
                                                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                                                <input {{ $item_locked ? 'disabled' : '' }} type="checkbox" name="refund[original_shipping]" value="1" class="custom-control-input checkbox-item" id="item-original_shipping">
                                                                                                <label class="custom-control-label" for="item-original_shipping"></label>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input {{ $item_locked ? 'disabled' : '' }} type="number" class="form-control qty" name="refund[original_shipping][qty]" max="1" value="1">
                                                                                        </td>
                                                                                        <td><b>1 x</b> Shipping</td>
                                                                                        <td>
                                                                                            <?php if($item_locked) : ?>
                                                                                                <span class="text-danger">Refunded £{{ number_format($refunded_item_amount,2,'.',',') }}</span>
                                                                                            <?php else : ?>
                                                                                                <input type="number" step="any" class="form-control item-cost" name="refund[original_shipping][item_cost]" value="{{ $orig_shipping_cost-$refunded_item_amount }}" max="{{ $orig_shipping_cost-$refunded_item_amount }}">
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ '@'.$orig_shipping_vat->title }}
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="number" readonly step="any" class="form-control gross-cost" name="refund[original_shipping][gross_cost]" value="{{ $gross }}" max="{{ $gross }}">
                                                                                        </td>
                                                                                        <input type="hidden" name="refund[original_shipping][vat_type_id]" value="{{ $orig_shipping_vat->id }}">
                                                                                    </tr>
                                                                                <?php endif; ?>

                                                                                </tbody>
                                                                            </table>

                                                                        </div><!-- .card-preview -->

                                                                        <div class="justify-between mt-3">
                                                                            <div class="">
                                                                                <?php if($selected_invoice->payments()->income()->complete()->count()) : ?>
                                                                                    <div class="alert alert-info p-1">
                                                                                        <?php
                                                                                            $no_can_be_refunded_to_card = 0;
                                                                                            $total_can_be_refunded_to_card = 0;
                                                                                            $no_can_be_refunded_manually = 0;
                                                                                            $total_can_be_refunded_manually = 0;

                                                                                            foreach($selected_invoice->payments()->income()->complete()->get() as $value)
                                                                                            {
                                                                                                if($value->payment_ref == 'MANUAL-PAYMENT') {
                                                                                                    $no_can_be_refunded_manually++;
                                                                                                    $total_can_be_refunded_manually += $value->amount;
                                                                                                } else {
                                                                                                    $no_can_be_refunded_to_card++;
                                                                                                    $total_can_be_refunded_to_card += $value->amount;
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                        <!-- What has to be refunded manually -->
                                                                                        <?php if($no_can_be_refunded_manually) : ?>
                                                                                        <div class="alert alert-info"><b>Payment was taken manually</b><br>
                                                                                            You can make <b>{{ $no_can_be_refunded_manually }}</b> manual refund of <b>£{{ number_format($total_can_be_refunded_manually) }}</b><br>
                                                                                            You will need to refund the user via the original payment method, once done mark the payment in the payout section
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                        <!-- What can be refunded via Stripe -->
                                                                                        <?php if($no_can_be_refunded_to_card) : ?>
                                                                                        <div class="alert alert-info"><b>Payment taken online</b><br>
                                                                                           System will automatically refund the amounts you specify, in total you can make <b>{{ $no_can_be_refunded_to_card }}</b> refund of <b>£{{ number_format($total_can_be_refunded_to_card) }}</b>, this will automatically taken care of.
                                                                                        </div>
                                                                                        <?php endif; ?>

                                                                                    </div>
                                                                                <?php else : ?>
                                                                                    <div class="alert alert-warning"><b>Warning</b> : No payments have been marked against this invoice, continuing is not advised.</div>
                                                                                <?php endif; ?>
                                                                            </div>

                                                                            <div class="">
                                                                                <table class="table mb-3" style="width: 300px">
                                                                                    <thead class="thead-dark">
                                                                                    <tr>
                                                                                        <th scope="col" colspan="2">Refund Total</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody class="card-bordered">
                                                                                    <tr>
                                                                                        <th scope="row">Total</th>
                                                                                        <td id="refund-total" class="font-weight-bolder text-primary">&pound; - </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="row">Vat</th>
                                                                                        <td id="refund-vat" class="font-weight-bolder text-primary">&pound; - </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th scope="row">Gross</th>
                                                                                        <td id="refund-gross" class="font-weight-bolder text-primary">&pound; - </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <button id="refund-items-btn" type="submit" disabled class="submit-btn btn-block btn btn-lg btn-primary">Refund Items</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>

                                                                <?php else : ?>

                                                                <div class="alert alert-info">Select an invoice to begin</div>

                                                                <?php endif; ?>
                                                            </div><!-- refund items -->
                                                        <?php else : ?>

                                                        <div class="alert alert-info">This refund has been filed in a VAT return. No more items or refunds can be processed on this refund ID.</div>

                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="nk-block-head pt-3">
                                                    <h4>Items refunded</h4>

                                                    <?php if($detail->invoice_id) : ?>
                                                    <a href="{{ url('invoices/'. $detail->invoice_id) }}" class="btn btn-primary" target="_blank">View original invoice</a>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if(isset($detail->items) && $detail->items->count() ) : ?>
                                                    <?php if( isset($detail->payments) && $detail->payments()->refund()->complete()->count() ) : ?>
                                                        <?php
                                                            $refunded = $detail->payments()->refund()->complete()->sum('amount');
                                                        ?>

                                                        <?php if($refunded >= $detail->gross_cost) : ?>
                                                            <div class="alert alert-success">Customer has been refunded £{{ number_format($refunded,2,'.',',') }} over {{ $detail->payments()->refund()->complete()->count() }} payments</div>
                                                        <?php else : ?>
                                                            <div class="alert alert-warning">Customer has been partially refunded £{{ number_format($refunded,2,'.',',') }} over {{ $detail->payments()->refund()->complete()->count() }} payments, £{{ number_format($detail->gross_cost-$refunded,2,'.',',') }} still left to refund</div>
                                                        <?php endif; ?>

                                                    <?php else : ?>
                                                    <div class="alert alert-warning">Please refund the customer £{{ number_format($detail->gross_cost,2,'.',',') }}. You can mark this refund as complete in the payout section</div>
                                                    <?php endif; ?>

                                                    <input type="hidden" name="sales_order_id" value="{{ $detail->id }}">
                                                    <div class="card card-bordered card-preview">
                                                        <table class="table">
                                                            <thead class="thead-light">
                                                                <th>#</th>
                                                                <th>Product</th>
                                                                <th>Refunded</th>
                                                            </thead>
                                                            <tbody>

                                                                <?php foreach($detail->items as $item) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php if($item->product_id) : ?>
                                                                        <a href="{{ url('products/'.$item->product_id) }}">{{ $item->product_id }}</a>
                                                                        <?php else : ?>
                                                                        <span class="text-light">NA</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><b>{{ $item->qty }} x</b> {{ $item->title }}</td>
                                                                    <td>£{{ number_format($item->refund_gross_cost, 2, '.',',') }}</td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>

                                                    </div><!-- .card-preview -->

                                                    <div class="row nk-block-head">
                                                        <div class="col-md-8 mb-4">

                                                        </div>
                                                        <div class="col-md-4">

                                                            <table class="table">
                                                                <thead class="thead-dark">
                                                                <tr>
                                                                    <th scope="col" colspan="2">Refund Totals</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="card-bordered">
                                                                <tr>
                                                                    <th scope="row">Net refund</th>
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

                                                <?php else : ?>

                                                <div class="alert alert-warning">There are no items on refund</div>

                                                <?php endif; ?>

                                            </div><!-- end Build Products -->

                                            <!-- Invoices -->
                                            <div id="tab-payments" style="display:none;" class="tab card">

                                                <div class="nk-block-head">
                                                    <div class="nk-block-between">
                                                        <div class="nk-block-head-content">
                                                            <p>View payout against this refund.</p>
                                                        </div>

                                                        <div class="nk-block-head-content">
                                                            <a href="#modalPayment" {{ $detail->status == 'complete' ? 'disabled' : '' }} class="btn btn-primary {{ $detail->status == 'complete' ? 'disabled' : '' }}" data-toggle="modal"><em class="icon ni ni-plus"></em> <span>Add Payment</span></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if($detail->payments && $detail->payments->count() ) : ?>

                                                    <div class="card card-bordered card-preview">
                                                        <table class="table">
                                                            <thead class="thead-light">
                                                                <th>#</th>
                                                                <th>Amount</th>
                                                                <th>Refund Ref</th>
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
                                                                                <li><a href="#" data-dismiss="modal" data-refund-id="{{ $detail->id }}" data-toggle="modal" data-target="#modalEmailRefund" class="text-primary">Email refund</a></li>
                                                                                <li><a href="{{ url('refunds/print/'.$detail->id) }}" target="_blank" class="text-primary">Print refund</a></li>
                                                                                <li><a href="{{ url('refunds/download/'.$detail->id) }}" class="text-primary">Download refund</a></li>
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
                                                                            <h6 class="overline-title-alt">TOTAL</h6>
                                                                            <div class="user-balance preview-gross-cost">&pound;{{ number_format($detail->gross_cost, 2, '.',',') }} </div>
                                                                            <div class="user-balance-sub">NET <span class="text-primary preview-net-cost">&pound;{{ number_format($detail->net_cost, 2, '.',',') }}</span></div>
                                                                            <?php if(isset($refunded) && $detail->gross_cost) : ?>
                                                                                <?php if($refunded >= $detail->gross_cost) : ?>
                                                                                    <div class="user-balance-sub">REFUNDED <span class="text-primary preview-net-cost">&pound;{{ number_format($refunded, 2, '.',',') }}</span></div>
                                                                                <?php else : ?>
                                                                                    <div class="user-balance-sub">REFUNDED <span class="text-success preview-net-cost">&pound;{{ number_format($refunded, 2, '.',',') }}</span></div>
                                                                                    <div class="user-balance-sub">LEFT TO REFUND <span class="text-danger preview-net-cost">&pound;{{ number_format($detail->gross_cost-$refunded, 2, '.',',') }}</span></div>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </div><!-- .card-inner -->
                                                                    <div class="card-inner p-0">
                                                                        <ul class="tab-links link-list-menu">
                                                                            <li><a class="active" href="#tab-details"><em class="icon ni ni-edit"></em><span>Customer Details</span></a></li>
                                                                            <li><a href="#tab-order-items"><em class="icon ni ni-grid-plus"></em><span>Items on refund</span></a></li>
                                                                            <li><a href="#tab-payments"><em class="icon ni ni-cc-alt"></em><span>Payout</span></a></li>
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
<div class="modal fade" tabindex="-1" id="modalEmailRefund">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-refund', ['id' => $detail->id,'email' => $detail->email]) }}
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
                <form action="{{ url('refunds/manual-payment/'.$detail->id) }}" method="post">
                    @csrf

                    <?php
                    $paid = 0;
                    if(isset($detail->payments) && $detail->payments->count())
                    {
                        $paid = $detail->payments()->refund()->complete()->sum('amount');
                    }
                    ?>

                    <div class="alert alert-info">
                       Mark refund as paid.
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
                            <input id="add-amount" name="amount" type="number" step="any" class="form-control" value="{{ $detail->gross_cost-$paid }}" max="{{ $detail->gross_cost-$paid }}" required>
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
<script type="text/javascript">
$(document).ready(function() {

    $(document).on('change', '[name="invoice_id"]', function() {
        var href = $(this).find(':selected').data('url');

        if(href) {
            window.location = href;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong selecting this invoice, please re-load and try again.',
                //footer: '<a href>Why do I have this issue?</a>'
            });
        }
    });

    if(show_email_invoice_modal) {
        $('#modalInvoiceAction').modal('show');
    }

    // show items on invoice
    $('#modalInvoiceItems').on('shown.bs.modal', function (e) {
        var button = e.relatedTarget;
        var refund_id = $(button).data('refund-id');

        $('#modalRefundItems .modal-header span').val(refund_id);
        $('#modalRefundItems .items-on-invoice').hide();
        $('#modalRefundItems #items-on-invoice-'+refund_id).show();
    });

    // Email an invoice
    $('#modalEmailRefund').on('shown.bs.modal', function (e) {
        var button = e.relatedTarget;
        var refund_id = $(button).data('refund-id');

        $('#modalEmailRefund [name="refund_id"]').val(refund_id);
    });

    // when invoicing allow immediate send of email
    $(document).on('click', '#email-invoice', function() {
        if($(this).is(':checked')) {
            $('#email-for-invoice').show();
            $('#email-for-invoice input').removeAttr('disabled');
        } else {
            $('#email-for-invoice').hide();
            $('#email-for-invoice input').attr('disabled','disabled');
        }
    });

    $(document).on('click','.checkbox-item-all, .checkbox-item', function() {
        if($(this).hasClass('checkbox-item-all'))
        {
            if($(this).is(':checked')) {
                // uncheck all
                $('#refund-items-form tbody input.checkbox-item').prop('checked', true);
            }
            else {
                // check all
                $('#refund-items-form tbody input.checkbox-item').prop('checked', false);
            }
        }

        var checked = $('#refund-items-form tbody input.checkbox-item:checked').length;
        if(checked) {
            $('#refund-items-btn').removeClass('disabled').removeAttr('disabled');
        } else {
            $('#refund-items-btn').addClass('disabled').attr('disabled','disabled');
        }
        refundTotal();
    });

    // Costings
    $(document).on('change focusout', '.qty, .item-cost', function(event) {
        refundTotal();
    });

});
function refundTotal()
{
    var net_total = 0;
    var vat = 0;
    var gross_total = 0;
    $('#refund-items-form tbody tr').each(function(id,row) {
        var checkbox = $(row).find('input[type=checkbox]');

        if(checkbox.is(':checked')) {
            var vat_rate = $(row).data('vat-percentage');
            var qty = $(row).find('.qty').val();
            var item_cost = $(row).find('.item-cost').val();
            var row_total = qty*item_cost;
            var row_vat = (vat_rate / 100) * row_total;
            var row_gross = parseFloat(row_total+row_vat);

            net_total += row_total;
            vat += row_vat;
            gross_total += row_gross

            $(row).find('.gross-cost').val(row_gross);
        }

    });
    $('#refund-total').html('&pound;' + net_total);
    $('#refund-vat').html('&pound;' + vat);
    $('#refund-gross').html('&pound;' + gross_total);
    var checked = $('#refund-items-form tbody').find('input[type=checkbox]:checked').length;
    $('#refund-items-btn').html('Refund ' + checked + ' Items');
}
</script>
</html>
