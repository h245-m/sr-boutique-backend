<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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

    public function rules(): array
    {
        
        return [
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'city' => 'string|max:255|in:' . implode(',', $this->citits),
            'postal_code' => 'digits:5|integer',
            'phone' => 'string|phone|max:255',
            'email' => 'string|email|max:255',
        ];
    }
}
