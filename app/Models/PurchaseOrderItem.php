<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Store purchase item
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public static function store(Request $request)
    {
        $order = PurchaseOrder::with(['supplier.products'])->findOrFail($request->input('purchase_order_id'));

        // Fetch product
        if(!$order->supplier->products) {
            Throw new \Exception('No products found linked to this supplier');
        }

        $code = null;
        $product_id = null;
        $product_title = null;
        $unit_of_measure_id = null;

        //None stock
        if($request->exists('none_stock_item'))
        {
            $product_title = $request->input('product_title');
            $code = $request->input('product_code');
            $unit_of_measure_id = $request->input('unit_of_measure_id');
        }
        else
        {
            $product = $order->supplier->products->find($request->input('product_id'));

            $product_id = $product->id;
            $product_title = $product->title;
            $code = $product->pivot->code;
            if($product->unit_of_measure_id) {
                $unit_of_measure_id = $product->unit_of_measure_id;
            }
        }

        $new_item = [
            'supplier_code'     => $code,
            'product_id'        => $product_id,
            'product_title'     => $product_title,
            'qty'               => $request->input('qty'),
            'vat_type_id'       => $request->input('vat_type_id')
        ];

        // ----------------------- START WEIGHT
        if($unit_of_measure_id) {
            $new_item['unit_of_measure_id'] = $unit_of_measure_id;
        }

        // Product weight
        if($request->input('weight') || isset($product->weight))
        {
            if($request->input('weight') > 0 ) {
                $new_item['weight'] = $request->input('weight')*$new_item['qty'];
            }
            elseif(isset($product->weight) && $product->weight) {
                $new_item['weight'] = $product->weight*$new_item['qty'];
            }


            // Weight KG
            if($new_item['weight'] > 0)
            {
                if(isset($new_item['unit_of_measure_id']) && $new_item['unit_of_measure_id'])
                {
                    $converted = UnitOfMeasure::convert(intval($new_item['unit_of_measure_id']),'Kg', $new_item['weight']);
                    if($converted->success) {
                        $new_item['weight_kg'] = $converted->value;
                    }
                }
                /*else
                {
                    unset($new_item['weight']);
                }*/
            }
        }
        // ------------------------ END WEIGHT

        // ------------------------ START COSTINGS / VAT
        $vat = VatType::find($new_item['vat_type_id']);

        if(!$request->exists('item_cost') && isset($product))
        {
            $new_item['item_cost'] = $product->pivot->cost_to_us;
        }
        else
        {
            $new_item['item_cost'] = $request->input('item_cost');
        }

        // recalculate costs in-case JS rounding errors
        $vat_value = $vat ? $vat->value : 20; // default 20 percent
        $new_item['vat_percentage'] = $vat_value;
        $new_item['net_cost'] = $new_item['item_cost']*$new_item['qty'];
        $new_item['vat_cost'] = ($vat_value / 100) * $new_item['net_cost'];
        $new_item['gross_cost'] = $new_item['net_cost'] + $new_item['vat_cost'];
        // ------------------------ END COSTINGS / VAT

        if(!$order->items()->create($new_item))
        {
            Throw new \Exception('Failed to store the item, rolling back changes');
        }

        return $order;
    }

    /**
     * Get the item for dispatch
     * @param $id
     */
    public static function getItemForDispatch(Int $id)
    {
        $data = \Illuminate\Support\Facades\DB::table('purchase_order_items')
            ->selectRaw('purchase_order_items.id, purchase_order_items.product_title,purchase_order_items.product_id, purchase_order_items.qty, SUM(IFNULL(purchase_order_dispatch_items.qty,0)) as qty_sent')
            ->join('purchase_order_dispatch_items','purchase_order_dispatch_items.purchase_order_item_id','=','purchase_order_items.id','left')
            ->where('purchase_order_items.id', $id)
            ->groupBy('purchase_order_items.id','purchase_order_items.product_title')
            ->first();

        if($data)
        {
            return $data;
        }

        return false;
    }

}
