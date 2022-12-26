<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookingCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "arrive_date" => "required|regex:/^\d{4}-\d{1,2}-\d{1,2}/",
            "user_id" => "sometimes|nullable|exists:users,id"
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ]));
    }
}
