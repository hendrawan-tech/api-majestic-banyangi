<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
    static public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required'],
        ];
    }

    static public function perbarui()
    {
        return [
            'name' => ['required', 'max:255', 'string'],
            'phone_number' => ['required'],
            'profile_photo_path' => ['nullable', 'image', 'max:1024'],
            'password' => ['nullable'],
        ];
    }
}
