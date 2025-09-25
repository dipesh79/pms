<?php

namespace App\Http\Requests\Authentication;

use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
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
             * Name Of The User
             *
             * @var string $name
             *
             * @example User
             */
            'name' => ['required', 'string', 'max:255'],
            /**
             * Email Address Of The User
             *
             * @var string $email
             *
             * @example user@gmail.com
             */
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            /**
             * Phone Number Of The User
             *
             * @var string $phone
             *
             * @example +1234567890
             */
            'phone' => ['required', 'string', 'max:15', 'unique:users,phone'],
            /**
             * Password For The User Account
             *
             * @var string $password
             *
             * @example password
             */
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            /**
             * Password Confirmation
             *
             * @var string $password_confirmation
             *
             * @example password
             */
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password']
        ];
    }
}
