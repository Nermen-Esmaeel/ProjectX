<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = ["attach_link", "subtask_id",];

    public function subtask()
    {
        return $this->belongsTo(Subtask::class);
    }
}
