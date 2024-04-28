<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShippingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    private $citits = [
        "Asir",
        "Al Bahah",
        "Al Jawf",
        "Al Madinah",
        "Al-Qassim",
        "Eastern Province",
        "Ha'il",
        "Jizan",
        "Makkah",
        "Najran",
        "Northern Borders",
        "Riyadh",
        "Tabuk"
    ];

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
            'type' => 'required|string|in:Category,Equal,Product,City',
            'value' => ['required', 'string' ,
                            Rule::when( 
                                $this->type == 'Category', 
                                'exists:categories,id', 
                            ), 
                            Rule::when( 
                                $this->type == 'Equal', 
                                'integer', 
                            ), 
                            Rule::when( 
                                $this->type == 'Product', 
                                'exists:products,sku', 
                            ), 
                            Rule::when( 
                                $this->type == 'City', 
                                "string|in:".implode(',', $this->citits),
                            ), 
                        ],
            'price' => 'required|integer|min:0|max:999999',
        ];
    }
}
