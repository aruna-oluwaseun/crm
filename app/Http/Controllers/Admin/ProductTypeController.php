<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = ProductType::with(['products'])->paginate(30);

        set_page_title('Product types');
        return view('admin.products.types-list',compact(['types']));
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
            'title' => 'required',
            'code'  => 'nullable'
        ]);

        if($attribute = ProductType::create($validated))
        {
            if($request->ajax())
            {
                return response()->json([
                    'success' => true
                ]);
            }

            return back()->with('success','Product attribute created');
        }

        if($request->ajax())
        {
            return response()->json([
                'success'=> false
            ]);
        }

        return back()->with('error','Failed to create product attribute, please try again');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = ProductType::findOrFail($id);

        set_page_title('View product type');
        return view('admin.products.type', compact(['detail']));
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
        //
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


    // none resource methods

    public function categoryStatus(Request $request)
    {
        $item = ProductType::find($request->input('id'));

        if( $item && $item->count() )
        {
            $item->status = $request->input('status');

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
}
