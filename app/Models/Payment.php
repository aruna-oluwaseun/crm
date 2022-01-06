<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
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

    public function scopeIncome($query)
    {
        return $query->where('type','income');
    }

    public function scopeRefund($query)
    {
        return $query->where('type','refund');
    }

    public function scopePayout($query)
    {
        return $query->where('type','payout');
    }

    public function scopeComplete($query)
    {
        return $query->where('status','complete');
    }

    public function scopePending($query)
    {
        return $query->where('status','pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status','processing');
    }

    public function scopeError($query)
    {
        return $query->where('status','error');
    }
}
