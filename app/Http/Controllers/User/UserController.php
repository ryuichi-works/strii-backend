<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:30'],
            'email' => ['required', 'email'],
            'file' => ['nullable', 'file']
        ]);

        try {
            $user = User::findOrFail($id);

            //新しいファイルがあれば新たに登録
            if ($file = $request->file('file')) {

                // 画像ファイルリサイジング
                $file = Image::make($request->file('file'));
                $file->orientate();
                $file->resize(
                    300,
                    null,
                    function ($constraint) {
                        // 縦横比を保持したままにする
                        $constraint->aspectRatio();
                        // 小さい画像は大きくしない
                        $constraint->upsize();
                    }
                );

                $filename = uniqid(rand(0, 99) . "_user_") . "." . $user->id . "." . $request->file('file')->extension();

                // storageに登録するためのpathを生成
                $storagePath = storage_path('app/public/images/users');
                $fileLocationFullPath = $storagePath . '/' . $filename;

                if ($file->save($fileLocationFullPath)) {
                    // 画像をAmazon S3にアップロード用urlに整形
                    $filePath = 'images/users/' . $filename;

                    // s3へアップロード
                    if (Storage::disk('s3')->put($filePath, file_get_contents($fileLocationFullPath))) {
                        // 成功時
                        // S3上の画像のURLを取得
                        $s3Url = Storage::disk('s3')->url($filePath);

                        // 以前のpathがあれば保管しておく
                        $previousFilePath = $user->file_path;

                        // 更新用の新しいurlを格納
                        $user->file_path = $s3Url;
                    } else {
                        // 失敗時
                        // 一時保存していたファイルを削除
                        unlink($fileLocationFullPath);

                        throw new Exception('s3への画像アップロードに失敗しました');
                    }
                }
            }

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if ($user->save()) {
                // s3に以前の画像があれば削除(strstr()はurlを整形)
                if (isset($previousFilePath) && Storage::disk('s3')->exists(strstr($previousFilePath, 'images'))) {
                    Storage::disk('s3')->delete(strstr($previousFilePath, 'images'));
                }

                return response()->json('ユーザー情報を更新しました', 200);
            }
        } catch (\ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            // s3上に画像登録完了していたらそれをclean up
            if (isset($s3Url)) {
                $trimedFilePath = strstr($s3Url, 'images');
                Storage::disk('s3')->delete($trimedFilePath);
            }

            throw $e;
        } finally {
            // 一時保存ファイルがあれば削除
            if ($request->file('file') && $s3Url) {
                unlink($fileLocationFullPath);
            }
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
        //
    }
}
