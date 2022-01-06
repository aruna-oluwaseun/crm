<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductionOrderProcessed;
use App\Http\Controllers\Controller;
use App\Models\BuildProduct;
use App\Models\Product;
use App\Models\ProductionOrder;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Int_;
use Ramsey\Uuid\Type\Integer;

class ProductionOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // eager load
        $production_orders = ProductionOrder::with(['product'])->orderBy('id','DESC')->paginate(30);
        $products = Product::active()->get();

        set_page_title('Manage your production orders');
        return view('admin.production-orders.production-order-list', compact(['production_orders','products']));
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
     * @TODO Add Transactions
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required',
            'qty'           => 'required',
            'due_date'      => 'required',
            'assigned_to_id'=> 'nullable',
            'batch'         => 'nullable',
            'location'      => 'nullable',
            'notes'         => 'nullable',
            'is_urgent'     => 'nullable'
        ]);

        $product = Product::find($validated['product_id']);

        if(!$product) {
            if($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This product no longer exists.']);
            }

            return back()->with('error','The product no longer exists.');
        }

        if( $id = ProductionOrder::create($validated)->id )
        {
            if( $product->children->count() )
            {
                foreach ($product->children as $item )
                {
                    // For product units
                    $build_qty = $validated['qty'] * $item->qty;

                    // For products with weight values
                    if( $item->weight && $item->qty ) {
                        $build_qty = $validated['qty'] * ($item->weight * $item->qty );
                    }

                    $build_product = [
                        'production_order_id'   => $id,
                        'product_id'            => $item->product_id,
                        'product_title'         => $item->product_title,
                        'qty'                   => $build_qty,
                        'unit_of_measure_id'    => $item->unit_of_measure_id?: null
                    ];

                    if( !BuildProduct::create($build_product) ) {
                        Log::error('Failed to add build product ', $build_product);
                    }
                }
            }

            if($request->ajax()) {
                return response()->json(['success' => true, 'id' => $id]);
            }

            return back()->with('success', 'Production order #'.$id.' created');
        }

        if($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to create production order. Please try again.']);
        }

        return back()->with('error','Failed to create production order. Please try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = ProductionOrder::with(['product','buildProducts'])->find($id);
        if(!$detail) {
            return back()->with('warning','Production order not found.');
        }
        $products = Product::active()->get();

        set_page_title('Production order #' . $detail->id);
        return view('admin.production-orders.production-order', compact(['detail','products']));
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
     * Process the production order
     */
    public function processProductionOrder(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            'production_order.qty' => 'required',
            'build_product' => 'required|array'
        ]);

        DB::beginTransaction();
        try {
            $detail = ProductionOrder::with(['product','buildProducts'])->find($id);

            if(!$detail->update(['qty' => $request->input('production_order.qty'), 'status' => 'completed', 'completed_at' => date('Y-m-d H:i:s')]))
            {
                Throw new \Exception('Failed to update the production order to completed.');
            }

            foreach($request->input('build_product') as $build_item_id => $value)
            {
                if(!$detail->buildProducts->find($build_item_id)->update(['qty_used' => $value['qty_used']]))
                {
                    Throw new \Exception('Failed to update the build items associated with this production order.');
                }
            }

            ProductionOrderProcessed::dispatch($detail->fresh());

            DB::commit();

            return back()->with('success','Production order updated.');

        } catch (\Throwable $exception)
        {
            report($exception);
            DB::rollBack();
        }

        return back()->with('error','There was error processing your production order.');
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
        $validated = $request->validate([
            'product_id'        => 'exclude_if:status,completed|required',
            'qty'               => 'exclude_if:status,completed|required',
            'due_date'          => 'exclude_if:status,completed|required',
            'assigned_to_id'    => 'nullable',
            'assembled_by_id'   => 'nullable',
            'checked_by_id'     => 'nullable',
            'batch'             => 'nullable',
            'location'          => 'nullable',
            'notes'             => 'nullable',
            'is_urgent'         => 'nullable',
            'status'            => 'required'
        ]);

        $production_order = ProductionOrder::find($id);

        if(!$request->exists('is_urgent')) {
            $validated['is_urgent'] = 0;
        }

        if($validated['status'] == 'processing' && !$production_order->processed_at) {
            $validated['processed_at'] = date('Y-m-d H:i:s');
        }

        if($validated['status'] == 'completed' && !$production_order->processed_at) {
            $validated['processed_at'] = date('Y-m-d H:i:s');
        }

        if($production_order->update($validated))
        {
            if($request->ajax()) {
                return response()->json([
                    'success' => true
                ]);
            }

            return back()->with('success', 'Production updated');
        }

        if($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred updating, please re-fresh and try again.'
            ]);
        }

        return back()->with('error', 'Error updating production order');
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


    // None resource methods

    /**
     * Add build item to a production order
     * @param Request $request
     */
    public function storeBuildItem(Request $request)
    {
        $validated = $request->validate([
            'production_order_id'   => 'required|integer',
            'product_id'            => 'required|integer',
            'qty'                   => 'required',
            'unit_of_measure_id'    => 'required'
        ]);

        // unit
        if($validated['unit_of_measure_id'] == 'unit') {
            $validated['unit_of_measure_id'] = null;
        }

        $product = Product::active()->find($request->input('product_id'));
        if( $product ) {
            $validated['product_title'] = $product->title;
        }

        if($id = BuildProduct::create($validated)->id )
        {
            $validated['id'] = $id;

            if($request->ajax()) {
                return response()->json(['success' => true, 'data' => $validated]);
            }

            return back()->with('success', 'Build product added');
        }

        if($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to add build product. Please try again.']);
        }

        return back()->with('error','Failed to add build product. Please try again.');
    }


    /**
     * Add build item to a production order
     * @param $id Integer
     */
    public function destroyBuildItem($id)
    {
        if( BuildProduct::destroy($id) )
        {
            if(request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Build product removed');
        }

        if(request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Failed to remove build product. Please try again.']);
        }

        return back()->with('error','Failed to remove build product. Please try again.');
    }
}
