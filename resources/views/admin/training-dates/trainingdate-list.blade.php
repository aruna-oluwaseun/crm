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
                                            <h4 class="nk-block-title">Training Dates</h4>
                                            <p>Manage your training dates</p>
                                        </div>

                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                                <div class="toggle-expand-content" data-content="pageMenu">
                                                    <ul class="nk-block-tools g-3">
                                                        <!--<li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>-->
                                                        @can('create-training-dates')
                                                        <li><a href="#" data-toggle="modal" data-target="#modalCreateTrainingDate" class="btn btn-white btn-primary"><em class="icon ni ni-plus"></em><span>Add Training Date</span></a></li>
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
                                                    <th class="nk-tb-col"><span class="sub-text">Course</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Dates</span></th>
                                                    <th class="nk-tb-col tb-col-mb"><span class="sub-text">Booked / Vacant</span></th>
                                                    <th class="nk-tb-col tb-col-md"><span class="sub-text">Status</span></th>
                                                    <th class="nk-tb-col nk-tb-col-tools text-right">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($dates) && $dates->count()) : ?>

                                                    <?php foreach($dates as $item) : ?>
                                                        <?php
                                                            $status = 'success';
                                                            if($item->status == 'upcoming') {
                                                                $status = 'info';
                                                            }
                                                            if($item->status == 'deleted') {
                                                                $status = 'danger';
                                                            }

                                                            $spaces = training_spaces($item->id);

                                                        ?>
                                                        <tr class="nk-tb-item data-container" data-id="{{$item->id}}">
                                                            <!--<td class="nk-tb-col nk-tb-col-check">
                                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                                    <input type="checkbox" class="custom-control-input" id="uid1">
                                                                    <label class="custom-control-label" for="uid1"></label>
                                                                </div>
                                                            </td>-->
                                                            <td class="nk-tb-col"><a href="{{ url('training-dates/'.$item->id) }}">{{ $item->id }}</a></td>
                                                            <td class="nk-tb-col">
                                                                <div class="user-card">
                                                                    <div class="user-info">
                                                                        <span class="tb-lead"><a href="{{ url('training-dates/'.$item->id) }}">{{ $item->product_title }}</a> <!--<span class="dot dot-success d-md-none ml-1"></span>--></span>
                                                                        <span>Total spaces : {{ $item->stock }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-mb">
                                                                <span class="tb-lead">
                                                                   {{ format_date($item->date_start) }} - {{ format_date($item->date_end) }}
                                                                </span>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-mb">
                                                                <span class="tb-lead">
                                                                   {{ $spaces->paid }} / {{ $spaces->remaining }}
                                                                </span>
                                                            </td>
                                                            <td class="nk-tb-col tb-col-md">
                                                                <span class="tb-status text-{{ $status }}">{{ ucfirst($item->status) }}</span>
                                                            </td>
                                                            <td class="nk-tb-col nk-tb-col-tools">
                                                                <ul class="nk-tb-actions gx-1">
                                                                    <?php if($item->status == 'upcoming') : ?>
                                                                        <li class="nk-tb-action-hidden status-result">
                                                                            <a href="{{ url('training-dates/destroy/'.$item->id) }}" class="btn btn-trigger btn-action destroy-btn btn-icon" data-action="disabled" data-toggle="tooltip" data-placement="top" title="Delete">
                                                                                <em class="icon ni ni-cross"></em>
                                                                            </a>
                                                                        </li>
                                                                    <?php endif; ?>

                                                                    <li>
                                                                        <div class="drodown">
                                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                                <ul class="link-list-opt no-bdr">
                                                                                    <?php if($item->status == 'upcoming') : ?>
                                                                                        <li><a class="destroy-btn" href="{{ url('training-dates/destroy/'.$item->id) }}"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                                                    <?php endif; ?>
                                                                                    <li><a href="{{ url('training-dates/'.$item->id) }}"><em class="icon ni ni-eye"></em><span>View</span></a></li>
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


<div class="modal fade" tabindex="-1" id="modalCreateTrainingDate">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add <span>Training</span></h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <?php if(isset($products) && $products->count()) : ?>
                <form method="post" action="{{ url('training-dates') }}" id="create-form" class=" form-validate">
                    @csrf
                    <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

                    <div class="alert alert-info">You can link other courses for stock purposes once training is added.</div>

                    <div class="form-group">
                        <label class="form-label" for="notes">Product *</label>
                        <div class="form-control-wrap">
                            <select class="form-select" name="product_id" id="create-product-id" data-search="on" required>
                                <option value="" selected="selected">Select a product</option>
                                <?php foreach($products as $product) : ?>
                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group" id="show-end-date">
                                <label class="form-label">Training Start</label>
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>
                                    <input type="text" name="date_start" id="date-start" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly="readonly" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="show-end-date">
                                <label class="form-label">Training Last Day</label>
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>
                                    <input type="text" name="date_end" id="date-end" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly="readonly" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Spaces available</label>
                        <div class="form-group-wrap">
                            <input type="number" class="form-control" name="stock" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit"  class="submit-btn btn btn-lg btn-primary">Save</button>
                    </div>
                </form>
                <?php else : ?>

                <div class="alert alert-warning">You have not got any active training products. Make sure the product is ticked for "Is Training" and the status is enabled</div>

                <?php endif; ?>
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
@endpush
<!-- app-root @e -->
{{ view('admin.templates.footer') }}
</body>

<script type="text/javascript">
    $(document).ready(function() {

        // Keywords
        $('[name=keywords]').tagify();

        // slug
        $(document).on('keyup','#modalCreateCategory [name="title"]', function(event) {
            var title = $(this).val();
            setTimeout(function() {
                var slug = title.toLowerCase().replace(/ /g,'-').replace(/[-]+/g, '-').replace(/[^\w-]+/g,'');
                $('#modalCreateCategory [name="slug"]').val(slug);
            }, 400);
        });
    });
</script>


</html>
