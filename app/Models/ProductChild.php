<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductChild extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the product detail
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detail() {
        return $this->belongsTo('products');
    }
}
