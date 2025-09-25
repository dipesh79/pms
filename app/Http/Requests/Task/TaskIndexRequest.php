<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
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
            /**
             * The page field is nullable but must be integer
             * @var integer $page
             * @example 1
             */
            'page' => ['nullable', 'integer'],
            /**
             * The size field is nullable but must be integer
             * @var integer $size
             * @example 5
             */
            'size' => ['nullable', 'integer'],
            /**
             * The global search field is nullable but must be string
             * @var string $search
             * @example "Project Name"
             */
            'search' => ['nullable', 'string']
        ];
    }

    public function cacheKey(): string
    {
        $filters = array_filter($this->validated(), fn($v) => !is_null($v));
        ksort($filters);
        return 'tasks:' . md5(json_encode($filters));
    }
}
