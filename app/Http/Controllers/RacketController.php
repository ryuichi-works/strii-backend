<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Racket;
use App\Http\Requests\Racket\RacketStoreRequest;

class RacketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rackets = Racket::with(['maker', 'racketImages'])->get();

            return response()->json($rackets, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RacketStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $racket = Racket::create([
                'name_ja' => $validated['name_ja'],
                'name_en' => $validated['name_en'],
                'maker_id' => $validated['maker_id'],
                'image_id' => isset($validated['image_id']) ? $validated['image_id'] : null,
                'need_posting_image' => $validated['need_posting_image'],
            ]);

            if ($racket) {
                return response()->json('ラケットを登録しました', 200);
            }
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
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
            $racket = Racket::with(['maker', 'racketImages'])->findOrFail($id);

            return response()->json($racket, 200);
        } catch (\ModelNotFoundException $e) {
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
