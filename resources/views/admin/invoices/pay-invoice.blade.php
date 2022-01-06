<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/invoice.css') }}">
@endpush
{{ view('admin.templates.header') }}

<body class="nk-body npc-default has-apps-sidebar has-sidebar">

<div class="nk-app-root">

    <!-- main @s -->
    <div class="nk-main ">

            <!-- main header @s -->
        <div class="nk-header nk-header-fixed is-light">
            <div class="container-fluid">
                <div class="nk-header-wrap">
                    <div class="nk-menu-trigger d-xl-none ml-n1">
                        <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                    </div>
                    <div class="nk-header-app-name">
                        <div class="nk-header-app-logo">
                            <img src="{{ asset('assets/files/img/Jenflow-Logo.png') }}" alt="Jenflow Systems Ltd">
                        </div>
                        <!--<div class="nk-header-app-info">
                            <span class="sub-text">DashLite</span>
                            <span class="lead-text">Dashboard</span>
                        </div>-->
                    </div>
                    <div class="nk-header-menu is-light">
                        <div class="nk-header-menu-inner">
                            <!-- Menu -->
                            <ul class="nk-menu nk-menu-main">
                                <li class="nk-menu-item">
                                    <a href="https://jenflow.co.uk" class="nk-menu-link">
                                        <span class="nk-menu-text"><em class="icon ni ni-back-arrow"></em> BACK TO JENFLOW</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- Menu -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- main header @e -->

            <!-- content @s -->
            <div class="nk-content ">

                <div class="container">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">

                            <div class="receipt-content">
                                <div class="container bootstrap snippets bootdey">

                                    <div class="row">

                                        {{ view('admin.templates.alert') }}

                                        <?php if(get_setting('testing_payments')) : ?>

                                        <div class="col-md-12 mb-3" >
                                            <div class="alert alert-warning alert-icon">
                                                PAYMENT GATEWAY IN TEST
                                            </div>
                                        </div>

                                        <?php endif; ?>

                                        <?php if($detail->is_paid) : ?>

                                        <div class="col-md-12 mb-3" >
                                            <div class="alert alert-success alert-icon">
                                                <em class="icon ni ni-check"></em> Thank you, this invoice was paid on {{ long_date_time($detail->updated_at) }}.
                                            </div>
                                        </div>

                                        <?php elseif($detail->client_secret && !$detail->is_paid) : ?>

                                            <?php if($detail->link_expired) : ?>

                                                <div class="col-md-6 offset-md-3 text-center mt-5">
                                                    <div class="alert alert-info">
                                                        <div style="font-size: 48px">
                                                            <em class="icon ni ni-alert-circle"></em>
                                                        </div>
                                                        <h4>Link has expired</h4>
                                                        <p>
                                                            Payment links are valid for 24 hours from date of invoice for security reasons, please contact us if you would like a new payment link.
                                                        </p>
                                                        <a href="https://jenflow.co.uk" class="btn btn-success btn-lg">Go back to Jenflow</a>
                                                    </div>
                                                </div>

                                            <?php else : ?>

                                                <div id="pay-form" class="col-md-12">
                                                    <div class="card card-bordered">
                                                        <div class="card-header bg-primary text-white border-bottom">Pay invoice</div>
                                                        <div class="card-body">

                                                            <div class="alert alert-info">
                                                                <b>Please note :</b> Address of card must match billing address below
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="form-label">Card Owner</label>
                                                                <div class="input-wrapper">
                                                                    <input type="text" name="card-owner" placeholder="Name on card" class="form-control required" >
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="form-label">Card Number</label>
                                                                <div class="input-wrapper" id="card_number">

                                                                </div>
                                                            </div>

                                                            <div class="row mb-5">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Card Expiry Date</label>
                                                                        <div class="input-wrapper" id="card_expiry">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">CVC / CVV2</label>
                                                                        <div class="input-wrapper" id="card_cvc">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox terms">
                                                                    <input name="terms_accept" type="checkbox" class="custom-control-input required" id="terms-accept" value="1">
                                                                    <label class="custom-control-label" for="terms-accept"> I agree to the <a href="">terms &amp; conditions</a>.</label>
                                                                </div>

                                                                <div class="float-right" style="font-size: 34px;">
                                                                    <em class="icon ni ni-cc-stripe "></em>
                                                                    <em class="icon ni ni-visa"></em>
                                                                    <em class="icon ni ni-cc-mc"></em>
                                                                    <em class="icon ni ni-american-express"></em>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <button type="button" class="submit-btn btn btn-lg btn-primary" disabled>Pay £{{ number_format($detail->total_due,2,',','.') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php endif; ?>

                                        <?php else : ?>

                                        <div class="col-md-12 mb-3" >
                                            <div class="alert alert-danger alert-icon">
                                                {{ $detail->client_secret ? 'An unknown error has occurred, please try again, if the problem persists, please contact us and quote #error-#-'.$detail->id : 'Sorry we could not connect to our payment service, please reload and try again.' }}
                                            </div>
                                        </div>

                                        <?php endif; ?>

                                        <div class="col-md-12">
                                            {{ view('admin.invoices.templates.invoice-detail',['detail' => $detail]) }}

                                            <div class="footer">
                                                Copyright © {{ date('Y') }}. {{ env('COMPANY_NAME') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- content @e -->

    </div>
    <!-- main @e -->
</div>

<?php
    $sales_order = false;
    if($detail->salesOrder) {
        $sales_order = $detail->salesOrder;
    }
?>

<div class="modal fade" tabindex="-1" id="modalEmail">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-invoice', ['email' => $sales_order->email,'invoice_id' => $detail->id]) }}
    </div>
</div>


@push('scripts')
    <script src="{{ env('STRIPE_JS_URL', 'https://js.stripe.com/v3/') }}"></script>
    <script src="{{ asset('assets/js/checkout.js') }}"></script>
@endpush
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">
    let url = '{{ url('invoices/pay/'.sha1($detail->id)) }}';
    let invoice_id = '{{ $detail->id }}';
    let payment_id = '{{ $detail->payment_id }}';
    let stripe_public_key = '{{ get_setting('testing_payments') ? env('STRIPE_TEST_KEY') : env('STRIPE_KEY') }}';
    let stripe_client_secret = '{{ $detail->client_secret }}';
    let receipt_email = '{{$sales_order->email}}';

    // init
    let city = null;
    let country = null;
    let line1 = null;
    let line2 = null
    let postcode = null;
    let county = null;

    <?php if($sales_order->billing_address_data) : ?>
        <?php foreach($detail->salesOrder->billing_address_data as $key => $line) : ?>
        <?php
        if($key == 'country') {
            $line = country_code($line);
        }
        ?>
        {{$key}} = '{{$line}}';
    <?php endforeach; ?>
    <?php endif; ?>

</script>

</html>
