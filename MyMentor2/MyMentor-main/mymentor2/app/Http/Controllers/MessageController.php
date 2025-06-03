<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Affiche la liste de tous les messages reçus et envoyés.
     */
    public function index()
    {
        $user = Auth::user();

        $received = $user->receivedMessages()->with('sender')
                          ->latest()->get();
        $sent     = $user->sentMessages()->with('receiver')
                          ->latest()->get();

        return view('messages.index', compact('received', 'sent'));
    }

    /**
     * Affiche le formulaire d’envoi de message.
     */
    public function create()
    {
        // Vous pouvez passer la liste des mentors consultables, etc.
        return view('messages.create');
    }

    /**
     * Stocke un nouveau message.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content'     => 'required|string'
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $data['receiver_id'],
            'content'     => $data['content'],
            'sent_at'     => now()
        ]);

        return back()->with('success', 'Message envoyé !');
    }

    /**
     * Supprime un message.
     */
    public function destroy(Message $message)
    {
        // Vérifier si l’utilisateur peut vraiment supprimer le message
        $this->authorize('delete', $message);

        $message->delete();

        return back()->with('success', 'Message supprimé.');
    }
}
