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
}
