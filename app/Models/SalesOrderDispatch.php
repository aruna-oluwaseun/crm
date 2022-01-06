<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesOrderDispatch extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::updating(function ($model) {
            $model->updated_by = isset(get_user()->id) ? get_user()->id : null;
        });

        static::creating(function ($model) {
            $model->created_by = isset(get_user()->id) ? get_user()->id : null;
        });
    }

    protected $guarded = [];

    protected $casts = [
        'delivery_address_data' => 'array'
    ];

    /**
     * The items on dispatch
     * @return HasMany
     */
    public function items() : HasMany
    {
        return $this->hasMany(SalesOrderDispatchItem::class);
    }

    /**
     * The courier
     * @return HasOne
     */
    public function courier() : BelongsTo
    {
        return $this->belongsTo(Courier::class)->withDefault();
    }

    /**
     * Who loaded the item
     * @return HasOne
     */
    public function loadedBy() : BelongsTo
    {
        return $this->belongsTo(User::class,'loaded_by');
    }

    /**
     * Who created the dispatch
     * @return HasOne
     */
    public function createdBy() : BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }


    /**
     * Who updated the dispatch
     * @return HasOne
     */
    public function updatedBy() : BelongsTo
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    /**
     * The delivery address
     * @return HasOne
     */
    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class,'delivery_id');
    }

    /**
     * The sales order
     * @return HasOne
     */
    public function salesOrder() : BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    /**
     * Get the boxes / pallets used
     * @return HasMany
     */
    public function boxes()
    {
        return $this->hasMany(SalesOrderDispatchBox::class);
    }
}
