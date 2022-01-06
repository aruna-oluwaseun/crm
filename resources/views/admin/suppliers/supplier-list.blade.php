<!DOCTYPE html>
<html lang="eng" class="js">

@push('styles')

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
                                            <h4 class="nk-block-title">Suppliers</h4>
                                            <p>View and create suppliers</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <!--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>-->
                                                        @can('create-suppliers')
                                                        <li><a href="#" data-toggle="modal" data-target="#modalCreateSupplier" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Supplier</span></a></li>
                                                        @endcan
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

                                <div class="card card-preview">
                                    <div class="card-inner p-0">

                                        <table class="table table-tranx">
                                            <thead>
                                            <tr class="tb-tnx-head">
                                                <th class="tb-tnx-id"><span class="">#</span></th>
                                                <th class="tb-tnx-info">
                                                            <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                                <span>Supplier</span>
                                                            </span>
                                                    <span class="tb-tnx-date d-md-inline-block d-none">
                                                                <span class="d-md-none">Contact</span>
                                                                <span class="d-none d-md-block">
                                                                    <span>Phone</span>
                                                                    <span>Email</span>
                                                                </span>
                                                            </span>
                                                </th>
                                                <th class="tb-tnx-amount is-alt">
                                                    <span class="tb-tnx-total">Spend</span>
                                                    <span class="tb-tnx-status d-none d-md-inline-block">Products Linked</span>
                                                </th>
                                                <th class="tb-tnx-action">
                                                    <span>&nbsp;</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($suppliers) && $suppliers->count()) : ?>

                                                <?php foreach($suppliers as $item) : ?>
                                                <?php
                                                    $spend = 0;
                                                    if(isset($item->purchases->items))
                                                    {
                                                        $spend = $item->purchases->items()->whereNull('cancelled')->sum('gross_cost');
                                                    }
                                                ?>
                                                <tr class="tb-tnx-item">
                                                    <td class="tb-tnx-id">
                                                        <a href="{{ url('suppliers/'.$item->id) }}"><span>{{ $item->id }}</span></a>
                                                    </td>
                                                    <td class="tb-tnx-info">
                                                        <div class="tb-tnx-desc">
                                                            <span class="title"><a href="{{ url('suppliers/'.$item->id) }}">{{ $item->title }}</a></span>
                                                        </div>
                                                        <div class="tb-tnx-date">
                                                            <span class="date">{{ $item->contact_number }}</span>
                                                            <span class="date">{{ $item->email }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-tnx-amount is-alt">
                                                        <div class="tb-tnx-total">
                                                            <span class="amount">£{{ number_format($spend, 2,'.',',') }}</span>
                                                        </div>
                                                        <div class="tb-tnx-status">
                                                            <span class="badge badge-dot badge-{{ $item->products->count() ? 'success' : 'warning' }}">{{ $item->products->count() }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="tb-tnx-action">
                                                        <div class="dropdown">
                                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                                <ul class="link-list-plain">
                                                                    <li><a href="{{ url('suppliers/'.$item->id) }}">View</a></li>
                                                                    <!--<li><a href="#">Remove</a></li>-->
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>

                                            <?php else: ?>
                                            <tr>
                                                <div class="alert alert-warning m-3">No records found</div>
                                            </tr>
                                            <?php endif; ?>

                                            </tbody>
                                        </table>


                                    </div>
                                    <div class="card-inner">
                                        <?php if(isset($suppliers) && $suppliers->count()) : ?>
                                            {{ $suppliers->links() }}
                                        <?php endif; ?>
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


<div class="modal fade" tabindex="-1" id="modalCreateSupplier">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>Supplier</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('suppliers') }}" id="create-form" class=" form-validate">
                    @csrf
                    <div class="alert alert-info">More options will be available after you have created the supplier</div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Supplier *</label>
                        <div class="form-control-wrap">
                            <input name="title" class="form-control" type="text" value="{{ old('title') }}" placeholder="Supplier name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Supplier Code</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" name="code" value="{{ old('code') }}" placeholder="A reference code">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label" for="notes">Contact name *</label>
                        <div class="form-control-wrap">
                            <input name="contact_name" class="form-control" type="text" value="{{ old('contact_name') }}" placeholder="Contact name" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Email *</label>
                        <div class="form-control-wrap">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Phone *</label>
                        <div class="form-control-wrap">
                            <input type="number" class="form-control" name="contact_number" value="{{ old('contact_number') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
            </div>
            {{--<div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>--}}
        </div>
    </div>
</div>

@push('scripts')

@endpush
<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">

</script>


</html>
