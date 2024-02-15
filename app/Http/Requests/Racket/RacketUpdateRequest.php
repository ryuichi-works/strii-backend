<?php

namespace App\Http\Requests\Racket;

use Illuminate\Foundation\Http\FormRequest;

class RacketUpdateRequest extends FormRequest
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
            'name_ja' => ['nullable', 'string', 'max:30'],
            'name_en' => ['nullable', 'string','max:30'],
            'maker_id' => ['nullable', 'integer', 'exists:makers,id'],
            'image_id' => ['nullable', 'integer', 'exists:racket_images,id'],
            'need_posting_image' => ['nullable','boolean'],
            'posting_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'series_id' => ['nullable', 'integer', 'exists:racket_series,id'],
            'head_size' => ['nullable', 'integer', 'max:150'],
            'pattern' => ['nullable', 'string', 'max:15'],
            'weight' => ['nullable', 'integer', 'max:400'],
            'balance' => ['nullable', 'integer', 'max:400'],
        ];
    }
}
