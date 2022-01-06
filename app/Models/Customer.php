<?php

namespace App\Models;

use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;

class Customer extends Model
{
    use HasFactory, SearchTrait;

    protected $guarded = [
        'password',
        'remember_token'
    ];

    /*
     * First field is where
     * Subsequent are orWhere
     */
    protected $searchable = [
        'first_name',
        'last_name',
        'email'
    ];

    /**
     * Accessor
     * Full name
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Accessor
     * Get full name
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        $intials = substr($this->first_name, 0,1);
        $intials .= substr($this->last_name, 0,1);
        $intials = strtoupper($intials);
        return "{$intials}";
    }

    /**
     * First letter capital
     * @param $value
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

    /**
     * Second letter capital
     * @param $value
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }

    /**
     * Hash the password before update
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Lowercase email
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status','active');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSuspended($query)
    {
        return $query->where('status','suspended');
    }

    /**
     * Get customer addresses
     */
    public function addresses()
    {
        return $this->belongsToMany(Address::class,'customer_addresses')->active();
        //return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Get the customers orders
     */
    public function orders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    /**
     * Get the invoices via salesorders
     * @return HasManyThrough
     */
    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class,SalesOrder::class);
    }
}

