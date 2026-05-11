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
            // Managers should only see conversations related to their hotels or general support
            $query = User::where('id', '!=', $user->id)
                ->where('role', 'customer');

            if ($user->role === 'manager') {
                $query->whereHas('messagesReceived', function ($q) use ($user) {
                    $q->where('receiver_id', $user->id);
                })->orWhereHas('messagesSent', function ($q) use ($user) {
                    $q->where('sender_id', $user->id);
                });
            }

            $conversations = $query->with(['messagesReceived' => function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)->orWhere('sender_id', $user->id);
                }])
                ->get();
            
            return response()->json($conversations);
        } else {
            // Customers see managers they've talked to
            $managers = User::whereIn('role', ['manager', 'admin'])->get();
            return response()->json($managers);
        }
    }

    public function conversations()
    {
        $user = Auth::user();

        if ($user->role === 'manager' || $user->role === 'admin') {
            // Get users who have messaged this manager or messaged their hotels
            $customers = User::where('role', 'customer')
                ->where(function($q) use ($user) {
                    $q->whereHas('messagesSent', function($sq) use ($user) {
                        $sq->where('receiver_id', $user->id);
                    })->orWhereHas('messagesReceived', function($sq) use ($user) {
                        $sq->where('sender_id', $user->id);
                    });
                    
                    if ($user->role === 'manager') {
                        $hotelIds = $user->hotels()->pluck('id');
                        $q->orWhereHas('messagesSent', function($sq) use ($hotelIds) {
                            $sq->whereIn('hotel_id', $hotelIds);
                        });
                    }
                })->get();
            
            return $customers->map(function ($customer) use ($user) {
                $lastMessage = Message::where(function ($q) use ($user, $customer) {
                    $q->where(function($sq) use ($user, $customer) {
                        $sq->where('sender_id', $customer->id)->where('receiver_id', $user->id);
                    })->orWhere(function($sq) use ($user, $customer) {
                        $sq->where('sender_id', $user->id)->where('receiver_id', $customer->id);
                    });
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
            // Customer side: Group by Hotel or General Support
            $messages = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->with('hotel')
                ->get();
            
            $conversations = [];
            
            // General support (no hotel_id)
            $supportLast = $messages->where('hotel_id', null)->sortByDesc('created_at')->first();
            $supportUnread = $messages->where('hotel_id', null)->where('receiver_id', $user->id)->where('is_read', false)->count();
            
            $conversations[] = [
                'user_id' => 'support',
                'user_name' => 'Platform Support',
                'last_message' => $supportLast?->message,
                'unread' => $supportUnread,
                'last_message_time' => $supportLast?->created_at?->diffForHumans(),
            ];

            // Hotel specific conversations
            $hotelConversations = $messages->whereNotNull('hotel_id')->groupBy('hotel_id');
            foreach ($hotelConversations as $hotelId => $msgs) {
                $last = $msgs->sortByDesc('created_at')->first();
                $unread = $msgs->where('receiver_id', $user->id)->where('is_read', false)->count();
                $hotel = $last->hotel;

                $conversations[] = [
                    'user_id' => 'hotel_' . $hotelId,
                    'user_name' => $hotel->name . ' (Concierge)',
                    'hotel_id' => $hotelId,
                    'last_message' => $last->message,
                    'unread' => $unread,
                    'last_message_time' => $last->created_at?->diffForHumans(),
                ];
            }

            return $conversations;
        }
    }

    public function messagesByUserId(Request $request, $userId)
    {
        $user = Auth::user();
        
        if (str_starts_with($userId, 'hotel_')) {
            $hotelId = str_replace('hotel_', '', $userId);
            $messages = Message::where('hotel_id', $hotelId)
                ->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })->orderBy('created_at', 'asc')->get();
            
            Message::where('hotel_id', $hotelId)->where('receiver_id', $user->id)->update(['is_read' => true]);
            return response()->json($messages);
        }

        if ($userId === 'support') {
            $messages = Message::where('hotel_id', null)
                ->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
                })->orderBy('created_at', 'asc')->get();
            
            Message::where('hotel_id', null)->where('receiver_id', $user->id)->update(['is_read' => true]);
            return response()->json($messages);
        }

        $otherUser = User::findOrFail($userId);
        $messages = Message::where(function ($query) use ($user, $otherUser) {
            $query->where('sender_id', $user->id)->where('receiver_id', $otherUser->id);
        })->orWhere(function ($query) use ($user, $otherUser) {
            $query->where('sender_id', $otherUser->id)->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $otherUser->id)->where('receiver_id', $user->id)->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessageDirect(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required',
            'message' => 'required|string|max:1000',
            'hotel_id' => 'nullable|exists:hotels,id',
        ]);

        $sender = Auth::user();
        $receiverId = $request->receiver_id;
        $hotelId = $request->hotel_id;

        if (str_starts_with($receiverId, 'hotel_')) {
            $hotelId = str_replace('hotel_', '', $receiverId);
            $hotel = \App\Models\Hotel::findOrFail($hotelId);
            $receiverId = $hotel->manager_id;
        } elseif ($receiverId === 'support') {
            $admin = User::where('role', 'admin')->first();
            $receiverId = $admin->id;
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'hotel_id' => $hotelId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load(['sender', 'receiver']),
        ], 201);
    }
}