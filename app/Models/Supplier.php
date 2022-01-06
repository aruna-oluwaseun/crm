<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get products for supplier
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        //return $this->hasMany(ProductSupplier::class);
        //return $this->hasManyThrough(Product::class,ProductSupplier::class,'product_id','id');
        return $this->belongsToMany(Product::class)->withPivot(['default_supplier','code','cost_to_us','vat_type_id']);
    }

    /**
     * Purchase Order
     * @return HasMany
     */
    public function purchases()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class,'billing_id');
    }

    public function headOfficeAddress()
    {
        return $this->belongsTo(Address::class,'head_office_id');
    }
}
