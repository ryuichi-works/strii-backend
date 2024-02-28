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
            RacketSeries::storeByCsv($request);

            return response()->json('csvデータを登録しました', 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
        }
    }
}
