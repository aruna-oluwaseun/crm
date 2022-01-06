<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory;

    //protected $primaryKey = ['address_id', 'customer_id'];
    //public $incrementing = false;

    protected $guarded = [];

    public function detail()
    {
        return $this->belongsTo(Address::class,'address_id');
    }
}
