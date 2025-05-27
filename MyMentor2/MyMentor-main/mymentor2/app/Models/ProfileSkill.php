<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileSkill extends Model
{
    use HasFactory;

    protected $table = 'profile_skill';

    protected $fillable = [
        'profile_id',
        'skill_id',
        'level',
    ];
}