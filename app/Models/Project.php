<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'slug', 'status'];

    // Relationships
    // Định nghĩa quan hệ giữa Project và User (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Định nghĩa quan hệ giữa Project và ProjectMember
    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    // Định nghĩa quan hệ giữa Project và Task
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    //  Định nghĩa quan hệ giữa Project và Category
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // Định nghĩa quan hệ giữa Project và ActivityLog
    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Kiểm tra xem người dùng có phải là thành viên của dự án hay không
    public function isMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    // Kiểm tra xem người dùng có phải là chủ sở hữu của dự án hay không
    public function isOwner(User $user)
    {
        return $this->owner_id === $user->id;
    }
}
