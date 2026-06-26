<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role',
        'joined_at',
    ];
}