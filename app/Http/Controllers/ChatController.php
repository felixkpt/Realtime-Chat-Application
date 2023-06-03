<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    function rooms()
    {
        return ChatRoom::all();
    }

    function messages(Request $request, $room_id)
    {
        return ChatMessage::with('user')->where('chat_room_id', $room_id)->orderby('created_at', 'desc')->get();
    }

    function newMessage(Request $request, $room_id)
    {
        $new_message = new ChatMessage();
        $new_message->user_id = Auth()->id();
        $new_message->chat_room_id = $room_id;
        $new_message->message = $request->message;
        $new_message->save();

        broadcast(new NewChatMessage($new_message))->toOthers();

        return $new_message;
    }
}
