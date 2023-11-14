<?php

namespace App\Http\Controllers;

use App\Http\Requests\RacketImage\RacketImageStoreRequest;
use App\Http\Requests\RacketImage\RacketImageUpdateRequest;
use App\Models\RacketImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RacketImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $racket_images = RacketImage::all();

            $image_infos = [];

            //各imageのpathを整形して返却
            foreach ($racket_images as $image) {
                $image_info = [
                    'id' => $image->id,
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
    public function store(RacketImageStoreRequest $request)
    {
        $validated_request = $request->validated();

        try {
            $file = $request->file('file');

            $filename = now()->format('YmdHis') . $validated_request['title'] . "." . $request->file('file')->extension();

            $path = $file->storeAs('images/rackets', $filename, 'public');

            $racket_image = RacketImage::create([
                'file_path' => $path,
                'title' => $validated_request['title']
            ]);

            if (isset($racket_image)) {
                return response()->json([
                    'file_path' => Storage::url($racket_image['file_path']),
                    'title' => $racket_image['title']
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
            $racket_image = RacketImage::findOrFail($id);

            return response()->json([
                'id' => $racket_image['id'],
                'file_path' => Storage::url($racket_image['file_path']),
                'title' => $racket_image['title']
            ]);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
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
    public function update(RacketImageUpdateRequest $request, $id)
    {
        $validated_request = $request->validated();

        try {
            $image = RacketImage::findOrFail($id);

            //新しいファイルがあれば新たにstorageに登録
            if($request->file('file')) {
                $file = $request->file('file');
                $filename = now()->format('YmdHis') . $validated_request['title'] . "." . $request->file('file')->extension();
                $path = $file->storeAs('images/guts', $filename, 'public');
    
                //以前のイメージファイルをstorageフォルダから削除
                Storage::disk('public')->delete($image->file_path);
                
                $image->file_path = $path;
            }

            $image->title = $validated_request['title'];
            $image->save();

            return response()->json([
                'id' => $image['id'],
                'file_path' => Storage::url($image['file_path']),
                'title' => $image['title']
            ], 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
        }
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
            $image = RacketImage::findOrFail($id);

            Storage::disk('public')->delete($image->file_path);

            $image->delete();

            return response()->json("{$image['title']}の画像を削除しました", 200);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }
}
