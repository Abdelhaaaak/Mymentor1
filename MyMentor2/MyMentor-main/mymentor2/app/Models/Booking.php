<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['mentor_id','student_id','scheduled_at','status'];

    public function mentor()  { return $this->belongsTo(User::class, 'mentor_id'); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
}
