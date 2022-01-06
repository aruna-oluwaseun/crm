<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/invoice.css') }}">
@endpush
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

                            <div class="nk-block nk-block-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Refund #{{ $detail->id }}</h4>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        {{--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>--}}
                                                        <li><a href="{{ url('refunds/download/'.$detail->id) }}" class="btn btn-white btn-primary"><em class="icon ni ni-download"></em><span>Download Refund</span></a></li>
                                                        <li><a href="#" data-toggle="modal" data-target="#modalEmail" class="btn btn-white btn-outline-light"><em class="icon ni ni-mail"></em><span>Email Refund</span></a></li>

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
                            </div>


                            <div class="receipt-content">
                                <div class="container bootstrap snippets bootdey">
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{ view('admin.refunds.templates.refund-detail',['detail' => $detail]) }}

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
        <!-- wrap @e -->
    </div>
    <!-- main @e -->
</div>

<div class="modal fade" tabindex="-1" id="modalEmail">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-refund', ['email' => $detail->email,'id' => $detail->id]) }}
    </div>
</div>


@push('scripts')

@endpush
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">

</script>

</html>
