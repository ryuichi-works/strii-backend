<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutImage;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GutImage\GutImageStoreRequest;

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

            return response()->json($gut_images, 200);
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

            $gutImage = GutImage::create([
                'file_path' => Storage::url($path),
                'title' => $validated_request['title']
            ]);

            if(isset($gutImage)) {
                return response()->json($gutImage, 200);
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
