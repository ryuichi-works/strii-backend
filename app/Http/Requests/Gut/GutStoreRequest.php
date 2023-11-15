<?php

namespace App\Http\Requests\Gut;

use Illuminate\Foundation\Http\FormRequest;

class GutStoreRequest extends FormRequest
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
            'name_ja' => ['required', 'max:30'],
            'name_en' => ['max:30'],
            'maker_id' => ['required', 'integer', 'exists:makers,id'],
            'image_id' => ['sometimes', 'integer', 'exists:gut_images,id'],
            'need_posting_image' => ['required','boolean']
        ];
    }
}
