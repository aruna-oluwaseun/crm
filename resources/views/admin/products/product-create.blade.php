
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
                                            <h4 class="title nk-block-title">Create new product</h4>
                                            <div class="nk-block-des">
                                                <p>
                                                    Fill in as much detail as you can, more options will be available once you have added the product
                                                </p>
                                            </div>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="{{ url('products') }}" class="btn btn-danger"><span>Cancel</span></a></li>
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

                                    <div class="col-lg-12">
                                        <div class="card card-bordered h-100">
                                            <div class="card-inner">
                                                <?php if(isset($detail->title)) : ?>
                                                <div class="card-head">
                                                    <h5 class="card-title">You are duplicating : <a href="{{ url('products/'.$detail->id) }}" target="_blank">{{ $detail->title }}</a></h5>
                                                </div>
                                                <?php endif; ?>

                                                <form method="post" action="{{ url('products') }}" id="product-detail-form" class=" form-validate">
                                                    @csrf

                                                    <div class="form-group">
                                                        <label class="form-label" for="notes">Product *</label>
                                                        <div class="form-control-wrap">
                                                            <input type="text" class="form-control" name="title" value="{{ old('title', isset($detail->title) ? $detail->title : null ) }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-8">
                                                            <div class="form-group">
                                                                <label class="form-label" for="notes">URL Slug <span>Url friendly title</span></label>
                                                                <div class="form-control-wrap">
                                                                    <input readonly type="text" class="form-control" name="slug" value="{{ old('slug', isset($detail->slug) ? $detail->slug : null) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="notes">Product Code</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" name="code" value="{{ old('slug', isset($detail->code) ? $detail->code : null) }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                     <label for="cat_id">Category <span class="text-danger">*</span></label>
                                                     <select name="cat_id" id="cat_id" class="form-control">
                                                      <option value="">--Select any category--</option>
                                                       @foreach($categories as $key=>$cat_data)
                                                      <option value='{{$cat_data->id}}'>{{$cat_data->title}}</option>
                                                      @endforeach
                                                     </select>
                                                  </div>

                                                <div class="form-group d-none" id="child_cat_div">
                                                <label for="child_cat_id">Sub Category</label>
                                                <select name="child_cat_id" id="child_cat_id" class="form-control">
                                                 <option value="">--Select any category--</option>
                                                {{-- @foreach($parent_cats as $key=>$parent_cat)
                                                <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
                                               @endforeach --}}
                                               </select>
                                              </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Description</label>
                                                        <div class="form-group-wrap">
                                                            <textarea name="description" class="summernote-minimal form-control">
                                                                {{ old('description', isset($detail->description) ? $detail->description : null) }}
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Short description </label>
                                                        <div class="form-group-wrap">
                                                            <input type="text" name="short_description" class="form-control" maxlength="200" value="{{ old('short_description', isset($detail->short_description) ? $detail->short_description : null) }}">
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info">The short description will be used in SEO lookups. Keep it short and sweet.</div>

                                                    <div class="form-group">
                                                        <label class="form-label">Keywords </label>
                                                        <div class="form-group-wrap">
                                                            <input type="text" name="keywords" class="form-control" value="{{ old('keywords', isset($detail->keywords) ? $detail->keywords : null) }}">
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info">Keywords will be used in SEO lookups. Don't overkill it.</div>

                                                    <div class="row mb-4">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Product type</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="product_type_id" class="form-control form-select" data-search="on">
                                                                        <option value="0">Select product type</option>
                                                                        <?php if($product_types = product_types()) : ?>
                                                                        <?php foreach($product_types as $type) : ?>
                                                                        <option value="{{ $type->id }}" {{ is_selected($type->id, old('product_type_id', isset($detail->product_type_id) ? $detail->product_type_id : null)) }}>{{ $type->title }}</option>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Availability lead time</label>
                                                                <div class="form-control-wrap">
                                                                    <select name="lead_time_id" class="form-control form-select" data-search="on">
                                                                        <option value="0">Select lead time</option>
                                                                        <?php if($lead_times = lead_times()) : ?>
                                                                        <?php foreach($lead_times as $time) : ?>
                                                                        <option value="{{ $time->id }}" {{ is_selected($time->id, old('lead_time_id', isset($detail->lead_time_id) ? $detail->lead_time_id : '')) }}>{{ $time->title }}</option>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">

                                                        <!-- Costings -->
                                                        <div class="col-md-6">
                                                            <div class="card card-preview mb-3">
                                                                <div class="card-inner">
                                                                    <h4>Costings</h4>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Vat Type</label>
                                                                        <div class="form-control-wrap">
                                                                            <select id="vat-type-id" name="vat_type_id" class="form-select form-control" data-search="on">
                                                                                <?php if($vat_types = vat_types()) : ?>
                                                                                <?php foreach($vat_types as $vats) : ?>
                                                                                <option value="{{ $vats->id }}" data-value="{{ $vats->value }}" {{ is_selected($vats->id, old('vat_type_id', isset($detail->vat_type_id) ? $detail->vat_type_id : null)) }}>{{ $vats->title }}</option>
                                                                                <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Net cost</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="net-cost" type="text" name="net_cost" class="form-control" value="{{ old('net_cost', isset($detail->net_cost) ? $detail->net_cost : null) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">VAT</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="vat-cost" readonly type="text" name="vat_cost" class="form-control" value="{{ old('vat_cost', isset($detail->vat_cost) ? $detail->vat_cost : null) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Gross</label>
                                                                        <div class="form-control-wrap">
                                                                            <input id="gross-cost" readonly type="text" name="gross_cost" class="form-control form-control-lg" value="{{ old('gross_cost', isset($detail->gross_cost) ? $detail->gross_cost : null) }}">
                                                                        </div>
                                                                    </div>

                                                                    <hr>

                                                                    <div class="row mb-4">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Sale Cost</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="sale-net-cost" type="text" name="sale_net_cost" class="form-control" value="{{ old('sale_net_cost', isset($detail->sale_net_cost) ? $detail->sale_net_cost : null) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="form-label">Sale VAT</label>
                                                                                <div class="form-control-wrap">
                                                                                    <input id="sale-vat-cost" readonly type="text" name="sale_vat_cost" class="form-control" value="{{ old('sale_vat_cost', isset($detail->sale_vat_cost) ? $detail->sale_vat_cost : null) }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="form-label">Gross</label>
                                                                        <div class="form-control-wrap">
                                                                            <input id="sale-gross-cost" readonly type="text" name="sale_gross_cost" class="form-control form-control-lg" value="{{ old('sale_gross_cost', isset($detail->sale_gross_cost) ? $detail->sale_gross_cost : null) }}">
                                                                        </div>
                                                                    </div>

                                                                    <p id="sale-saving" style="display: {{ (isset($detail->sale_net_cost) ? $detail->sale_net_cost : '') ? 'block' : 'none' }}">Saving of <mark>0%</mark></p>

                                                                    <hr>

                                                                    <div class="form-group">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input name="deposit_allowed" type="checkbox" class="custom-control-input" id="deposit-allowed" value="1">
                                                                            <label class="custom-control-label" for="deposit-allowed"> Allow deposit on this product?</label>
                                                                        </div>
                                                                    </div>

                                                                    <div id="deposit-costings" style="display: {{ (isset($detail->deposit_allowed) ? $detail->deposit_allowed : '') ? 'block' : 'none' }}">
                                                                        <div class="row mb-4">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Net deposit amount</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input id="deposit-net-cost" type="text" name="deposit_net_cost" class="form-control" value="{{ old('deposit_net_cost', isset($detail->deposit_net_cost) ? $detail->deposit_net_cost : null) }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Deposit VAT</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input id="deposit-vat-cost" readonly type="text" name="deposit_vat_cost" class="form-control" value="{{ old('deposit_vat_cost', isset($detail->deposit_vat_cost) ? $detail->deposit_vat_cost : null) }}">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="form-label">Deposit Gross</label>
                                                                            <div class="form-control-wrap">
                                                                                <input id="deposit-gross-cost" readonly type="text" name="deposit_gross_cost" class="form-control form-control-lg" value="{{ old('deposit_gross_cost', isset($detail->deposit_gross_cost) ? $detail->deposit_gross_cost : null) }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Options -->
                                                        <div class="col-md-6">

                                                            <div class="row mb-3">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Product Weight</label>
                                                                        <div class="form-control-wrap">
                                                                            <input name="weight" type="number" class="form-control" id="weight" value="{{ old('weight', isset($detail->weight) ? $detail->weight : null) }}" step="0.0001">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Unit</label>
                                                                        <div class="form-control-wrap">
                                                                            <select class="form-select" name="unit_of_measure_id" id="add-unit-of-measure-id">
                                                                                <option value="unit">Unit</option>
                                                                                <?php if($uoms = uoms()) : ?>
                                                                                <?php foreach($uoms as $uom) : ?>
                                                                                <option value="{{ $uom->id }}" {{ is_selected($uom->id, old('unit_of_measure_id', isset($detail->unit_of_measure_id) ? $detail->unit_of_measure_id : null)) }}>{{ $uom->title }}</option>
                                                                                <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <?php
                                                            $is_packaging = false;
                                                            if(request()->exists('is_packaging')) {
                                                                $is_packaging = true;
                                                            }
                                                            ?>
                                                            <div class="form-group">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input name="is_packaging" type="checkbox" class="custom-control-input" id="is-packaging" value="1" {{ is_checked(1, old('is_packaging', $is_packaging)) }}>
                                                                    <label class="custom-control-label" for="is-packaging"> Is this item a Box or Pallet for shipping?</label>
                                                                </div>
                                                            </div>
                                                            <div id="packaging-options" style="display: {{ $is_packaging ? 'block' : 'none' }}; padding-left: 15px;">
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_shipping_box" type="checkbox" class="custom-control-input" id="is-shipping-box" value="1" {{ is_checked(1, old('is_shipping_box')) }}>
                                                                        <label class="custom-control-label" for="is-shipping-box"> It's a Box</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_shipping_pallet" type="checkbox" class="custom-control-input" id="is-shipping-pallet" value="1" {{ is_checked(1, old('is_shipping_pallet')) }}>
                                                                        <label class="custom-control-label" for="is-shipping-pallet"> It's a Pallet</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="product-options" style="display: {{ $is_packaging ? 'none' : 'block' }}">
                                                                <div class="form-group">
                                                                    <label class="form-label">Assembly time in minutes</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="form-text-hint" style="right: 20px">
                                                                            <span class="overline-title">Mins</span>
                                                                        </div>
                                                                        <input type="number" name="assembly_minutes" id="assembly-minutes" class="form-control" value="{{ old('assembly_minutes', isset($detail->assembly_minutes) ? $detail->assembly_minutes : null) }}">
                                                                    </div>
                                                                </div>


                                                                <div class="form-group">
                                                                    <label class="form-label">Commodity Code</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" name="commodity_code" id="commodity-code" class="form-control" value="{{ old('commodity_code', isset($detail->commodity_code) ? $detail->commodity_code : null) }}">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="builds_by_unit" type="checkbox" class="custom-control-input" id="builds-by-unit" value="1" {{ is_checked(1, old('builds_by_unit', isset($detail->builds_by_unit) ? $detail->builds_by_unit : null)) }}>
                                                                        <label class="custom-control-label" for="builds-by-unit"> Is this product built or added to builds per unit rather than it's weight?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_manufactured" type="checkbox" class="custom-control-input" id="is-manufactured" value="1" {{ is_checked(1, old('is_manufactured', isset($detail->is_manufactured) ? $detail->is_manufactured : null)) }}>
                                                                        <label class="custom-control-label" for="is-manufactured"> Is this product manufactured?</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_discountable" type="checkbox" class="custom-control-input" id="is-discountable" value="1" {{ is_checked(1, old('is_discountable', isset($detail->is_discountable) ? $detail->is_discountable : null)) }}>
                                                                        <label class="custom-control-label" for="is-discountable"> Is this product discountable?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_training" type="checkbox" class="custom-control-input" id="is-training" value="1" {{ is_checked(1, old('is_training', isset($detail->is_training) ? $detail->is_training : null)) }}>
                                                                        <label class="custom-control-label" for="is-training"> Training product?</label>
                                                                    </div>
                                                                </div>

                                                                <div id="assessment-product" class="form-group" style="display: {{ (isset($detail->is_assessment) ? $detail->is_assessment : '') ? 'block' : 'none' }}; padding-left: 15px">
                                                                    <hr>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input name="is_assessment" type="checkbox" class="custom-control-input" id="is-assessment" value="1" {{ is_checked(1, old('is_assessment', isset($detail->is_assessment) ? $detail->is_assessment : null)) }}>
                                                                        <label class="custom-control-label" for="is-assessment"> Do this training product require customers to have an assessment?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-control custom-checkbox">
                                                                        <input name="is_free_shipping" type="checkbox" class="custom-control-input" id="free-shipping" value="1" {{ is_checked(1, old('is_free_shipping', isset($detail->is_free_shipping) ? $detail->is_free_shipping : null)) }}>
                                                                        <label class="custom-control-label" for="free-shipping"> Free ship item?</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="custom-control custom-control custom-switch">
                                                                        <input name="is_available_online" type="checkbox" class="custom-control-input" id="available-online" value="1" {{ is_checked(1, old('is_available_online', isset($detail->is_available_online) ? $detail->is_available_online : null)) }}>
                                                                        <label class="custom-control-label" for="available-online"> Available to purchase online?</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Status *</label>
                                                        <div class="g-4 align-center flex-wrap">
                                                            <div class="g">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" name="status" id="status-active" value="active" {{ is_checked('active', old('status', isset($detail->status) ? $detail->status : null)) }}>
                                                                    <label class="custom-control-label" for="status-active">Active</label>
                                                                </div>
                                                            </div>
                                                            <div class="g">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" class="custom-control-input" name="status" id="status-disabled" value="disabled" {{ is_checked('disabled', old('status', isset($detail->status) ? $detail->status : null)) }}>
                                                                    <label class="custom-control-label" for="status-disabled">Disabled</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="status-warning" style="display: none;" class="alert alert-info"><b><em class="icon ni ni-alert"></em> Warning :</b> once you complete this production order you will not be able to make changes to it. The build stock will also be updated.</div>

                                                    <div class="form-group">
                                                        <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                                    </div>
                                                </form>

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


@push('scripts')
    <script src="{{ asset('assets/js/libs/editors/summernote.js?ver=2.2.0') }}"></script>
    <script src="{{ asset('assets/js/libs/tagify.js') }}"></script>
    <script src="{{ asset('assets/js/admin/product-create.js') }}"></script>
    <script>
  
  $('#cat_id').change(function(){
    var cat_id=$(this).val();
    // alert(cat_id);
    if(cat_id !=null){
      // Ajax call
      $.ajax({
        url:"/categories/"+cat_id+"/child",
        data:{
          _token:"{{csrf_token()}}",
          id:cat_id
        },
        type:"POST",
        success:function(response){
          if(typeof(response) !='object'){
            response=$.parseJSON(response)
          }
          // console.log(response);
          var html_option="<option value=''>----Select sub category----</option>"
          if(response.status){
            var data=response.data;
            // alert(data);
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data,function(id,title){
                html_option +="<option value='"+id+"'>"+title+"</option>"
              });
            }
            else{
            }
          }
          else{
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
    else{
    }
  })
</script>
@endpush
{{ view('admin.templates.footer') }}
</body>

</html>
