<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kiểm tra quyền - có được submit form này không?
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ];
    }

    // Định nghĩa các thông báo lỗi tùy chỉnh
    public function messages()
    {
        return [
            'name.required' => 'Project name is required',
            'name.string' => 'Project name must be a string',
            'name.max' => 'Project name must not exceed 100 characters',
            'description.string' => 'Description must be a string',
        ];
    }
}