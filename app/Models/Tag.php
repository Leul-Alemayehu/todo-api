<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }
}
