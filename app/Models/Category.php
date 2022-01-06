<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
     * Disabled
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status','active');
    }

    /**
     * Active
     * @param $query
     * @return mixed
     */
    public function scopeDisabled($query)
    {
        return $query->where('status','disabled');
    }

    /*public function scopeDeleted($query)
    {
        return $query->where('status','deleted');
    }*/

    /**
     * The subcategories
     */
    public function children()
    {
        return $this->hasMany(Category::class,'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id');
    }

    /**
     * Products linked
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }


    public static function getChildByParentID($id)
    {
        return Category::where('parent_id',$id)->orderBy('id','ASC')->pluck('title','id');
    }

}
