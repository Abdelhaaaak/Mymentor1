<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfileSkill extends Pivot
{
    /**
     * Nom explicite de la table
     *
     * @var string
     */
    protected $table = 'profile_skill';

    /**
     * Colonnes autorisées pour le « mass assignment »
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'profile_id',
        'skill_id',
        'level',
    ];
}
