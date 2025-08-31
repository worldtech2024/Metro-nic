<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use App\Rules\ValidatePasswordUpdate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function failedValidation(Validator $validator)
{
    $response = response()->json([
        'status' => false,
        'message' => implode("\n ", $validator->errors()->all()),
    ], 422);

    throw new HttpResponseException($response);
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth('sanctum')->user();
        return [
            'name' => 'sometimes|string',
            'username' => 'sometimes|string|unique:admins,username,' . $user->id,
            "image" => 'sometimes',

            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|array',
            'phone' => [
                'sometimes',
                'unique:admins,phone,' . $user->id,
            ],

            'current_password' => [
                'nullable',
                'string',
                new ValidatePasswordUpdate(),
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                new ValidatePasswordUpdate(),
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }


}
