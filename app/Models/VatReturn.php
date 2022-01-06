<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::updating(function ($model) {
            $model->updated_by = isset(get_user()->id) ? get_user()->id : null;
        });

        static::creating(function ($model) {
            $model->created_by = isset(get_user()->id) ? get_user()->id : null;
        });
    }

    /**
     * Get associated items
     */
    public function items()
    {
        return $this->hasMany(VatReturnItem::class);
    }
}
