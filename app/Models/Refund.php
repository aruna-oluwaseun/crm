<?php

namespace App\Models;


use App\Mail\EmailRefund;
use App\Scopes\FranchiseScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Refund extends Model
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

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
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

    public function items()
    {
        return $this->hasMany(RefundItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function scopeComplete($query)
    {
        return $query->where('status','complete');
    }

    /**
     * Get the refunds associated with refund
     * @return HasMany
     */
    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all emails that were sent
     * with this refund
     */
    public function emails()
    {
        return $this->hasMany(RefundEmail::class);
    }

    /**
     * Email the invoice
     * @param $id
     * @param string $email
     */
    public static function email($id, $email = '')
    {
        $refund = Refund::with('items')->find($id);

        if(!$refund) {
            log('Refund could not be emailed, refund '.$id.' not found. Its possible a transaction was reversed on invoice creation.');
            return false;
        }

        if(!$email) {
            // use the email address on from the sale
            $mail = $refund->email;
        }

        Mail::to($email)->queue(new EmailRefund($refund));

        if(count(Mail::failures()) <= 0)
        {
            $info = RefundEmail::create([
                'refund_id' => $id,
                'email'      => $email
            ]);

            if($info) {
                return true;
            }
            return true;
        }

        return false;
    }
}
