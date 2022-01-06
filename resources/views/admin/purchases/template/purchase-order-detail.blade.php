<?php
    $ignore_address_keys = ['lat','lng'];
    $additional_shipping = 0;
?>

<div class="invoice-wrapper">
    <div class="logo-wrapper">
        <div class="logo">
            <img src="{{ asset('assets/files/img/Jenflow-Logo.png') }}" alt="Jenflow Systems Ltd">
        </div>
        <div class="call-to-action pt-3 text-uppercase">
            <b>PURCHASE ORDER</b>
        </div>
    </div>


    <div class="payment-info">
        <div class="row">
            <div class="col-sm-6">
                <span>Jenflow Systems Ltd.</span><br>
                Unit 16, Old Hall Industrial<br>
                Estate Revival Street<br>
                Walsall, WS3 3HJ<br>
                Registered in Scotland<br>
                VAT: 234 3454 24<br>
                01922 907711 | 07539 081756 | 07377 335978<br>
            </div>
            <div class="col-sm-6 text-right">
                <span>Purchase Date</span>
                <strong>{{ format_date_time($detail->created_at) }}</strong>
                <span>Purchase Order No.</span>
                <strong>{{ $detail->id }}</strong>
            </div>
        </div>
    </div>

    <div class="payment-details">
        <div class="row">
            <div class="col-sm-6">
                <span>Supplier</span>
                <b>
                    {{ $detail->supplier_title }}
                </b>
                <p>
                    <?php if(isset($detail->supplier->billingAddress)) : $supplier_address = $detail->supplier->billingAddress; ?>
                        {{ $supplier_address->line1 }}<br>
                        {{ $supplier_address->line2 }}<br>
                        {{ $supplier_address->line3 }}<br>
                        {{ $supplier_address->city }},{{ $supplier_address->county }}<br>
                        {{ $supplier_address->country }}
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-sm-6">
                <span>Shipping Address</span>
                <p>
                    <?php foreach ($detail->delivery_address_data as $key => $line) : if(in_array($key,$ignore_address_keys)) { continue; } ?>
                    {!! $line != '' ? $line.'<br>' : '' !!}
                    <?php endforeach; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="line-items">
        <div class="headers clearfix">
            <div class="row mr-0 ml-0">
                <div class="col-sm-4">Description</div>
                <div class="col-sm-2">Qty</div>
                <div class="col-sm-2">Rate</div>
                <div class="col-sm-2">VAT</div>
                <div class="col-sm-2 text-right">Amount</div>
            </div>
        </div>
        <div class="items">
            <?php foreach ($detail->items as $item) : ?>
            <?php
            if($item->is_additional_shipping)  {
                $additional_shipping = $item->net_cost;
            }
            ?>
            <div class="row mr-0 ml-0 item">
                <div class="col-sm-4 ">
                    {{ $item->product_title }}
                </div>
                <div class="col-sm-2 ">
                    {{ $item->qty }}
                </div>
                <div class="col-sm-2 ">
                    {{ number_format($item->item_cost,2,'.',',') }}
                </div>
                <div class="col-sm-2 ">
                    {{ $item->vat_percentage }}%
                </div>
                <div class="col-sm-2 text-right">
                    £{{ number_format($item->net_cost,2,'.',',') }}
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="total text-right">
            <p class="extra-notes">
                <strong>Extra Notes</strong>
                {{ $detail->notes }}
            </p>
            <div class="field">
                Subtotal <span>£{{ number_format($detail->net_cost-$additional_shipping,2,'.',',') }}</span>
            </div>
            <?php if($detail->shipping) : ?>
            <div class="field">
                Shipping <span>£{{ number_format($detail->shipping->shipping_cost,2,'.',',') }}</span>
            </div>
            <?php elseif($additional_shipping) : ?>
            <div class="field">
                Shipping <span>£{{ number_format($additional_shipping,2,'.',',') }}</span>
            </div>
            <?php endif; ?>
            <div class="field">
                VAT <span>£{{ number_format($detail->vat_cost,2,'.',',') }}</span>
            </div>
            <!--<div class="field">
                Discount <span>4.5%</span>
            </div>-->
            <div class="field grand-total">
                Total <span>£{{ number_format($detail->gross_cost,2,'.',',') }}</span>
            </div>
        </div>

        {{--<div class="account-details">
            <span><b>Payments</b></span><br>
            Bank : <b>{{ env('BANK_NAME') }}</b> | Sort : <b>{{ env('BANK_SORT') }}</b> | Account : <b>{{ env('BANK_ACCOUNT') }}</b>

            <div class="terms" >
                Terms and conditions attached
            </div>
        </div>--}}

        <div class="print">
            <a href="{{ url('purchases/print/'.$detail->id) }}" target="_blank" class="btn btn-outline-primary">
                <em class="icon ni ni-printer"></em>
                Print this purchase order
            </a>
        </div>
    </div>
</div>
