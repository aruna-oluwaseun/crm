<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/editors/summernote.css?ver=2.2.0') }}">
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
                                            <h4 class="nk-block-title">Invoices</h4>
                                            <p>Your invoices</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div>

                                    </div>
                                </div>

                                <div class="card card card-preview">
                                    <div class="card-inner p-0">
                                        <?php if($invoices && $invoices->count()) : ?>
                                        <table class="table table-tranx">
                                            <thead>
                                            <tr class="tb-tnx-head">
                                                <th class="tb-tnx-id"><span class="">#</span></th>
                                                <th class="tb-tnx-info">
                                                    <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                        <span>Bill to</span>
                                                    </span>
                                                    <span class="tb-tnx-date d-md-inline-block d-none">
                                                        <span class="d-md-none">Date</span>
                                                        <span class="d-none d-md-block">
                                                            <span>Issue Date</span>
                                                            <span>Items Ordered</span>
                                                        </span>
                                                    </span>
                                                </th>
                                                <th class="tb-tnx-amount is-alt">
                                                    <span class="tb-tnx-total">Gross</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Status</span>
                                                </th>
                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    <span>&nbsp;</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($invoices as $invoice) : ?>
                                            <?php
                                                $customer_name = 'NA';
                                                $customer_email= '';
                                                if(isset($invoice->salesOrder) && $invoice->salesOrder) {
                                                    $customer_name = $invoice->salesOrder->getFullNameAttribute();
                                                    $customer_email = $invoice->salesOrder->email;
                                                }
                                            ?>
                                            <tr class="tb-tnx-item">
                                                <td class="tb-tnx-id">
                                                    <a href="{{ url('invoices/detail/'.$invoice->id) }}"><span>#{{ $invoice->id }}</span></a>
                                                </td>
                                                <td class="tb-tnx-info">
                                                    <div class="tb-tnx-desc">
                                                        <span class="title"><a href="{{ url('invoices/detail/'.$invoice->id) }}">{{ $customer_name }}</a></span>
                                                    </div>
                                                    <div class="tb-tnx-date">
                                                        <span class="date">{{ format_date($invoice->created_at, 'dS M y') }}</span>
                                                        <span class="date">{{ $invoice->items->count() }}</span>
                                                    </div>
                                                </td>
                                                <td class="tb-tnx-amount is-alt">
                                                    <div class="tb-tnx-total">
                                                        <span class="amount">£{{ number_format($invoice->gross_cost) }}</span>
                                                    </div>
                                                    <div class="tb-tnx-status">
                                                        <span class="badge badge-dot badge-{{ $invoice->status->classes }}">{{ $invoice->status->title }}</span>
                                                    </div>
                                                </td>
                                                <td class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                            <span data-toggle="modal" data-target="#modalEmailInvoice" data-invoice-id="{{ $invoice->id }}" data-email-address="{{ $customer_email }}">
                                                                <a class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Email invoice to {{ $customer_email }}">
                                                                    <em class="icon ni ni-mail-fill"></em>
                                                                </a>
                                                            </span>
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-plain">
                                                                        <li><a href="{{ url('invoices/detail/'.$invoice->id) }}">View Detail</a></li>
                                                                        <li><a href="{{ url('invoices/'.$invoice->id) }}">View Invoice</a></li>
                                                                        <li><a href="{{ url('invoices/print/'.$invoice->id) }}" target="_blank">Print</a></li>
                                                                        <li><a href="{{ url('invoices/download/'.$invoice->id) }}">Download</a></li>
                                                                        <!--<li><a href="#">Remove</a></li>-->
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <?php else : ?>

                                        <div class="alert alert-warning text-center">There are no invoices to display</div>

                                        <?php endif; ?>

                                    </div>

                                    <div class="card-inner">
                                        <?php if($invoices && $invoices->count()) : ?>
                                        {{ $invoices->links() }}
                                        <?php endif;?>
                                    </div>
                                </div><!-- .card-preview -->


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

<div class="modal fade" tabindex="-1" id="modalEmailInvoice">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-invoice', ['invoice_id' => null,'email' => null]) }}
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
@endpush
<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">

    $(document).ready(function() {

        $('#modalEmailInvoice').on('shown.bs.modal', function (e) {
            var button = e.relatedTarget;
            var invoice_id = $(button).data('invoice-id');
            var email = $(button).data('email-address');

            $('#modalEmailInvoice [name="invoice_id"]').val(invoice_id);
            $('#modalEmailInvoice [name="email"]').val(email);
        });

    });

</script>


</html>
