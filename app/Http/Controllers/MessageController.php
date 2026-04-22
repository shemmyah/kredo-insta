<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index() {
        $auth_id = auth()->id();

        $rooms = ChatRoom::where('user_one_id', $auth_id)
                ->orWhere('user_two_id', $auth_id)
                ->with(['messages' => function($query) {
                    $query->latest();
                }])
                ->get()
                ->sort(function($a, $b) use ($auth_id) {
                    $unreadA = $a->messages->where('is_read', false)->where('sender_id', '!=', $auth_id)->count();
                    $unreadB = $b->messages->where('is_read', false)->where('sender_id', '!=', $auth_id)->count();

                    if($unreadA != $unreadB) {
                        return $unreadB <=> $unreadA;
                    }

                    $timeA = $a->messages->first()->created_at ?? $a->created_at;
                    $timeB = $b->messages->first()->created_at ?? $b->created_at;

                    return $timeB <=> $timeA;
                });
            return view('users.messages.index', compact('rooms'));
    }
    public function chat($to_user_id) {
        $auth_user_id = auth()->id();

        $room = ChatRoom::where(function($query) use ($auth_user_id, $to_user_id) {
            $query->where('user_one_id', $auth_user_id)->where('user_two_id', $to_user_id);
        })->orWhere(function($query) use ($auth_user_id, $to_user_id) {
            $query->where('user_one_id', $to_user_id)->where('user_two_id', $auth_user_id);
        })->first();

        if(!$room) {
            $room = ChatRoom::create([
                'user_one_id' => $auth_user_id,
                'user_two_id' => $to_user_id
            ]);
        }

        Message::where('chat_room_id', $room->id)
                ->where('sender_id', '!=', $auth_user_id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

        $to_user = User::findOrFail($to_user_id);
        $messages = Message::where('chat_room_id', $room->id)->oldest()->get();

        return view('users.messages.chat', compact('room', 'messages', 'to_user'));
    }

    public function store(Request $request, $chat_room_id) {
        $request->validate(['body' => 'required|max:1000']);

        Message::create([
            'chat_room_id' => $chat_room_id,
            'sender_id' => auth()->id(),
            'body' => $request->body
        ]);

        return redirect()->back();
    }
}
