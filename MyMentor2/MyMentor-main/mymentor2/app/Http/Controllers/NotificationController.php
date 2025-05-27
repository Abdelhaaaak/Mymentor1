<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Marquer toutes comme lues
    public function readAll(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();
        return back();
    }

    // Marquer une seule notification comme lue
    public function readOne(Request $request, $id)
    {
        $notif = $request->user()
                         ->notifications()
                         ->where('id', $id)
                         ->firstOrFail();
        $notif->markAsRead();
        return back();
    }
}
