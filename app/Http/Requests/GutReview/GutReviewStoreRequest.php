<?php

namespace App\Http\Requests\GutReview;

use Illuminate\Foundation\Http\FormRequest;

class GutReviewStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'match_rate'             => ['required','numeric'],
            'pysical_durability'     => ['required','numeric'],
            'performance_durability' => ['required','numeric'],
            'review'                 => ['nullable','string', 'max:500'],

            // 登録済みのmy_equpipmentでレビューを書いた場合使用
            'equipment_id' => ['nullable', 'integer', 'exists:my_equipments,id'],

            // 未登録のmy_equpipmentでレビューを投稿しようとした場合
            // gut_reviewと同時にmy_equipment新規登録するために使用
            'need_creating_my_equipment' => ['nullable', 'boolean'],
            'user_height'       => ['required_if:need_creating_my_equipment,true', 'string', 'max:20'],
            'user_age'          => ['required_if:need_creating_my_equipment,true', 'string', 'max:20'],
            'experience_period' => ['required_if:need_creating_my_equipment,true', 'integer'],

            'racket_id'         => ['required_if:need_creating_my_equipment,true', 'integer', 'exists:rackets,id'],
            'stringing_way'     => ['required_if:need_creating_my_equipment,true', 'string', 'max:20'],
            'main_gut_id'       => ['required_if:need_creating_my_equipment,true', 'integer', 'exists:guts,id'],
            'main_gut_guage'    => ['required_if:need_creating_my_equipment,true', 'numeric',],
            'main_gut_tension'  => ['required_if:need_creating_my_equipment,true', 'integer'],
            'cross_gut_id'      => ['required_if:need_creating_my_equipment,true', 'integer', 'exists:guts,id'],
            'cross_gut_guage'   => ['required_if:need_creating_my_equipment,true', 'numeric',],
            'cross_gut_tension' => ['required_if:need_creating_my_equipment,true', 'integer'],
            'new_gut_date'      => ['required_if:need_creating_my_equipment,true', 'date'],
        ];
    }
}
