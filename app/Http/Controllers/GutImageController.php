<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutImage;
use Illuminate\Support\Facades\Storage;

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

            foreach($gut_images as $gut_image) {
                $image_info = [
                    'id' => $gut_image->id,
                    'name' => $gut_image->name,
                    'image_path' => self::BASE_IMAGE_PATH . $gut_image->file_name
                ];
                
                array_push($image_infos, $image_info);
            }

            return response()->json($image_infos, 200);
        } catch(\Throwable $e) {
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
