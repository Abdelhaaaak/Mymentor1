<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $selectedUser  = null;
    $messages      = collect();

    if ($user->role === 'mentee') {
        // Liste des mentors suivis
        $contacts = $user->following;
        $queryKey = 'mentor';
    } else {
        // Mentor : tous les mentees avec qui il a échangé un message
        $contacts = User::whereIn('id', function($q) use($user){
            $q->select('sender_id')
              ->from('messages')
              ->where('receiver_id', $user->id)
            ->union(
              Message::select('receiver_id')
                     ->where('sender_id',$user->id)
            );
        })->get();
        $queryKey = 'mentee';
    }

    // Sélection d’une conversation
    if ($id = $request->query($queryKey)) {
        $selectedUser = $contacts->firstWhere('id',$id);
        if ($selectedUser) {
            $messages = Message::where(function($q) use($user,$selectedUser){
                    $q->where('sender_id',  $user->id)
                      ->where('receiver_id',$selectedUser->id);
                })->orWhere(function($q) use($user,$selectedUser){
                    $q->where('sender_id',  $selectedUser->id)
                      ->where('receiver_id',$user->id);
                })->orderBy('sent_at')->get();
        }
    }

    return view('messages.index', [
        'contacts'      => $contacts,
        'selectedUser'  => $selectedUser,
        'messages'      => $messages,
        'queryKey'      => $queryKey,
    ]);
}

    public function create(Request $request)
    {
        $recipientId = $request->query('recipient');
        $recipient   = User::findOrFail($recipientId);

        return view('messages.create', compact('recipient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content'     => 'required|string',
        ]);

        $message = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $data['receiver_id'],
            'content'     => $data['content'],
            'sent_at'     => now(),
        ]);

        User::find($data['receiver_id'])
            ->notify(new NewMessageNotification($message));

        // Redirige vers la conversation du mentor
        return redirect()
            ->route('messages.index', ['mentor' => $data['receiver_id']])
            ->with('success', 'Message envoyé.');
    }
}
