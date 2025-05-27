<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;  // optionnel si vous queuez
use Illuminate\Notifications\Messages\DatabaseMessage; // pour stocker en DB
use App\Models\User;

class NewFollowerNotification extends Notification
{
    use Queueable;

    protected $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    // Sélectionnez les canaux : ici on stocke en base
    public function via($notifiable)
    {
        return ['database'];
    }

    // Le contenu de la notification en base
    public function toDatabase($notifiable)
    {
        return [
            'message'   => "{$this->follower->name} vient de vous suivre.",
            'follower_id' => $this->follower->id,
        ];
    }

    // (optionnel) si vous voulez envoyer un e-mail
    // public function toMail($notifiable) { … }
}
