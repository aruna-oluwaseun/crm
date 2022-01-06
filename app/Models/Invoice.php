<?php

namespace App\Models;

use App\Mail\EmailInvoice;
use App\Mail\EmailRefund;
use App\Mail\EmailPurchase;
use App\Scopes\FranchiseScope;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Invoice extends Model
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

        static::addGlobalScope(new FranchiseScope);
    }

    protected $guarded = [];

    protected $casts = [
        'billing_address_data' => 'array',
        'delivery_address_data' => 'array'
    ];

    /**
     * Get the sales order
     * @return BelongsTo
     */
    public function salesOrder() : BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    /**
     * Get the items on this invoice
     * @return HasMany
     */
    public function items() : HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    /**
     * The original order shipping
     * Not all order can have more than one invoice see
     * @return HasOne
     */
    public function shipping() : HasOne
    {
        return $this->hasOne(SalesOrder::class,'shipping_invoice');
    }

    /**
     * Get the invoice status
     * @return BelongsTo
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(InvoiceStatus::class, 'invoice_status_id');
    }

    /**
     * Get the payments associated with invoice
     * @return HasMany
     */
    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the associated vat return
     */
    public function vatReturn()
    {
        return $this->belongsTo(VatReturn::class);
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

    /**
     * All paid invoices
     * @param $query
     */
    public function scopePaid($query)
    {
        return $query->where('invoice_status_id', 3);
    }

    /**
     * Part paid invoices
     * @param $query
     */
    public function scopePartPaid($query)
    {
        return $query->where('invoice_status_id', 2);
    }

    /**
     * Part paid invoices
     * @param $query
     */
    public function scopePendingPayment($query)
    {
        return $query->where('invoice_status_id', 1);
    }

    /**
     * Part paid invoices
     * @param $query
     */
    public function scopeVoided($query)
    {
        return $query->where('invoice_status_id', 4);
    }

    /**
     * Get all emails that were sent
     * with this invoice
     */
    public function emails()
    {
        return $this->hasMany(InvoiceEmail::class);
    }

    /**
     * Update an invoice
     * @param array $data
     * @param array $id
     * @return bool|void
     */
    public function updateInvoice($data, $id)
    {

    }

    /**
     * Email the invoice
     * @param $id
     * @param string $email
     */
    public static function email($id, $email = '')
    {
        $invoice = Invoice::with('salesOrder')->find($id);

        if(!$invoice) {
            log('Invoice could not be emailed, invoice '.$id.' not found. Its possible a transaction was reversed on invoice creation.');
            return false;
        }

        if(!$email) {
            // use the email address on from the sale
            $mail = $invoice->salesOrder->email;
        }


        Mail::to($email)->queue(new EmailInvoice($invoice));

        if(count(Mail::failures()) <= 0)
        {
            $info = InvoiceEmail::create([
                'invoice_id' => $id,
                'email'      => $email
            ]);

            if($info) {
                return true;
            }
        }

        return false;
    }
}
