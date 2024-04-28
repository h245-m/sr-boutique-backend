<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:1000',
            'long_description' => 'required|string|max:6000',
            'quantity' => 'required|integer|min:1',
            'live' => 'required|boolean',
            'price' => 'required|min:1|max:99998.99|numeric|decimal:0,2',
            'discount' => 'required|max:99999.99|numeric|decimal:0,2|lt:price',
            'expires_at' => 'date|format:Y-m-d H:m:s i|after:now',
            'sku' => 'required|string|max:255|unique:products',
            'category_id' => 'required|integer|min:1|exists:categories,id',
            'image' => 'array|max:1',
            'image.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'additional_images' => 'array|max:20',
            'additional_images.*' => 'image|mimes:jpeg,jpg,png|max:2048',
            'colors' => 'required|array|max:20',
            'colors.*' => 'string|max:255|distinct|hex_color',
            'sizes' => 'required|array|distinct',
            'sizes.*' => ['string', 'max:255', 'distinct', 'regex:^(S|M|L|X*L)$^i'],
        ];
    }
}
