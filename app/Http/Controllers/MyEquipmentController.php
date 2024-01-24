<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyEquipment;
use App\Models\Racket;
use App\Models\Gut;
use App\Http\Requests\MyEquipment\MyEquipmentStoreRequest;
use App\Http\Requests\MyEquipment\MyEquipmentUpdateRequest;

class MyEquipmentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:user')->only(['store', 'show', 'update', 'delete', 'getAllEquipmentOfUser']);
        // $this->middleware('auth:admin')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $my_equipment = MyEquipment::with(['user', 'mainGut', 'crossGut'])->paginate(8);

            return response()->json($my_equipment, 200);
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
    public function store(MyEquipmentStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $my_equipment = MyEquipment::create([
                'user_id'           => $validated['user_id'],
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
                'change_gut_date'   => empty($validated['change_gut_date']) ? null : $validated['change_gut_date'],
                'comment'           => empty($validated['comment']) ? '' : $validated['comment']
            ]);

            if ($my_equipment) {
                return response()->json('マイ装備を追加しました。', 200);
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
            $my_equipment = MyEquipment::with([
                'user',
                'racket' => ['maker', 'racketImage'],
                'mainGut' => ['maker', 'gutImage'],
                'crossGut' => ['maker', 'gutImage']
            ])->findOrFail($id);

            return response()->json($my_equipment, 200);
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
    public function update(MyEquipmentUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $my_equipment = MyEquipment::findOrFail($id);

            $my_equipment->user_height       = $validated['user_height'];
            $my_equipment->user_age          = $validated['user_age'];
            $my_equipment->experience_period = $validated['experience_period'];
            $my_equipment->racket_id         = $validated['racket_id'];
            $my_equipment->stringing_way     = $validated['stringing_way'];
            $my_equipment->main_gut_id       = $validated['main_gut_id'];
            $my_equipment->main_gut_guage    = $validated['main_gut_guage'];
            $my_equipment->main_gut_tension  = $validated['main_gut_tension'];
            $my_equipment->cross_gut_id      = $validated['cross_gut_id'];
            $my_equipment->cross_gut_guage   = $validated['cross_gut_guage'];
            $my_equipment->cross_gut_tension = $validated['cross_gut_tension'];
            $my_equipment->new_gut_date      = $validated['new_gut_date'];
            $my_equipment->change_gut_date   = empty($validated['change_gut_date']) ? null : $validated['change_gut_date'];
            $my_equipment->comment           = empty($validated['comment']) ? '' : $validated['comment'];

            if ($my_equipment->save()) {
                return response()->json('マイ装備の情報を更新しました', 200);
            }
        } catch (ModelNotFoundException $e) {
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
            $my_equipment = MyEquipment::findOrFail($id);

            $my_equipment->delete();

            return response()->json("id:{$my_equipment->id}のマイ装備を削除しました", 200);
        } catch (ModelNotFoundException $e) {
            throw $e;
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
    public function getAllEquipmentOfUser($id)
    {
        try {
            $user_equipments = MyEquipment::where('user_id', '=', $id)->with([
                'user',
                'racket' => ['maker', 'racketImage'],
                'mainGut' => ['maker', 'gutImage'],
                'crossGut' => ['maker', 'gutImage']
            ])->paginate(8);

            return response()->json($user_equipments, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function searchEquipmentOfUser(Request $request, $id)
    {
        try {
            $severalWords = $request->query('several_words');

            if ($severalWords) {
                //全角スペースを半角に変換
                $spaceConversion = mb_convert_kana($severalWords, 's');

                // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
                $severalWordsArray = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
            }

            $stringing_way = $request->query('stringing_way');

            $search_date = $request->query('search_date');
            $date_range_type = $request->query('date_range_type');

            if ($date_range_type === 'or_more') {
                $range_type = '>=';
            } elseif ($date_range_type === 'or_less') {
                $range_type = '<=';
            }

            $userEquipmentsQuery = MyEquipment::query();

            $userEquipmentsQuery->where('user_id', '=', $id);
            
            // gut張り方で検索
            if ($stringing_way) {
                $userEquipmentsQuery
                    ->where('stringing_way', '=', $stringing_way);
            }

            // 日にち（ある日時以前、以後）で検索
            if ($search_date && $date_range_type) {
                $userEquipmentsQuery
                    ->where('new_gut_date', $range_type, $search_date);
            }

            // キーワード検索
            if ($severalWords) {
                // racketsで検索query
                $userEquipmentsQuery->where(function ($userEquipmentsQuery) use ($severalWordsArray) {
                    foreach ($severalWordsArray as $searchWord) {
                        // 一旦検索ワードでラケット一覧を取得
                        $searchedRackets = Racket::where('name_ja', 'like', "%$searchWord%")
                            ->orWhere('name_en', 'like', "%$searchWord%")->get();

                        // 検索ラケット一覧結果があればqueryを生成
                        if ($searchedRackets) {
                            $userEquipmentsQuery->orWhere(function ($userEquipmentsQuery) use ($searchedRackets) {
                                foreach ($searchedRackets as $racket) {
                                    $userEquipmentsQuery->orWhere('my_equipments.racket_id', '=', (int) $racket->id);
                                }
                            });
                        }
                    }
                });

                // gutsで検索query
                $userEquipmentsQuery->where(function ($userEquipmentsQuery) use ($severalWordsArray) {
                    foreach ($severalWordsArray as $searchWord) {
                        // 一旦検索ワードでgut一覧を取得
                        $searchedGuts = Gut::where('name_ja', 'like', "%$searchWord%")
                            ->orWhere('name_en', 'like', "%$searchWord%")->get();

                        // 検索gut一覧結果があればqueryを生成
                        if ($searchedGuts) {
                            $userEquipmentsQuery->orWhere(function ($userEquipmentsQuery) use ($searchedGuts) {
                                foreach ($searchedGuts as $gut) {
                                    $userEquipmentsQuery
                                        ->orWhere('my_equipments.main_gut_id', '=', (int) $gut->id)
                                        ->orWhere('my_equipments.cross_gut_id', '=', (int) $gut->id);
                                }
                            });
                        }
                    }
                });
            }

            // query確認用
            // $sql = $userEquipmentsQuery->toSql();

            $searchedUserEquipments = $userEquipmentsQuery
                ->with([
                    'user',
                    'racket' => ['maker', 'racketImage'],
                    'mainGut' => ['maker', 'gutImage'],
                    'crossGut' => ['maker', 'gutImage']
                ])
                ->paginate(8)
                ->appends([
                    'several_words' => $severalWords,
                    'stringing_way' => $stringing_way,
                    'search_date' => $search_date,
                    'date_range_type' => $date_range_type,
                ]);

            return response()->json($searchedUserEquipments, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }
}
