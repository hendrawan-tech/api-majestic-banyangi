<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'code' => ['required', 'max:255', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'user_id' => ['required', 'exists:users,id'],
            'payment_id' => ['required', 'exists:payments,id'],
            'quantity' => ['required', 'max:255', 'string'],
            'total' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'status' => [
                'required',
                'in:menunggu konfirmasi,pembayaran dikonfirmasi,menunggu pembayaran,dibatalkan,selesai',
            ],
        ];
    }
}
