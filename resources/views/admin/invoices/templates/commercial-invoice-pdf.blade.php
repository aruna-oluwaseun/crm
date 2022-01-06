<?php
    $sales_order = $detail->salesOrder;

    $customer_name = $sales_order->getFullNameAttribute();
    $billing_address = $sales_order->billing_address_data;
    $delivery_address = $sales_order->delivery_address_data;

    $ignore_address_keys = ['lat','lng'];

?>
<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <title>Invoice #{{ $detail->id }}</title>

    <style type="text/css">

        body {
            font-family: Roboto, sans-serif, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            font-size: 0.755rem;
            font-weight: 400;
            line-height: 1.65;
            color: black;
        }

        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }

        .pt-3, .py-3 {
            padding-top: 1rem !important;
        }

        .text-uppercase, .ucap {
            text-transform: uppercase !important;
        }

        .invoice-wrapper .payment-details span {
            color: black;
            display: block;
        }

        .invoice-wrapper .payment-details a {
            display: inline-block;
        }

        .invoice-wrapper .line-items .print a {
            display: inline-block;
            border: 1px solid black;
            padding: 13px 13px;
            border-radius: 5px;
            color: black;
            font-size: 13px;
            transition: all 0.2s linear;
        }

        .invoice-wrapper .line-items .print a:hover {
            text-decoration: none;
            border-color: black;
            color: #fff;
            background-color: black;
        }

        .invoice-wrapper .intro {
            line-height: 25px;
            color: black;
        }

        .invoice-wrapper .call-to-action {
            display: inline-block;
            float: right;
        }

        .invoice-wrapper .logo {
            max-width: 120px;
            display: inline-block;
            color: black;
        }

        .invoice-wrapper .logo img {
            width: 100%;
        }

        .invoice-wrapper .payment-info span {
            color: black;
        }

        .invoice-wrapper .payment-info strong {
            display: block;
            color: black;
            margin-top: 3px;
        }

        .invoice-wrapper .line-items {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .invoice-wrapper .line-items thead.headers {
            color: black;
            font-size: 13px;
            letter-spacing: 0.3px;
            padding-bottom: 4px;
        }

        .invoice-wrapper .line-items thead.headers tr th {
            text-align: left;
        }

        .invoice-wrapper .line-items .items {
            margin-top: 8px;
            border-bottom: 2px solid #fff4f1;
            padding-bottom: 8px;
        }

        .invoice-wrapper .line-items .items .item {
            padding: 10px 0;
            color: black;
            font-size: 15px;
        }


        .invoice-wrapper .line-items .items .item .amount {
            letter-spacing: 0.1px;
            color: black;
            font-size: 16px;
        }

        .invoice-wrapper .line-items .total {
            margin-top: 30px;
        }

        .invoice-wrapper .line-items .total .extra-notes {
            float: left;
            width: 40%;
            text-align: left;
            line-height: 20px;
        }

        .invoice-wrapper .account-details {
            border-top: 2px solid #fff4f1;
            padding-top: 10px;
        }

        .invoice-wrapper .account-details span {
            color: black;
        }

        .invoice-wrapper .terms {
            color: #C9C9C9;
            text-align: right;
            padding-top: 5px;
        }

        .invoice-wrapper .line-items .total .extra-notes strong {
            display: block;
            margin-bottom: 5px;
            color: black;
        }

        .invoice-wrapper .line-items .total .field {
            margin-bottom: 7px;
            font-size: 14px;
            color: black;
        }

        .invoice-wrapper .line-items .total .field.grand-total {
            margin-top: 10px;
            font-size: 16px;
            font-family: Arial, "Noto Sans", sans-serif;
        }

        .invoice-wrapper .line-items .total .field.grand-total span {
            font-size: 16px;
        }

        .invoice-wrapper .line-items .total .field span {
            display: inline-block;
            margin-left: 20px;
            min-width: 85px;
            color: black;
            font-size: 15px;
        }

        p { margin-top: 0; }

        table {
            width: 100%;
            margin: 0;
        }
        table.bordered {
            border: solid 1px black;
        }
        table thead {
            margin: 0; padding: 0;
        }
        table thead th {
            background: black;
            color: #fcfcfe;
            text-align: left;
            padding: 5px 10px;
        }

        table tbody tr td {
            padding: 5px 10px;
        }

        table tbody tr td:not(:last-child) {
            border-right: 1px solid black;
        }

        .signature {
            font-family: 'Mrs Saint Delafield', cursive;
            font-size: 34px;
        }

    </style>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Mrs+Saint+Delafield&display=swap" rel="stylesheet">
</head>
<body class="invoice-wrapper">
<h2 class="text-center" style="margin-top: 0">Commercial Invoice</h2>


<div class="payment-info">
    <table cellpadding="0" cellspacing="0" class="bordered">
        <thead>
            <tr>
                <th colspan="2">
                    From
                </th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php
                    $franchise = current_user_franchise();
                    $franchise_address = isset($franchise->addresses) ? $franchise->addresses()->first() : null;
                ?>
                Tax ID/VAT No: {{ $franchise->vat_number }}<br>
                EORI No: {{ $franchise->eori_number }}<br>
                <strong>Shipper Address:</strong>
                Michael Gibbs.<br>
                <?php if($franchise_address) : ?>
                {{ $franchise_address['line1'] }}<br>
                {{ $franchise_address['line2'] }}<br>
                {{ $franchise_address['line3'] }}<br>
                {{ $franchise_address['city'] }},{{ $franchise_address['county'] }}<br>
                {{ $franchise_address['postcode'] }},{{ $franchise_address['country'] }}<br>
                <?php endif; ?>
                Registered in Scotland<br>
                01922 907711 | 07539 081756 | 07377 335978<br>
            </td>
            <td class="text-right">
                Waybill Number: <br><br>
                Courier: {{ $detail->courier_title }}<br><br>
                Date: {{ format_date_time($detail->created_at) }}<br>
                Invoice No: {{ $sales_order->id }}<br>
                Terms of SAke (Incoterm): DAP<br>
                Reason for Export: Sales<br>
            </td>
        </tr>
        </tbody>

    </table>
</div>

<div class="payment-details">
    <table cellpadding="0" cellspacing="0" class="bordered">
        <thead>
            <tr>
                <th>Ship To</th>
                <th>Sold To</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <span>Tax ID/VAT No:</span>
                    <span>EORI:</span>
                    {{ $customer_name }}
                    <p>
                        <?php foreach ($billing_address as $key => $line) : if(in_array($key,$ignore_address_keys)) { continue; } if($line == '') { continue; } ?>
                        {{ $line }}{!! in_array($key,['title','city','county']) ? ',' : '<br>' !!}
                        <?php endforeach; ?>
                    </p>
                    <span>Phone:</span>
                    <span>Email:</span>
                </td>
                <td class="text-right">
                    <span>Tax ID/VAT No:</span>
                    <span>EORI:</span>
                    {{ $customer_name }}
                    <p>
                        <?php foreach ($delivery_address as $key => $line) : if(in_array($key,$ignore_address_keys)) { continue; } if($line == '') { continue; } ?>
                        {{ $line }}{!! in_array($key,['title','city','county']) ? ',' : '<br>' !!}
                        <?php endforeach; ?>
                    </p>
                    <span>Phone:</span>
                    <span>Email:</span>
                </td>
            </tr>
        </tbody>

    </table>
</div>

<div class="line-items">
    <table class="bordered" cellpadding="0" cellspacing="0">
        <thead class="headers">
            <tr>
                <th>Qty</th>
                <th>Description</th>
                <th>Commodity Code</th>
                <th>C/O</th>
                <th>Unit Value</th>
                <th>Total Value</th>
                <th>Currency</th>
                <th>Unit Net Weight</th>
                <th class="text-right">Net Weight</th>
            </tr>
        </thead>
        <tbody class="items">
        <?php
            $gross_net_weight = 0;
            $additional_shipping = 0;
            $gross_unit_value = 0;
            $gross_total = 0;
        ?>
        <?php foreach ($detail->items as $item) : ?>
            <?php
                $item_order_detail = isset($item->orderedItem) ? $item->orderedItem : null;
                $item_detail = isset($item->product) ? $item->product : null;

                if($item_order_detail->is_additional_shipping)  {
                    $additional_shipping = $item_order_detail->gross_cost;
                }

                $unit_net_weight = isset($item_order_detail->weight_kg) ? $item_order_detail->weight_kg/$item->qty : 'NA';
                $net_weight = isset($item_order_detail->weight_kg) ? $item_order_detail->weight_kg : 'NA';
                $gross_net_weight+=$net_weight;

                $gross_unit_value+=$item_order_detail->item_cost;
                $gross_total+= $item_order_detail->gross_cost;
            ?>
            <tr>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->product_title }}</td>
                <td>{{ $item_detail ? $item_detail->commodity_code : 'NA' }}</td>
                <td>UK</td>
                <td>{{ $item_order_detail ? number_format($item_order_detail->item_cost,2,'.',',') : 'NA' }}</td>{{-- Show with vat--}}
                <td>{{$item_order_detail ? number_format($item_order_detail->gross_cost,2,'.',',') : 'NA' }}</td>
                <td>GBP</td>
                <td>{{ number_format($unit_net_weight,2,'.',',')  }}kg</td>
                <td class="text-right">{{ number_format($net_weight,2,'.',',') }}kg</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<table cellpadding="0" cellspacing="0" class="total">
    <tr>
        <td class="extra-notes" width="55%" style="text-align: left; border: solid 1px black; border-right: none">
            <strong>Declaration: I declare that the information provided is true and correct to the best of my knowledge.</strong>
            <div>
                Signature: <span class="signature">{{ get_user()->getFullNameAttribute() }}</span>
                <div style="float: right">Date: {{ date('d/m/Y') }}</div>
            </div>
            <div>
                Position in Company: {{ get_user()->position_in_company ?: 'Business Administrator' }}</span>
            </div>

        </td>
        <td class="text-right" width="45%" style="border: solid 1px black; padding: 0;">
            <?php
                $boxes = 0; $pallets = 0;
                if(isset($detail->boxes) && $detail->boxes->count())
                {
                    foreach($detail->boxes as $box)
                    {
                        if(isset($box->product) && $box->product->count())
                        {
                            if($box->product->is_shipping_box) {
                                $boxes++;
                            } elseif( $box->product->is_shipping_pallet) {
                                $pallets++;
                            }
                        }
                    }
                }

                $packages = 'NA';
                if($boxes) {
                    $packages = $boxes.' '.($boxes > 1 ? 'Boxes' : 'Box').',';
                }
                if($pallets) {
                    $packages .= ' '.$pallets.' '.($pallets > 1 ? 'Pallets' : 'Pallet');
                }
                $packages = rtrim($packages, ',');
            ?>
            <table cellpadding="0" cellspacing="0" style="text-align: right">
                <tr>
                    <td style="text-align: left; padding: 0px 10px" width="50%">Invoice line total : £{{ number_format($gross_unit_value,2,'.',',') }}</td>
                    <td style="text-align: right; padding: 0px 10px" width="50%">Invoice sub-total : £{{ number_format($gross_total,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 0px 10px">Shipping : £{{ number_format($additional_shipping+$sales_order->shipping_gross,2,'.',',') }}</td>
                    <td style="text-align: right; padding: 0px 10px">Insurance : N/A</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 0px 10px">Total invoice amount : £{{ number_format($gross_total+$additional_shipping+$sales_order->shipping_gross,2,'.',',') }}</td>
                    <td style="text-align: right; padding: 0px 10px">Total number of packages : {{ $packages }}</td>
                </tr>
                <tr>
                    <td style="text-align: left; padding: 0px 10px">Total net weight : {{ number_format($gross_net_weight,2,'.',',') }}kg</td>
                    <td style="text-align: right; padding: 0px 10px">Total gross weight : {{ $detail->weight_kg }}kg</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table cellspacing="0" cellpadding="0">
    <tr>
        <td style="font-size: 11px">
            <strong>Additional Declaration:</strong> The exporter of the products (Jenflow Systems Ltd) covered by this document EORI NUMBER HERE declares that, except where otherwise clearly indicated, these products are of United Kingdom preferential origin according to rules of origin of the Generalized System of Preferences of the United Kingdom.<br>
            <strong>Signed: </strong><span class="signature" style="font-size: 18px">{{ get_user()->getFullNameAttribute() }}</span>
        </td>
    </tr>
</table>

</body>

<?php if(isset($print)) : ?>
    <script type="text/javascript">
        window.print()
    </script>
<?php endif; ?>
</html>
