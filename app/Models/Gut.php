<?php

namespace App\Models;

use Error;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Gut extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ja',
        'name_en',
        'maker_id',
        'image_id',
        'need_posting_image'
    ];

    public static function rules()
    {
        return [
            'name_ja' => ['required', 'max:30'],
            'name_en' => ['max:30'],
            'maker_id' => ['required', 'integer', 'exists:makers,id'],
            'image_id' => ['sometimes', 'integer', 'exists:gut_images,id'],
            'need_posting_image' => ['required', 'boolean']
        ];
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function gutImage()
    {
        return $this->belongsTo(GutImage::class, 'image_id', 'id');
    }

    public function myEquipmentsWithMainGut()
    {
        return $this->hasMany(MyEquipment::class, 'main_gut_id', 'id');
    }

    public function myEquipmentsWithCrossGut()
    {
        return $this->hasMany(MyEquipment::class, 'cross_gut_id', 'id');
    }


    // csvファイルで登録
    // csvファイルの一行目はheaderとして下記を想定
    // 「name_ja,name_en,maker_id,image_id,need_posting_image」下記を想定
    public static function storeByCsv($request)
    {
        if (!$request->hasFile('csv_file')) {
            throw new Error('ファイルが存在しません');
        }

        // ファイルを取得
        $csvFile = $request->file('csv_file');

        // ファイルの拡張子をチェック
        if ($csvFile->extension() !== 'csv') {
            throw new Exception('ファイルの形式が不正なものです');
        }

        // csvを配列に変換。
        // file()はファイル全体を読み込んで配列に格納
        // str_getcsvはphpの関数でCSV文字列をパースして配列に格納
        $csvData = array_map('str_getcsv', file($csvFile));

        // csvの一行目がデータでなくheaderなので$csvDataから抽出・削除
        $header = array_shift($csvData);

        $validatedCsvData = [];

        $errors = [];

        // csvデータのバリデーションチェック
        foreach ($csvData as $row) {
            // array_combineでキーとバリューの連勝配列に変換し検証
            // $validator = Validator::make(array_combine($header, $row), RacketSeries::rules());
            $validator = Validator::make(array_combine($header, $row), self::rules());

            // バリデーションエラーがある場合の処理
            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorHeader = "[{$header[0]},{$header[1]},{$header[2]},{$header[3]},{$header[4]}]";
                $errorRow = "[{$row[0]},{$row[1]},{$row[2]},{$row[3]},{$row[4]}]";

                throw new Exception(
                    "{$errorHeader}{$errorRow}:{$errors}"
                );
            }

            array_push($validatedCsvData, $row);
        }

        // バリデーションを通過していれば登録
        if ($validatedCsvData) {
            foreach ($validatedCsvData as $data) {
                Gut::create([
                    'name_ja' => $data[0],
                    'name_en' => $data[1],
                    'maker_id' => $data[2],
                    'image_id' => $data[3],
                    'need_posting_image' => $data[4] ? $data[4] : true,
                ]);
            }
        }
    }
}
