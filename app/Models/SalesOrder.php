<?php

namespace App\Models;

use App\Mail\EmailPurchase;
use App\Mail\EmailQuote;
use App\Scopes\FranchiseScope;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SalesOrder extends Model
{
    use HasFactory, SearchTrait;

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
        'id',
        'first_name',
        'last_name',
        'email'
    ];

    protected $guarded = [];

    protected $casts = [
        'billing_address_data' => 'array',
        'delivery_address_data' => 'array'
    ];

    /**
     * Full name on order
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
    public function getInitialsAttribute(): string
    {
        $intials = substr($this->first_name, 0,1);
        $intials .= substr($this->last_name, 0,1);
        $intials = strtoupper($intials);
        return "{$intials}";
    }

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the associated status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(SalesOrderStatus::class, 'sales_order_status_id');
    }

    /**
     * Get the associated status
     */
    public function orderType(): BelongsTo
    {
        return $this->belongsTo(OrderType::class);
    }

    /**
     * Get the associated payment terms
     */
    public function paymentTerm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    /**
     * Invoices
     */
    public function invoices() : HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * The order items
     */
    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    /**
     * Dispatches
     */
    public function dispatches() : HasMany
    {
        return $this->hasMany(SalesOrderDispatch::class);
    }

    /**
     * Get address in-case it wasn't stored on order
     * @return BelongsTo
     */
    public function billingAddress() : BelongsTo
    {
        return $this->belongsTo(Address::class,'billing_id');
    }

    /**
     * Get address in-case it wasn't stored on order
     * @return BelongsTo
     */
    public function deliveryAddress() : BelongsTo
    {
        return $this->belongsTo(Address::class,'delivery_id');
    }

    /**
     * Get active sales.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        // get order statuses that are considered active orders i.e not cancelled
        $ids = DB::table('sales_order_statuses')->select(DB::raw('GROUP_CONCAT(id) as active_ids'))->where('active_order', 1)->first();

        if( $ids ) {
            $ids->active_ids = (array) @explode(',',$ids->active_ids);
            return $query->whereIn('sales_order_status_id', $ids->active_ids);
        }

    }

    /**
     * Status scope
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfStatus($query, $id)
    {
        return $query->where('sales_order_status_id', $id);
    }

    /**
     * Create a sales order
     * Will be used in shop and admin
     * @param $data
     * @param $items
     * @TODO process customer discounts
     * @TODO process training
     * @TODO process options
     */
    public static function createOrder($data, $items)
    {
        // shipping vat
        /** @TODO change this because the VAT ID is provided now  */
        if($data['shipping_cost'] > 0) {
            $shipping_vat = VatType::find($data['shipping_vat_type_id']);

            if($shipping_vat) {
                $shipping_vat_rate = $shipping_vat->value;
            } else {
                $shipping_vat_rate = 20;
            }
            $data['shipping_vat']   = ($shipping_vat_rate / 100) * $data['shipping_cost'];
            $data['shipping_gross'] = $data['shipping_vat'] + $data['shipping_cost'];
        } else {
            $data['shipping_cost'] = null;
        }

        if(!$order = SalesOrder::create($data))
        {
            Throw new \Exception('Failed to create order');
        }

        if(empty($items)) {
            Throw new \Exception('Failed to create order because no order items were found');
        }

        foreach($items as $item)
        {
            // Default vars
            $train_date     = null;
            $train_course   = null;
            $train_date_id  = null;
            $attendee       = null;

            // Fetch product
            $product = Product::find($item['product_id']);

            if(isset($item['training_date_id']))
            {
                if(! $training = TrainingDate::find($item['training_date_id']) )
                {
                    Throw new \Exception('Could not save item to database because training course could not be found');
                }

                $train_date = explode(' ', $training->date_start)[0];
                $train_course = $training->product_title ?: $product->title;
                $train_date_id = $training->id;
                $attendee = isset($item['attendee']) ? $item['attendee'] : null;
            }

            $new_item = [
                'product_title'             => $product->title,
                'sales_order_id'            => $order->id,
                'product_id'                => $item['product_id'],
                'qty'                       => $item['qty'],
                'vat_type_id'               => $item['vat_type_id'],
                //'discount_code'             =>
                //'discount_id'               =>
                //'profit'                    =>
                //'cost_to_us'                =>
                //'paid_deposit'              =>
                //'amount_outstanding'        =>
                //'amount_outstanding_due'    =>
                //'is_paid'                   =>
                'training_date'             => $train_date,
                'training_course'           => $train_course,
                'training_date_id'          => $train_date_id,
                'attendee'                  => $attendee,
                //'attributes'                =>
                //'notes'                     =>
                //'is_additional_shipping'    =>
            ];

            // ----------------------- START WEIGHT
                if($product->unit_of_measure_id) {
                    $new_item['unit_of_measure_id'] = $product->unit_of_measure_id;
                }

                // Product weight
                if($product->weight)
                {
                    $new_item['weight'] = $product->weight*$new_item['qty'];

                    // Weight KG
                    if($new_item['weight'] > 0)
                    {
                        if(isset($new_item['unit_of_measure_id']) && $new_item['unit_of_measure_id'])
                        {
                            $converted = UnitOfMeasure::convert(intval($new_item['unit_of_measure_id']),'Kg', $new_item['weight']);
                            if($converted->success) {
                                $new_item['weight_kg'] = $converted->value;
                            }
                        }
                        /*else
                        {
                            unset($new_item['weight']);
                        }*/
                    }
                }
            // ------------------------ END WEIGHT

            // ------------------------ START COSTINGS / VAT
                $vat = VatType::find($new_item['vat_type_id']);

                if(!isset($item['item_cost']))
                {
                    $new_item['item_cost'] = $product->net_cost;
                    if($product->sale_net_cost)
                    {
                        $new_item['item_cost'] = $product->sale_net_cost;
                    }
                }
                else
                {
                    $new_item['item_cost'] = $item['item_cost'];
                }

                // recalculate costs in-case JS rounding errors
                $vat_value = $vat ? $vat->value : 20; // default 20 percent
                $new_item['vat_percentage'] = $vat_value;

                $new_item['net_cost'] = $new_item['item_cost']*$new_item['qty'];

                // if sale cost is used store the discount cost
                if($new_item['item_cost'])
                {
                    $new_item['discount_cost'] = ($new_item['item_cost']*$new_item['qty'])-$new_item['net_cost'];
                }

                if(isset($new_item['discount_cost']) && $new_item['discount_cost'] == 0.00) {
                    $new_item['discount_cost'] = null;
                }

                $new_item['vat_cost'] = ($vat_value / 100) * $new_item['net_cost'];
                $new_item['gross_cost'] = $new_item['net_cost'] + $new_item['vat_cost'];
            // ------------------------ END COSTINGS / VAT

            SalesOrderItem::create($new_item);
        }

        return $order->fresh();

    }

    /**
     * Email quote the order
     * @param $oder
     * @param $email
     * @return bool
     */
    public static function emailQuote($order, $email): bool
    {
        Mail::to($email)->queue(new EmailQuote($order));

        if(count(Mail::failures()) <= 0)
        {
            $info = QuoteEmail::create([
                'sales_order_id' => $order->id,
                'email'      => $email
            ]);

            if($info) {
                return true;
            }
        }

        return false;

    }

}
