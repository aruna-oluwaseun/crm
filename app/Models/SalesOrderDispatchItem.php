<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderDispatchItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The product shipped
     * @return BelongsTo
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the actual order item from the sale
     * @return BelongsTo
     */
    public function orderedItem() : BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class,'sales_order_item_id');
    }
}
