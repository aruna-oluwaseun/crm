<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeRead($query)
    {
        return $query->whereNotNull('read')->orderBy('id','DESC');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read')->orderBy('id','DESC');
    }

    /**
     * Store notification
     * @param $data
     * @return false
     */
    public static function store($data)
    {
        if($notification = Notification::create($data))
        {
            return $notification->id;
        }

        return false;
    }
}
