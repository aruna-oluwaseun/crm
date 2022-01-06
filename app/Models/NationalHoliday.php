<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalHoliday extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get holiday for current year
     * @param $query
     * @return mixed
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('date',date('Y'));
    }

}
