<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
   



    use Notifiable;
    


    protected $fillable = [
        'name','email','password','role',
        'expertise','bio','profile_image',
        'language','level','style',  // ← ajoutez-les ici
    ];
    // …



    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function mentorSessions()
{
    return $this->hasMany(SessionMM::class, 'mentor_id');
}

public function studentSessions()
{
    return $this->hasMany(SessionMM::class, 'mentee_id');
}
    public function skills()
{
    return $this->belongsToMany(Skill::class, 'profile_skills', 'user_id', 'skill_id');
}

public function followers()
{
    return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
}

public function following()
{
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
}

public function isFollowing(User $user): bool
{
    return $this->following()
                ->where('user_id', $user->id)
                ->exists();
}
public function mentee()
{
    return $this->hasOne(Mentee::class);
}
 public function feedbacks()
    {
        return $this->hasManyThrough(
            Feedback::class,        // Le modèle final
            SessionMM::class,   // Le modèle « pivot »
            'mentor_id',            // FK sur mentor_sessions → users.id
            'mentor_session_id',    // FK sur feedback       → mentor_sessions.id
            'id',                   // PK de users
            'id'                    // PK de mentor_sessions
        );
    }

public function averageRating()
{
    return $this->feedbacks()->avg('rating');
}


}
