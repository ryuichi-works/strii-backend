<?php

namespace App\Http\Controllers;

use App\Http\Requests\RacketSeriesRequests\RacketSeriesStoreByCsvRequest;
use Illuminate\Http\Request;
use App\Models\RacketSeries;

use App\Http\Requests\RacketSeriesRequests\RacketSeriesStoreRequest;
use App\Http\Requests\RacketSeriesRequests\RacketSeriesUpdateRequest;
use Error;
use Illuminate\Support\Facades\Validator;

class RacketSeriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // $racketSeries = RacketSeries::all();
            $racketSeries = RacketSeries::with('maker')->get();

            return response()->json($racketSeries, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RacketSeriesStoreRequest $request)
    {
        $validated_request = $request->validated();

        try {
            $racketSeries = RacketSeries::create($validated_request);

            return response()->json($racketSeries, 200);
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
            $racketSeries = RacketSeries::with('maker')->findOrFail($id);

            return response()->json($racketSeries, 200);
        } catch (ModelNotFoundException $e) {
            // データが見つからなかっただけならロギング不要
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
    public function update(RacketSeriesUpdateRequest $request, $id)
    {
        $validated_request = $request->validated();

        try {
            $racketSeries = RacketSeries::findOrFail($id);

            $racketSeries->name_ja = $validated_request['name_ja'];

            if ($racketSeries->name_en) $racketSeries->name_en = $validated_request['name_en'];

            $racketSeries->maker_id = $validated_request['maker_id'];

            if ($racketSeries->save()) {
                return response()->json([
                    'messages' => 'completed updating racketSeries',
                    'status' => 'ok'
                ], 200);
            }
        } catch (ModelNotFoundException $e) {
            // データが見つからなかっただけならロギング不要
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
        try {
            $racketSeries = RacketSeries::findOrFail($id);
            $racketSeries->delete();

            return response()->json([
                'massages' => 'deleted',
                'status' => 'ok'
            ], 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function storeByCsv(RacketSeriesStoreByCsvRequest $request)
    {
        try {
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

            // csvデータのバリデーションチェック
            foreach ($csvData as $row) {
                // array_combineでキーとバリューの連勝配列に変換し検証
                $validator = Validator::make(array_combine($header, $row), RacketSeries::rules());

                // バリデーションエラーがある場合の処理
                if ($validator->fails()) {
                    throw new Error($validator->errors());
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

                return response()->json('csvデータを登録しました', 200);
            }
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
        }
    }
}
