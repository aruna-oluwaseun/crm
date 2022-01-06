<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Holiday extends Model
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
     * Get user for holiday
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get user for holiday
     * @return BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by','id');
    }

    /**
     * Get user for holiday
     * @return BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by','id');
    }
    /**
     * Get user for holiday
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    /**
     * Get holiday for current year
     * @param $query
     * @return mixed
     */
    public function scopeCurrentYear($query)
    {
        /*$date = new \DateTime();
        $start = $date->modify('first day of january')->format('Y-m-d');
        $end = $date->modify('last day of december')->format('Y-m-d');*/
        return $query->whereYear('holidays.date_start',date('Y'))->whereYear('holidays.date_end',date('Y'));
    }

    /**
     * Holidays only
     * @param $query
     * @return mixed
     */
    public function scopeHoliday($query)
    {
        return $query->where('holidays.type','holiday');
    }

    /**
     * Absent only
     * @param $query
     * @return mixed
     */
    public function scopeAbsent($query)
    {
        return $query->where('holidays.type','!=','holiday');
    }

    /**
     * Approved holidays
     * @param $query
     * @return mixed
     */
    public function scopeApproved($query)
    {
        return $query->where('holidays.status','approved');
    }

    /**
     * Pending holidays
     * @param $query
     * @return mixed
     */
    public function scopePending($query)
    {
        return $query->where('holidays.status','pending');
    }

    /**
     * Declined holidays
     * @param $query
     * @return mixed
     */
    public function scopeDeclined($query)
    {
        return $query->where('holidays.status','declined');
    }

    /**
     * Get holiday requests
     * @return mixed|false
     */
    public static function requests()
    {
        $holidays = Holiday::select('holidays.*')
            ->join('users','users.id','=','holidays.user_id','left')
            ->pending()->currentYear()->where('users.franchise_id',current_user_franchise_id());

        return $holidays->get();

    }

    /**
     * Fetch the calendar events
     * @TODO Modify to fetch only holidays specific to your franchise
     * @param string $user_id
     * @return array
     */
    public static function fetch( $user_id = 'all' )
    {

        if($user_id == 'all') {
            $items = Holiday::with(['user'])->get();
        } else {
            $items = Holiday::with(['user'])->where('user_id', $user_id)->get();
        }

        $events = [];
        if( $items && $items->count() )
        {
            $tmp = [];
            foreach ($items as $item)
            {
                $class = 'fc-event-primary-dim';
                switch($item->type) {
                    case 'holiday' :
                        $class = $item->status == 'approved' ? 'fc-event-success' : 'fc-event-warning';
                        break;
                    case 'sick' :
                        $class = 'fc-event-danger';
                        break;
                    case 'authorised-absence' :
                        $class = 'fc-event-danger-dim';
                        break;
                }

                $tmp[] = [
                    'className'         => $class.' '.$item->type,
                    'id'                => $item->id,
                    'editable'          => get_user()->hasRole('super-admin') ? true : false,
                    'startEditable'     => get_user()->hasRole('super-admin') ? true : false,
                    'durationEditable'  => get_user()->hasRole('super-admin') ? true : false,
                    'title'             => $item->user->getFullNameAttribute() .' - '.($item->title ?: ucfirst($item->type)),
                    'description'       => $item->description,
                    //'url'               => '#',
                    'start'             => $item->date_start,
                    'end'               => $item->date_end,
                    'type'              => $item->type,
                    'user_id'           => $item->user_id,
                    'editTitle'         => $item->title ?: ucfirst($item->type),
                    'start_type'        => $item->start_type,
                    'end_type'          => $item->end_type,
                ];
            }

            $events = array_merge($events, $tmp);
        }

        if($franchise = current_user_franchise())
        {
            $national_holidays = NationalHoliday::where('division',$franchise->national_holiday)->get();
        }
        else
        {
            $national_holidays = NationalHoliday::all();
        }

        if( $national_holidays && $national_holidays->count() )
        {
            $tmp = [];
            foreach ($national_holidays as $item)
            {

                $nation = '';
                switch($item->division) {
                    case 'england-and-wales' :
                        $nation = 'England & Wales';
                        break;
                    case 'scotland' :
                        $nation = 'Scotland';
                        break;
                    case 'northern-ireland' :
                        $nation = 'Northern Ireland';
                        break;
                }

                $tmp[] = [
                    'className'         => 'fc-event-default',
                    'title'             => $item->title .' - '.$nation,
                    'start'             => $item->date,
                    'end'               => $item->date,
                    'allDay'            => true,
                    'display'           => 'background',
                    'description'       => 'National Holiday',
                    'backgroundColor'   => '#eeeee4',
                ];
            }

            $events = array_merge($events, $tmp);
        }

        //Log::info('Events '.print_r($events, true));

        return $events;
    }
}
