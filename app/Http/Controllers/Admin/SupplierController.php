<?php

namespace App\Http\Controllers\Admin;

use App\Models\Address;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::with(['purchases.items'])->active()->paginate(50);

        set_page_title('Suppliers');
        return view('admin.suppliers.supplier-list', compact(['suppliers']));
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required',
            'code'              => 'nullable',
            'contact_name'      => 'required',
            'contact_number'    => 'required',
            'email'             => 'required',
        ]);

        try {

            if($supplier = Supplier::create($validated))
            {
                return redirect('suppliers/'.$supplier->id)->with('success','Supplier created');
            }

        } catch (\Throwable $exception) {
            custom_reporter($exception);
        }

        return back()->withInput()->with('error','Failed to add new supplier, please try again');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = Supplier::with('purchases.items','products','billingAddress','headOfficeAddress')->findOrFail($id);
        $products = Product::active()->get();
        //dd($detail->products[0]);

        set_page_title('Supplier '.$detail->title);
        return view('admin.suppliers.supplier',compact(['detail','products']));
    }

    /**
     * Set addresses
     */
    public function addresses(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        DB::beginTransaction();
        try {

            if($request->exists('billing_id'))
            {
                if(! Address::find($request->input('billing_id'))->update($request->input('billing_address_data')) )
                {
                    Throw new \Exception('Failed to update the billing address.');
                }
            }
            else
            {
                if(! $address = Address::create($request->input('billing_address_data')) )
                {
                    Throw new \Exception('Failed to add the billing address.');
                }

                if( ! $supplier->update(['billing_id' => $address->id]) )
                {
                    Throw new \Exception('Failed to add the billing address.');
                }

            }

            if($request->exists('head_office_id'))
            {
                if(! Address::find($request->input('head_office_id'))->update($request->input('head_office_address_data')) )
                {
                    Throw new \Exception('Failed to update the head office address.');
                }
            }
            else
            {
                if(! $address =  Address::create($request->input('head_office_address_data')) )
                {
                    Throw new \Exception('Failed to add the head office address.');
                }

                if( ! $supplier->update(['head_office_id' => $address->id]) )
                {
                    Throw new \Exception('Failed to add the head office address.');
                }

            }

            DB::commit();

            return back()->withFragment('tab-addresses')->with('success','Addresses updated for this supplier');

        } catch (\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withFragment('tab-addresses')->with('error','Addresses failed to update');
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'required',
            'code'              => 'nullable',
            'contact_name'      => 'required',
            'contact_number'    => 'required',
            'email'             => 'required',
        ]);

        if( $supplier->update($validated) )
        {
            return back()->with('success','Supplier updated');
        }

        return  back()->withInput()->with('error', 'Failed to update the supplier');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @TODO UPDATE Now using pivot table
     * Store supplier ref
     * @param Request $request
     */
    public function storeSupplierRef(Request $request)
    {

        $validated = $request->validate([
            'product_id'    => 'required|integer',
            'supplier_id'   => 'required|integer',
            'cost_to_us'    => 'required',
            'code'          => 'required'
        ]);

        if( ProductSupplier::create($validated) )
        {
            return back()->with('success', 'Supplier linked to product');
        }

        return back()->with('error', 'Failed to add the supplier link, please try again.');
    }

    /**
     * @TODO UPDATE Now using pivot table
     * @param $id Integer
     */
    public function destroySupplierRef($id)
    {
        if( $res = ProductSupplier::destroy($id) )
        {
            if(request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Supplier ref removed');
        }

        if(request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to remove supplier ref. Please try again.']);
        }

        return back()->with('error','Failed to remove supplier ref. Please try again.');
    }

    /**
     * Get products for a supplier for purchase orders
     * @param $id
     */
    public function getProducts($id)
    {
        $products = Supplier::with(['products.unitOfMeasure'])->find($id)->products;

        // attach unit of measure title to output
        if($products && $products->count())
        {
            if(\request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $products
                ]);
            }

            return $products;
        }

        if(\request()->ajax()) {
            return response()->json([
                'success' => false
            ]);
        }

        return false;
    }
}
