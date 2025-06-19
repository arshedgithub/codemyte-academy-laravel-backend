<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all users for now; adjust as needed for your app's logic
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
            'title' => ['sometimes', 'string', 'max:255'],
            'instructor' => ['sometimes', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_free' => ['sometimes', 'boolean'],
            'is_instructor_led' => ['sometimes', 'boolean'],
            'description' => ['sometimes', 'string'],
            'duration' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:100'],
            'topics' => ['nullable', 'array'],
            'topics.*' => ['string', 'max:255'],
            'syllabus_pdf' => ['nullable', 'string', 'max:255'],
        ];
    }
}
