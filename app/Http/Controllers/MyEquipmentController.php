<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyEquipment;
use App\Http\Requests\MyEquipment\MyEquipmentStoreRequest;
use App\Http\Requests\MyEquipment\MyEquipmentUpdateRequest;

class MyEquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $my_equipment = MyEquipment::with(['user', 'mainGut', 'crossGut'])->get();
    
            return response()->json($my_equipment, 200);
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
    public function store(MyEquipmentStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $my_equipment = MyEquipment::create([
                'user_id'           => $validated['user_id'],
                'user_height'       => $validated['user_height'],
                'user_age'          => $validated['user_age'],
                'expefience_period' => $validated['experience_period'],
                'racket_id'         => $validated['racket_id'],
                'stringing_way'     => $validated['stringing_way'],
                'main_gut_id'       => $validated['main_gut_id'],
                'main_gut_guage'    => $validated['main_gut_guage'],
                'main_gut_tension'  => $validated['main_gut_tension'],
                'cross_gut_id'      => $validated['cross_gut_id'],
                'cross_gut_guage'   => $validated['cross_gut_guage'],
                'cross_gut_tension' => $validated['cross_gut_tension'],
                'new_gut_date'      => $validated['new_gut_date'],
                'change_gut_date'   => empty($validated['change_gut_date']) ? null : $validated['change_gut_date'],
                'comment'           => empty($validated['comment']) ? '' : $validated['comment']
            ]);
    
            if($my_equipment) {
                return response()->json('マイ装備を追加しました。', 200);
            }
        } catch(\Throwable $e) {
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
            $my_equipment = MyEquipment::with(['user', 'mainGut', 'crossGut'])->findOrFail($id);
    
            return response()->json($my_equipment, 200);
        } catch (\ModelNotFoundException $e) {
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
    public function update(MyEquipmentUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $my_equipment = MyEquipment::findOrFail($id);
    
            $my_equipment->user_height       = $validated['user_height'];
            $my_equipment->user_age          = $validated['user_age'];
            $my_equipment->experience_period = $validated['experience_period'];
            $my_equipment->racket_id         = $validated['racket_id'];
            $my_equipment->stringing_way     = $validated['stringing_way'];
            $my_equipment->main_gut_id       = $validated['main_gut_id'];
            $my_equipment->main_gut_guage    = $validated['main_gut_guage'];
            $my_equipment->main_gut_tension  = $validated['main_gut_tension'];
            $my_equipment->cross_gut_id      = $validated['cross_gut_id'];
            $my_equipment->cross_gut_guage   = $validated['cross_gut_guage'];
            $my_equipment->cross_gut_tension = $validated['cross_gut_tension'];
            $my_equipment->new_gut_date      = $validated['new_gut_date'];
            $my_equipment->change_gut_date   = empty($validated['change_gut_date']) ? null : $validated['change_gut_date'];
            $my_equipment->comment           = empty($validated['comment']) ? '' : $validated['comment'];

            if($my_equipment->save()) {
                return response()->json('マイ装備の情報を更新しました', 200);
            }
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
            $my_equipment = MyEquipment::findOrFail($id);
    
            $my_equipment->delete();
    
            return response()->json("id:{$my_equipment->id}のマイ装備を削除しました", 200);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch(\Throwable $e) {
            \Log::error($e);

            throw $e;
        }
    }
}
