<?php

    if($duplicate_salesorder) {
        $detail = $duplicate_salesorder;
    }

?>
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
                                            <h4 class="title nk-block-title">Create new expenses</h4>
                                            
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <li><a href="#modalRefundCreate" data-toggle="modal" class="btn btn-white btn-primary"><span>Add Expense Type</span></a></li>
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
                                <form method="post" action="{{ url('expenses') }}" enctype="multipart/form-data" id="create-saleorder-form" class="form-validate">
                                    <div class="row g-gs">

                                       @csrf
                                        <div class="col-lg-6">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner">
                                                    <div class="card-head">
                                                        <?php if(isset($detail->id)) : ?>
                                                            <h5 class="card-title">You are duplicating : <a href="{{ url('salesorder/'.$detail->id) }}" target="_blank">{{ $detail->id }}</a></h5>
                                                        <?php else : ?>
                                                            <h5 class="card-title">Expense details</h5>
                                                        <?php endif; ?>
                                                    </div>

                                                        <div class="form-group">
                                                          
                                                        <label class="form-label">Supplier *</label>
                                                            <div class="form-control-wrap">
                                                            <input class="form-control" type="text" name="supplier" required>
                                                                        </div>
                                                           <ul id="searchResult"></ul>
                                                        </div>

                                                        <div class="form-group">
                                                          
                                                           <label class="form-label">Created</label>
                                                                    <div class="form-control-wrap">
                                                                        <div class="form-icon form-icon-left">
                                                                            <em class="icon ni ni-calendar"></em>
                                                                        </div>
                                                                        <input type="text" class="form-control date-picker" name="dates" id="quote_valid_until" value="{{ old('quote_valid_until',date('Y-m-d', strtotime('+ 30 days'))) }}" data-date-format="yyyy-mm-dd" name='dates'>
                                                                    </div> 
                                                    
                                                        </div>

                                                        <div class="form-group">
                                                          
                                                        <label class="form-label">Reference Number *</label>
                                                            <div class="form-control-wrap">
                                                            <input class="form-control" type="text" name="reference" required>
                                                                        </div>
                                                    
                                                        </div>


                                                        <div class="form-group">
                                                          
                                                        <label class="form-label">Payment Type *</label>
                                                            <div class="form-control-wrap">

                                                             <select class="form-select select-product" name="type"  required>
                                                                   <option value="card">Select Payment Type</option>
                                                                    <option value="card">Card</option>
                                                                    <option value="cash">Cash</option>
                                                                    <option value="bank">Bank</option>
                                                            </select>
                                                            
                                                            </div>
                                                    
                                                        </div>



                                                        <div class="form-group">
                                                          
                                                          <label class="form-label">Status *</label>
                                                            <div class="form-control-wrap">
                                                            <select class="form-select select-product" name="status"  required>
                                                                    <option value="pending">pending</option>
                                                                    <option value="processing">processing</option>
                                                                    <option value="processing">complete</option>
                                                            </select>
                                                            </div>
                                                    
                                                        </div>

                                                         <div class="form-group">
                                                          
                                                          <label class="form-label">Attach Receipt *</label>
                                                            <div class="form-control-wrap">
                                                             <input class="form-control" type="file" name="image" required>
                                                            </div>
                                                    
                                                        </div>
                                                
                                                       <div class="form-group">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="notes" class="form-control" required></textarea>
                                                        </div>




                                                    </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card card-bordered h-100">
                                                <div class="card-inner">
                                                    <div class="card-head">
                                                        <h5 class="card-title">Items</h5>
                                                    </div>

                                                    <div class="card card-bordered card-preview">
                                                        <table id="products-list" class="table table-tranx">
                                                            <thead>
                                                                <tr class="tb-tnx-head">
                                                                    <th width="5%">
                                                                        <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                            <input type="checkbox" class="custom-control-input checkbox-item-all" id="items-all">
                                                                            <label class="custom-control-label" for="items-all"></label>
                                                                        </div>
                                                                    </th>
                                                                    <th width="40%">Expense Type</th>
                                                                    <th width="10%">Qty</th>
                                                                    <th width="15%">Vat</th>
                                                                    <th width="15%">Item Cost</th>
                                                                    <th width="15%" class="text-right">Gross</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            <?php if(!empty(old('items'))) : ?>
                                                                <div id="update-costings"></div>
                                                                <!-- repopulate list if error -->
                                                               <?php foreach(old('items') as $key => $item) : ?>
                                                                    <tr class="tb-tnx-item">
                                                                        <td class="nk-tb-col nk-tb-col-check sorting_1">
                                                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                                <input type="checkbox" value="1" class="custom-control-input checkbox-item" id="item-{{ $key }}">
                                                                                <label class="custom-control-label" for="item-{{ $key }}"></label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select select-product" name="product_id[]" data-search="on" required>
                                                                                    <option>Select item</option>
                                                                                    <?php foreach($products as $product) : ?>
                                                                                    <option value="{{ $product->title }}" >{{ $product->title }}</option>
                                                                                    <?php endforeach; ?>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" name="qty[]"  class="qty form-control" min="1" required>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-control-wrap">
                                                                                <select name="vat_type_id[]" class="vat-type form-select form-control"  required>
                                                                                    <?php if($vat_types = vat_types()) : ?>
                                                                                    <?php foreach($vat_types as $vats) : ?>
                                                                                    <option value="{{ $vats->value }}">{{ $vats->title }}</option>
                                                                                    <?php endforeach; ?>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </div>
                                                                            <input type="hidden" class="vat-cost form-control" value="0">
                                                                        </td>
                                                                        <td>
                                                                            <input name="item_cost[]" type="text" class="item-cost form-control" value="{{ $item['item_cost'] }}" required>
                                                                        </td>
                                                                        <td class="text-right gross-cost">
                                                                            <input name="gross[]" readonly type="text" class="gross-cost form-control" value="{{ $item['gross_cost'] }}">
                                                                            <input type="hidden" class="net-cost form-control" value="">
                                                                        </td>
                                                                    </tr>
                                                               <?php endforeach; ?>

                                                            <?php else : ?>

                                                                {{ view('admin.expenses.template.product-row', ['products' => $products, 'id' => 0]) }}


                                                            <?php endif; ?>


                                                            </tbody>

                                                        </table>

                                                        <div class="pl-3 pr-3 pb-3">
                                                            <a id="add-product" href="#" class="btn btn-white btn-primary"><span> Add row </span></a>
                                                        </div>

                                                    </div><!-- .card-preview -->

                                                    <div class="row mt-4">
                                                        <div class="col-md-6">
                                                            <a href="#" id="remove-items-btn" class="btn btn-danger disabled" disabled><span> Remove items </span></a>
                                                        </div>

                                                        <div class="col-md-6">

                                                            <table class="table">
                                                                <thead class="thead-dark">
                                                                <tr>
                                                                    <th scope="col" colspan="2">Order Totals</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="card-bordered">
                                                                <tr>
                                                                    <th scope="row">Sub Total</th>
                                                                    <td id="show-total-net">&pound; <span>{{ number_format(0.00,2,'.',',') }}</span></td>
                                                                </tr>
                                                               
                                                                <tr>
                                                                    <th scope="row">Vat Total</th>
                                                                    <td id="show-total-vat">&pound; <span>{{ number_format( (0.00),2,'.',',') }}</span></td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">Total</th>
                                                                    <td id="show-total-gross" class="font-weight-bolder text-primary">&pound; <span>{{ number_format(0,2,'.',',') }}</span> </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

<table style="display: none;" id="product-prototype">
    <tbody>
        {{ view('admin.expenses.template.product-row', ['prototype' => true, 'products' => $products]) }}
    </tbody>
</table>

<div class="modal fade" tabindex="-1" id="modalRefundCreate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create <span>Expense Type</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('expense-types') }}">
                    @csrf

                   
                    <div class="form-group">
                        <label class="form-label" for="notes">Expense Type *</label>
                        <div class="form-control-wrap">
                           <input class="form-control" type="text" name="title"  required>
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


@push('scripts')

@endpush
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">
    var pre_fetch_product = {{ $sell_product ? $sell_product : 'false' }};
</script>
<script type="text/javascript">
$(document).ready(function(){

    $("#txt_search").keyup(function(){
        var search = $(this).val();

        if(search != ""){

            $.ajax({
                url: '/suppliers',
                type: 'post',
                data: {search:search, type:1},
                success:function(response){
                
                    var len = response.length;
                    $("#searchResult").empty();
                    for( var i = 0; i<len; i++){
                        var id = response[i]['id'];
                        var name = response[i]['name'];

                        $("#searchResult").append("<li value='"+id+"'>"+name+"</li>");

                    }

                    // binding click event to li
                    $("#searchResult li").bind("click",function(){
                        setText(this);
                    });

                }
            });
        }

    });

});

// Set Text to search box and get details
function setText(element){

    var value = $(element).text();
    var userid = $(element).val();

    $("#txt_search").val(value);
    $("#searchResult").empty();
    
    // Request User Details
    $.ajax({
        url: '/suppliers',
        type: 'post',
        data: {userid:userid, type:2},
        dataType: 'json',
        success: function(response){

            var len = response.length;
            $("#userDetail").empty();
            if(len > 0){
                var username = response[0]['username'];
                var email = response[0]['email'];
                $("#userDetail").append("Username : " + username + "<br/>");
                $("#userDetail").append("Email : " + email);
            }
        }

    });
}


</script>    






<script type="text/javascript" src="{{ asset('assets/js/admin/expense-create.js') }}"></script>

</html>
