<?php

namespace App\Http\Requests\MyEquipment;

use Illuminate\Foundation\Http\FormRequest;

class MyEquipmentStoreRequest extends FormRequest
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
            'user_id'           => ['required', 'integer', 'exists:users,id'],
            'user_height'       => ['required', 'string', 'max:20'],
            'user_age'          => ['required', 'string', 'max:20'],
            'experience_period' => ['required', 'integer'],
            'racket_id'         => ['required', 'integer', 'exists:rackets,id'],
            'stringing_way'     => ['required', 'string', 'max:20'],
            'main_gut_id'       => ['required', 'integer', 'exists:guts,id'],
            'main_gut_guage'    => ['required', 'numeric',],
            'main_gut_tension'  => ['required', 'integer'],
            'cross_gut_id'      => ['required', 'integer', 'exists:guts,id'],
            'cross_gut_guage'   => ['required', 'numeric',],
            'cross_gut_tension' => ['required', 'integer'],
            'new_gut_date'      => ['required', 'date'],
            'change_gut_date'   => ['nullable','date'],
            'comment'           => ['nullable','string', 'max:500']
        ];
    }
}
