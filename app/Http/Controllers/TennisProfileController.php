<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TennisProfile;
use App\Http\Requests\TennisProfile\TennisProfileStoreRequest;
use App\Http\Requests\TennisProfile\TennisProfileUpdateRequest;

class TennisProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tennis_profiles = TennisProfile::with(['user', 'racket'])->get();

            return response()->json($tennis_profiles, 200);
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
    public function store(TennisProfileStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            $tennis_profile = TennisProfile::create([
                'user_id'           => $validated['user_id'],
                'gender'            => $validated['gender'],
                'my_racket_id'      => empty($validated['my_racket_id']) ? null : $validated['my_racket_id'],
                'grip_form'         => $validated['grip_form'],
                'height'            => $validated['height'],
                'age'               => $validated['age'],
                'physique'          => $validated['physique'],
                'experience_period' => $validated['experience_period'],
                'frequency'         => $validated['frequency'],
                'play_style'        => $validated['play_style'],
                'favarit_shot'      => $validated['favarit_shot'],
                'weak_shot'         => $validated['weak_shot'],
            ]);

            if ($tennis_profile) {
                return response()->json('テニスプロフィールと登録しました', 200);
            }
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
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
            $tennis_profile = TennisProfile::with(['user', 'racket'])->get();

            return response()->json($tennis_profile, 200);
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
    public function update(TennisProfileUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        
        try {
            $tennis_profile = TennisProfile::findOrFail($id);

            $tennis_profile->gender            = $validated['gender'];
            $tennis_profile->my_racket_id      = empty($validated['my_racket_id']) ? null : $validated['my_racket_id'];
            $tennis_profile->grip_form         = $validated['grip_form'];
            $tennis_profile->height            = $validated['height'];
            $tennis_profile->age               = $validated['age'];
            $tennis_profile->physique          = $validated['physique'];
            $tennis_profile->experience_period = $validated['experience_period'];
            $tennis_profile->frequency         = $validated['frequency'];
            $tennis_profile->play_style        = $validated['play_style'];
            $tennis_profile->favarit_shot      = $validated['favarit_shot'];
            $tennis_profile->weak_shot         = $validated['weak_shot'];

            if ($tennis_profile->save()) {
                return response()->json('テニスプロフィールを更新しました', 200);
            }
        } catch (\ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            \Log::error($e);

            return $e;
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
        //
    }
}
