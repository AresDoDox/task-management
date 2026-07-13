<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    // Relationships
    // Một project member thuộc về một user (Owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Một project member thuộc về một project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
