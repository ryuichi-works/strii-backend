<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

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
        return $this->hasMany(Racket::class, 'image_id', 'id');
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
    public static function registerRacketImage($request)
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

        // storageに保存するためのpathを生成
        $storagePath = storage_path('app/public/images/rackets');
        $fileLocationFullPath = $storagePath . '/' . $filename;

        try {
            // intervention imageを使っているためstorageフォルダ下に一時的に保存
            if ($file->save($fileLocationFullPath)) {
                $filePath = 'images/rackets/' . $filename;

                // 画像をAmazon S3にアップロード
                if (Storage::disk('s3')->put($filePath, file_get_contents($fileLocationFullPath))) {
                    // 成功時
                    // S3上の画像のURLを取得
                    $s3Url = Storage::disk('s3')->url($filePath);
                } else {
                    // 失敗時
                    throw new Exception('s3への画像アップロードに失敗しました');
                }

                // データベースにファイルパスを保存などの処理
                $racket_image = RacketImage::create([
                    'file_path' => $s3Url,
                    'title' => $request['title'],
                    'maker_id' => $request['maker_id'],
                    'posting_user_id' => $request['posting_user_id'],
                ]);
            }

            return $racket_image;
        } catch (\Throwable $e) {
            // s3上に画像登録完了していたらそれをclean up
            if(isset($s3Url)) {
                $trimedFilePath = strstr($s3Url, 'images');
                Storage::disk('s3')->delete($trimedFilePath);
            }

            throw $e;
        } finally {
            // storageに一時的に保存していた画像ファイルを削除
            unlink($fileLocationFullPath);
        }
    }
}
