<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maker;

class MakerController extends Controller
{
    public function index()
    {
        $makers = Maker::all();

        return response()->json($makers, 200);
    }

    public function show($id)
    {
        $maker = Maker::where('id', $id)->get();

        return response()->json($maker, 200);
    }

    public function store(Request $request)
    {
        $validated_request = $request->validate([
            'name_ja' => ['required','string', 'max:30' ],
            'name_en' => ['string', 'max:30']
        ]);

        $maker = Maker::create($validated_request);

        return response()->json($maker, 200);
    }

    public function update(Request $request, $id)
    {
        $validated_request = $request->validate([
            'name_ja' => ['required','string', 'max:30' ],
            'name_en' => ['string', 'max:30']
        ]);
        
        try{
            $maker = Maker::find($id);
    
            $maker->name_ja = $validated_request['name_ja'];
            $maker->name_en = $validated_request['name_en'];
    
            $maker->save();
    
            return response()->json([
                'messages' => 'completed updating maker',
                'status' => 'ok'
            ], 200);
        }  catch (\Throwable $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => 'Bad Request'
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $maker = Maker::find($id);
            $maker->delete();
    
            return response()->json([
                'massages' => 'destroyed maker',
                'status' => 'ok'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'status' => 'Bad request'
            ], 400);
        }
    }   
}
