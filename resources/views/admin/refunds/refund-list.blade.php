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
                                            <h4 class="nk-block-title">Refunds</h4>
                                            <p>Your refunds</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                        @can('create-refunds')
                                                            <li><a href="#modalRefundCreate" data-toggle="modal" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>New refund</span></a></li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div>

                                    </div>
                                </div>

                                <div class="card card card-preview">
                                    <div class="card-inner p-0">
                                        <?php if($refunds && $refunds->count()) : ?>
                                        <table class="table table-tranx">
                                            <thead>
                                            <tr class="tb-tnx-head">
                                                <th class="tb-tnx-id"><span class="">#</span></th>
                                                <th class="tb-tnx-info">
                                                    <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                        <span>Refund to</span>
                                                    </span>
                                                    <span class="tb-tnx-date d-md-inline-block d-none">
                                                        <span class="d-md-none">Date</span>
                                                        <span class="d-none d-md-block">
                                                            <span>Issue Date</span>
                                                            {{--<span>Total Refunded</span>--}}
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
                                            <?php foreach($refunds as $refund) : ?>
                                            <?php
                                                $status_class = 'default';
                                                switch ($refund->status)
                                                {
                                                    case 'pending' :
                                                        $status_class = 'default';
                                                        break;
                                                    case 'processing' :
                                                        $status_class = 'info';
                                                        break;
                                                    case 'complete' :
                                                        $status_class = 'complete';
                                                        break;
                                                    case 'error' :
                                                        $status_class = 'danger';
                                                        break;
                                                }
                                            ?>
                                            <tr class="tb-tnx-item">
                                                <td class="tb-tnx-id">
                                                    <a href="{{ url('refunds/detail/'.$refund->id) }}"><span>#{{ $refund->id }}</span></a>
                                                </td>
                                                <td class="tb-tnx-info">
                                                    <div class="tb-tnx-desc">
                                                        <span class="title"><a href="{{ url('refunds/detail/'.$refund->id) }}">{{ $refund->getFullNameAttribute() }}</a> </span>
                                                    </div>
                                                    <div class="tb-tnx-date">
                                                        <span class="date">{{ format_date($refund->created_at, 'dS M y') }}</span>
                                                        {{--<span class="date">{{ $refund->items->count() }}</span>--}}
                                                    </div>
                                                </td>
                                                <td class="tb-tnx-amount is-alt">
                                                    <div class="tb-tnx-total">
                                                        <span class="amount">£{{ number_format($refund->gross_cost) }}</span>
                                                    </div>
                                                    <div class="tb-tnx-status">
                                                        <span class="badge badge-dot badge-{{ $status_class }}">{{ ucfirst($refund->status) }}</span>
                                                    </div>
                                                </td>
                                                <td class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                            <span data-toggle="modal" data-target="#modalEmailRefund" data-refund-id="{{ $refund->id }}" data-email-address="{{ $refund->email }}">
                                                                <a class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Email invoice to {{ $refund->email }}">
                                                                    <em class="icon ni ni-mail-fill"></em>
                                                                </a>
                                                            </span>
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-plain">
                                                                        <li><a href="{{ url('refunds/detail/'.$refund->id) }}">View Detail</a></li>
                                                                        <li><a href="{{ url('refunds/'.$refund->id) }}">View Refund</a></li>
                                                                        <li><a href="{{ url('refunds/print/'.$refund->id) }}" target="_blank">Print</a></li>
                                                                        <li><a href="{{ url('refunds/download/'.$refund->id) }}">Download</a></li>
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
                                        <?php if($refunds && $refunds->count()) : ?>
                                        {{ $refunds->links() }}
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

<div class="modal fade" tabindex="-1" id="modalRefundCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create <span>Refund</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('refunds') }}">
                    @csrf

                    <div class="alert alert-info">
                        You can add the items you are refunding in the next section
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Customer *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="customer_id" data-search="on" required>
                                <?php if($customers = customers(true)) : ?>
                                <option>Select customer</option>
                                <?php foreach($customers as $customer) : ?>
                                <option value="{{ $customer->id }}" data-first-name="{{ $customer->first_name }}" data-last-name="{{ $customer->last_name }}" data-email="{{ $customer->email }}" data-contact-number="{{ $customer->contact_number }}" data-contact-number2="{{ $customer->contact_number2 }}" {{ is_selected($customer->id, old('customer_id', isset($detail->customer_id) ? $detail->customer_id : '')) }}>{{ $customer->getFullNameAttribute() }} - {{ $customer->email }}</option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Customer detail -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last name *</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="text" name="first_name" value="{{ old('first_name', isset($detail->first_name) ? $detail->first_name : '') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last name *</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="text" name="last_name" value="{{ old('last_name', isset($detail->last_name) ? $detail->last_name : '') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contact number</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="tel" name="contact_number" value="{{ old('contact_number', isset($detail->contact_number) ? $detail->contact_number : '') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="email" name="email" value="{{ old('email', isset($detail->email) ? $detail->email : '') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Reason for issuing refund *</label>
                        <textarea name="reason" class="form-control" required>{{ old('notes', isset($detail->notes) ? $detail->notes : '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalEmailRefund">
    <div class="modal-dialog" role="document">
        {{ view('admin.templates.modals.email-refund', ['id' => null,'email' => null]) }}
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

        $('#modalEmailRefund').on('shown.bs.modal', function (e) {
            var button = e.relatedTarget;
            var refund_id = $(button).data('refund-id');
            var email = $(button).data('email-address');

            $('#modalEmailRefund [name="refund_id"]').val(refund_id);
            $('#modalEmailRefund [name="email"]').val(email);
        });

        $(document).on('change','[name="customer_id"]', function() {
            var selected = $(this).find(':selected');
            var first_name = selected.data('first-name');
            var last_name = selected.data('last-name');
            var email = selected.data('email');
            var contact_number = selected.data('contact-number');
            var contact_number2 = selected.data('contact-number2');

            if(contact_number) {
                $('[name="contact_number"]').val(contact_number);
            } else {
                $('[name="contact_number"]').val(contact_number2);
            }

            $('[name="first_name"]').val(first_name);
            $('[name="last_name"]').val(last_name);
            $('[name="email"]').val(email);
        });

    });

</script>


</html>
