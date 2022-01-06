<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDispatchBox extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The dispatch
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispatch()
    {
        return $this->belongsTo(SalesOrderDispatch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
