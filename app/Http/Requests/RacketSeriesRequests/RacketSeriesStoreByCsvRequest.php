<?php

namespace App\Http\Requests\RacketSeriesRequests;

use Illuminate\Foundation\Http\FormRequest;

class RacketSeriesStoreByCsvRequest extends FormRequest
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
            'csv_file' => ['required', 'file', 'mimes:csv'],
        ];
    }
}
