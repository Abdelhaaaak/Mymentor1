<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    /**
     * Champs fillables
     */
    protected $fillable = [
        'user_id',
        'bio',
        'languages',
        'skills',
        'learning_goals',
        'availability',
    ];

    /**
     * On caste certains champs en tableau
     */
    protected $casts = [
        'languages'     => 'array',
        'skills'        => 'array',
        'availability'  => 'array',
    ];

    /**
     * Relation vers l’utilisateur propriétaire du profil.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation many-to-many vers Skill via la table pivot `profile_skill`
     */
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'profile_skill'
        )->withTimestamps();
    }
}

