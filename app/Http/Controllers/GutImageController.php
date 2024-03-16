<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GutImage;
use App\Models\Maker;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GutImage\GutImageStoreRequest;
use App\Http\Requests\GutImage\GutImageUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InterventionImage;
use Intervention\Image\Facades\Image;

class GutImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $gut_images = GutImage::with('maker')->paginate(8);

            return response()->json($gut_images, 200);
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
    public function store(GutImageStoreRequest $request)
    {
        $validated_request = $request->validated();

        try {
            $gut_image = GutImage::registerGutImage($validated_request);

            if (isset($gut_image)) {
                $maker = Maker::find($gut_image->maker_id);

                return response()->json([
                    'file_path' => Storage::url($gut_image['file_path']),
                    'title' => $gut_image['title'],
                    'maker' => $maker
                ], 200);
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
            $gut_image = GutImage::with('maker')->findOrFail($id);

            return response()->json([
                'id' => $gut_image['id'],
                'file_path' => $gut_image['file_path'],
                'title' => $gut_image['title'],
                'maker' => $gut_image['maker']
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
    public function update(GutImageUpdateRequest $request, $id)
    {
        $validated_request = $request->validated();

        try {
            $image = GutImage::with('maker')->findOrFail($id);

            //新しいファイルがあれば新たにstorageに登録
            if ($request->file('file')) {
                // 画像ファイルリサイジング
                $file = Image::make($request->file('file'));
                $file->orientate();
                $file->resize(
                    560,
                    null,
                    function ($constraint) {
                        // 縦横比を保持したままにする
                        $constraint->aspectRatio();
                        // 小さい画像は大きくしない
                        $constraint->upsize();
                    }
                );

                $filename = now()->format('YmdHis') . $validated_request['title'] . "." . $request->file('file')->extension();

                // storageに登録するためのpathを生成
                $storagePath = storage_path('app/public/images/guts');
                $fileLocationFullPath = $storagePath . '/' . $filename;

                if ($file->save($fileLocationFullPath)) {
                    // 画像をAmazon S3にアップロード用urlに整形
                    $filePath = 'images/guts/' . $filename;

                    // s3へアップロード
                    if (Storage::disk('s3')->put($filePath, file_get_contents($fileLocationFullPath))) {
                        // 成功時
                        // S3上の画像のURLを取得
                        $s3Url = Storage::disk('s3')->url($filePath);

                        // 以前のpathを保管しておく
                        $previousFilePath = $image->file_path;

                        // 更新用の新しいurlを格納
                        $image->file_path = $s3Url;
                    } else {
                        // 失敗時
                        // 一時保存していたファイルを削除
                        unlink($fileLocationFullPath);

                        throw new Exception('s3への画像アップロードに失敗しました');
                    }
                }
            }

            $image->title = $validated_request['title'];
            $image->maker_id = $validated_request['maker_id'];

            if ($image->save()) {
                if ($request->file('file') && $s3Url) {
                    // s3上の以前の画像を削除(strstr()はurlの整形)
                    $trimedFilePath = strstr($previousFilePath, 'images');
                    Storage::disk('s3')->delete($trimedFilePath);
                    unlink($fileLocationFullPath);
                }

                $maker = Maker::find($image->maker_id);

                return response()->json([
                    'id' => $image['id'],
                    'file_path' => $image['file_path'],
                    'title' => $image['title'],
                    'maker' => $maker
                ], 200);
            }
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
            $image = GutImage::findOrFail($id);

            // file_pathが下記の様に登録してある
            // https://strii-bucket.s3.ap-northeast-1.amazonaws.com/images/guts/20240313003254〜〜〜.jpg
            // これを『images/rackets/20240313003254〜〜〜.jpg』の様に整形する
            $trimedFilePath = strstr($image->file_path, 'images');

            if (Storage::disk('s3')->delete($trimedFilePath)) {
                $image->delete();

                return response()->json("{$image['title']}の画像を削除しました", 200);
            } else {
                throw new Exception("画像の削除に失敗しました");
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function gutImageSearch(Request $request)
    {
        $severalWords = $request->query('several_words');

        if ($severalWords) {
            //全角スペースを半角に変換
            $spaceConversion = mb_convert_kana($severalWords, 's');

            // 単語を半角スペースで区切り、配列にする（例："山田 翔" → ["山田", "翔"]）
            $severalWordsArray = preg_split('/[\s,]+/', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);
        }

        $maker_id = $request->query('maker');

        $gutImageQuery = GutImage::query();

        if ($severalWords && $maker_id) {
            foreach ($severalWordsArray as $word) {
                //severalWordsで複数取れてきてもmakerが一致しない場合は弾かれる
                $gutImageQuery
                    ->orWhere(function ($gutImageQuery) use ($word, $maker_id) {
                        $gutImageQuery
                            ->where('title', 'like', '%' . $word . '%')
                            ->where('maker_id', '=', $maker_id);
                    });
            }
        } elseif ($severalWords && empty($maker_id)) {
            //makerの指定がないのでseveralWordsのor検索となる
            foreach ($severalWordsArray as $word) {
                $gutImageQuery->orWhere('title', 'like', '%' . $word . '%');
            }
        } elseif (empty($severalWords) && $maker_id) {
            //makerのみでの検索
            $gutImageQuery->where('maker_id', '=', $maker_id);
        }

        $searchedGutImages = $gutImageQuery
            ->with('maker')
            ->paginate(8)
            ->appends(['several_words' => $severalWords, 'maker' => $maker_id]);

        return response()->json($searchedGutImages, 200);
    }
}
