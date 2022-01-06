<?php
$ignore_address_keys = ['lat','lng'];
$additional_shipping = 0;

?>
<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <title>Purchase Order #{{ $detail->id }}</title>

    <style type="text/css">

        body {
            font-family: Roboto, sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.65;
            color: #526484;
        }

        .text-right {
            text-align: right !important;
        }

        .pt-3, .py-3 {
            padding-top: 1rem !important;
        }

        .text-uppercase, .ucap {
            text-transform: uppercase !important;
        }

        .invoice-wrapper .payment-details span {
            color: #ff5e29;
            display: block;
        }

        .invoice-wrapper .payment-details a {
            display: inline-block;
            margin-top: 5px;
        }

        .invoice-wrapper .line-items .print a {
            display: inline-block;
            border: 1px solid #ff5e29;
            padding: 13px 13px;
            border-radius: 5px;
            color: #ff5e29;
            font-size: 13px;
            transition: all 0.2s linear;
        }

        .invoice-wrapper .line-items .print a:hover {
            text-decoration: none;
            border-color: #ff5e29;
            color: #fff;
            background-color: #ff5e29;
        }

        .invoice-wrapper .intro {
            line-height: 25px;
            color: #444;
        }

        .invoice-wrapper .call-to-action {
            display: inline-block;
            float: right;
        }

        .invoice-wrapper .logo {
            max-width: 120px;
            display: inline-block;
            color: #444;
        }

        .invoice-wrapper .logo img {
            width: 100%;
        }

        .invoice-wrapper .payment-info {
            margin-top: 25px;
            padding-top: 15px;
        }

        .invoice-wrapper .payment-info span {
            color: #ff5e29;
        }

        .invoice-wrapper .payment-info strong {
            display: block;
            color: #444;
            margin-top: 3px;
        }

        .invoice-wrapper .payment-details {
            border-top: 2px solid #ffd3c4;
            margin-top: 30px;
            padding-top: 20px;
            line-height: 22px;
        }

        .invoice-wrapper .line-items {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .invoice-wrapper .line-items thead.headers {
            color: #ff5e29;
            font-size: 13px;
            letter-spacing: 0.3px;
            padding-bottom: 4px;
        }

        .invoice-wrapper .line-items thead.headers tr th {
            border-bottom: 2px solid #ffe5dc;
            text-align: left;
        }

        .invoice-wrapper .line-items .items {
            margin-top: 8px;
            border-bottom: 2px solid #fff4f1;
            padding-bottom: 8px;
        }

        .invoice-wrapper .line-items .items .item {
            padding: 10px 0;
            color: #696969;
            font-size: 15px;
        }


        .invoice-wrapper .line-items .items .item .amount {
            letter-spacing: 0.1px;
            color: #84868A;
            font-size: 16px;
        }

        .invoice-wrapper .line-items .total {
            margin-top: 30px;
        }

        .invoice-wrapper .line-items .total .extra-notes {
            float: left;
            width: 40%;
            text-align: left;
            font-size: 13px;
            color: #7A7A7A;
            line-height: 20px;
        }

        .invoice-wrapper .account-details {
            border-top: 2px solid #fff4f1;
            padding-top: 10px;
        }

        .invoice-wrapper .account-details span {
            color: #ff5e29;
        }

        .invoice-wrapper .terms {
            color: #C9C9C9;
            text-align: right;
            padding-top: 5px;
        }

        .invoice-wrapper .line-items .total .extra-notes strong {
            display: block;
            margin-bottom: 5px;
            color: #454545;
        }

        .invoice-wrapper .line-items .total .field {
            margin-bottom: 7px;
            font-size: 14px;
            color: #555;
        }

        .invoice-wrapper .line-items .total .field.grand-total {
            margin-top: 10px;
            font-size: 16px;
            font-family: Arial, "Noto Sans", sans-serif;
        }

        .invoice-wrapper .line-items .total .field.grand-total span {
            color: #20A720;
            font-size: 16px;
        }

        .invoice-wrapper .line-items .total .field span {
            display: inline-block;
            margin-left: 20px;
            min-width: 85px;
            color: #84868A;
            font-size: 15px;
        }

        p { margin-top: 0; }

        table {
            width: 100%;
        }
    </style>
</head>
<body class="invoice-wrapper">
<div class="logo-wrapper">
    <div class="logo">
        <img src="{{ asset('assets/files/img/Jenflow-Logo.png') }}" alt="Jenflow Systems Ltd">
    </div>
    <div class="call-to-action pt-3 text-uppercase">
        <b>PURCHASE ORDER</b>
    </div>
</div>


<div class="payment-info">
    <table >
        <tr>
            <td>
                <span>Jenflow Systems Ltd.</span><br>
                Unit 16, Old Hall Industrial<br>
                Estate Revival Street<br>
                Walsall, WS3 3HJ<br>
                Registered in Scotland<br>
                VAT: 234 3454 24<br>
                01922 907711 | 07539 081756 | 07377 335978<br>
            </td>
            <td class="text-right">
                <span>Purchase Date</span>
                <strong>{{ format_date_time($detail->created_at) }}</strong>
                <span>Purchase Order No.</span>
                <strong>{{ $detail->id }}</strong>
            </td>
        </tr>
    </table>
</div>

<div class="payment-details">
    <table >
        <tr>
            <td>
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
            </td>
            <td class="text-right">
                <span>Shipping Address</span>
                <p>
                    <?php foreach ($detail->delivery_address_data as $key => $line) : if(in_array($key,$ignore_address_keys)) { continue; } ?>
                    {!! $line != '' ? $line.'<br>' : '' !!}
                    <?php endforeach; ?>
                </p>
            </td>
        </tr>
    </table>
</div>

<div class="line-items">
    <table>
        <thead class="headers">
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>VAT</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="items">
        <?php foreach ($detail->items as $item) : ?>
            <?php
                if($item->is_additional_shipping)  {
                    $additional_shipping = $item->net_cost;
                }
            ?>
            <tr>
                <td>{{ $item->product_title }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->item_cost,2,'.',',') }}</td>
                <td>{{ $item->vat_percentage }}%</td>
                <td class="text-right"> £{{ number_format($item->net_cost,2,'.',',') }}</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <table class="total">
        <tr>
            <td class="extra-notes" style="width: 75%; text-align: left">
                <div style="width: 300px;">
                    <strong>Extra Notes</strong>
                    {{ $detail->notes }}
                </div>
            </td>
            <td class="text-right" style="width: 25%;">
                <table style="text-align: right">
                    <tr class="field">
                        <td style="text-align: left" width="50%">Subtotal</td>
                        <td style="text-align: right" width="50%"><span>£{{ number_format($detail->net_cost-$additional_shipping,2,'.',',') }}</span></td>
                    </tr>
                    <?php if($detail->shipping) : ?>
                    <tr class="field">
                        <td style="text-align: left">Shipping</td>
                        <td style="text-align: right"><span>£{{ number_format($detail->shipping->shipping_cost,2,'.',',') }}</span></td>
                    </tr>
                    <?php elseif($additional_shipping) : ?>
                    <tr class="field">
                        <td style="text-align: left">Shipping</td>
                        <td style="text-align: right"><span>£{{ number_format($additional_shipping,2,'.',',') }}</span></td>
                    </tr>
                    <?php endif; ?>
                    <tr class="field">
                        <td style="text-align: left">VAT</td>
                        <td style="text-align: right"><span>£{{ number_format($detail->vat_cost,2,'.',',') }}</span></td>
                    </tr>
                    <!--<tr class="field">
                        <td style="text-align: left">Discount</td>
                        <td style="text-align: right"><span>£0</span></td>
                    </tr>-->
                    <tr class="field grand-total">
                        <td style="text-align: left">Total</td>
                        <td style="text-align: right"><span>£{{ number_format($detail->gross_cost,2,'.',',') }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

</body>

<?php if(isset($print)) : ?>
    <script type="text/javascript">
        window.print()
    </script>
<?php endif; ?>
</html>
