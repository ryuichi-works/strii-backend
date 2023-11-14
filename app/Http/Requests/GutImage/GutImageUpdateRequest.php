<?php

namespace App\Http\Requests\GutImage;

use Illuminate\Foundation\Http\FormRequest;

class GutImageUpdateRequest extends FormRequest
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
            'file' => ['file', 'image', 'mimes:jpeg,png'],
            'title' => ['max:30']
        ];
    }
}
