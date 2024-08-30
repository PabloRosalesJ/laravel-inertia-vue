<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client' => ['required', 'numeric', 'exists:clients,id'],
            'productsList' => ['required', 'array'],
            'productsList.*.product' => ['required', 'numeric', 'exists:products,id'],
            'productsList.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }
}
