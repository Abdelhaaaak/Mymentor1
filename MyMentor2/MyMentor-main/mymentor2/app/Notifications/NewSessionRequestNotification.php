<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\SessionMM;

class NewSessionRequestNotification extends Notification
{
    use Queueable;

    protected SessionMM $session;

    public function __construct(SessionMM $session)
    {
        $this->session = $session;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message'     => "Nouvelle demande de session par « {$this->session->mentee->name} »",
            'session_id'  => $this->session->id,
            'scheduled_at'=> $this->session->scheduled_at->toDateTimeString(),
        ];
    }
}
