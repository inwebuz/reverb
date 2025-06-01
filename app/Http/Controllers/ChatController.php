<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'with' => 'required|exists:users,id',
        ]);
        if ($data['with'] == $user->id) {
            return redirect()->route('dashboard');
        }
        $chat = Chat::where('type', 'private')
            ->whereHas('users', function ($query) use ($data) {
                $query->where('users.id', $data['with']);
            })
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->first();
        if (!$chat) {
            $chat = Chat::create([
                'type' => 'private',
            ]);
            $chat->users()->attach([$user->id, $data['with']]);
        }
        return redirect()->route('chats.show', $chat->id);
    }

    public function show(Request $request, Chat $chat)
    {
        $user = $request->user();
        if (!$chat->users->contains($user->id)) {
            abort(403);
        }
        $with = $chat->users()->where('users.id', '!=', $user->id)->first();
        $messages = $chat->messages()->with('user')->latest()->take(10)->get();
        return view('chats.show', compact('chat', 'user', 'with', 'messages'));
    }

    public function send(Request $request, Chat $chat)
    {
        $user = $request->user();
        if (!$chat->users->contains($user->id)) {
            abort(403);
        }
        $data = $request->validate([
            'message' => 'required|max:65535',
        ]);
        $message = Message::create([
            'content' => $data['message'],
            'user_id' => $user->id,
            'chat_id' => $chat->id,
        ]);
        event(new \App\Events\ChatMessageSent($chat, $message));
        return redirect()->route('chats.show', $chat->id);
    }
}
