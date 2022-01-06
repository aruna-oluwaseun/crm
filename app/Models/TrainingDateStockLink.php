<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDateStockLink extends Model
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

    /**
     * The parent training course that the stock link relates to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingDate()
    {
        return $this->belongsTo(TrainingDate::class);
    }

    /**
     * The course of this stock link
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingCourse()
    {
        return $this->belongsTo(TrainingDate::class,'updates_training_id_stock');
    }
}
