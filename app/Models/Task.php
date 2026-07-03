<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'category_id',
        'due_date',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    // Scopes

    // Scope để tìm kiếm tasks theo tiêu đề
    public function scopeSearchTask($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    // Scope để lọc tasks theo trạng thái
    public function scopeFilterTaskByStatus($query, $status)
    {
        if (is_null($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    // Scope để lọc tasks theo mức độ ưu tiên
    public function scopeFilterTaskByPriority($query, $priority)
    {
        if (is_null($priority)) {
            return $query;
        }

        return $query->where('priority', $priority);
    }

    // Scope để lọc tasks theo người được giao
    public function scopeFilterTaskByAssignedUser($query, $userId)
    {
        if (is_null($userId)) {
            return $query;
        }

        return $query->where('assigned_to', $userId);
    }

    // Scope để lọc tasks theo danh mục
    public function scopeFilterTaskByCategory($query, $categoryId)
    {
        if (is_null($categoryId)) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    // Scope để lọc tasks theo ngày hết hạn
    public function scopeFilterTaskByDueDate($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('due_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('due_date', '<=', $to);
        }
        return $query;
    }

    // Scope để sắp xếp tasks theo [created_at, due_date, status, priority, title]
    public function scopeSortTask($query, $sort = 'created_at', $direction = 'desc')
    {
        $allowedSorts = ['created_at', 'due_date', 'status', 'priority', 'title'];
        $sortField = in_array($sort, $allowedSorts) ? $sort : 'created_at';

        return $query->orderBy($sortField, $direction);
    }
}