<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductUpload;
use App\Models\Upload;
use App\Models\Watermark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media;

class UploadController extends Controller
{

    /**
     * Upload file
     * @param Request $request
     * @param null $table
     * @param null $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $table=null, $id=null)
    {
        $model = null;
        $visibility = 'private';
        $collection = 'default';

        $filename = $request->input('filename');
        $filetype = $request->input('filetype');
        $tmp = $request->input('tmp');

        info('The file uploaded = '.$filename.' the type = '.$filetype.' the tmp file '.$tmp);

        if($table)
        {
            switch ($table)
            {
                case 'products' :
                    $model = '\App\Models\Product';
                    $visibility = 'public';
                    $collection = 'images';
                    break;

            }
        }

        if($request->exists('tmp'))
        {
            DB::beginTransaction();
            try {

                if(!Storage::move($tmp, 'tmp/'.$filename))
                {
                    Throw new \Exception('Failed to move from tmp location to permanent');
                }

                if($model && $id)
                {

                    $table = $model::find($id);

                    if($visibility == 'public')
                    {
                        $table->addMediaFromDisk('tmp/'.$filename,'s3')
                            ->addCustomHeaders([
                                'ACL' => 'public-read'
                            ])
                            ->toMediaCollection($collection);
                    }
                    else
                    {
                        $table->addMediaFromDisk('tmp/'.$filename)
                            ->toMediaCollection($collection);
                    }
                }

                DB::commit();

                if($request->ajax())
                {
                    return response()->json([
                        'success' => true
                    ],200);
                }

                return back()->with('success','Your files have been uploaded');

            } catch (\Throwable $e) {
                report($e);
                DB::rollBack();
            }

            if($request->ajax())
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Error uploading files, please try again. If the problem persists please get in touch.'
                ],200);
            }

            return back()->with('danger','Error uploading files, please try again. If the problem persists please get in touch');
        }
    }


    /**
     * Delete
     * @param Int $upload_id (singular int for now)
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($upload_id)
    {
        $file = Media::findOrFail($upload_id);

        if($file)
        {
            DB::beginTransaction();
            try {
                if(!Media::destroy($upload_id)) {
                    Throw new \Exception('Failed to delete file from database');
                }

                DB::commit();

                if(\request()->ajax())
                {
                    return response()->json([
                       'success' => true
                    ],200);
                }

                return back()->with('success','File removed!');

            } catch (\Throwable $e) {
                report($e);
                DB::rollBack();
            }

            if(\request()->ajax())
            {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }

            return back()->with('error',$e->getMessage());
        }
    }

    /**
     * Set the visibility of an file
     */
    public function setVisibility()
    {

    }
}
