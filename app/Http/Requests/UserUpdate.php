<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdate extends FormRequest
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

            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|string|unique:users,email',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string',
            'nationality' => 'nullable|string',
            'department' => 'nullable|string',
            'designation' => 'nullable|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:10000|mimes:doc,docx',
            'country' => 'nullable|string',
            'onteak' => 'boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' =>false,
            'errors' => $validator->errors()
        ]));

    }
}
