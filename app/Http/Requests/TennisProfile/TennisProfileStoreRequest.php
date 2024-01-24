<?php

namespace App\Http\Requests\TennisProfile;

use Illuminate\Foundation\Http\FormRequest;

class TennisProfileStoreRequest extends FormRequest
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
            'user_id'           => ['required', 'integer', 'exists:users,id', 'unique:tennis_profiles,user_id'],
            'gender'            => ['required', 'string', 'max:15'],
            'my_racket_id'      => ['sometimes', 'integer', 'exists:rackets,id'],
            'grip_form'         => ['required', 'string', 'max:15'],
            'height'            => ['required', 'string', 'max:20'],
            'age'               => ['required', 'string', 'max:15'],
            'physique'          => ['required', 'string', 'max:15'],
            'experience_period' => ['required', 'integer', 'max:120'],
            'frequency'         => ['required', 'string', 'max:20'],
            'play_style'        => ['required', 'string', 'max:20'],
            'favarit_shot'      => ['required', 'string', 'max:20'],
            'weak_shot'         => ['required', 'string', 'max:20'],
        ];
    }
}
