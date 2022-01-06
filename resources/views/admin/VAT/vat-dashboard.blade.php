<?php
    $environment = \App\Repositories\HMRC\Environment\Environment::getInstance();
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

            <?php if($environment->isSandbox()) : ?>
                <div class="alert alert-warning text-center" style="border-radius: 0;">You are in test mode for VAT, disable test mode <a href="{{ url('vat/testing/0') }}">here</a></div>
            <?php endif; ?>

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
                                            <h4 class="title nk-block-title">VAT Reporting Tools</h4>
                                            <div class="nk-block-des">
                                                <p></p>
                                            </div>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <!--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-printer"></em><span>Print build sheet</span></a></li>-->
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
                                <div class="row g-gs">

                                    <?php if($franchise->hmrc_access_token || $franchise->hmrc_test_access_token) : ?>

                                        <div class="col-12">
                                            <?php if($environment->isSandbox()) : ?>
                                                <div class="alert alert-info">You are using the VAT Reporting Tools in test mode</div>
                                            <?php else : ?>
                                                <div class="alert alert-info">You are using the VAT Reporting Tools in test mode</div>
                                            <?php endif; ?>
                                        </div>


                                        <div class="col-12">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner">

                                                    <ul class="nav nav-tabs">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" data-toggle="tab" href="#tabObligations">Obligations</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#tabSubmitVat">Submit VAT</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#tabViewVatReturn">VAT Return</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" data-toggle="tab" href="#tabVatPayments">VAT Payments</a>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content">

                                                        <!-- Tab Obligations -->
                                                        <div class="tab-pane active" id="tabObligations">
                                                            <livewire:admin.vat-obligation :franchise="$franchise" />
                                                        </div>

                                                        <!-- Submit VAT for period -->
                                                        <div class="tab-pane" id="tabSubmitVat">
                                                            <livewire:admin.vat-return :franchise="$franchise" />
                                                        </div>

                                                        <!-- View VAT return -->
                                                        <div class="tab-pane" id="tabViewVatReturn">
                                                            <livewire:admin.view-vat-return :franchise="$franchise" />
                                                        </div>

                                                        <!-- View VAT return -->
                                                        <div class="tab-pane" id="tabVatPayments">
                                                            <livewire:admin.vat-payment :franchise="$franchise" />
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">

                                        </div>

                                    <?php else : ?>

                                        <div class="col-12">
                                            <div class="alert alert-warning text-center">You have not authorised your HMRC vat account to be accessed via this software, please <a href="{{ url('vat/settings') }}">click here to authorise this software</a>.</div>
                                        </div>

                                    <?php endif; ?>
                                </div>
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

{{ view('admin.templates.footer') }}
</body>
<script type="text/javascript" src="{{ asset('assets/js/admin/vat.js') }}"></script>


</html>
