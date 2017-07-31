<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlaceRequest extends FormRequest
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
            'street'          => ['nullable', 'max:191'],
            'city'            => ['nullable', 'max:191'],
            'state'           => ['nullable', 'size:2'],
            'zip'             => ['nullable', 'between:4,10'],
            'facebook_id'     => ['nullable', 'unique:places,facebook_id,' . $this->input('id')],
            'meetup_id'       => ['nullable', 'unique:places,meetup_id,' . $this->input('id')],
            'profile'         => ['nullable', 'array'],
            'profile.phone'   => ['nullable'],
            'profile.website' => ['nullable', 'url'],
            'profile.email'   => ['nullable', 'email'],
        ];
    }
}
