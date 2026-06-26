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
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function activities()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper methods
    public function isMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isOwner(User $user)
    {
        return $this->owner_id === $user->id;
    }
}
