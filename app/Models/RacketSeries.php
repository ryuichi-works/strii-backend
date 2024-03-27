<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Error;
use Illuminate\Support\Facades\Validator;

class RacketSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ja',
        'name_en',
        'maker_id',
    ];

    public static function rules()
    {
        return [
            'name_ja' => ['required', 'string', 'max:30'],
            'name_en' => ['string', 'max:30'],
            'maker_id' => ['required', 'integer', 'exists:makers,id'],
        ];
    }

    public function rackets()
    {
        return $this->hasMany(Racket::class, 'series_id', 'id');
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public static function storeByCsv($request)
    {
        if (!$request->hasFile('csv_file')) {
            throw new Error('ファイルが存在しません');
        }

        // ファイルを取得
        $csvFile = $request->file('csv_file');

        // ファイルの拡張子をチェック
        if ($csvFile->extension() !== 'csv') {
            throw new Error('ファイルの形式が不正なものです');
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
            $validator = Validator::make(array_combine($header, $row), RacketSeries::rules());

            // バリデーションエラーがある場合の処理
            if ($validator->fails()) {
                $errors = $validator->errors();
                
                throw new Error("[{$header[0]},{$header[1]},{$header[2]}][{$row[0]},{$row[1]},{$row[2]}]:{$errors}");
            }

            array_push($validatedCsvData, $row);
        }

        // バリデーションを通過していれば登録
        if ($validatedCsvData) {
            foreach ($validatedCsvData as $data) {
                RacketSeries::create([
                    'name_ja' => $data[0],
                    'name_en' => $data[1],
                    'maker_id' => $data[2],
                ]);
            }
        }
    }
}
