<x-mail::message>
# New Task Assigned

Hello {{ $user->name }},

A new task has been assigned to you:

**Task Title:** {{ $task->title }}

Please complete this task before the due date.

Thanks,
{{ config('app.name') }}
</x-mail::message>
