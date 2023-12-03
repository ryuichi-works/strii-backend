<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Racket;
use App\Http\Requests\Racket\RacketStoreRequest;
use App\Http\Requests\Racket\RacketUpdateRequest;
use Illuminate\Support\Facades\Auth;

class RacketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rackets = Racket::with(['maker', 'racketImage'])->get();

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
            $racket = Racket::with(['maker', 'racketImage'])->findOrFail($id);

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
    public function update(RacketUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $racket = Racket::findOrFail($id);

            $racket->name_ja = $validated['name_ja'];
            $racket->name_en = $validated['name_en'];
            $racket->maker_id = $validated['maker_id'];
            $racket->image_id = isset($validated['image_id']) ? $validated['image_id'] : null;
            $racket->need_posting_image = $validated['need_posting_image'];

            if ($racket->save()) {
                return response()->json('ラケット情報を更新しました', 200);
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
            $racket = Racket::findOrFail($id);

            if ($racket->delete()) {
                return response()->json("{$racket->name_ja}を削除しました", 200);
            }
        } catch (\ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function getRandamOtherRackets(Request $request, $id)
    {
        // 取得するracketデータの数を指定
        $count = $request->query('count');

        $otherRackets = Racket::where('id', '!=', $id)->with(['racketImage', 'maker'])->get();

        // otherRacketsからランダムにデータを取り出すためのランダムindexの配列を生成
        $randomIndexes = [];
        for ($i = 0; $i < $count; $i++) {
            while (true) {
                // /** otherRacketsの配列の中身の数を元に一時的な乱数を作成 */
                $num = mt_rand(0, count($otherRackets) - 1);

                // randomIndexesに含まれているならwhile続行、含まれてないなら配列に代入してbreak            
                if (!in_array($num, $randomIndexes)) {
                    array_push($randomIndexes, $num);
                    break;
                }
            }
        }

        //responseRacketsが整列されるようにrandomIndexesを整列
        sort($randomIndexes, SORT_ASC);

        // 生成されたrandumndexesを元に取り出したracket情報をresponseRacketsにそれぞれ格納
        $responseRackets = [];
        foreach ($randomIndexes as $index) {
            array_push($responseRackets, $otherRackets[$index]);
        }

        return response()->json($responseRackets, 200);
    }



    public function racketSearch(Request $request)
    {
        $severalWords = $request->query('several_words');

        if ($severalWords) {
            //全角スペースを半角に変換
            $spaceConversion = mb_convert_kana($severalWords, 's');

            // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
            $severalWordsArray = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
        }

        $maker_id = $request->query('maker');

        $racketQuery = Racket::query();

        if ($severalWords && $maker_id) {
            foreach ($severalWordsArray as $word) {
                //severalWordsで複数取れてきてもmakerが一致しない場合は弾かれる
                $racketQuery
                    ->orWhere(function ($racketQuery) use ($word, $maker_id) {
                        $racketQuery
                            ->where(function ($racketQuery) use ($word, $maker_id) {
                                $racketQuery
                                    ->orWhere('name_ja', 'like', '%' . $word . '%')
                                    ->orWhere('name_en', 'like', '%' . $word . '%');
                            })
                            ->where('maker_id', '=', $maker_id);
                    });
            }
        } elseif ($severalWords && empty($maker_id)) {
            //makerの指定がないのでseveralWordsのor検索となる
            foreach ($severalWordsArray as $word) {
                $racketQuery
                    ->orWhere('name_ja', 'like', '%' . $word . '%')
                    ->orWhere('name_en', 'like', '%' . $word . '%');
            }
        } elseif (empty($severalWords) && $maker_id) {
            //makerのみでの検索
            $racketQuery->where('maker_id', '=', $maker_id);
        }

        $searchedRackets = $racketQuery->with(['maker', 'racketImage'])->get();

        return response()->json($searchedRackets, 200);
    }
}
