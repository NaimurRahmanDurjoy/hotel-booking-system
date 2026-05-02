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
            // Staff sees all customers who have sent messages or exist
            $users = User::where('role', 'customer')->get();
            
            return $users->map(function ($customer) use ($user) {
                $lastMessage = Message::where(function ($q) use ($user, $customer) {
                    $q->where('sender_id', $customer->id)
                      ->orWhere('receiver_id', $customer->id);
                })->latest('created_at')->first();

                $unread = Message::where('sender_id', $customer->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                return [
                    'user_id' => $customer->id,
                    'user_name' => $customer->name,
                    'last_message' => $lastMessage?->message,
                    'unread' => $unread,
                    'last_message_time' => $lastMessage?->created_at->diffForHumans(),
                ];
            });
        } else {
            // Customer sees only ONE unified 'Concierge' conversation
            $lastMessage = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->latest('created_at')
                ->first();

            $unread = Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            return [[
                'user_id' => 'support',
                'user_name' => 'Hotel Concierge',
                'last_message' => $lastMessage?->message,
                'unread' => $unread,
                'last_message_time' => $lastMessage?->created_at?->diffForHumans(),
            ]];
        }
    }

    public function messagesByUserId(Request $request, $userId)
    {
        $user = Auth::user();
        
        if ($userId === 'support' && $user->role === 'customer') {
            // Customer fetching unified thread with all staff
            $messages = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })->orderBy('created_at', 'asc')->get();

            Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json($messages);
        }

        $otherUser = User::findOrFail($userId);

        // If staff is viewing a customer conversation, show ALL messages for that customer
        if (($user->isAdmin() || $user->isManager()) && $otherUser->role === 'customer') {
            $messages = Message::where(function ($query) use ($otherUser) {
                $query->where('sender_id', $otherUser->id)
                      ->orWhere('receiver_id', $otherUser->id);
            })->orderBy('created_at', 'asc')->get();

            // Mark these messages as read for the current staff member's view
            Message::where('sender_id', $otherUser->id)
                ->where('receiver_id', $user->id) // Actually, we should probably mark all incoming for this customer as read for the system
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json($messages);
        }

        // Default private messaging logic
        $messages = Message::where(function ($query) use ($user, $otherUser) {
            $query->where('sender_id', $user->id)->where('receiver_id', $otherUser->id);
        })->orWhere(function ($query) use ($user, $otherUser) {
            $query->where('sender_id', $otherUser->id)->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function sendMessageDirect(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required',
            'message' => 'required|string|max:1000',
        ]);

        $sender = Auth::user();
        $receiverId = $request->receiver_id;

        if ($receiverId === 'support') {
            // Find the first available manager/admin to receive the message (for DB integrity)
            $admin = User::whereIn('role', ['admin', 'manager'])->first();
            $receiverId = $admin->id;
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver']),
        ], 201);
    }
}