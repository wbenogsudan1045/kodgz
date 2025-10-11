<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'description',
        'user_id',
        'password', // add this
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'board_user')->withTimestamps();
    }

    // Relationship with StickyNote
    public function stickyNotes()
    {
        return $this->hasMany(\App\Models\StickyNote::class, 'board_id');
    }

    

}
