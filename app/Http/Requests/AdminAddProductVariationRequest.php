<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAddProductVariationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'price'             => 'required|numeric',
            'image'             => 'required|image',
            'sizes'             => 'sometimes|json',
            'color_id'          => 'required|exists:colors,id',
            'product_id'          => 'required|exists:products,id'
        ];
    }
}
