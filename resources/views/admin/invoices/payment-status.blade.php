<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')

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

                                        <div class="col-12">

                                            <?php if($success) : ?>
                                                <div class="col-md-6 offset-md-3 text-center mt-5">
                                                    <div class="alert alert-success">
                                                        <div style="font-size: 48px">
                                                            <em class="icon ni ni-check-circle"></em>
                                                        </div>
                                                        <h4>Thanks for your payment</h4>
                                                        <p>
                                                            Your payment reference is : <b>{{ $payment->payment_ref }}</b>
                                                            <?php if(isset($email)) : ?>
                                                                We have sent you confirmation to : <b>{{ $email }}</b>.
                                                            <?php endif; ?>
                                                        </p>
                                                        <a href="https://jenflow.co.uk" class="btn btn-success btn-lg">Go back to Jenflow</a>
                                                    </div>
                                                </div>
                                            <?php else : ?>

                                                <div class="col-md-6 offset-md-3 text-center mt-5">
                                                    <div class="alert alert-danger">
                                                        <div style="font-size: 48px">
                                                            <em class="icon ni ni-cross-circle"></em>
                                                        </div>
                                                        <h4>Payment failed</h4>
                                                        <p>
                                                           An error occurred processing your payment, we have not charged your card. We recommend double checking your account to make sure. Please try again or contact us if you need further help.
                                                        </p>
                                                        <a href="https://jenflow.co.uk" class="btn btn-danger btn-lg">Go back to Jenflow</a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        </div>

                                        <div class="col-md-12 mt-5">

                                            <div class="footer text-center">
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


@push('scripts')

@endpush
{{ view('admin.templates.footer') }}
</body>

</html>
