<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
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
     * Get the roles this permission is linked too
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get all users with this individual permission
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
