<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\ProductChild;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$products = Product::orderBy('title')->paginate(100);
        $filtered = Product::query();
        $filtered->select('products.*')
            ->join('category_product','category_product.product_id','=','products.id','left');

        $filters = \request()->except(['page','rows','sort']);
        foreach($filters as $field => $value)
        {
            if($value != 'all') {
                if($field == 'category') {
                    $filtered->where('category_product.category_id',$value);
                }
                else {
                    $filtered->where($field,$value);
                }
            }
        }

        $per_page = 100;
        if(\request()->exists('rows')) {
            $per_page = \request()->input('rows');
        }

        $page_sort = 'desc';
        if(\request()->exists('sort')) {
            $page_sort = \request()->input('sort');
        }

        $filtered->orderBy('id',$page_sort);
        $products = $filtered->paginate($per_page);

        set_page_title('Manage your products');
        return view('admin.products.product-list', compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (get_user()->cannot('create-products')) {
            abort(403);
        }

        // duplicate id
        $id = false;
        if ($request->exists('id')) {
            $id = $request->get('id');
        }

        $categories=Category::where('is_parent',1)->get();
        // return $category;
        

        $detail = $id ? Product::find($id) : null;

        set_page_title('Create product');
        return view('admin.products.product-create', compact(['categories','detail']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            'unit_of_measure_id' => 'required',
            'weight' => 'exclude_if:unit_of_measure_id,unit|required'
        ]);

        $data = $request->except(['_token', '_method','is_packaging','files']);
        $data['franchise_id'] = current_user_franchise_id();

        // remove dropdowns that are value 0
        if (isset($data['product_type_id']) && $data['product_type_id'] == 0) {
            unset($data['product_type_id']);
        }

        if (isset($data['lead_time_id']) && $data['lead_time_id'] == 0) {
            unset($data['lead_time_id']);
        }

        if($data['unit_of_measure_id'] == 'unit') {
            $data['unit_of_measure_id'] = null;
        }

        /** @TODO caluclate vat / gross rather than using jQuery formatted result */
        //dd($request);
        if ($id = Product::create($data)->id) {

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->to('products/' . $id)->with('success', 'Product created.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Error updating, please re-fresh your page and try again.']);
        }

        return back()->with('error', 'Error adding product, please try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (get_user()->cannot('create-products')) {
            abort(403);
        }

        $detail = Product::with(['children', 'suppliers', 'media', 'attributes','stock.movements'])->findOrFail($id);
        $products = Product::active()->get();
        $suppliers = Supplier::active()->get();

        // update vat cost if a vat ID is selected
        if (!$detail->vat_cost && $detail->vat_type_id && $detail->net_cost) {
            if ($vat = $detail->vatType) {
                $detail->vat_cost = ($vat->value / 100) * $detail->net_cost;
                $detail->gross_cost = $detail->net_cost + $detail->vat_cost;
                $detail->save();
            }
        }

        set_page_title($detail->title);
        return view('admin.products.product', compact(['detail', 'products', 'suppliers']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     * @TODO add conditional validation to form
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            'unit_of_measure_id' => 'required',
            'weight' => 'exclude_if:unit_of_measure_id,unit|required'
        ]);

        $data = $request->except(['_token', '_method','is_packaging']);

        // remove dropdowns that are value 0
        if (isset($data['product_type_id']) && $data['product_type_id'] == 0) {
            unset($data['product_type_id']);
        }

        if (isset($data['lead_time_id']) && $data['lead_time_id'] == 0) {
            unset($data['lead_time_id']);
        }

        if($data['unit_of_measure_id'] == 'unit') {
            $data['unit_of_measure_id'] = null;
        }

        /** @TODO caluclate vat / gross rather than using jQuery formatted result */

        if (Product::find($id)->update($data)) {

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Product updated.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Error updating, please re-fresh your page and try again.']);
        }

        return back()->with('error', 'Error updating, please re-fresh your page and try again.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Product::destroy($id))
        {
            if(\request()->ajax())
            {
                return response()->json(['success' => true]);
            }

            return redirect('products')->with('success','Product deleted');
        }

        if(\request()->ajax())
        {
            return response()->json(['success' => false]);
        }

        return back()->with('error','Failed to delete product');
    }

    // ------ NONE RESOURCE METHODS

    /**
     * Update user status
     */
    public function productStatus(Request $request)
    {
        $item = Product::find($request->input('id'));

        if ($item && $item->count()) {
            $item->status = $request->input('status');

            if ($item->save()) {
                if ($request->ajax()) {
                    return response()->json(['success' => true]);
                }

                return back()->with('success', 'Resource updated.');
            }

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update. Please try again.']);
            }

            return back()->with('error', 'Failed to update. Please try again.');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'We could not locate the resource you are after, please re-fresh your page and try again.']);
        }

        return back()->with('error', 'We could not locate the resource you are after, please re-fresh your page and try again.');

    }


    /**
     * Get a product via ajax or method call
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse
     */
    public function getProduct(Request $request)
    {
        $product = Product::active()->find($request->input('id'));

        if ($product) {

            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $product]);
            }

            return $product;
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'We could not locate the resource you are after, please re-fresh your page and try again.']);
        }

        return false;
    }

    /**
     * Add build item to a production order
     * @param Request $request
     */
    public function storeProductChild(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required',
            'unit_of_measure_id' => 'required',
            'weight' => 'nullable'
        ]);

        $weight_kg = null;

        if ($validated['unit_of_measure_id'] == 'unit') {
            $validated['unit_of_measure_id'] = null;
            $validated['weight'] = null;
        } // Work out weight in Kg
        else {
            $weight_kg = UnitOfMeasure::convert(intval($validated['unit_of_measure_id']), 'Kg', $validated['weight']);

            if ($weight_kg->success) {
                $weight_kg = $weight_kg->value;
            } else {
                // Log conversion error
                Log::error($weight_kg->message ?: 'There was an error converting ' . $validated['unit_of_measure_id'] . ' to Kg');
            }

        }

        $validated['weight_kg'] = $weight_kg;

        $product = Product::active()->find($validated['product_id']);
        if ($product) {
            $validated['product_title'] = $product->title;
        }

        if ($id = ProductChild::create($validated)->id) {
            $validated['id'] = $id;

            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $validated]);
            }

            return back()->with('success', 'Build product added');
        }

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to add build product. Please try again.']);
        }

        return back()->with('error', 'Failed to add build product. Please try again.');
    }


    /**
     * Add build item to a production order
     * @param $id Integer
     */
    public function destroyProductChild($id)
    {
        if ($res = ProductChild::destroy($id)) {
            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Build product removed');
        }

        if (request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to remove build product. Please try again.']);
        }

        return back()->with('error', 'Failed to remove build product. Please try again.');
    }

    /**
     * Unlink a category
     */
    public function unlinkCategory($product_id,$category_id)
    {
        if(!$product_id || !$category_id) {
            return back()->with('error','No product or category was specified.');
        }

        $product = Product::with(['categories'])->find($product_id);

        $product->categories()->detach($category_id);

        return back()->with('success','Category unlinked from product');
    }

    /**
     * Add stock
     * @param Request $request
     * @param $id
     */
    public function addStock(Request $request, $id)
    {
        $data = $request->validate([
            'stock'     => 'numeric|required',
            'unit_level_stock' => 'nullable',
            'unit_of_measure_id' => 'nullable',
        ]);

        if($stock = Stock::addStock($id, $data))
        {
            return back()->with('success','Stock has been updated');
        }

        return back()->with('error','Failed to update stock, please try again');
    }

}
