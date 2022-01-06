<?php

namespace App\Models;

use App\Events\ManualStockAdded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PhpParser\Node\Expr\Throw_;

class Stock extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movements() : HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate stock levels
     * @param $id
     */
    public static function stockLevel($id, $option_id = false): \stdClass
    {
        // Pending stock
        $pending_sales = SalesOrderItem::where('sales_order_items.product_id',$id)
            ->where('sales_order_items.is_paid',1)->where('sales_orders.sales_order_status_id','!=', 9)
            ->selectRaw('SUM(sales_order_items.qty) as pending_stock')
            ->join('sales_orders','sales_orders.id','=','sales_order_items.sales_order_id','left')
            ->first();

        // Actual stock
        $actual = \App\Models\Stock::where('product_id',$id)->first();

        if($actual === null)
        {
            $actual = new \stdClass();
            $actual->stock = 0;
            $actual->unit_level_stock = 0;
        }

        $stock = new \stdClass();
        $stock->pending_sales = intval($pending_sales->pending_stock);
        $stock->pending = $actual->stock-intval($pending_sales->pending_stock);
        $stock->actual = intval($actual->stock);
        $stock->actual_unit_stock = $actual->unit_level_stock;

        return $stock;
    }

    /**
     * Check stock item exists
     * if not create one
     * @param $product_id
     * @return mixed
     */
    public static function stockExists($product_id)
    {
        $stock = Stock::where('product_id', $product_id)->first();

        if($stock === null)
        {
            $stock = Stock::create(['product_id' => $product_id])->fresh();
        }

        return $stock;
    }

    /**
     * Add stock
     * @param $product_id
     * @param array $data
     */
    public static function addStock($product_id, $data = [])
    {
        if(empty($data)) {
            return false;
        }

        $stock = self::stockExists($product_id);

        if($data['unit_level_stock'] <= 0) {
            $data['unit_level_stock'] = null;
        }

        $movement = $stock->movements()->create([
            'movement'          => $data['stock'],
            'unit_movement'     => $data['unit_level_stock'],
            'manually_added'    => 1
        ]);

        if($movement)
        {
            ManualStockAdded::dispatch($movement);

            return true;
        }

        return false;
    }

    /**
     * Update the stock
     * @param $id
     */
    /*public static function updateStock($id)
    {
        $detail = SalesOrderDispatch::with(['items'])->find($id);

        if($detail && $detail->items) {

            foreach($detail->items as $item)
            {
                if(!$item->product_id) {
                    continue;
                }

                // Main stock
                $stock = Stock::stockExists($item->product_id);

                // Stock movement
                $stock_movement = StockMovement::create([
                    'stock_id'                  => $stock->id,
                    'movement'                  => -$item->qty,
                    'sales_order_item_id'       => $item->sale_order_id
                ]);

                $stock->stock = $stock->stock-$item->qty;
                $stock->save();
            }
        }
    }*/
}
