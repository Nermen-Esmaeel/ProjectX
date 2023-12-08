<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ["attach_link", "subtask_id",];

    public function subtasks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'subtasks_attachments', 'attachment_id', 'subtask_id');
    }
}
