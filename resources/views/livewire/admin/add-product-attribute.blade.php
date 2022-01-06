<div>
    <form method="post" action="{{ url('attributes/link-product') }}" class=" form-validate" wire:ignore>
        @csrf
        <input type="hidden" id="attribute-product-id" name="product_id" value="{{ $product_id }}">
        <div class="alert alert-warning" id="fetch-product-status" style="display: none"></div>

        <?php if($attributes) : ?>
        <div class="form-group">
            <label class="form-label" for="add-attr-attribute-id">Attribute *</label>
            <div class="form-control-wrap">
                <select class="form-select" name="attribute_id" id="add-attr-attribute-id" data-search="on" required>
                    <option value="" selected="selected">Select attribute</option>
                    <?php foreach($attributes as $attribute) : ?>
                    <option value="{{ $attribute->id }}">{{ $attribute->title }}</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endif; ?>

        <?php if($products) : ?>
        <div class="form-group" >
            <label class="form-label" for="add-attr-product-id">Product *</label>
            <div class="form-control-wrap">
                <select class="form-select" name="value_id" id="add-attr-product-id" data-search="on" required>
                    <option value="" selected="selected">Select product</option>
                    <?php foreach($products as $product) : ?>
                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group" >
            <label class="form-label" for="notes">Override the product title</label>
            <div class="form-control-wrap">
                <input class="form-control" type="text" name="attribute_title" placeholder="E.g if the product title is Baby Blue 1g you could override with just Baby Blue" value="">
            </div>
        </div>

        <div class="form-group" >
            <label class="form-label">Net adjustment (leave blank if price isn't affected)</label>
            <div class="form-control-wrap">
                <input class="form-control" type="number" name="net_adjustment" value="" step="00.01">
            </div>
        </div>
        <?php endif; ?>

        <?php if($product_attributes !== null) : ?>
        {{--<div class="mb-4"  id="show-attributes">
            <div class="alert alert-info mb-2">
                This product has attributes of its own, by default they will be included as an additional dropdown selection when viewing online, you can exclude them below
            </div>

            <div class="form-group">
                <label class="form-label">Exclude selected product attributes : </label><br>
                <?php foreach($product_attributes as $attribute) : $unique_id = uniqid(); ?>
                <div class="custom-control custom-control custom-checkbox mr-3">
                    <input name="exclude_value_attributes[]" type="checkbox" class="custom-control-input" id="exclude-{{ $unique_id }}" value="{{ $attribute->pivot->value_id }}">
                    <label class="custom-control-label" for="exclude-{{ $unique_id }}"> {{ isset($attribute->pivot->attribute_title) ? $attribute->pivot->attribute_title : $attribute->detail->title }}</label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>--}}
        <?php endif; ?>

        <div class="mb-4" id="show-attributes" style="display: none">
            <div class="alert alert-info mb-2">
                This product has attributes of its own, by default they will be included as an additional dropdown selection when viewing online, you can exclude them below
            </div>

            <div class="form-group">

            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="submit-btn btn btn-lg btn-primary">Save</button>
        </div>
    </form>
</div>

