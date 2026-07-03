<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProjectMemberStoreRequrest extends FormRequest
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
        // Lấy object project hoặc ID của project từ URL Route
        $project = $this->route('project');
        $projectId = is_object($project) ? $project->id : $project;

        return [
            'user_id' => 'required|exists:users,id|unique:project_members,user_id,NULL,id,project_id,' . $projectId,
            'project_id' => 'required|exists:projects,id',
            'role' => 'required|in:owner,member,admin,viewer',
        ];
    }

    // Định nghĩa các thông báo lỗi tùy chỉnh
    public function messages()
    {
        return [
            'user_id.required' => 'User is required',
            'user_id.exists' => 'User does not exist',
            'user_id.unique' => 'User is already a member of this project',
            'project_id.required' => 'Project ID is required',
            'project_id.exists' => 'Project ID does not exist',
            'role.required' => 'Role is required',
            'role.in' => 'Role must be one of the following: owner, member, admin, viewer',
        ];
    }
}
