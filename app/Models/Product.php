<?php

namespace App\Models;

use App\Scopes\FranchiseScope;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, HasMediaTrait, SearchTrait;

    protected $guarded = [];

    protected static function booted()
    {
        static::updating(function ($model) {
            $model->updated_by = isset(get_user()->id) ? get_user()->id : null;
        });

        static::creating(function ($model) {
            $model->created_by = isset(get_user()->id) ? get_user()->id : null;
        });

        static::addGlobalScope(new FranchiseScope);
    }

    /*
     * First field is where
     * Subsequent are orWhere
     */
    protected $searchable = [
        'title',
        'code',
        'commodity_code'
    ];

    /**/
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(160)->height(120)
            ->performOnCollections('images');

        $this->addMediaConversion('small')
            ->width(280)->height(210)
            ->performOnCollections('images');

        $this->addMediaConversion('medium')
            ->width(400)->height(300)
            ->performOnCollections('images');

        $this->addMediaConversion('large')
            ->width(640)->height(480)
            ->performOnCollections('images');
    }

    /**
     * Uppercase title
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords($value);
    }

    /**
     * Get the unit of measure
     * @return BelongsTo
     */
    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }

    /**
     * Get the vat type
     * @return BelongsTo
     */
    public function vatType() : BelongsTo
    {
        return $this->belongsTo(VatType::class);
    }

    /**
     * Get active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'active');
    }


    /**
     * Get disabled products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'disabled');
    }

    /**
     * Get deleted products
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeleted($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'deleted');
    }

    /**
     * Get manufactured
     * @param $query
     * @return mixed
     */
    public function scopeIsManufactured($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_manufactured',1);
    }

    /**
     * Is available online
     * @param $query
     * @return mixed
     */
    public function scopeIsAvailableOnline($query)
    {
        return $query->where('is_available_online',1);
    }

    /**
     * Is training Product
     * @param $query
     * @return mixed
     */
    public function scopeIsTraining($query)
    {
        return $query->where('is_training',1);
    }

    /**
     * Is NOT training Product
     * @param $query
     * @return mixed
     */
    public function scopeIsNotTraining($query)
    {
        return $query->where('is_training',0);
    }

    public function scopeIsBox($query)
    {
        return $query->where('is_shipping_box', 1);
    }

    public function scopeIsPallet($query)
    {
        return $query->where('is_shipping_pallet', 1);
    }

    /**
     * Get the sub products
     */
    public function children() : HasMany
    {
        return $this->hasMany(ProductChild::class,'parent_id');
    }

    /**
     * Get the supplier
     */
    public function suppliers() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Supplier::class);
    }

    /**
     * Get all stock movements
     */
    public function stock() : HasOne
    {
        return $this->hasOne(Stock::class);
    }

    /**/
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the linked products
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withPivot(['id','value_id','attribute_title','exclude_value_attributes','net_adjustment','created_at','updated_at', 'created_by','updated_by']);
    }

    /**
     * DEPRECATED : using spatie media library
     * @return HasMany
     */
    /*public function uploads()
    {
        return $this->belongsToMany(Upload::class)->withPivot(['upload_id','product_id','default_photo']);
    }*/

    /**/
    public static function getAll($parameters = [])
    {
        extract(array_merge([
            'status'                => 'active',
            'title'                 => false,
            'code'                  => false,
            'is_available_online'   => false,
            'is_manufactured'       => false,
            'is_discountable'       => false,
            'is_training'           => false,
            'is_assessment'         => false,
            'is_free_shipping'      => false
        ], $parameters));

    }



}
