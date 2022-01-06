<?php

/**
 * Set the page title
 */
if( !function_exists('set_page_title') )
{
    function set_page_title($str) {
        global $tpl_page_title;
        $tpl_page_title = $str;
    }
}

/**
 * Get a page title
 */
if( !function_exists('get_page_title') )
{
    function get_page_title($default = '') {
        global $tpl_page_title;

        if(isset($tpl_page_title) && $tpl_page_title != '')
        {
            return $tpl_page_title;
        }

        return $default;
    }
}

/**
 * Check a element is selected
 */
if(!function_exists('is_selected'))
{
    function is_selected($a,$b, $return='selected="selected"'): string
    {
        if($a == $b) {
            return $return;
        }

        return '';
    }
}

/**
 * Check a element is checked
 */
if(!function_exists('is_checked'))
{
    function is_checked($a,$b, $return='checked="checked"'): string
    {
        if($a == $b) {
            return $return;
        }

        return '';
    }
}

/**
 * Return a unit of measure
 */
if(!function_exists('uom'))
{
    function uom($id = null)
    {
        if($id && $uom = \App\Models\UnitOfMeasure::find($id)) {
            return $uom;
        }

        return (object) [
            'id' => null,
            'title' => 'Unit',
            'code'  => 'Unit'
        ];
    }
}

/**
 * Return a units of measure
 */
if(!function_exists('uoms'))
{
    function uoms()
    {
        return \App\Models\UnitOfMeasure::all();
    }
}

/**
 * Vat ids
 */
if(!function_exists('vat_types'))
{
    function vat_types()
    {
        return \App\Models\VatType::all();
    }
}

if(!function_exists('vat_type'))
{
    function vat_type($id)
    {
        return \App\Models\VatType::find($id);
    }
}

/**
 * Product Types
 */
if(!function_exists('product_types'))
{
    function product_types()
    {
        return \App\Models\ProductType::all();
    }
}

/**
 * Get a product
 */
if(!function_exists('get_product'))
{
    function get_product($product_id)
    {
        return \App\Models\Product::find($product_id);
    }
}

if(!function_exists('products'))
{
    function products( $supplier_id = false )
    {
        if($supplier_id) {
            return \App\Models\Supplier::with('products.unitOfMeasure')->find($supplier_id)->products;
        }

        return \App\Models\Product::with(['unitOfMeasure'])->active()->get();
    }
}

if(!function_exists('get_boxes'))
{
    function get_boxes()
    {
        return \App\Models\Product::active()->isBox()->get();
    }
}

if(!function_exists('get_pallets'))
{
    function get_pallets()
    {
        return \App\Models\Product::active()->isPallet()->get();
    }
}

/**
 * Lead Times
 */
if(!function_exists('lead_times'))
{
    function lead_times()
    {
        return \App\Models\LeadTime::all();
    }
}

/**
 * Countries
 */
if(!function_exists('countries'))
{
    function countries()
    {
        return \App\Models\Country::all();
    }
}



/**
 * Countries
 */
if(!function_exists('country_code'))
{
    function country_code($country): string
    {
        $code = \App\Models\Country::where('title',$country)->first();

        if($code) {
            return $code->code;
        }

        return '';
    }
}

/**
 * Countries
 */
if(!function_exists('categories'))
{
    function categories()
    {
        return \App\Models\Category::with(['children'])->active()->get();
    }
}

/**
 * Get payment terms
 */
if(!function_exists('payment_terms'))
{
    function payment_terms()
    {
        return \App\Models\PaymentTerm::all();
    }
}

if(!function_exists('payment_methods'))
{
    function payment_methods()
    {
        return \App\Models\PaymentMethod::all();
    }
}

/**
 * Order types
 */
if(!function_exists('order_types'))
{
    function order_types()
    {
        return \App\Models\OrderType::all();
    }
}

/**
 * Sales order statuses
 */
if(!function_exists('sales_order_statuses'))
{
    function sales_order_statuses()
    {
        return \App\Models\SalesOrderStatus::all();
    }
}

/**
 * Sales order statuses
 */
if(!function_exists('purchase_order_statuses'))
{
    function purchase_order_statuses()
    {
        return \App\Models\PurchaseOrderStatus::all();
    }
}

/**
 * Couriers
 */
if(!function_exists('couriers'))
{
    function couriers()
    {
        return \App\Models\Courier::all();
    }
}

if(!function_exists('stock_level'))
{
    function stock_level($id): stdClass
    {
        return \App\Models\Stock::stockLevel($id);
    }
}

if(!function_exists('training_spaces'))
{
    function training_spaces($training_id = false) : stdClass
    {
        return \App\Models\TrainingDate::spaces($training_id);
    }
}

/**
 * Get the customer
 */
if(!function_exists('get_customer'))
{
    function get_customer($id)
    {
        return \App\Models\Customer::find($id);
    }
}

/**
 * Get the customer
 */
if(!function_exists('customers'))
{
    function customers($only_active = false)
    {
        if($only_active) {
            return  \App\Models\Customer::active()->get();
        }

        return \App\Models\Customer::all();
    }
}

if(!function_exists('users'))
{
    function users()
    {
        return \App\Models\User::all();
    }
}


if(!function_exists('get_setting'))
{
    function get_setting(String $key)
    {
        if($setting = \App\Models\Setting::find($key))
        {
            return $setting->value;
        }

        return false;
    }
}

/**
 * Get all the possible user roles
 */
if(!function_exists('roles'))
{
    function roles()
    {
        return \App\Models\Role::all();
    }
}

/**
 * Get all the possible user permissions
 */
if(!function_exists('permissions'))
{
    function permissions()
    {
        return \App\Models\Permission::all();
    }
}

/**
 * Locate the IP address
 */
if(!function_exists('ip_locate'))
{
    function ip_locate($ip = false)
    {
        if(!$ip) {
            $ip = request()->ip();
        }

        // local host
        if($ip == '::1') {
            $ip = '92.24.69.38'; // just used ip assigned when i was in the office
        }

        try {
            $url = env('IPSTACK_URL','http://api.ipstack.com').'/'.$ip;

            $response = Http::get($url, [
                'access_key' => env('IPSTACK_KEY')
            ]);

            if($response->successful()) {
                return $response->json();
            }
        } catch (Throwable $e) {
            report($e);
        }

        return false;
    }
}

if(!function_exists('get_attribute_by_value_and_product'))
{
    function get_attribute_by_value_and_product($value_id, $product_id)
    {
        return \App\Models\Product::with(['attributes' => function($q) use ($value_id) {
            $q->where('value_id', $value_id);
        }])->find($product_id);
    }
}

/**
 * Get the excluded attributes
 */
if(!function_exists('get_excluded_attributes'))
{
    function get_excluded_attributes($attribute_values, $product_id)
    {
        $excludes = [];
        if($attribute_values)
        {
            if(is_string($attribute_values)) {
                $attribute_values = explode(',', $attribute_values);
            }

            foreach ($attribute_values as $value)
            {
                $tmp = get_attribute_by_value_and_product($value, $product_id);
                if($tmp) {
                    $excludes[$value] = isset($tmp->attributes[0]->pivot->attribute_title) ? $tmp->attributes[0]->pivot->attribute_title : $tmp->title;
                }
            }
        }

        return $excludes;
    }
}

if(!function_exists('format_date'))
{
    function format_date($date, $format = 'D dS M y')
    {
        $dt = new \DateTime($date);
        return $dt->format($format);
    }
}

if(!function_exists('format_date_time'))
{
    function format_date_time($date, $format = 'D dS M y - H:ia')
    {
        $dt = new \DateTime($date);
        return $dt->format($format);
    }
}

if(!function_exists('long_date'))
{
    function long_date($date)
    {
        return format_date($date, 'l dS F Y');
    }
}

if(!function_exists('long_date_time'))
{
    function long_date_time($date)
    {
        return format_date($date, 'l dS F Y - H:ia');
    }
}

/**
 * Relative time
 */
if(!function_exists('relative_time'))
{
    function relative_time($date)
    {
        if($date instanceof \Illuminate\Support\Carbon)
        {
            $date = $date->toDateTimeString();
        }

        $date = \Illuminate\Support\Carbon::createFromDate($date);
        $diff = $date->diff(\Illuminate\Support\Carbon::now());


        $str = '';
        if($diff->y)
        {
            $str .= $diff->y.' years, ';
        }
        if($diff->m)
        {
            $str .= $diff->m.' months, ';
        }
        if($diff->d)
        {
            $str .= $diff->d.' days ';
        }

        if($str == '') {

            if($diff->h && $diff->i)
            {
                if($diff->h)
                {
                    $str = $diff->h.' hours, '.$diff->i.' minutes ';
                }
            }
            elseif(!$diff->h && $diff->i)
            {
                if($diff->i > 5)
                {
                    $str = $diff->i.' minutes ';
                }
                else
                {
                    $str = 'Just now';
                }
            }
            else
            {
                $str = 'Just now';
            }
        }

        return $str;
    }
}

/**
 * Get hours between two times
 * @param $start_time
 * @param $end_time
 * @return float|int
 */
function get_hours($start_time, $end_time)
{
    $start_time = explode(':',$start_time);
    $end_time = explode(':',$end_time);
    $start_hours = ($start_time[0]*60) + $start_time[1];
    $end_hours = ($end_time[0]*60) + $end_time[1];

    if( $start_hours < $end_hours ) {
        $hours = ($end_hours - $start_hours) / 60;
    } else {
        $hours = ((1440 - $start_hours) + $end_hours) / 60; // 1440 (24 hours)
    }

    return $hours;
}

/**
 * Vapor will only use s3
 * but for local below will help
 * Using Spatie media library now
 */
if(!function_exists('get_file'))
{
    function get_file($filepath)
    {
        $file = new stdClass();
        if(\Illuminate\Support\Facades\Storage::exists($filepath))
        {
            $file->url = \Illuminate\Support\Facades\Storage::url($filepath);
            $file->visibility = \Illuminate\Support\Facades\Storage::getVisibility($filepath);
            $file->size = \Illuminate\Support\Facades\Storage::size($filepath);
        }

        else
        {
            $file->url = asset('storage/'.$filepath);
            $file->visibility = 'public';
            $file->size = @filesize('storage/'.$filepath);
        }

        return $file;
    }
}

/**
 * Check a str to see if its sha1
 */
if(!function_exists('is_sha1'))
{
    function is_sha1($string)
    {
        return (bool) preg_match('/^[0-9a-f]{40}$/i', $string);
    }
}

