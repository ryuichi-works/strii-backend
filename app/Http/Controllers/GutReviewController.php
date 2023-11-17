<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutReview;
use App\Http\Requests\GutReview\GutReviewStoreRequest;

class GutReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        try {
            $gut_reviews = GutReview::with([
                'myEquipment' => [
                    'user:id,name',
                    'mainGut:id,name_ja,name_en,need_posting_image,maker_id,image_id' => [
                        'maker:id,name_ja,name_en',
                        'gutImages:id,file_path,title'
                    ],
                    'crossGut:id,name_ja,name_en,need_posting_image,maker_id,image_id' => [
                        'maker:id,name_ja,name_en',
                        'gutImages:id,file_path,title'
                    ],
                ]
            ])->get();
    
            return response()->json($gut_reviews, 200);
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
    public function store(GutReviewStoreRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $gut_review = GutReview::create([
                'equipment_id' => $validated['equipment_id'],
                'match_rate' => $validated['match_rate'],
                'pysical_durability' => $validated['pysical_durability'],
                'performance_durability' => $validated['performance_durability'],
                'review' => empty($validated['review']) ? '' : $validated['review']
            ]);
    
            if($gut_review) {
                return response()->json('ガットレビューを投稿しました', 200);
            }
        } catch(\Throwable $e) {
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
