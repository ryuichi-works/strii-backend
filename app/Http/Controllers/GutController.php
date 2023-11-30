<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gut\GutStoreRequest;
use App\Http\Requests\Gut\GutUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Gut;

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
            $guts = Gut::with(['maker', 'gutImage'])->get();

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

        try {
            $gut = Gut::create([
                'name_ja' => $validated['name_ja'],
                'name_en' => $validated['name_en'],
                'maker_id' => $validated['maker_id'],
                'image_id' => isset($validated['image_id']) ? $validated['image_id'] : null,
                'need_posting_image' => $validated['need_posting_image'],
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
            $gut->image_id = isset($validated['image_id']) ? $validated['image_id'] : null;
            $gut->need_posting_image = $validated['need_posting_image'];
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
}
