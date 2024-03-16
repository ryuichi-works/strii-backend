<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class GutImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'title',
        'maker_id'
    ];

    public function guts() {
        return $this->hasMany(Gut::class, 'image_id', 'id');
    }

    public function maker() {
        return $this->belongsTo(Maker::class);
    }

    // dbリクエスト処理関連

    // ガット画像登録処理
    public static function registerGutImage($request)
    {
        // 画像ファイルリサイジング
        $file = Image::make($request['file']);
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

        $filename = now()->format('YmdHis') . $request['title'] . "." . $request['file']->extension();

        // storageに保存するためのpathを生成
        $storagePath = storage_path('app/public/images/guts');
        $fileLocationFullPath = $storagePath . '/' . $filename;

        try {
            // intervention imageを使っているためstorageフォルダ下に一時的に保存
            if ($file->save($fileLocationFullPath)) {
                $filePath = 'images/guts/' . $filename;

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
                $gut_image = GutImage::create([
                    'file_path' => $s3Url,
                    'title' => $request['title'],
                    'maker_id' => $request['maker_id']
                ]);
            }

            return $gut_image;
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
