<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
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
            'name'            => ['required', 'max:191'],
            'facebook_id'     => ['nullable', 'unique:places'],
            'meetup_id'       => ['nullable', 'unique:places'],
            'street'          => ['nullable'],
            'profile'         => ['array'],
            'profile.website' => ['url'],
            'profile.email'   => ['email'],
        ];
    }
}
