<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255|in:' . implode(',', $this->citits),
            'postal_code' => 'required|digits:5|integer',
            'phone' => 'required|string|phone|max:255',
            'email' => 'required|string|email|max:255',
        ];
    }
}
