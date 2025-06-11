<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Affiche la liste des contacts et la conversation avec un utilisateur sélectionné.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Récupérer tous les utilisateurs sauf l'utilisateur connecté
        $contacts = User::where('id', '!=', $user->id)->get();

        // Récupérer l'utilisateur sélectionné s’il existe
        $selectedUserId = $request->get('user');
        $selectedUser = $selectedUserId ? User::find($selectedUserId) : null;

        // Messages échangés avec l'utilisateur sélectionné
        $messages = collect(); // collection vide par défaut
        if ($selectedUser) {
            $messages = Message::where(function ($query) use ($user, $selectedUser) {
                    $query->where('sender_id', $user->id)
                          ->where('receiver_id', $selectedUser->id);
                })
                ->orWhere(function ($query) use ($user, $selectedUser) {
                    $query->where('sender_id', $selectedUser->id)
                          ->where('receiver_id', $user->id);
                })
                ->orderBy('sent_at')
                ->get();
        }

        return view('messages.index', [
            'contacts'     => $contacts,
            'selectedUser' => $selectedUser,
            'messages'     => $messages,
            'queryKey'     => 'user',
        ]);
    }

    /**
     * Affiche le formulaire d’envoi de message (optionnel).
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Enregistre un nouveau message.
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
     * Supprime un message (si l'utilisateur est autorisé).
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return back()->with('success', 'Message supprimé.');
    }
}
