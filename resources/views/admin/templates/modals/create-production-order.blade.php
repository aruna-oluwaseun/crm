<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Create <span>Production Order</span></h5>
        <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <em class="icon ni ni-cross"></em>
        </a>
    </div>
    <div class="modal-body">
        <?php if( isset($products) && $products->count() ) : ?>
        <form method="post" action="{{ url('productionorders') }}" id="create-form" class=" form-validate">
            @csrf
            <div class="form-group">
                <label class="form-label" for="notes">Product *</label>
                <div class="form-control-wrap">
                    <select class="form-select" name="product_id" id="create-product-id" data-search="on" required>
                        <option value="" selected="selected">Select a product</option>
                        <?php foreach($products as $product) : if(!$product->is_manufactured) {continue;} ?>
                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Qty required *</label>
                        <input class="form-control" type="number" name="qty" id="create-qty" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Due date *</label>
                        <div class="form-control-wrap">
                            <div class="form-icon form-icon-left">
                                <em class="icon ni ni-calendar"></em>
                            </div>
                            <input type="text" class="form-control date-picker" name="due_date" id="production-order-due-date" data-date-format="yyyy-mm-dd" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?php if($users = get_users()) : ?>
                <label class="form-label" for="notes">Assign to </label>
                <div class="form-control-wrap">
                    <select class="form-select" name="assigned_to_id" id="create-assigned-to-id" data-search="on">
                        <option value="" selected="selected">Select staff member</option>
                        <?php foreach($users as $user) : ?>
                        <option value="{{ $user->id }}">{{ $user->getFullNameAttribute() }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else : ?>
                <div class="alert alert-warning">Please <a href="{{ url('users/create') }}">create a new staff member</a></div>
                <?php endif; ?>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Batch Ref</label>
                        <input class="form-control" type="text" name="batch" id="create-batch">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <div class="form-control-wrap">
                            <input class="form-control" type="text" name="location" id="create-location">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="notes">Notes</label>
                <div class="form-control-wrap">
                    <textarea class="form-control" name="notes" id="create-notes"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="is_urgent" id="production-order-is-urgent" value="1">
                    <label class="custom-control-label" for="production-order-is-urgent">Is urgent</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
            </div>
        </form>
        <?php else : ?>
        <div class="alert alert-warning">You can't create a production order without any products, please <a href="{{ url('products/create') }}">create a product</a></div>
        <?php endif; ?>
    </div>
    {{--<div class="modal-footer bg-light">
        <span class="sub-text">Modal Footer Text</span>
    </div>--}}
</div>
