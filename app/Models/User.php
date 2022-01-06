<?php

namespace App\Models;

use App\Scopes\FranchiseScope;
use App\Traits\HasPermissionsTrait;
use App\Traits\SearchTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait, SearchTrait;

    protected static function booted()
    {
        static::updating(function ($model) {
            $model->updated_by = isset(get_user()->id) ? get_user()->id : null;
        });

        static::creating(function ($model) {
            $model->created_by = isset(get_user()->id) ? get_user()->id : null;
        });

    }

    protected $searchable = [
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'manager_id',
        'holiday_allowance',
        'position_in_company',
        'contact_number',
        'contact_number2',
        'em_name',
        'em_phone',
        'em_email',
        'status',
        'notes',
        'role_id',
        'franchise_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Accessor
     * Get full name
     * @return string
     */
    public function getFullNameAttribute() : string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Accessor
     * Get full name
     * @return string
     */
    public function getInitialsAttribute() : string
    {
        $intials = substr($this->first_name, 0,1);
        $intials .= substr($this->last_name, 0,1);
        $intials = strtoupper($intials);
        return "{$intials}";
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeInMyFranchise($query)
    {
        return $query->where('franchise_id', current_user_franchise_id());
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Get the franchise the user belongs to
     */
    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    /**
     * Get notifications
     */
    public function notifications() : HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get notifications
     */
    public function notificationsUnread() : HasMany
    {
        return $this->hasMany(Notification::class)->unread();
    }

    /**
     * Get notifications
     * @return HasMany
     */
    public function notificationsRead() : HasMany
    {
        return $this->hasMany(Notification::class)->read();
    }

    /**
     * Get holidays
     * @return HasMany
     */
    public function holidays() : HasMany
    {
        return $this->hasMany(Holiday::class)->holiday();
    }

    /**
     * Get holidays
     * @return HasMany
     */
    public function absent() : hasMany
    {
        return $this->hasMany(Holiday::class)->absent();
    }

    /**
     * Get all conditionally for search results
     * @param array $parameters
     */
    public function getAll($parameters = [])
    {
        extract(array_merge([
            'status'        => 'active',
            'first_name'    => false,
            'last_name'     => false,
            'email'         => false,
            'role_id'       => false,
            'manager_id'    => false
        ], $parameters));

        $query = User::query();

        /** @var $status */
        if($status) {
            $query->where('status', $status);
        }
        /** @var $first_name */
        if($first_name) {
            $query->where('first_name', $first_name);
        }
        /** @var $last_name */
        if($last_name) {
            $query->where('last_name', $last_name);
        }
        /** @var $email */
        if($email) {
            $query->where('email', $email);
        }
        /** @var $role_id */
        if($last_name) {
            $query->where('role_id', $role_id);
        }
        /** @var $manger_id */
        if($last_name) {
            $query->where('manager_id', $last_name);
        }

        $results = $query->get();

        if($results && $results->count())
        {
            return $results;
        }

        return false;
    }


    public static function remainingHolidays($user)
    {
        if(is_int($user))
        {
            $user = get_user($user);
        }

        $approved = $user->holidays()->approved()->get();

        if(!$days_taken = $approved->sum('time_off'))
        {
            $days_taken = 0;
        }

        $national_holiday_zone = current_user_franchise() ? current_user_franchise()->national_holiday : 'england-and-wales';
        $national_holidays = NationalHoliday::where('division',$national_holiday_zone)->currentYear()->get();

        $national_days_off = 0;
        $bookable = $user->holiday_allowance;
        if($national_holidays && $national_holidays->count())
        {
            $national_days_off = $national_holidays->count();
            $bookable-= $national_days_off;
        }

        return $bookable-$days_taken;
    }

}
