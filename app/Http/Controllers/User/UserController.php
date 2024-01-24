<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

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
            if($file = $request->file('file')) {
                $filename = uniqid(rand(0, 99) . "_user_") . "." . $request->file('file')->extension();
        
                $path = $file->storeAs('images/users', $filename, 'public');

                //以前のイメージファイルがあればstorageフォルダから削除
                if($user->file_path) {
                    Storage::disk('public')->delete($user->file_path);
                }

                $user->file_path = $path;
            }

            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if($user->save()) {
                return response()->json('ユーザー情報を更新しました', 200);
            }
        }catch(\ModelNotFoundException $e) {
            throw $e;
        }catch(\Throwable $e) {
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
