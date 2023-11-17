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
            'equipment_id' => ['required', 'integer', 'exists:my_equipments,id'],
            'match_rate' => ['required','numeric'],
            'pysical_durability' => ['required','numeric'],
            'performance_durability' => ['required','numeric'],
            'review' => ['nullable','string', 'max:500'],
        ];
    }
}