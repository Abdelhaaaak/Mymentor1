<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentor_id',
        'student_id',
        'start_time',
        'end_time',
        'status',
    ];

    // Relationship: the mentor (user)
   public function mentor()
{
    return $this->belongsTo(User::class, 'mentor_id');
}

public function student()
{
    return $this->belongsTo(User::class, 'student_id');
}

}
