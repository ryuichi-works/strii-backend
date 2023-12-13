<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maker;
use App\Http\Requests\Maker\MakerStoreRequest;
use App\Http\Requests\Maker\MakerUpdateRequest;

class MakerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->only(['show', 'store', 'update', 'destroy']);
    }
    
    public function index()
    {
        try {
            $makers = Maker::all();

            return response()->json($makers, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $maker = Maker::findOrFail($id);

            return response()->json($maker, 200);
        } catch (ModelNotFoundException $e) {
            // データが見つからなかっただけならロギング不要
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    // public function store(Request $request)
    public function store(MakerStoreRequest $request)
    {
        $validated_request = $request->validated();
        try {
            $maker = Maker::create($validated_request);

            return response()->json($maker, 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }

    public function update(MakerUpdateRequest $request, $id)
    {
        $validated_request = $request->validated();

        try {
            $maker = Maker::findOrFail($id);

            $maker->name_ja = $validated_request['name_ja'];
            $maker->name_en = $validated_request['name_en'];

            if(!$maker->save()) {
                throw new Execption('failed saving data');
            }

            return response()->json([
                'messages' => 'completed updating maker',
                'status' => 'ok'
            ], 200);
        } catch (ModelNotFoundException $e) {
            // データが見つからなかっただけならロギング不要
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);
            
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $maker = Maker::findOrFail($id);
            $maker->delete();

            return response()->json([
                'massages' => 'deleted',
                'status' => 'ok'
            ], 200);
        } catch (\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }
}
