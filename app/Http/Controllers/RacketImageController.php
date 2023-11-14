<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
}
