<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'priority', 'start_date', 'end_date', 'desciption','owner_id', 'user_id', 'task_id'];


    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subtasks_attachments', 'subtask_id', 'attachment_id');
    }
}
