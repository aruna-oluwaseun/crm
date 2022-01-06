<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalesOrderItem extends Model
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
     * Get what has been dispatched
     */
    public function dispatches() : HasMany
    {
        return $this->hasMany(SalesOrderDispatchItem::class);
    }

    /**
     * Get invoice
     */
    public function invoice() : BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get product
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    /**
     * Check if item is paid
     */
    public function scopeIsPaid( $query )
    {
        return $query->where('is_paid',1);
    }
    /**
     * Check if item is paid
     */
    public function scopeDepositPaid( $query )
    {
        return $query->where('paid_deposit',1);
    }
    /**
     * Check if item is paid
     */
    public function scopeNotPaid( $query )
    {
        return $query->where('is_paid',0);
    }

    /**
     * Check if item is paid
     */
    public function scopeDepositNotPaid( $query )
    {
        return $query->where('paid_deposit',0);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActiveTrainingBooking($query)
    {
        return $query->where('paid_deposit',1)->orWhere('is_paid',1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePendingTrainingBooking($query)
    {
        return $query->where('paid_deposit',0)->where('is_paid',0);
    }

    /**
     * Check if item is paid
     */
    public function scopeIsInvoiced( $query )
    {
        return $query->where('invoiced_id',1);
    }

    /**
     * Get the item for dispatch
     * @param $id
     */
    public static function getItemForDispatch(Int $id)
    {
        $data = \Illuminate\Support\Facades\DB::table('sales_order_items')
            ->selectRaw('sales_order_items.id, sales_order_items.product_title,sales_order_items.product_id, sales_order_items.qty, SUM(IFNULL(sales_order_dispatch_items.qty,0)) as qty_sent')
            ->join('sales_order_dispatch_items','sales_order_dispatch_items.sales_order_item_id','=','sales_order_items.id','left')
            ->where('sales_order_items.id', $id)
            ->groupBy('sales_order_items.id','sales_order_items.product_title')
            ->first();

        if($data)
        {
            return $data;
        }

        return false;
    }


    public static function store(Request $request)
    {
        $data = $request->except(['_token','none_stock_item','product_id','product_title']);

        // The form should post correct values as JS is taking care of it but just incase
        $product = false;
        $train_date     = null;
        $train_course   = null;
        $train_date_id  = null;
        $attendee       = null;

        if($request->exists('none_stock_item'))
        {
            $data['product_title'] = $request->input('product_title');
            if($request->input('weight') != '') {
                $data['unit_of_measure_id'] = $request->input('unit_of_measure_id');
                $data['weight'] = $request->input('weight')*$data['qty'];
            }
        }
        else
        {
            $data['product_id'] = $request->input('product_id');
            $product = Product::find($data['product_id']);
            $data['product_title'] = $product->title;
            if($product->unit_of_measure_id) {
                $data['unit_of_measure_id'] = $product->unit_of_measure_id;
            }

            $data['weight'] = $product->weight*$data['qty'];

            if($request->exists('training_date_id'))
            {
                if(! $training = TrainingDate::find($request->input('training_date_id')) )
                {
                    if($request->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Could not find selected training date in the system.']);
                    }

                    return back()->with('error','Could not find selected training date in the system.');
                }

                $train_date = explode(' ', $training->date_start)[0];
                $train_course = $training->product_title ?: $product->title;
                $train_date_id = $training->id;
                $attendee = $request->exists('attendee') ? $request->input('attendee') : null;
            }
        }

        // Weight KG
        if(isset($data['weight']) && $data['weight'] > 0)
        {
            if(isset($data['unit_of_measure_id']) && $data['unit_of_measure_id'])
            {
                $converted = UnitOfMeasure::convert(intval($data['unit_of_measure_id']),'Kg', $data['weight']);
                if($converted->success) {
                    $data['weight_kg'] = $converted->value;
                }
            }
            else
            {
                unset($data['weight']);
            }

        }

        $vat = VatType::find($request->vat_type_id);
        $price = false;
        if($product)
        {
            $price = $product->net_cost;
            if($product->sale_net_cost)
            {
                $price = $product->sale_net_cost;
            }
        }

        // recalculate costs in-case JS rounding errors
        $vat_value = $vat ? $vat->value : 20; // default 20 percent
        $data['vat_percentage'] = $vat_value;

        $data['net_cost'] = $data['item_cost']*$request->input('qty');
        // if sale cost is used store the discount cost
        if($price)
        {
            $data['discount_cost'] = ($price*$request->input('qty'))-$data['net_cost'];
        }

        $data['vat_cost'] = ($vat_value / 100) * $data['net_cost'];
        $data['gross_cost'] = $data['net_cost'] + $data['vat_cost'];

        if($data['discount_cost']  <= 0 ) { $data['discount_cost'] = null; }

        $data['training_date'] = $train_date;
        $data['training_course'] = $train_course;
        $data['training_date_id'] = $train_date_id;
        $data['attendee'] = $attendee;

        if(!$item = SalesOrderItem::create($data))
        {
            Throw new \Exception('Failed save item on purchase order');
        }

        return $item;
    }
}
