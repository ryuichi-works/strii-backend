<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gut\GutStoreByCsvRequest;
use App\Http\Requests\Gut\GutStoreRequest;
use App\Http\Requests\Gut\GutUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Gut;
use App\Models\GutImage;

class GutController extends Controller
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
            $guts = Gut::with(['maker', 'gutImage'])->paginate(8);

            return response()->json($guts, 200);
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
    public function store(GutStoreRequest $request)
    {
        $validated = $request->validated();

        if(empty($validated['image_id'])) {
            $appUrl = env('APP_URL');
            $defaultgutImage = GutImage::where('file_path', '=', "{$appUrl}/storage/images/guts/default_gut_image.png")->get()[0];
        }

        try {
            $gut = Gut::create([
                'name_ja' => $validated['name_ja'],
                'name_en' => $validated['name_en'],
                'maker_id' => $validated['maker_id'],
                'image_id' => isset($validated['image_id']) ? $validated['image_id'] : $defaultgutImage->id,
                'need_posting_image' => $validated['need_posting_image'],
                'guage' => isset($validated['guage']) ? $validated['guage'] : '',
                'category' => isset($validated['category']) ? $validated['category'] : '',
            ]);

            if ($gut) {
                return response()->json('ガットを登録しました', 200);
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
            $gut = Gut::with(['maker', 'gutImage'])->findOrFail($id);

            return response()->json($gut, 200);
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
    public function update(GutUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $gut = Gut::findOrFail($id);

            $gut->name_ja = $validated['name_ja'];
            $gut->name_en = $validated['name_en'];
            $gut->maker_id = $validated['maker_id'];
            $gut->image_id = isset($validated['image_id']) ? $validated['image_id'] : $gut->image_id;
            $gut->need_posting_image = $validated['need_posting_image'];
            $gut->guage = isset($validated['guage']) ? $validated['guage'] : $gut->guage;
            $gut->category = isset($validated['category']) ? $validated['category'] : $gut->category;

            if ($gut->save()) {
                return response()->json('ガット情報を更新しました', 200);
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
            $gut = Gut::findOrFail($id);

            if ($gut->delete()) {
                return response()->json("{$gut->name_ja}を削除しました", 200);
            }
        } catch (\ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function getRandamOtherGuts(Request $request, $id)
    {
        // 取得するgutデータの数を指定
        $count = $request->query('count');

        // $otherGuts = Gut::where('id', '!=', $id)->get();
        $otherGuts = Gut::where('id', '!=', $id)->with(['gutImage', 'maker'])->get();

        // othergutsからランダムにデータを取り出すためのランダムindexの配列を生成
        $randomIndexes = [];
        for ($i = 0; $i < $count; $i++) {
            while (true) {
                // /** otherGutsの配列の中身の数を元に一時的な乱数を作成 */
                $num = mt_rand(0, count($otherGuts) - 1);

                // randomIndexesに含まれているならwhile続行、含まれてないなら配列に代入してbreak            
                if (!in_array($num, $randomIndexes)) {
                    array_push($randomIndexes, $num);
                    break;
                }
            }
        }

        //responseGutsが整列されるようにrandomIndexesを整列
        sort($randomIndexes, SORT_ASC);

        // 生成されたrandumndexesを元に取り出したgut情報をresponseGutsにそれぞれ格納
        $responseGuts = [];
        foreach ($randomIndexes as $index) {
            array_push($responseGuts, $otherGuts[$index]);
        }

        return response()->json($responseGuts, 200);
    }

    public function gutSearch(Request $request)
    {
        $severalWords = $request->query('several_words');

        if ($severalWords) {
            //全角スペースを半角に変換
            $spaceConversion = mb_convert_kana($severalWords, 's');

            // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
            $severalWordsArray = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
        }

        $maker_id = $request->query('maker');

        $guage = $request->query('guage');

        $category = $request->query('category');

        $gutQuery = Gut::query();

        // 太さguageで検索
        if($guage) {
            $gutQuery->where(function ($gutQuery) use ($guage) {
                $gutQuery->where('guage', 'like', "%{$guage}%");
            });
        }

        // gutカテゴリーで検索
        if($category) {
            $gutQuery->where(function ($gutQuery) use ($category) {
                $gutQuery->where('category', '=', $category);
            });
        }

        // メーカーで検索
        if ($maker_id) {
            $gutQuery->where(function ($gutQuery) use ($maker_id) {
                $gutQuery->where('maker_id', '=', $maker_id);
            });
        }

        // キーワード検索
        if ($severalWords) {
            $gutQuery->where(function ($gutQuery) use ($severalWordsArray) {
                foreach ($severalWordsArray as $word) {
                    $gutQuery
                        ->orWhere('name_ja', 'like', '%' . $word . '%')
                        ->orWhere('name_en', 'like', '%' . $word . '%');
                }
            });
        }

        $searchedGuts = $gutQuery
            ->with(['maker', 'gutImage'])
            ->paginate(8)
            ->appends(['several_words' => $severalWords, 'maker' => $maker_id]);

        return response()->json($searchedGuts, 200);
    }

    public function storeByCsv(GutStoreByCsvRequest $request)
    {
        try {
            Gut::storeByCsv($request);

            return response()->json('csvデータを登録しました', 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
        }
    }
}
