<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutImage;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GutImage\GutImageStoreRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class GutImageController extends Controller
{
    // private const BASE_IMAGE_PATH = Storage::url('images/');
    private const BASE_IMAGE_PATH = 'storage/images/guts/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $gut_images = GutImage::all();

            $image_infos = [];

            //各imageのpathを整形して返却
            foreach ($gut_images as $image) {
                $image_info = [
                    'title' => $image->title,
                    'file_path' => Storage::url($image->file_path)
                ];

                array_push($image_infos, $image_info);
            }

            return response()->json($image_infos, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    public function store(GutImageStoreRequest $request)
    {
        $validated_request = $request->validated();

        try {
            $file = $request->file('file');

            $filename = now()->format('YmdHis') . $validated_request['title'] . "." . $request->file('file')->extension();

            $path = $file->storeAs('images/guts', $filename, 'public');

            $gut_image = GutImage::create([
                'file_path' => $path,
                'title' => $validated_request['title']
            ]);

            if (isset($gut_image)) {
                return response()->json([
                    'file_path' => Storage::url($gut_image['file_path']),
                    'title' => $gut_image['title']
                ], 200);
            }
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
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
        try {
            $gut_image = GutImage::findOrFail($id);

            return response()->json([
                'file_path' => Storage::url($gut_image['file_path']),
                'title' => $gut_image['title']
            ]);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            \Log::error($e);

            throw $e;
        }
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
        try {
            $image = GutImage::findOrFail($id);

            Storage::disk('public')->delete($image->file_path);

            $image->delete();

            return response()->json("{$image['title']}の画像を削除しました", 200);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }
}
