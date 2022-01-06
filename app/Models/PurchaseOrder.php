<?php

namespace App\Models;

use App\Mail\EmailPurchase;
use App\Scopes\FranchiseScope;
use App\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use mysql_xdevapi\Exception;

class PurchaseOrder extends Model
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

    protected $guarded = [];

    protected $casts = [
        'billing_address_data' => 'array',
        'delivery_address_data' => 'array'
    ];

    /*
     * First field is where
     * Subsequent are orWhere
     */
    protected $searchable = [
        'id',
        'supplier_title'
    ];

    /**
     * Get the associated vat return
     */
    public function vatReturn()
    {
        return $this->belongsTo(VatReturn::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('purchase_order_status_id',[2,3,4]);
    }

    /**
     * The order items
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Dispatches
     */
    public function dispatches() : HasMany
    {
        return $this->hasMany(PurchaseOrderDispatch::class);
    }

    /**
     * Dispatches
     */
    public function status()
    {
        return $this->belongsTo(PurchaseOrderStatus::class,'purchase_order_status_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Not filed for VAT
     * @param $query
     * @return mixed
     */
    public function scopeVatNotFiled($query)
    {
        return $query->whereNull('vat_return_id');
    }

    /**
     * Filed for VAT
     * @param $query
     * @return mixed
     */
    public function scopeVatFiled($query)
    {
        return $query->whereNotNull('vat_return_id');
    }


    public function scopeOrdered($query)
    {
        return $query->where('purchase_order_status_id', 2);
    }

    /**
     * Create purchase order
     * @param $data
     * @param $items
     * @return mixed
     * @throws \Exception
     */
    public static function createOrder($data, $items)
    {
        // finish
        // shipping vat
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

        $supplier = Supplier::with(['products.unitOfMeasure'])->find($data['supplier_id']);
        $data['supplier_title'] = $supplier->title;
        $data['franchise_id'] = current_user_franchise_id();

        if(!$order = PurchaseOrder::create($data))
        {
            Throw new \Exception('Failed to create order');
        }

        if(empty($items)) {
            Throw new \Exception('Failed to create order because no order items were found');
        }

        $products = $supplier->products;

        foreach($items as $item)
        {
            // Fetch product
            $product = $products->find($item['product_id']);

            $new_item = [
                'supplier_code'     => $product->pivot->code,
                'product_id'        => $product->id,
                'product_title'     => $product->title,
                'qty'               => $item['qty'],
                'vat_type_id'       => $item['vat_type_id']
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
                $new_item['item_cost'] = $product->pivot->cost_to_us;
            }
            else
            {
                $new_item['item_cost'] = $item['item_cost'];
            }

            // recalculate costs in-case JS rounding errors
            $vat_value = $vat ? $vat->value : 20; // default 20 percent
            $new_item['vat_percentage'] = $vat_value;
            $new_item['net_cost'] = $new_item['item_cost']*$new_item['qty'];
            $new_item['vat_cost'] = ($vat_value / 100) * $new_item['net_cost'];
            $new_item['gross_cost'] = $new_item['net_cost'] + $new_item['vat_cost'];
            // ------------------------ END COSTINGS / VAT

            if(!$order->items()->create($new_item))
            {
                Throw new \Exception('Failed to store the item, rolling back changes');
            }
        }

        return $order->fresh();

    }

    /**
     * Email Purchase order
     * @param $order
     * @param $email
     * @return bool
     */
    public static function emailPurchase($order, $email): bool
    {
        Mail::to($email)->queue(new EmailPurchase($order));

        if(count(Mail::failures()) <= 0)
        {
            $info = PurchaseOrderEmail::create([
                'purchase_order_id' => $order->id,
                'email'      => $email
            ]);

            if($info) {
                return true;
            }
        }

        return false;

    }
}
