<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;

class SessionMM extends Model
{
    protected $table = 'mentor_sessions';

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'scheduled_at',
        'notes',
        'status',
    ];

    /** <<< Ajoutez ceci */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
    /** Fin du ajout >>> */

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
