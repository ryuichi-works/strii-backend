<?php

namespace App\Http\Requests\RacketImage;

use Illuminate\Foundation\Http\FormRequest;

class RacketImageStoreRequest extends FormRequest
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
            'file' => ['required', 'file', 'image', 'mimes:jpeg,png'],
            'title' => ['required', 'max:30'],
            'maker_id' => ['required', 'integer', 'exists:makers,id'],
        ];
    }
}
