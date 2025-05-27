<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SessionMM;
use App\Models\User;

class Feedback extends Model
{
    protected $table = 'feedback'; // ou 'feedbacks' si vous avez nommé la table au pluriel

    protected $fillable = [
        'mentor_session_id',
        'author_id',
        'rating',
        'comment',
    ];

    public function session()
    {
        return $this->belongsTo(SessionMM::class, 'mentor_session_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
