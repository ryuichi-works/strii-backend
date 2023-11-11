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
}
