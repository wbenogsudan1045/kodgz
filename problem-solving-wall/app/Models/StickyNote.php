<?php

// app/Models/StickyNote.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StickyNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'user_id',
        'title',
        'content',
        'color',
        'attachment',
        'x',
        'y',
        'is_archived'
    ];



    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? asset('storage/' . $this->attachment) : null;
    }


    // A note belongs to a board
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    // A note belongs to a user (creator)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
