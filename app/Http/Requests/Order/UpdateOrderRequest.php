<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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

     const Pending = 0;
     const In_Progress = 1;
     const Cancelled = 2;
     const Delayed = 3;
     const Shipped = 4;
     const Out_For_Delivery = 5;
     const Delivered = 6;
     const Returned_To_Sender = 7;

    public function rules(): array
    {
        return [
            'status' => 'required|in:In_Progress,Cancelled,Delayed,Shipped,Out_For_Delivery,Delivered,Returned_To_Sender',
        ];
    }
}
