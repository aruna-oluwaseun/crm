<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendCustomerNewPassword;
use App\Mail\WelcomeNewStaff;
use App\Mail\WelcomeNewCustomer;
use App\Models\Address;
use App\Models\CustomerAddress;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('first_name')->paginate(30);
        //dump($customers);
        set_page_title('Mange customers');
        return view('admin.customers.customer-list', compact(['customers']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:App\Models\Customer,email',
            'password' => 'required',
            'contact_number' => 'nullable',
            'contact_number2' => 'nullable'
        ]);

        $password = $validated['password'];
        if($password == 'GENERATE_ME_ONE')
        {
            $validated['password'] = Str::random(8);
            $password = $validated['password'];
        }

        if( $customer = Customer::create($validated) )
        {
            if($request->exists('send_password_email')) {
                $customer->temp_password = $password;
            }

            // Send email // Cant queue because temp pass is omitted
            Mail::to($validated['email'])->send(new WelcomeNewCustomer($customer));

            if($request->ajax())
            {
                return response()->json(['success' => true]);
            }

            return redirect('customers/'.$customer->id)->with('success','Customer created.');
        }

        if($request->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Error adding customer, please re-fresh your page and try again.']);
        }

        return back()->with('error','Error adding customer, please try again.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function show($id, $name = '')
    {
        $detail = Customer::with(['addresses','orders'])->find($id);
        if(!$detail) {
            return back()->with('warning','Customer not found.');
        }

        $detail->gross_sales = SalesOrder::active()->where('customer_id', $id)->sum('gross_cost');
        $detail->net_sales = SalesOrder::active()->where('customer_id', $id)->sum('net_cost');

        set_page_title('Customer : '.$detail->getFullNameAttribute());
        return view('admin.customers.customer', compact(['detail']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if( $customer->email == $request->input('email')) {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'status' => 'required',
                'password' => 'exclude_unless:change_password,1|required'
            ]);
        } else {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:App\Models\Customer,email',
                'status' => 'required',
                'password' => 'exclude_unless:change_password,1|required'
            ]);
        }

        $data = $request->except(['_token','_method']);

        // Send password via email
        $send_password = false;
        if(isset($data['change_password']) && isset($data['send_password_email'])) {
            $send_password = true;
        }

        $password = $data['password'];
        if(isset($data['change_password'])) {
            if($data['password'] == 'GENERATE_ME_ONE')
            {
                $data['password'] = Str::random(8);
                $password = $data['password'];
            }
        } else {
            // dont update password
            unset($data['password']);
        }

        unset($data['change_password']);
        unset($data['send_password_email']);
        if( $customer->update($data) )
        {
            if($send_password)
            {
                $customer->temp_password = $password;
                // Send email // Cant queue because temp pass is omitted
                Mail::to($data['email'])->send(new SendCustomerNewPassword($customer));

            }

            if($request->ajax())
            {
                return response()->json(['success' => true]);
            }

            return back()->with('success','Customer updated.');
        }

        if($request->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Error updating, please re-fresh your page and try again.']);
        }

        return back()->withInput()->with('error','Error updating, please re-fresh your page and try again.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $customer = Customer::with(['addresses'])->find($id);

            if($customer->addresses->count())
            {
                if(!$customer->addresses()->delete())
                {
                    Throw new \Exception('Error removing associated addresses');
                }
            }

            if(!$customer->delete())
            {
                Throw new \Exception('Error removing customer');
            }

            DB::commit();

            if(\request()->ajax())
            {
                return response()->json(['success' => true]);
            }

            return redirect('customers')->with('success','Customer deleted');


        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

        if(\request()->ajax())
        {
            return response()->json(['success' => false]);
        }

        return back()->with('error','Failed to delete customer');
    }


    // ------ NONE RESOURCE METHODS

    /**
     * Update user status
     */
    public function customerStatus(Request $request)
    {
        $item = Customer::find($request->input('id'));

        if( $item && $item->count() )
        {
            $item->status = $request->input('status');
            if($request->input('notes')) {
                $item->notes = $request->input('notes') ?: null;
            }

            if( $item->save() )
            {
                if($request->ajax())
                {
                    return response()->json(['success' => true]);
                }

                return back()->with('success','Resource updated.');
            }

            if($request->ajax())
            {
                return response()->json(['success' => false, 'message' => 'Failed to update. Please try again.']);
            }

            return back()->with('error','Failed to update. Please try again.');
        }

        if($request->ajax())
        {
            return response()->json(['success' => false, 'message' => 'We could not locate the resource you are after, please re-fresh your page and try again.']);
        }

        return back()->with('error','We could not locate the resource you are after, please re-fresh your page and try again.');

    }

    /**
     * Set the default billing address
     * @param $customer_id
     * @param $address_id
     * @param string $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function defaultAddress($customer_id, $address_id, $type='billing')
    {
        switch ($type)
        {
            case 'billing' :
                $column = 'default_billing_address';
                break;
            case 'shipping' :
                $column = 'default_shipping_address';
                break;
            default :
                $column = 'default_billing_address';
        }

        $success = false;
        // remove all currently set
        if( Address::find($address_id)->update([$column => 0]) )
        {
            if(Address::find($address_id)->update([$column => 1]))
            {
                $success = true;
            }
        }

        if($success)
        {
            if(Request()->ajax())
            {
                return response()->json(['success' =>true, 'message' => 'Address updated.']);
            }

            return back()->with('success','Address updated');
        }

        if(Request()->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Failed to update. Please re-fresh and try again.']);
        }

        return back()->with('error','Failed to update the addresses.');

    }

    /**/
    public function destroyAddress($id)
    {
        if(Address::find($id)->update(['status' => 'deleted']))
        {
            if(Request()->ajax())
            {
                return response()->json(['success' =>true, 'message' => 'Address removed.']);
            }

            return back()->with('success','Address removed');
        }

        if(Request()->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Failed to remove address. Please re-fresh and try again.']);
        }

        return back()->with('error','Failed to remove address the addresses.');
    }

    /**
     * Persist new address
     * @param Request $request
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required',
            'title'         => 'nullable',
            'line1'         => 'required',
            'line2'         => 'nullable',
            'line3'         => 'nullable',
            'city'          => 'required',
            'county'        => 'nullable',
            'postcode'      => 'required',
            'country'       => 'required',
            'lat'           => 'nullable',
            'lng'           => 'nullable',
        ]);

        $customer_id = $validated['customer_id'];
        unset($validated['customer_id']);

        if( $address = Address::create($validated) )
        {
            Customer::find($customer_id)->addresses()->save($address);

            if( $request->ajax() )
            {
                return response()->json(['success' =>true, 'message' => 'Address added.']);
            }

            return back()->with('success','Address added');
        }

        if(Request()->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Failed to add address. Please re-fresh and try again.']);
        }

        return back()->with('error','Failed to add address the addresses.');
    }

    /**
     * Check if a customer email exists
     * @param Request $request
     */
    public function checkCustomerEmail(Request $request): int
    {
        $email = $request->input('email');

        return DB::table('customers')->where('email',$email)->count();
    }

    /**
     * Get the customer address via ajax
     * @param $customer_id
     */
    public function getAddresses($customer_id)
    {
        $addresses = Customer::with(['addresses' => function($query) {
            $query->active();
        }])->find($customer_id)->addresses;

        if($addresses && $addresses->count())
        {
            if(\request()->ajax()) {
                return response()->json([
                   'success' => true,
                   'data' => $addresses
                ]);
            }

            return $addresses;
        }

        if(\request()->ajax()) {
            return response()->json([
                'success' => false
            ]);
        }

        return false;
    }
}
