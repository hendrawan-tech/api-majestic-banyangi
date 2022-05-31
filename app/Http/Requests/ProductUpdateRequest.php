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
    public function rules()
    {
        return [
            'title' => ['required', 'max:255', 'string'],
            'category' => ['required', 'in:destinasi,event'],
            'address' => ['required', 'max:255', 'string'],
            'description' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'price' => ['nullable', 'numeric'],
        ];
    }
}
