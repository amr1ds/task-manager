<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Право комментировать проверяется в контроллере через Policy.
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Комментарий не может быть пустым.',
            'body.max' => 'Комментарий слишком длинный (макс. :max символов).',
        ];
    }
}
