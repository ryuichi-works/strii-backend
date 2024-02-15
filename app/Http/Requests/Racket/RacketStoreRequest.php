<?php

namespace App\Http\Requests\Racket;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\AgreementConfirmation;

class RacketStoreRequest extends FormRequest
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
            'image_id' => ['sometimes', 'integer', 'exists:racket_images,id'],
            'need_posting_image' => ['required', 'boolean'],
            'posting_user_id' => ['required', 'integer', 'exists:users,id'],
            'series_id' => ['nullable', 'integer', 'exists:racket_series,id'],
            'head_size' => ['required', 'integer', 'max:150'],
            'pattern' => ['required', 'string', 'max:15'],
            'weight' => ['nullable', 'integer', 'max:400'],
            'balance' => ['nullable', 'integer', 'max:400'],
            'agreement' => ['required', 'boolean', new AgreementConfirmation],
        ];
    }
}
