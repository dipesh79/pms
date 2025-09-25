<?php

namespace App\Http\Requests\Authentication;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            /**
             * Email of the user
             * @var string $email
             * @example user@gmail.com
             */
            'email' => ['required', 'email', 'exists:users,email'],
            /**
             * Password of the user
             * @var string $password
             * @example password
             */
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
