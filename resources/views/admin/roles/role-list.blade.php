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
                                            <h4 class="nk-block-title">Roles</h4>
                                            <p>Manage your roles your roles and permissions</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#modalRoleCreate" data-toggle="modal" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Role</span></a></li>
                                                    </ul>
                                                </div>
                                            </div><!-- .toggle-wrap -->
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-preview">
                                    <div class="card-inner" data-select2-id="19">
                                        <form action="" method="get">
                                            <div class="card-title-group" data-select2-id="18">
                                                <div class="card-title">
                                                    <h5 class="title">All Roles</h5>
                                                    <?php if(isset($roles) && $roles->count()) : ?>
                                                        {!! '<span class="text-primary">'.$roles->total().'</span> roles found'  !!}
                                                    <?php else : ?>
                                                        0 Roles found
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
                                                    <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Roles</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">No. Permissions</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Users Linked</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($roles) && $roles->count()) : ?>

                                                    <?php foreach($roles as $item) : if($item->slug == 'super-admin') { continue; } ?>
                                                        <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                        <!--<td class="nk-tb-col nk-tb-col-check">
                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                <input type="checkbox" class="custom-control-input" id="uid1">
                                                                <label class="custom-control-label" for="uid1"></label>
                                                            </div>
                                                        </td>-->
                                                        <td class="nk-tb-col"><a href="{{ url('roles/'.$item->id) }}">{{ $item->id }}</a></td>
                                                        <td class="nk-tb-col">
                                                            <div class="user-card">
                                                                <div class="user-info">
                                                                    <span class="tb-lead"><a href="{{ url('roles/'.$item->id) }}">{{ $item->title }}</a> <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                    <span>{{ $item->slug }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status">{{ $item->permissions->count() }}</span>
                                                        </td>
                                                        <td class="nk-tb-col tb-col-md">
                                                            <span class="tb-status">{{ $item->users->count() }}</span>
                                                        </td>
                                                        <td class="nk-tb-col nk-tb-col-tools">
                                                            <ul class="nk-tb-actions gx-1">
                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <ul class="link-list-opt no-bdr">
                                                                                <li><a href="{{ url('roles/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
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
                                        <?php if(isset($roles) && $roles->count()) : ?>
                                        {{ $roles->appends(request()->except('page'))->links() }}
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

<div class="modal fade" tabindex="-1" id="modalRoleCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create <span>Role</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('roles') }}">
                    @csrf

                    <div class="alert alert-info">
                        You can add permissions to this role in the next section
                    </div>

                    <!-- Customer detail -->
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Slug *</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="text" name="slug" value="{{ old('slug') }}" required>
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

<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">
    $(document).ready(function() {
        // slug
        $(document).on('keyup','[name="title"]', function(event) {
            var title = $(this).val();
            setTimeout(function() {
                var slug = title.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
                $('[name="slug"]').val(slug);
            }, 400);

        });
    });
</script>


</html>
