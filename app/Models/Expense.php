<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the associated vat return
     */
    public function vatReturn()
    {
        return $this->belongsTo(VatReturn::class);
    }

    /**
     * Not filed for VAT
     * @param $query
     * @return mixed
     */

    public function eitems(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function scopeVatNotFiled($query)
    {
        return $query->whereNull('vat_return_id');
    }

    /**
     * Filed for VAT
     * @param $query
     * @return mixed
     */
    public function scopeVatFiled($query)
    {
        return $query->whereNotNull('vat_return_id');
    }
}
