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

            //新しいファイルがあれば新たにstorageに登録
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
                    // "/var/www/html/strii-backend/storage/app/public/images/users/~~~.jpg"
                    // intervension image導入前の登録の仕様に合わせるため
                    // 上記のようなfullPathをDBのfile_pathカラム用に整形
                    $trimedFilePath = strstr($fileLocationFullPath, 'images');

                    //以前のイメージファイルがあればstorageフォルダから削除
                    if ($user->file_path) {
                        Storage::disk('public')->delete($user->file_path);
                    }

                    $user->file_path = $trimedFilePath;
                }
            }

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if ($user->save()) {
                return response()->json('ユーザー情報を更新しました', 200);
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
        //
    }
}
