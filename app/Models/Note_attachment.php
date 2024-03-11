<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note_attachment extends Model
{
    use HasFactory;
    protected $fillable = ['attachment'];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
