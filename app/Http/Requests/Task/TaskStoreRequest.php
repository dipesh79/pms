<?php

namespace App\Http\Requests\Task;

use App\Enums\RoleEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role === RoleEnum::MANAGER->value;
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
             * Title of the task.
             *
             * @var string $title
             *
             * @example "Design Homepage"
             */
            'title' => ['required', 'string', 'max:255'],
            /**
             * Description of the task.
             *
             * @var string|null $description
             *
             * @example "Create a modern and responsive homepage design."
             */
            'description' => ['required', 'string'],
            /**
             * Status of the task.
             *
             * @var string $status
             *
             * @example "pending"
             */
            'status' => ['required', 'string', Rule::enum(TaskStatusEnum::class)],
            /**
             * Due date of the task.
             *
             * @var string|null $due_date
             *
             * @example "2023-12-31"
             */
            'due_date' => ['required', 'date', 'after_or_equal:today']
        ];
    }
}
