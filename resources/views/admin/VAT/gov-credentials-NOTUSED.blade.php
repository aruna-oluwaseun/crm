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
                                            <h4 class="title nk-block-title">Gov Credentials</h4>
                                            <div class="nk-block-des">
                                                <p></p>
                                            </div>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <?php if($credentials->count() < 2) : ?>
                                                            <li><a href="#" data-toggle="modal" data-target="#modalCreate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add credentials</span></a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div>
                                    </div>
                                </div>

                                <!-- table -->
                                <div class="card card-preview">
                                    <div class="card-inner">
                                        <table class=" nk-tb-list nk-tb-ulist"><!-- datatable-init-->
                                            <thead>
                                            <tr class="nk-tb-item nk-tb-head">
                                                <!--<th class="nk-tb-col nk-tb-col-check">
                                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                                        <input type="checkbox" class="custom-control-input" id="uid">
                                                        <label class="custom-control-label" for="uid"></label>
                                                    </div>
                                                </th>-->
                                                <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Client ID</span></th>
                                                <th class="nk-tb-col tb-col-md"><span class="sub-text">Client Secret</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Type</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Has Access</span></th>
                                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(isset($credentials) && $credentials->count()) : ?>

                                            <?php foreach($credentials as $item) : ?>

                                            <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                <!--<td class="nk-tb-col nk-tb-col-check">
                                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                                        <input type="checkbox" class="custom-control-input" id="uid1">
                                                        <label class="custom-control-label" for="uid1"></label>
                                                    </div>
                                                </td>-->
                                                <td class="nk-tb-col">{{ $item->id }}</td>
                                                <td class="nk-tb-col">
                                                    <span class="tb-status ">{{ $item->client_id }}</span>
                                                </td>
                                                <td class="nk-tb-col tb-col-md">
                                                    <span class="tb-status ">{{ $item->client_secret }}</span>
                                                </td>
                                                <td class="nk-tb-col">
                                                    <span class="tb-status {{ $item->is_test_creds ? '' : 'text-success' }}">{{ $item->is_test_creds ? 'Testing' : 'Production' }}</span>
                                                </td>
                                                <td class="nk-tb-col">
                                                    <span class="tb-status">{!! $item->access_token ? '<a href="'.url('vat/create-access-token/'.$item->id).'" class="btn btn-success">Access Granted</a>' : '<a href="'.url('vat/create-access-token/'.$item->id).'" class="btn btn-primary">Get access</a>' !!}</span>
                                                </td>
                                                <td class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <form id="delete-form-{{ $item->id }}" method="post" action="{{ url('vat/delete/'.$item->id) }}">
                                                                            @method('delete')
                                                                            @csrf
                                                                            <input type="hidden" name="im_here_so_the_form_submits" value="1">
                                                                        </form>
                                                                        <li><a href="#" class="destroy-resource" data-message="Please note : If you delete all the HMRC credentials you will not be able to access the reporting tools." form="delete-form-{{ $item->id }}"><em class="icon ni ni-eye"></em><span>Delete</span></a></li>
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

<div class="modal fade" tabindex="-1" id="modalCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Accessing Gov API's</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('vat/store') }}" id="create-form" class=" form-validate">
                    @csrf
                    <?php
                        $vat_number = null;
                        if( $franchise = current_user_franchise() )
                        {
                             $vat_number = str_replace(' ', '', $franchise->vat_number);
                        }
                    ?>
                    <div class="alert alert-info">
                        To access the government API's you will need a HMRC developer account, you can sign up here : https://developer.service.hmrc.gov.uk/api-documentation
                        <ol style="list-style: decimal; padding-left: 25px;">
                            <li>Create Account</li>
                            <li>Add application (name it whatever you want) subscribte to hello and VAT MTD api's</li>
                            <li>Create a client ID</li>
                            <li>Create a client Secret</li>
                            <li>Add these details here</li>
                        </ol>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Vat Number *</label>
                        <input class="form-control" type="number" name="vrn" value="{{ $vat_number ? $vat_number : '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Client ID *</label>
                        <input class="form-control" type="text" name="client_id">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Client Secret *</label>
                        <input class="form-control" type="text" name="client_secret">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Application ID</label>
                        <input class="form-control" type="text" name="application_id">
                    </div>

                    <?php if(!$credentials->count() || !$credentials[0]->is_test_creds) : ?>

                    <?php else : ?>
                        <div class="alert alert-warning">You have already added test credentials, therefore you cannot add new test credentials, please remove the old credentials.</div>
                    <?php endif; ?>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input {{ (!$credentials->count() || !$credentials[0]->is_test_creds) ? '' : 'disabled' }} type="checkbox" name="is_test_creds" value="1" class="custom-control-input" id="isTestCreds">
                            <label class="custom-control-label" for="isTestCreds">Tick here if these are test credentials?</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{ view('admin.templates.footer') }}
</body>


</html>
