<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteLink extends Model
{
    use HasFactory;

    protected $fillable = ['note_a_id', 'note_b_id', 'relation_type'];

    public function noteA()
    {
        return $this->belongsTo(StickyNote::class, 'note_a_id');
    }

    public function noteB()
    {
        return $this->belongsTo(StickyNote::class, 'note_b_id');
    }
}