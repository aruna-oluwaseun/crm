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

                <div class="alert alert-info mt-3"><b>Please note :</b> This categories section is intended for the online shops only, if you want to internally manage what what a product is associated with use the <a class="text-info" href="{{ url('product-types') }}">product types</a> </div>

                <div class="container-fluid">
                    <div class="nk-content-inner">
                        <div class="nk-content-body">

                            <div class="nk-block nk-block-lg">

                                <div class="nk-block-head">
                                    <div class="nk-block-between">

                                        <div class="nk-block-head-content">
                                            <h4 class="nk-block-title">Categories</h4>
                                            <p>Manage your categories</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <!--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>-->
                                                        @can('create-categories')
                                                        <li><a href="#" data-toggle="modal" data-target="#modalCreateCategory" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Category</span></a></li>
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
                                        <table class=" nk-tb-list nk-tb-ulist" data-auto-responsive="false"><!-- datatable-init-->
                                            <thead>
                                                <tr class="nk-tb-item nk-tb-head">
                                                    <!--<th class="nk-tb-col nk-tb-col-check">
                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                            <input type="checkbox" class="custom-control-input" id="uid">
                                                            <label class="custom-control-label" for="uid"></label>
                                                        </div>
                                                    </th>-->
                                                     
                                                    <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Category</span></th>
                                                     <th class="nk-tb-col tb-col-mb"><span class="sub-text">Slug</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Is Parent</span></th>
                                                    <th class="nk-tb-col"><span class="sub-text">Parent Category</span></th>
                                                   
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($categories) && $categories->count()) : ?>

                                                    <?php foreach($categories as $item) : ?>
                                                        <?php
                                                            $status = 'success';
                                                            if($item->status == 'disabled') {
                                                                $status = 'warning';
                                                            }
                                                        ?>
                                                        <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                            <!--<td class="nk-tb-col nk-tb-col-check">
                                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                    <input type="checkbox" class="custom-control-input" id="uid1">
                                                                    <label class="custom-control-label" for="uid1"></label>
                                                                </div>
                                                            </td>-->
                                                            <td class="nk-tb-col"><a href="{{ url('categories/'.$item->id) }}">{{ $item->id }}</a></td>
                                                            <td class="nk-tb-col">
                                                                <div class="user-card">
                                                                    <div class="user-info">
                                                                        <span class="tb-lead"><a href="{{ url('categories/'.$item->id) }}">{{ $item->title }}</a> <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                        <span>{{ $item->code }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                           <td class="nk-tb-col tb-col-mb">
                                                                <span class="tb-lead">
                                                                   {{ $item->slug }}
                                                                </span>
                                                            </td>






                                                            <td class="nk-tb-col tb-col-mb">
                                                                <span class="tb-lead">
                                                                   {{(($item->is_parent==1)? 'Yes': 'No')}}
                                                                </span>
                                                            </td>

                                                              <td class="nk-tb-col tb-col-mb">
                                                                <?php  if ($item->is_parent==0) :?>
                                                                <span class="tb-lead">
                                                                   
                                                                    {{$item->parent->title}}
                                                                   
                                                                </span>
                                                               <?php  else: ?>
                                                                 <span class="tb-lead">
                                                                   
                                                                    Root Category
                                                                   
                                                                </span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-md">
                                                                <span class="tb-status text-{{ $status }}">{{ ucfirst($item->status) }}</span>
                                                            </td>
                                                            <td class="nk-tb-col nk-tb-col-tools">
                                                                <ul class="nk-tb-actions gx-1">
                                                                    <?php if($item->status == 'active') : ?>
                                                                        <li class="nk-tb-action-hidden status-result">
                                                                            <a href="{{ url('category-status') }}" class="btn btn-trigger btn-action btn-icon" data-action="disabled" data-toggle="tooltip" data-placement="top" title="Disable category">
                                                                                <em class="icon ni ni-cross"></em>
                                                                            </a>
                                                                        </li>
                                                                    <?php else : ?>
                                                                        <li class="nk-tb-action-hidden status-result">
                                                                            <a href="{{ url('category-status') }}" class="btn btn-trigger btn-action btn-icon" data-action="active" data-toggle="tooltip" data-placement="top" title="Enable category">
                                                                                <em class="icon ni ni-check-thick"></em>
                                                                            </a>
                                                                        </li>
                                                                    <?php endif; ?>

                                                                    <li>
                                                                        <div class="drodown">
                                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <ul class="link-list-opt no-bdr">
                                                                                    <!--<li><a href="#"><em class="icon ni ni-focus"></em><span>Quick View</span></a></li>-->
                                                                                    <li><a href="{{ url('categories/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
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
                                        <?php if(isset($categories) && $categories->count()) : ?>
                                            {{ $categories->links() }}
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


<div class="modal fade" tabindex="-1" id="modalCreateCategory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>Category</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('categories') }}" id="create-form" class=" form-validate">
                    @csrf
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Category name *</label>
                        <div class="form-control-wrap">
                            <input name="title" class="form-control" type="text" placeholder="Category name" required>
                        </div>
                    </div>
                 <div class="form-group">
                  <label class="form-label" for="is_parent">Is Parent</label><br>
                  <input type="checkbox" name='is_parent' id='is_parent' value='1' checked> Yes                        
                </div>

        <div class="form-group d-none" id='parent_cat_div'>
          <label  class="form-label" for="parent_id">Parent Category</label>
          <select name="parent_id" class="form-control">
              <option value="">--Select any category--</option>
            @foreach($parent_cats as $key=>$parent_cat)
                  <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
              @endforeach
             
          </select>
        </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <div class="form-group-wrap">
                        <textarea name="description" class="summernote-minimal form-control">

                            </textarea>
                        </div>
                </div>

                    <div class="form-group">
                        <label class="form-label">Short description </label>
                        <div class="form-group-wrap">
                            <input type="text" name="short_description" class="form-control" maxlength="200" value="">
                        </div>
                    </div>
                    <div class="alert alert-info">The short description will be used in SEO lookups. Keep it short and sweet.</div>

                    <div class="form-group">
                        <label class="form-label">Keywords </label>
                        <div class="form-group-wrap">
                            <input type="text" name="keywords" class="form-control" value="">
                        </div>
                    </div>
                    <div class="alert alert-info">Keywords will be used in SEO lookups. Don't overkill it.</div>


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
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
   
    <script>
    $('#is_parent').change(function(){
    var is_checked=$('#is_parent').prop('checked');
    // alert(is_checked);
    if(is_checked){
      $('#parent_cat_div').addClass('d-none');
      $('#parent_cat_div').val('');
    }
    else{
      $('#parent_cat_div').removeClass('d-none');
    }
  })
</script>
@endpush
<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>


</html>
