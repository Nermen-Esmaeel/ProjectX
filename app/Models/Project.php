<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable =
    [
      'title',
       'type',
       'start_date',
       'end_date',
       'description',
        'status',
        'image',

    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projects_users', 'project_id', 'user_id');
    }
}
