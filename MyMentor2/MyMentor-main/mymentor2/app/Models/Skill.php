<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Utilisateurs ayant cette compétence.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
