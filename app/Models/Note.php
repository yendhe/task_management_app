<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = ['subject','note'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    // Relationship with attachments
    public function attachments()
    {
        return $this->hasMany(Note_attachment::class);
    }
}
