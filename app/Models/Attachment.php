<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ['attach_link'];

    protected $hidden = ['pivot'];

    public function subtasks(): BelongsToMany
    {
        return $this->belongsToMany(Subtask::class, 'subtasks_attachments', 'attachment_id', 'subtask_id');
    }
}
