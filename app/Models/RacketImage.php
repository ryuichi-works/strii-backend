<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class RacketImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'title',
        'maker_id',
        'posting_user_id'
    ];

    // dbリレーション関連メソッド
    public function rackets()
    {
        return $this->hasMany(Racket::class, 'image_id','id');
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'posting_user_id', 'id');
    }

    // dbリクエスト処理関連

    // ラケット画像登録処理
    public function registerRacketImage($request)
    {
        // 画像ファイルリサイジング
        $file = Image::make($request['file']);
        $file->orientate();
        $file->resize(
            480,
            null,
            function ($constraint) {
                // 縦横比を保持したままにする
                $constraint->aspectRatio();
                // 小さい画像は大きくしない
                $constraint->upsize();
            }
        );

        $filename = now()->format('YmdHis') . $request['title'] . "." . $request['file']->extension();

        // storageに登録するためのpathを生成
        $storagePath = storage_path('app/public/images/rackets');
        $fileLocationFullPath = $storagePath . '/' . $filename;

        if ($file->save($fileLocationFullPath)) {
            // "/var/www/html/strii-backend/storage/app/public/images/rackets/20240105123954リサイズ確認５.jpg"
            // intervension image導入前の登録の仕様に合わせるため
            // 上記のようなfullPathをDBのfile_pathカラム用に整形
            $trimedFilePath = strstr($fileLocationFullPath, 'images');

            $racket_image = RacketImage::create([
                'file_path' => $trimedFilePath,
                'title' => $request['title'],
                'maker_id' => $request['maker_id'],
                'posting_user_id' => $request['posting_user_id'],
            ]);
        }

        return $racket_image;
    }
}
