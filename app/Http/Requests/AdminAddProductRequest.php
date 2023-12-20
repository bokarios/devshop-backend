<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAddProductRequest extends FormRequest
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
            'name'                  => 'required',
            'short_description'     => 'sometimes',
            'description'           => 'sometimes',
            'price'                 => 'required|numeric',
            'image'                 => 'required|image',
            'featured'              => 'sometimes|boolean',
            'category_id'           => 'required|numeric|exists:categories,id'
        ];
    }
}
