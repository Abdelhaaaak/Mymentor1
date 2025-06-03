<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'session_id',
        'rating',
        'comment'
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }

    public function session()
    {
        return $this->belongsTo(SessionMM::class, 'session_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
