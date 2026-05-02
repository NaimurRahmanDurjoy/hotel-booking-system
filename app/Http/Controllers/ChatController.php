<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isManager() || $user->isAdmin()) {
            // Get all conversations for manager/admin
            $conversations = User::where('id', '!=', $user->id)
                ->where('role', 'customer')
                ->with(['messagesReceived' => function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)->orWhere('sender_id', $user->id);
                }])
                ->get();
            
            return response()->json($conversations);
        } else {
            // Get conversations with managers for customer
            $managers = User::whereIn('role', ['manager', 'admin'])->get();
            return response()->json($managers);
        }
    }

    public function messages(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        $messages = Message::where(function ($query) use ($currentUser, $user) {
            $query->where(function ($q) use ($currentUser, $user) {
                $q->where('sender_id', $currentUser->id)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($currentUser, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $currentUser->id);
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sender = Auth::user();

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver']),
        ], 201);
    }

    public function unreadCount()
    {
        $user = Auth::user();
        
        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }

    public function conversations()
    {
        $user = Auth::user();

        if ($user->isManager() || $user->isAdmin()) {
            $users = User::where('role', 'customer')->get();
        } else {
            $users = User::whereIn('role', ['manager', 'admin'])->get();
        }

        $conversations = $users->map(function ($conversationUser) use ($user) {
            $lastMessage = Message::where(function ($query) use ($user, $conversationUser) {
                $query->where('sender_id', $user->id)->where('receiver_id', $conversationUser->id);
            })->orWhere(function ($query) use ($user, $conversationUser) {
                $query->where('sender_id', $conversationUser->id)->where('receiver_id', $user->id);
            })->latest('created_at')->first();

            $unread = Message::where('sender_id', $conversationUser->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            return [
                'user_id' => $conversationUser->id,
                'user_name' => $conversationUser->name,
                'last_message' => $lastMessage?->message,
                'unread' => $unread,
            ];
        });

        return response()->json($conversations);
    }

    public function messagesByUserId(Request $request, $userId)
    {
        $user = Auth::user();
        $otherUser = User::findOrFail($userId);

        $messages = Message::where(function ($query) use ($user, $otherUser) {
            $query->where('sender_id', $user->id)->where('receiver_id', $otherUser->id);
        })->orWhere(function ($query) use ($user, $otherUser) {
            $query->where('sender_id', $otherUser->id)->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $otherUser->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessageDirect(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver']),
        ], 201);
    }
}