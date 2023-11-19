<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ["title", "type", "start_date", "end_date", "time_spent", "description", "priority", "status", "project_id", "user_id"];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subtask()
    {
        return $this->hasMany(Subtask::class);
    }
}
