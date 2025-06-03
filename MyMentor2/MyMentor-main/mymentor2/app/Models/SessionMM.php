<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SessionMM extends Model
{
    use HasFactory;

    /**
     * Comme notre migration crée la table "mentor_sessions",
     * on indique explicitement à Eloquent d’utiliser cette table.
     */
    protected $table = 'mentor_sessions';

    /**
     * Les champs que l’on peut remplir en masse.
     */
    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'scheduled_at',
        'notes',
        'status',
    ];

    /**
     * Si vous souhaitez caster scheduled_at en Carbon automatiquement :
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * Relation vers le mentor (User dont role = 'mentor').
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Relation vers le mentee (User dont role = 'mentee').
     */
    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
