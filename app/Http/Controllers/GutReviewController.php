<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutReview;
use App\Models\MyEquipment;
use App\Http\Requests\GutReview\GutReviewStoreRequest;
use App\Http\Requests\GutReview\GutReviewUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ])->paginate(8);

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
            DB::beginTransaction();

            $userId = Auth::guard('user')->id();

            $newMyEquipment = null;
            
            // gut_review登録前に新しくmy_equipmentの追加が必要なとき処理
            if($validated['need_creating_my_equipment'] && empty($validated['equipment_id'])) {
                $newMyEquipment = MyEquipment::create([
                    'user_id'           => $userId,
                    'user_height'       => $validated['user_height'],
                    'user_age'          => $validated['user_age'],
                    'expefience_period' => $validated['experience_period'],
                    'racket_id'         => $validated['racket_id'],
                    'stringing_way'     => $validated['stringing_way'],
                    'main_gut_id'       => $validated['main_gut_id'],
                    'main_gut_guage'    => $validated['main_gut_guage'],
                    'main_gut_tension'  => $validated['main_gut_tension'],
                    'cross_gut_id'      => $validated['cross_gut_id'],
                    'cross_gut_guage'   => $validated['cross_gut_guage'],
                    'cross_gut_tension' => $validated['cross_gut_tension'],
                    'new_gut_date'      => $validated['new_gut_date'],
                    'change_gut_date'   => null,
                    'comment'           => ''
                ]);
            }

            $equpmentId = $newMyEquipment ? $newMyEquipment->id : $validated['equipment_id'];

            $gut_review = GutReview::create([
                'user_id' => $userId,
                'equipment_id' => $equpmentId,
                'match_rate' => $validated['match_rate'],
                'pysical_durability' => $validated['pysical_durability'],
                'performance_durability' => $validated['performance_durability'],
                'review' => empty($validated['review']) ? '' : $validated['review']
            ]);

            DB::commit();

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
            DB::beginTransaction();

            $gut_review = GutReview::findOrFail($id);

            // my_equipmentも変更したい時の処理
            if($validated['need_editing_my_equipment']) {
                $myEquipment = MyEquipment::findOrFail($gut_review->equipment_id);

                $myEquipment->user_height       = $validated['user_height'];
                $myEquipment->user_age          = $validated['user_age'];
                $myEquipment->experience_period = $validated['experience_period'];
                $myEquipment->racket_id         = $validated['racket_id'];
                $myEquipment->stringing_way     = $validated['stringing_way'];
                $myEquipment->main_gut_id       = $validated['main_gut_id'];
                $myEquipment->main_gut_guage    = $validated['main_gut_guage'];
                $myEquipment->main_gut_tension  = $validated['main_gut_tension'];
                $myEquipment->cross_gut_id      = $validated['cross_gut_id'];
                $myEquipment->cross_gut_guage   = $validated['cross_gut_guage'];
                $myEquipment->cross_gut_tension = $validated['cross_gut_tension'];

                $myEquipment->save();
            }

            $gut_review->match_rate = $validated['match_rate'];
            $gut_review->pysical_durability = $validated['pysical_durability'];
            $gut_review->performance_durability = $validated['performance_durability'];
            $gut_review->review = empty($validated['review']) ? '' : $validated['review'];

            if ($gut_review->save()) {
                DB::commit();

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
            $experience_period = (int) $request->query('experience_period');
            $racket_id = $request->query('racket_id');
            $stringing_way = $request->query('stringing_way');
            $main_gut_id = $request->query('main_gut_id');
            $cross_gut_id = $request->query('cross_gut_id');

            // tennis_profilesテーブルの項目での検索項目
            $gender = $request->query('gender');
            $grip_form = $request->query('grip_form');
            $physique = $request->query('physique');
            $frequency = $request->query('frequency');
            $play_style = $request->query('play_style');
            $favarit_shot = $request->query('favarit_shot');
            $weak_shot = $request->query('weak_shot');

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

            // 等値比較メソッド
            function searchGutReviewWithEqualityComparison($gutReviewQuery, $searchColumn, $searchVal)
            {
                // joinさせてから検索する場合、最後のquery実行時にデータの構造がjoinさせたカラムで崩れ
                // eager loadingさせたい時その分余分なデータが残ってしまうので、
                // その場合に意図したデータ構造にするためにselectでカラムを指定
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
            if($user_height || $user_age || ($experience_period || $experience_period === 0) || $racket_id || $stringing_way || $main_gut_id || $cross_gut_id) {
                // my_equipmentsテーブルの項目で検索するときは先にjoinさせておく
                $gutReviewQuery->join('my_equipments', 'gut_reviews.equipment_id', '=', 'my_equipments.id');
                
                if($user_height) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'user_height', $user_height);
                }
    
                if($user_age) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'user_age', $user_age);
                }

                if($experience_period || $experience_period === 0) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'experience_period', $experience_period);
                }

                if($racket_id) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'racket_id', $racket_id);
                }

                if($stringing_way) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'stringing_way', $stringing_way);
                }

                if($main_gut_id) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'main_gut_id', $main_gut_id);
                }

                if($cross_gut_id) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'cross_gut_id', $cross_gut_id);
                }
            }

            // tennis_profileの項目で検索
            if($gender || $grip_form || $physique || $frequency || $play_style || $favarit_shot || $weak_shot) {
                // gutReviewqueryで使うサブクエリとしてtennis_profilesテーブルをusersテーブルでjoinする句
                $userTennisProfileSubQuery = \DB::table('tennis_profiles')
                    ->select('user_id', 'gender', 'my_racket_id', 'grip_form', 'height', 'age', 'physique', 'experience_period', 'frequency', 'play_style', 'favarit_shot', 'weak_shot')
                    ->join('users', 'tennis_profiles.user_id', '=', 'users.id');
                
                // サブクエリである$userTennisProfileSubQueryの結果をを使ったgut_reviewsテーブルへのjoin句
                $gutReviewQuery
                    ->joinSub($userTennisProfileSubQuery, 'user_tennis_profile', function($join) {
                        $join->on('gut_reviews.user_id', '=', 'user_tennis_profile.user_id');
                    });
                
                // 検索
                if($gender) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'gender', $gender);
                }
    
                if($grip_form) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'grip_form', $grip_form);
                }

                if($physique) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'physique', $physique);
                }

                if($frequency) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'frequency', $frequency);
                }

                if($play_style) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'play_style', $play_style);
                }

                if($favarit_shot) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'favarit_shot', $favarit_shot);
                }

                if($weak_shot) {
                    searchGutReviewWithEqualityComparison($gutReviewQuery,'weak_shot', $weak_shot);
                }
            }

            $searchedGutReview = $gutReviewQuery
                ->with([
                    'myEquipment' => [
                        'user',
                        'racket' => ['maker', 'racketImage'],
                        'mainGut' => ['maker', 'gutImage'],
                        'crossGut' => ['maker', 'gutImage'],
                    ]
                ])
                ->paginate(8)
                ->appends([
                    // // gutReviewのレビュー項目での検索query
                    'match_rate' => $match_rate ? $match_rate : null,
                    'pysical_durability' => $pysical_durability ? $pysical_durability : null,
                    'performance_durability' => $performance_durability ? $performance_durability : null,
                    'search_range_type' => $search_range_type ? $search_range_type : null,

                    // 関連テーブルのmy_equipmentsでの検索query
                    'user_height' => $user_height,
                    'user_age' => $user_age,
                    'experience_period' => $experience_period,
                    'racket_id' => $racket_id,
                    'stringing_way' => $stringing_way,
                    'main_gut_id' => $main_gut_id,
                    'cross_gut_id' => $cross_gut_id,

                    // tennis_profilesテーブルの項目での検索query
                    'gender' => $gender,
                    'grip_form' => $grip_form ,
                    'physique' => $physique,
                    'frequency' => $frequency,
                    'play_style' => $play_style,
                    'favarit_shot' => $favarit_shot,
                    'weak_shot' => $weak_shot,
                ]);

            return response()->json($searchedGutReview, 200);
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
