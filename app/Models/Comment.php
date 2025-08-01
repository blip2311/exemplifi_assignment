<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'task_id',
        'content',
        'author_name',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
