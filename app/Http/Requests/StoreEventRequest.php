<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => ['required', 'string', 'max:191'],
            'facebook_id' => ['nullable', 'numeric','unique:events'],
            'place_id'    => ['required', 'numeric'],
            'start_time'  => ['required', 'date_format:Y-m-d H:i:s'],
            'end_time'    => ['nullable', 'after:start_time', 'date_format:Y-m-d H:i:s'],
            'description' => ['nullable', 'max:1000']
        ];
    }
}
