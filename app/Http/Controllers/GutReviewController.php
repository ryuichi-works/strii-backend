<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutReview;
use App\Http\Requests\GutReview\GutReviewStoreRequest;
use App\Http\Requests\GutReview\GutReviewUpdateRequest;

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
                    'user',
                    'racket' => ['maker', 'racketImage'],
                    'mainGut' => ['maker', 'gutImage'],
                    'crossGut' => ['maker', 'gutImage'],
                ]
            ])->get();
            // $gut_reviews = GutReview::with([
            //     'myEquipment' => [
            //         'user:id,name',
            //         'mainGut:id,name_ja,name_en,need_posting_image,maker_id,image_id' => [
            //             'maker:id,name_ja,name_en',
            //             'gutImage:id,file_path,title'
            //         ],
            //         'crossGut:id,name_ja,name_en,need_posting_image,maker_id,image_id' => [
            //             'maker:id,name_ja,name_en',
            //             'gutImage:id,file_path,title'
            //         ],
            //     ]
            // ])->get();

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

            if ($gut_review) {
                return response()->json('ガットレビューを投稿しました', 200);
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
            $gut_review = GutReview::with([
                'myEquipment' => [
                    'user',
                    'racket' => ['maker', 'racketImage'],
                    'mainGut' => ['maker', 'gutImage'],
                    'crossGut' => ['maker', 'gutImage'],
                ]
            ])->findOrFail($id);

            return response()->json($gut_review, 200);
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
    public function update(GutReviewUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $gut_review = GutReview::findOrFail($id);

            $gut_review->match_rate = $validated['match_rate'];
            $gut_review->pysical_durability = $validated['pysical_durability'];
            $gut_review->performance_durability = $validated['performance_durability'];
            $gut_review->review = empty($validated['review']) ? '' : $validated['review'];

            if ($gut_review->save()) {
                return response()->json('ガットレビューを更新しました', 200);
            }
        } catch (\ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
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
            $gut_review = GutReview::findOrFail($id);

            $gut_review->delete();

            return response()->json("id:{$gut_review->id}のガットレビューを削除しました", 200);
        } catch (\ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }
}
