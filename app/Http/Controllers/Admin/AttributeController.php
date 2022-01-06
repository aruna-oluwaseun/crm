<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    /**
     * Get the product attributes
     */
    public function index()
    {
        $attributes = Attribute::with(['products'])->paginate(30);

        set_page_title('Product attributes');
        return view('admin.attributes.attribute-list',compact(['attributes']));
    }

    /**
     * Store the product attributes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required',
            'slug'   => 'nullable',
            'code'   => 'nullable',
        ]);

        DB::beginTransaction();
        try {

            if(! $attribute = Attribute::create($validated))
            {
                throw new \Exception('Error adding the attribute');
            }

            DB::commit();

            return back()->with('success','Attribute added');

        } catch(\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();

            //dd($exception);
        }

        return back()->with('error','Failed to add attribute');
    }

    /**
     * View the attribute
     * @param $id
     */
    public function show($id)
    {
        $detail = Attribute::with('products')->findOrFail($id);

        set_page_title('Attribute '.$detail->title);
        return view('admin.attributes.attribute', compact(['detail']));
    }


    /**
     * Update the attribute
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {

    }


    /**
     * Destroy the attribute
     * @param $id
     */
    public function destroy($id)
    {
        if(Attribute::destroy($id))
        {
            if(\request()->ajax())
            {
                return response()->json(['success' => true]);
            }

            return redirect('attributes')->with('success','Attribute deleted');
        }

        if(\request()->ajax())
        {
            return response()->json(['success' => false]);
        }

        return back()->with('error','Failed to delete attribute');
    }

    /**
     * Link a product to attribute
     */
    public function linkProduct(Request $request)
    {
        $request->validate([
            'attribute_id'              => 'required',
            'product_id'                => 'required',
            'value_id'                  => 'required',
            'attribute_title'           => 'nullable',
            'exclude_value_attributes'  => 'nullable',
            'net_adjustment'            => 'nullable'
        ]);

        $validated = $request->except(['_token','attribute_id','product_id']);

        if($request->exists('exclude_value_attributes'))
        {
            $validated['exclude_value_attributes'] = implode(',',$request->input('exclude_value_attributes'));
        }

        DB::beginTransaction();
        try {

            $product = Product::with(['attributes'])->find($request->input('product_id'));

            if($product)
            {
                $validated['created_by'] = current_user_id();
                $validated['updated_by'] = current_user_id();

                $product->attributes()->attach($request->input('attribute_id'), $validated);

                DB::commit();

                return back()->withFragment('tab-attributes')->with('success','Attribute added');
            }

            return back()->withFragment('tab-attributes')->with('error','We could not find the product you are trying to attach the attribute to, please try again.');

        } catch(\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();

            //dd($exception);
        }

        return back()->withFragment('tab-attributes')->with('error','Failed to add attribute to product');
    }

    /**
     * Unlink a product
     * @param $product_id
     * @param $attribute_id
     */
    public function unlinkProduct($product_id, $attribute_id)
    {
        try {
            $product = Product::with(['attributes'])->find($product_id);

            if(!$product_id) {
                return back()->with('error','We could not find the associated attribute for this product');
            }

            if(!$product->attributes()->wherePivot('id',$attribute_id)->detach())
            {
                return back()->withFragment('tab-attributes')->with('error','Failed to remove the associated attribute');
            }

            return back()->withFragment('tab-attributes')->with('success','Attribute removed from product');

        } catch (\Throwable $exception) {
            custom_reporter($exception);
        }

        return back()->with('error','Something went wrong, failed to remove the associated attribute');
    }
}
