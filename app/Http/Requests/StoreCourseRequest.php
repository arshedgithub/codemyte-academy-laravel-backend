<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Course;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {  
        return [
            'title' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:6', 'unique:courses'],
            'instructor' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_free' => ['required', 'boolean'],
            'is_instructor_led' => ['required', 'boolean'],
            'description' => ['required', 'string'],
            'duration' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:100'],
            'topics' => ['nullable', 'array'],
            'topics.*' => ['string', 'max:255'],
            'syllabus_pdf' => ['nullable', 'string', 'max:255'],
        ];
    }
}
