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
                                            <h4 class="nk-block-title">Customers</h4>
                                            <p>Manage your customers</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                                        @can('create-customers')
                                                            <li><a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Customer</span></a></li>
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
                                        <table class=" nk-tb-list nk-tb-ulist" data-auto-responsive="false"><!-- datatable-init add to class -->
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <!--<th class="nk-tb-col nk-tb-col-check">
                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                            <input type="checkbox" class="custom-control-input" id="uid">
                                                            <label class="custom-control-label" for="uid"></label>
                                                        </div>
                                                    </th>-->
                                                    <th class="nk-tb-col"><span class="sub-text">Customer</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Phone(s)</span></th>
                                                    <th class="nk-tb-col tb-col-lg"><span class="sub-text">Created</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($customers) && $customers->count()) : ?>

                                                    <?php foreach($customers as $item) : ?>
                                                    <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                        <input type="hidden" class="customer-name" value="{{ $item->getFullNameAttribute() }}">
                                                        <input type="hidden" class="customer-notes" value="{{ $item->notes }}">
                                                        <!--<td class="nk-tb-col nk-tb-col-check">
                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input" id="uid1">
                                                                <label class="custom-control-label" for="uid1"></label>
                                                            </div>
                                                        </td>-->
                                                        <td class="nk-tb-col">
                                                            <div class="user-card">
                                                                <div class="ml-2 user-avatar hidden-800-up bg-dim-primary d-none d-sm-flex">
                                                                    <span>{{ $item->getInitialsAttribute() }}</span>
                                                                </div>
                                                                <div class="user-info">
                                                                    <span class="tb-lead"><a href="{{ url('customers/'.$item->id) }}">{{ $item->getFullNameAttribute() }}</a> <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                    <span>{{ $item->email }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-mb">
                                                            <span class="tb-lead">
                                                                {{ isset($item->contact_number) ? $item->contact_number : '' }}
                                                                {{ isset($item->contact_number2) ? ((isset($item->contact_number) && isset($item->contact_number2)) ? ' | ' : ' ').$item->contact_number2 : '' }}
                                                            </span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-lg">
                                                            <span>{{ date('dS M Y H:ia', strtotime($item->created_at)) }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status text-{{ $item->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($item->status) }}</span>
                                                        </td>
                                                        <td class="nk-tb-col nk-tb-col-tools">
                                                            <ul class="nk-tb-actions gx-1">
                                                                <?php if($item->status == 'active') : ?>
                                                                    <li class="nk-tb-action-hidden status-result">
                                                                        <a href="#" class="btn btn-icon" data-toggle="modal" data-target="#modalSuspend" data-toggle="tooltip" data-placement="top" title="Suspend Customer">
                                                                            <em class="icon ni ni-user-cross-fill"></em>
                                                                        </a>
                                                                    </li>
                                                                <?php else : ?>
                                                                    <li class="nk-tb-action-hidden status-result">
                                                                        <a href="{{ url('customer-status') }}" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Re-activate Customer">
                                                                            <em class="icon ni ni-user-check-fill"></em>
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <ul class="link-list-opt no-bdr">
                                                                                <!--<li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>-->
                                                                                <li><a href="{{ url('customers/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                                                <!--<li><a href="#"><em class="icon ni ni-repeat"></em><span>Transaction</span></a></li>
                                                                                <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Activities</span></a></li>
                                                                                <li class="divider"></li>
                                                                                <li><a href="#"><em class="icon ni ni-shield-star"></em><span>Reset Pass</span></a></li>
                                                                                <li><a href="#"><em class="icon ni ni-shield-off"></em><span>Reset 2FA</span></a></li>
                                                                                <li><a href="#"><em class="icon ni ni-na"></em><span>Suspend User</span></a></li>-->
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr><!-- .nk-tb-item  -->
                                                    <?php endforeach; ?>

                                                <?php else : ?>

                                                    <tr>
                                                        <div class="alert alert-warning m-3">No records found</div>
                                                    </tr>

                                                <?php endif; ?>

                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="card-inner">
                                        <?php if(isset($customers) && $customers->count()) : ?>
                                            {{ $customers->links() }}
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

{{-- Suspend user modal--}}
<div class="modal fade" tabindex="-1" id="modalSuspend">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suspend <span></span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ url('customer-status') }}" id="suspend-form" class="is-alter">
                    @method('PUT')
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="status" name="status" value="suspended">
                    <div class="form-group">
                        <label class="form-label" for="notes">Suspend Reason</label>
                        <div class="form-control-wrap">
                            <textarea class="form-control" name="notes" id="notes"></textarea>
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

<div class="modal fade" tabindex="-1" id="modalCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create <span>customer</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('customers') }}" id="create-form" class=" form-validate">
                    @csrf
                    <div class="alert alert-warning" id="create-status" style="display: none"></div>

                    <div class="alert alert-info mb-2"><b>Please note :</b> you can create an address for the customer once the customer is created</div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First name *</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="text" name="first_name" id="add-first-name" value="{{ old('first_name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last name *</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="text" name="last_name" id="add-last-name" value="{{ old('last_name') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email * (username)</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="email" name="email" id="add-email" value="{{ old('email') }}" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="text" name="password" id="add-password" value="GENERATE_ME_ONE" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input name="send_password_email" type="checkbox" class="custom-control-input" id="send-password-email" checked="checked" value="1">
                            <label class="custom-control-label" for="send-password-email"> Email password to <span class="text-primary" id="password-email">Enter Email</span></label>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contact number </label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="text" name="contact_number" value="{{ old('contact_number') }}" id="add-contact-number">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Contact number 2</label>
                                <div class="form-control-wrap">
                                    <input class="form-control" type="text" name="contact_number2" value="{{ old('contact_number2') }}" id="add-contact-number-2">
                                </div>
                            </div>
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

{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript" src="{{ asset('assets/js/admin/customer-list.js') }}"></script>

</html>
