<?php

    if(isset($prototype))
    {
        $id = '{REPLACE_ID}';
    }

    extract(array_merge([
        'product_id'    => null,
        'qty'           => 1,
        'vat_type_id'   => 1,
        'item_cost'     => 0,
        'gross_cost'    => 0,
    ], isset($data) ? $data : []));

?>

<tr class="tb-tnx-item" data-id="{{ $id }}">
    <td class="nk-tb-col nk-tb-col-check sorting_1">
        <div class="custom-control custom-control-sm custom-checkbox notext">
            <input type="checkbox" value="1" {{ $id == 0 ? 'disabled="disabled"' : '' }} class="custom-control-input checkbox-item" id="item-{{ $id }}">
            <label class="custom-control-label" for="item-{{ $id }}"></label>
        </div>
    </td>
    <td class="product-selection">
        <div class="product-alert mb-0 py-1 px-2 alert alert-warning" style="display: {{ isset($data) ? 'none' : 'block' }}">Select a supplier first</div>
        <div class="form-control-wrap product-row" style="display: {{ isset($data) ? 'block' : 'none' }}">
            <select class="{{ isset($prototype) ? '' : 'form-select' }} select-product" name="items[{{ $id }}][product_id]" data-search="on" required>
                <option>Select product</option>
            </select>
        </div>
    </td>
    <td>
        <input type="number" name="items[{{ $id }}][qty]" class="qty form-control" value="{{ $qty }}" min="1" required>
    </td>
    <td>
        <div class="form-control-wrap">
            <select name="items[{{ $id }}][vat_type_id]" class="vat-type form-control" data-search="on" required>
                <?php if($vat_types = vat_types()) : ?>
                <?php foreach($vat_types as $vats) : ?>
                <option value="{{ $vats->id }}" data-value="{{ $vats->value }}" {{ is_selected($vats->id, $vat_type_id) }}>{{ $vats->title }}</option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <input type="hidden" class="vat-cost form-control" value="0">
    </td>
    <td>
        <input name="items[{{ $id }}][item_cost]" type="text" class="item-cost form-control" value="{{ $item_cost }}" required>
    </td>
    <td class="text-right gross-cost">
        <input name="items[{{ $id }}][gross_cost]" readonly type="text" class="gross-cost form-control" value="{{ $gross_cost }}">
        <input type="hidden" class="net-cost form-control" value="0">
    </td>
</tr>
<tr class="options show-weight-{{ $id }}">
    <td colspan="6">
        <label>Total weight of the above product ordering in : <span class="text-primary">NA</span></label>
    </td>
</tr>
<tr class="options none-stock-item show-options-{{ $id }}" style="display: none">
    <td colspan="6">
        <label>Add new none stock item : </label>
        <div class="product-options">

        </div>
    </td>
</tr>

