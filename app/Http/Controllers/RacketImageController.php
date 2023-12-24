<?php

namespace App\Http\Controllers;

use App\Http\Requests\RacketImage\RacketImageStoreRequest;
use App\Http\Requests\RacketImage\RacketImageUpdateRequest;
use App\Models\RacketImage;
use App\Models\Maker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RacketImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->only(['index', 'show', 'update', 'delete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // $racket_images = RacketImage::with('maker')->all();
            $racket_images = RacketImage::with('maker')->get();

            $image_infos = [];

            //各imageのpathを整形して返却
            foreach ($racket_images as $image) {
                $image_info = [
                    'id' => $image->id,
                    'title' => $image->title,
                    'file_path' => Storage::url($image->file_path),
                    'maker' => $image->maker
                ];

                array_push($image_infos, $image_info);
            }

            return response()->json($image_infos, 200);
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
    public function store(RacketImageStoreRequest $request)
    {
        $validated_request = $request->validated();

        try {
            $file = $request->file('file');

            $filename = now()->format('YmdHis') . $validated_request['title'] . "." . $request->file('file')->extension();

            $path = $file->storeAs('images/rackets', $filename, 'public');

            $racket_image = RacketImage::create([
                'file_path' => $path,
                'title' => $validated_request['title'],
                'maker_id' => $validated_request['maker_id']
            ]);

            if (isset($racket_image)) {
                $maker = Maker::find($racket_image->maker_id);

                return response()->json([
                    'file_path' => Storage::url($racket_image['file_path']),
                    'title' => $racket_image['title'],
                    'maker' => $maker
                ], 200);
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
        try {
            $racket_image = RacketImage::with('maker')->findOrFail($id);

            return response()->json([
                'id' => $racket_image['id'],
                'file_path' => Storage::url($racket_image['file_path']),
                'title' => $racket_image['title'],
                'maker' => $racket_image['maker']
            ]);
        } catch (ModelNotFoundException $e) {
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
    public function update(RacketImageUpdateRequest $request, $id)
    {
        $validated_request = $request->validated();

        try {
            $image = RacketImage::with('maker')->findOrFail($id);

            //新しいファイルがあれば新たにstorageに登録
            if($request->file('file')) {
                $file = $request->file('file');
                $filename = now()->format('YmdHis') . $validated_request['title'] . "." . $request->file('file')->extension();
                $path = $file->storeAs('images/guts', $filename, 'public');
    
                //以前のイメージファイルをstorageフォルダから削除
                Storage::disk('public')->delete($image->file_path);
                
                $image->file_path = $path;
            }

            $image->title = $validated_request['title'];
            $image->maker_id = $validated_request['maker_id'];

            if($image->save()) {
                $maker = Maker::find($image->maker_id);

                return response()->json([
                    'id' => $image['id'],
                    'file_path' => Storage::url($image['file_path']),
                    'title' => $image['title'],
                    'maker' => $maker
                ], 200);
            }
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
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
            $image = RacketImage::findOrFail($id);

            Storage::disk('public')->delete($image->file_path);

            $image->delete();

            return response()->json("{$image['title']}の画像を削除しました", 200);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function racketImageSearch(Request $request)
    {
        $severalWords = $request->query('several_words');

        if ($severalWords) {
            //全角スペースを半角に変換
            $spaceConversion = mb_convert_kana($severalWords, 's');

            // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
            $severalWordsArray = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
        }

        $maker_id = $request->query('maker');

        $racketImageQuery = RacketImage::query();

        if ($severalWords && $maker_id) {
            foreach ($severalWordsArray as $word) {
                //severalWordsで複数取れてきてもmakerが一致しない場合は弾かれる
                $racketImageQuery
                    ->orWhere(function ($racketImageQuery) use ($word, $maker_id) {
                        $racketImageQuery
                            ->where('title', 'like', '%' . $word . '%')
                            ->where('maker_id', '=', $maker_id);
                    });
            }
        } elseif ($severalWords && empty($maker_id)) {
            //makerの指定がないのでseveralWordsのor検索となる
            foreach ($severalWordsArray as $word) {
                $racketImageQuery->orWhere('title', 'like', '%' . $word . '%');
            }
        } elseif (empty($severalWords) && $maker_id) {
            //makerのみでの検索
            $racketImageQuery->where('maker_id', '=', $maker_id);
        }

        $searchedRacketImages = $racketImageQuery->with('maker')->get();

        return response()->json($searchedRacketImages, 200);
    }
}
