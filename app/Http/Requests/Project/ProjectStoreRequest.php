<?php

namespace App\Http\Requests\Project;

use App\Enums\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role === RoleEnum::ADMIN->value;
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
             * Title
             *
             * @var string $title
             *
             * @example "New Project"
             */
            'title' => ['required', 'string', 'max:255'],
            /**
             * Description
             *
             * @var string $description
             *
             * @example "This is a new project"
             */
            'description' => ['required', 'string'],
            /**
             * Start Date
             *
             * @var string $start_date
             *
             * @example "2023-10-01"
             */
            'start_date' => ['required', 'date'],
            /**
             * End Date
             *
             * @var string $end_date
             *
             * @example "2023-12-31"
             */
            'end_date' => ['required', 'date', 'after_or_equal:start_date']
        ];
    }
}
