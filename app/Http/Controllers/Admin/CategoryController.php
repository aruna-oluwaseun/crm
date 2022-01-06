<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(30);
        $parent_cats=Category::where('is_parent',1)->orderBy('title','ASC')->get();
        set_page_title('Manage your categories');
        return view('admin.categories.categories-list', compact(['categories','parent_cats']));
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

    public function getChild(Request $request){
        // return $request->all();
        $category=Category::findOrFail($request->id);
        $child_cat=Category::getChildByParentID($request->id);
        // return $child_cat;
        if(count($child_cat)<=0){
            return response()->json(['status'=>false,'msg'=>'','data'=>null]);
        }
        else{
            return response()->json(['status'=>true,'msg'=>'','data'=>$child_cat]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'is_parent'=>'sometimes|in:1',
            'parent_id'=>'nullable|exists:categories,id',
        ]);
        $data= $request->all();
        $slug=Str::slug($request->title);
        $count=Category::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $data['is_parent']=$request->input('is_parent',0);
        // return $data;   
        $category=Category::create($data);
        if($id = $category->id){
            return redirect('categories/'.$id)->with('success','New category has been added.');
        }
        else{
            request()->session()->flash('error','Error occurred, Please try again!');
        }
       


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detail = Category::with(['products'])->findOrFail($id);
        $products = Product::isAvailableOnline()->active()->get();

        set_page_title('Category ' . $detail->title);
        return view('admin.categories.category',compact(['detail','products']));
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
        $request->validate([
            'title'     => 'required'
        ]);

        $data = $request->except(['_token']);

        if(Category::find($id)->update($data))
        {
            return redirect('categories/'.$id)->with('success','Category has been updated.');
        }

        return back()->withInput()->with('error', 'Error adding category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Category::destroy($id))
        {
            if(\request()->ajax())
            {
                return response()->json(['success' => true]);
            }

            return redirect('categories')->with('success','Category deleted');
        }

        if(\request()->ajax())
        {
            return response()->json(['success' => false]);
        }

        return back()->with('error','Failed to delete category');
    }

    // none resource methods

    public function categoryStatus(Request $request)
    {
        $item = Category::find($request->input('id'));

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

    /**
     * Link categories to other categories or products
     */
    public function linkProduct(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'product_id' => 'required'
        ]);

        $category = Category::with(['products'])->find($request->input('category_id'));

        $category->products()->detach($request->input('product_id'));
        $category->products()->attach($request->input('product_id'));

        if($request->ajax())
        {
            return response()->json(['success' => true]);
        }

        return back()->with('success','Product linked.');

    }

    /**
     * Link categories to other categories or products
     */
    public function linkCategory(Request $request)
    {
        $request->validate([
            'new_link_id' => 'required'
        ]);

        $link_id = $request->input('new_link_id');
        $result = false;

        // Link a subcategory
        if($request->exists('category_id'))
        {
            $result = Category::find($link_id)->update(['parent_id' => $request->input('category_id')]);
        }

        // Link a product
        if($request->exists('product_id'))
        {
            $category = Category::with(['products'])->find($link_id);

            // detach if it already exists
            $category->products()->detach($request->input('product_id'));

            $category->products()->attach($request->input('product_id'));

            $result = true;
        }

        if($result) {
            if($request->ajax())
            {
                return response()->json(['success' => true]);
            }

            return back()->with('success','Category linked.');
        }

        if($request->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Error linking category, please re-fresh your page and try again.']);
        }

        return back()->with('error','Error linking category, please re-fresh your page and try again.');

    }

    /**
     * Unlink a category
     */
    public function unlinkCategory($category_id)
    {
        if(!$category_id ) {
            return back()->with('error','No category was specified.');
        }

        if(Category::find($category_id)->update(['parent_id' => null]))
        {
            return back()->with('success','Child category unlinked');
        }

        return back()->with('error','An error occurred removing child category link');
    }
}
