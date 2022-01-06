<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    //
    public function index()
    {
        $detail = Franchise::with(['addresses'])->findOrFail(Auth::user()->franchise_id);

        return view('admin.settings.settings', compact(['detail']));
    }

    public function update(Request $request ,$id)
    {
        $request->validate([
            'title'             => 'required',
            'contact_number'    => 'required',
            'contact_name'      => 'required',
            'email'             => 'required',
            'company_number'    => 'required'
        ]);

        $data = $request->except(['_method','_token']);

        if(Franchise::find($id)->update($data))
        {
            return back()->with('success', 'Details updated');
        }

        return back()->with('error','Failed to update, please try again');
    }

    /**
     * Store a new address resource
     * @param Request $request
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
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

        $franchise = Franchise::with(['addresses'])->findOrFail(Auth::user()->franchise_id);

        if($franchise->addresses()->create($validated))
        {
            return back()->with('success','Address saved');
        }

        return back()->with('error','Failed to save the address, please try again.');
    }

    public function destroyAddress(Request $request)
    {
        $id = $request->input('address_id');

        if(Address::destroy($id))
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
}
