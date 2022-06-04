<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'title' => ['required', 'max:255', 'string'],
            'category' => ['required', 'in:Destinasi,Event'],
            'address' => ['required', 'max:255', 'string'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'price' => ['nullable', 'numeric'],
        ];
    }
}
