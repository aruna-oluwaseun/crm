<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDate extends Model
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

    public function scopeActive($query)
    {
        return $query->whereIn('training_dates.status',['upcoming','live']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('training_dates.status','upcoming');
    }

    public function scopeLive($query)
    {
        return $query->where('training_dates.status','live');
    }

    public function scopeCompleted($query)
    {
        return $query->where('training_dates.status','completed');
    }

    public function scopeDeleted($query)
    {
        return $query->where('training_dates.status','deleted');
    }

    /**
     * The stock links for course
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockLinks()
    {
        return $this->hasMany(TrainingDateStockLink::class);
    }

    /**
     * Get parent course that can manage this stock
     * so we can show users booked on this course
     */
    public function linkedToTrainingStock()
    {
        return $this->hasOne(TrainingDateStockLink::class,'updates_training_id_stock');
    }


    /**
     * Get the order items associated with the sales order
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendees()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    /**
     * Get the spaces remaining including any spaces booked via parent linked course
     * @return \stdClass (total, paid, pending, remaining)
     * @param $training_date
     */
    public static function spaces($training_date)
    {
        $detail = TrainingDate::with(['attendees.salesOrder','linkedToTrainingStock.trainingCourse'])->find($training_date);

        $stock = new \stdClass();
        $stock->total = $detail->stock;
        $stock->paid = 0;
        $stock->pending = 0;
        $stock->remaining = 0;

        if($detail && $detail->count())
        {
            $pending = $detail->attendees()->pendingTrainingBooking()->count();
            $paid = $detail->attendees()->activeTrainingBooking()->count();

            if(isset($detail->linkedToTrainingStock))
            {
                $pending += $detail->linkedToTrainingStock->trainingDate->attendees()->pendingTrainingBooking()->count();
                $paid += $detail->linkedToTrainingStock->trainingDate->attendees()->activeTrainingBooking()->count();
            }

            $stock->paid = $paid;
            $stock->pending = $pending;
            $stock->remaining = $stock->total - $paid;
        }

        return $stock;
    }

    /**
     * Get the active attendees for a given attendee
     * @param $training_date_id
     * @return false
     */
    /*public static function getActiveAttendees($training_date_id)
    {
        $active = SalesOrderItem::select('sales_order_items.*')->where('sales_order_items.training_date_id', $training_date_id)
            ->where('sales_order_statuses.active_order', 1)
            ->join('sales_orders','sales_orders.id','=','sales_order_items.sales_order_id','left')
            ->join('sales_order_statuses','sales_order_statuses.id', '=','sales_order_status_id')
            ->get();

        if($active->count())
        {
            return $active;
        }

        return false;
    }*/

}
