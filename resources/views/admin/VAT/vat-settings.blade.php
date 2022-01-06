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
                                            <h4 class="title nk-block-title">Authorise account</h4>
                                            <div class="nk-block-des">
                                                <p>Authorise this software to access your VAT account</p>
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

                                    <div class="col-12">
                                        <?php if($franchise->vat_number) : ?>
                                        <div class="alert alert-info">It is important you authorise a HMRC account that manages this VAT number : <b>{{ $franchise->vat_number }}</b> if this is not correct please change it in your <a href="{{ url('settings') }}">account settings here</a></div>
                                        <?php else : ?>
                                        <div class="alert alert-info">You need to enter your VAT number in your <a href="{{ url('settings') }}">account settings here</a> </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-sm-6 col-lg-4 col-xxl-3">
                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <div class="team">
                                                    <div class="user-card user-card-s2">
                                                        <div class="user-avatar lg bg-primary">
                                                            <!--<img src="./images/avatar/a-sm.jpg" alt="">-->
                                                            <span>VAT</span>
                                                            <!--<div class="status dot dot-lg dot-success"></div>-->
                                                        </div>
                                                        <div class="user-info">
                                                            <h6>Allow this software to access your VAT</h6>
                                                            <span class="sub-text">Software will be able to read:vat and write:vat</span>
                                                        </div>
                                                    </div>
                                                    <div class="team-view">
                                                        <?php if($environment->isSandbox()) : ?>
                                                            <a href="{{ url('vat/testing/0') }}" class="btn btn-block btn-dim btn-primary"><span>First : Disable Testing</span></a>
                                                        <?php else : ?>
                                                            <?php if($franchise->hmrc_access_token) : ?>
                                                            <a href="{{ url('vat/create-access-token/'.current_user_franchise_id()) }}" class="btn btn-block btn-dim btn-success"><span>Access Granted</span></a>
                                                            <?php else : ?>
                                                            <a href="{{ url('vat/create-access-token/'.current_user_franchise_id()) }}" class="btn btn-block btn-dim btn-primary"><span>Authorise</span></a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div><!-- .team -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card -->
                                    </div><!-- .col -->

                                    <?php if($franchise->global_owner) : ?>
                                    <div class="col-sm-6 col-lg-4 col-xxl-3">
                                        <div class="card card-bordered">
                                            <div class="card-inner">
                                                <div class="team">
                                                    <div class="user-card user-card-s2">
                                                        <div class="user-avatar lg bg-primary">
                                                            <!--<img src="./images/avatar/a-sm.jpg" alt="">-->
                                                            <span>VAT</span>
                                                            <!--<div class="status dot dot-lg dot-success"></div>-->
                                                        </div>
                                                        <div class="user-info">
                                                            <h6>TESTING</h6>
                                                            <span class="sub-text">Set up software for TESTING</span>
                                                        </div>
                                                    </div>
                                                    <div class="team-view">
                                                        <?php if($environment->isSandbox()) : ?>
                                                            <?php if($franchise->hmrc_test_access_token) : ?>
                                                                <a href="{{ url('vat/create-access-token/'.current_user_franchise_id()) }}" class="btn btn-block btn-dim btn-success"><span>Access Granted</span></a>
                                                            <?php else : ?>
                                                                <a href="{{ url('vat/create-access-token/'.current_user_franchise_id()) }}" class="btn btn-block btn-dim btn-primary"><span>Authorise</span></a>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <a href="{{ url('vat/testing/1') }}" class="btn btn-block btn-dim btn-primary"><span>First : Turn on Testing</span></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div><!-- .team -->
                                            </div><!-- .card-inner -->
                                        </div><!-- .card -->
                                    </div><!-- .col -->
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


</html>
