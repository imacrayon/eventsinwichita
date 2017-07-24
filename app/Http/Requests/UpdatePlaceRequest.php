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
            'name'            => ['filled', 'max:191'],
            'street'          => ['filled', 'max:191'],
            'city'            => ['filled', 'max:191'],
            'state'           => ['filled', 'size:2'],
            'zip'             => ['filled', 'between:4,10'],
            'facebook_id'     => ['nullable', 'unique:places,facebook_id,' . $this->input('id')],
            'meetup_id'       => ['nullable', 'unique:places,meetup_id,' . $this->input('id')],
            'profile'         => ['filled', 'array'],
            'profile.website' => ['url'],
            'profile.email'   => ['email'],
        ];
    }
}
