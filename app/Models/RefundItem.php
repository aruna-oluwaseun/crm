<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function refund()
    {
        return $this->belongsTo(Refund::class);
    }

    public function saleOrderItem()
    {
        return $this->belongsTo(SalesOrderItem::class);
    }
}
