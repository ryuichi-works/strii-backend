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
                    'user' => ['tennisProfile'],
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

    public function gutReviewSearch(Request $request)
    {

        try {
            $match_rate = (int) $request->query('match_rate');
            $pysical_durability = (int) $request->query('pysical_durability');
            $performance_durability = (int) $request->query('performance_durability');
            $search_range_type = $request->query('search_range_type');

            // 関連テーブルのmy_equipmentsでの検索項目
            $user_height = $request->query('user_height');
            $user_age = $request->query('user_age');
            $experience_period = $request->query('experience_period');
            $racket_id = $request->query('racket_id');
            $stringing_way = $request->query('stringing_way');
            $main_gut_id = $request->query('main_gut_id');
            $cross_gut_id = $request->query('cross_gut_id');

            if ($search_range_type === 'or_more') {
                $range_type = '>=';
            } elseif ($search_range_type === 'or_less') {
                $range_type = '<=';
            }

            $gutReviewQuery = GutReview::query();

            // gutReviewのレビュー項目での検索
            if($match_rate && $range_type) {
                $gutReviewQuery->where('match_rate', $range_type, $match_rate);
            }

            if($pysical_durability && $range_type) {
                $gutReviewQuery->where('pysical_durability', $range_type, $pysical_durability);
            }

            if($performance_durability && $range_type) {
                $gutReviewQuery->where('performance_durability', $range_type, $performance_durability);
            }

            // joinさせてから検索する場合、最後のquery実行時にデータの構造がフラットになり
            // eager loadingさせたい時その分余分なデータが残ってしまうので、
            // その場合に意図したデータ構造にするために使用する関数
            function searchGutReviewWithJoinedTable($gutReviewQuery, $searchColumn, $searchVal)
            {
                $gutReviewQuery
                    ->select(
                        'gut_reviews.id',
                        'equipment_id',
                        'match_rate',
                        'pysical_durability',
                        'performance_durability',
                        'review',
                        'gut_reviews.created_at',
                        'gut_reviews.updated_at'
                    )
                    ->where($searchColumn, '=', $searchVal);
            }

            // gutReviewに紐つくmy_equipmentsの項目で検索
            if($user_height || $user_age || $experience_period || $racket_id || $stringing_way || $main_gut_id || $cross_gut_id) {
                // my_equipmentsテーブルの項目で検索するときは先にjoinさせておく
                $gutReviewQuery->join('my_equipments', 'gut_reviews.equipment_id', '=', 'my_equipments.id');
                
                if($user_height) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'user_height', $user_height);
                }
    
                if($user_age) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'user_age', $user_age);
                }

                if($experience_period) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'experience_period', $experience_period);
                }

                if($racket_id) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'racket_id', $racket_id);
                }

                if($stringing_way) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'stringing_way', $stringing_way);
                }

                if($main_gut_id) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'main_gut_id', $main_gut_id);
                }

                if($cross_gut_id) {
                    searchGutReviewWithJoinedTable($gutReviewQuery,'cross_gut_id', $cross_gut_id);
                }
            }
            

            $searchedGutReview = $gutReviewQuery->with('myEquipment')->get();

            return response()->json($searchedGutReview, 200);
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
