<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'manager' || $user->role === 'admin') {
            // Unified support model: Get all customer users who have sent or received messages
            $customers = User::where('role', 'customer')
                ->where(function($q) {
                    $q->whereHas('messagesSent')
                      ->orWhereHas('messagesReceived');
                })->get();

            return response()->json($customers);
        } else {
            // Customers see managers and admins (staff)
            $staff = User::whereIn('role', ['manager', 'admin'])->get();
            return response()->json($staff);
        }
    }

    public function conversations()
    {
        $user = Auth::user();

        if ($user->role === 'manager' || $user->role === 'admin') {
            // Unified support model: Get all customer users who have sent or received messages
            $customers = User::where('role', 'customer')
                ->where(function($q) {
                    $q->whereHas('messagesSent')
                      ->orWhereHas('messagesReceived');
                })->get();

            return $customers->map(function ($customer) {
                $lastMessage = Message::where('sender_id', $customer->id)
                    ->orWhere('receiver_id', $customer->id)
                    ->latest('created_at')->first();

                $unread = Message::where('sender_id', $customer->id)
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
            // Customer side: Only show General Support conversation
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->get();

            $supportLast = $messages->sortByDesc('created_at')->first();
            $supportUnread = $messages->where('receiver_id', $user->id)->where('is_read', false)->count();

            return [
                [
                    'user_id' => 'support',
                    'user_name' => 'Platform Support',
                    'last_message' => $supportLast?->message,
                    'unread' => $supportUnread,
                    'last_message_time' => $supportLast?->created_at?->diffForHumans(),
                ]
            ];
        }
    }

    public function messagesByUserId(Request $request, $userId)
    {
        $user = Auth::user();

        if ($userId === 'support') {
            // Find all messages involving this customer
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->orderBy('created_at', 'asc')->get();

            // Mark messages received by this customer as read
            Message::where('receiver_id', $user->id)->update(['is_read' => true]);
            
            return response()->json($messages);
        }

        // Admin or Manager viewing a customer's chat messages
        $otherUser = User::findOrFail($userId);
        $messages = Message::where('sender_id', $otherUser->id)
            ->orWhere('receiver_id', $otherUser->id)
            ->orderBy('created_at', 'asc')->get();

        // Mark messages sent by this customer as read
        Message::where('sender_id', $otherUser->id)->update(['is_read' => true]);

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
            $admin = User::where('role', 'admin')->first();
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

    public function unreadCount()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['count' => 0]);

        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread_count' => $count]);
    }
}
