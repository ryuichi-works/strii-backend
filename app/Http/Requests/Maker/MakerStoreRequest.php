<?php

namespace App\Http\Requests\Maker;

use Illuminate\Foundation\Http\FormRequest;

class MakerStoreRequest extends FormRequest
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
            'name_ja' => ['required', 'string', 'max:30'],
            'name_en' => ['string', 'max:30']
        ];
    }
}
