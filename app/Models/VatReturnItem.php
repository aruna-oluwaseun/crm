<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vatReturn()
    {
        return $this->belongsTo(VatReturn::class);
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function refund(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function expense() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function purchase()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
