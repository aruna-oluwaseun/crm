<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\TrainingDate;
use App\Models\TrainingDateStockLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingDateController extends Controller
{
    //

    /**
     * View list of training dates
     */
    public function index()
    {
        $products = Product::isTraining()->active()->get();
        $dates = TrainingDate::active()->orderBy('date_start','DESC')->get();

        set_page_title('Training Dates');
        return view('admin.training-dates.trainingdate-list', compact(['products', 'dates']));
    }

    /**
     * Show training date
     * @param $id
     * @return string
     */
    public function show($id)
    {
        $detail = TrainingDate::with(['attendees.salesOrder.customer','stockLinks.trainingCourse','linkedToTrainingStock.trainingCourse'])->findOrFail($id);
        $products = Product::isTraining()->active()->get();

        $linkable = false;
        if($detail->date_start && $detail->date_end)
        {
            $start = explode(' ', $detail->date_start)[0];
            $end = explode(' ', $detail->date_end)[0];

            $linkable = TrainingDate::upcoming()->where('id', '!=', $id)->whereDate('date_start','>=', $start)->whereDate('date_end', '<=', $end)->get();
        }

        set_page_title($detail->product_title);
        return view('admin.training-dates.trainingdate', compact(['detail','products','linkable']));
    }

    /**
     * Store new training date
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required',
            'date_start' => 'required',
            'date_end'   => 'required',
            'stock'      => 'required'
        ]);

        DB::beginTransaction();
        try {

            $product = Product::find($validated['product_id']);

            if($product) {
                $validated['product_title'] = $product->title;
            }

            if(! $training = TrainingDate::create($validated) )
            {
                Throw new \Exception('Failed to store training to database');
            }

            DB::commit();

            return redirect('training-dates/'.$training->id)->with('success', 'Training date added');

        } catch (\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withInput()->with('error','Failed to add the training dates, please try again.');
    }

    /**
     * Update Training Product
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'nullable',
            'date_start' => 'nullable',
            'date_end'   => 'nullable',
            'stock'      => 'required'
        ]);

        DB::beginTransaction();
        try {

            $training = TrainingDate::find($id);

            if($request->exists('product_id'))
            {
                if($validated['product_id'] != $training->product_id)
                {
                    $product = Product::find($validated['product_id']);

                    if($product) {
                        $validated['product_title'] = $product->title;
                    }
                }
            }

            if(! $training->update($validated) )
            {
                Throw new \Exception('Failed to save training changes');
            }

            DB::commit();

            return back()->with('success', 'Training updated');

        } catch (\Throwable $exception) {
            custom_reporter($exception);
            DB::rollBack();
        }

        return back()->withInput()->with('error','Failed to update training, please try again.');
    }


    /**
     * Soft delete training
     * @param Request $request
     * @param $id
     */
    public function destroy(Request $request, $id)
    {

    }


    /**
     * Add stock link to training so it can be deducted
     * @param Request $request
     */
    public function storeStockLink(Request $request)
    {
        $validated = $request->validate([
            'training_date_id'  => 'required',
            'updates_training_id_stock'  => 'required'
        ]);

        if( TrainingDateStockLink::create($validated) )
        {
            return back()->with('success', 'Training date stock link added.');
        }

        return back()->with('error','Failed to add training date stock link please try again.');
    }


    /**
     * Destroy stock link
     * @param Request $request
     */
    public function destroyStockLink(Request $request, $id)
    {

        if( TrainingDateStockLink::destroy($id) )
        {
            return back()->with('success', 'Training date stock link removed.');
        }

        return back()->with('error','Failed to remove training date stock link please try again.');
    }


    /**
     * Load training dates
     * @return false|\Illuminate\Http\JsonResponse
     */
    public function getTrainingDates(Request $request)
    {
        $dates = TrainingDate::with(['attendees'])->upcoming()
            ->where('product_id',$request->input('id'))->get();

        if($dates->count())
        {
            foreach ($dates as& $item) {
                $item->formated_date = format_date($item->date_start);
            }

            if(\request()->ajax())
            {
                return response()->json([
                    'success' => true,
                    'data' => $dates
                ],200);
            }
        }

        if(\request()->ajax())
        {
            return response()->json([
                'success' => false,
                'message' => 'No training dates for this course'
            ]);
        }

        return false;
    }
}
