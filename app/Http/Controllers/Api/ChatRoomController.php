<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRooms;
use App\Models\User;
use App\Services\FirestoreService;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    protected $firestoreService;

    public function __construct(FirestoreService $firestoreService)
    {
        $this->firestoreService = $firestoreService;
    }

    /**
     * get all chat rooms for a user
     */
    public function getChatRoomsForUser(User $user)
    {

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if ($user->role == 'doctor') {

            $chatRooms = ChatRooms::where('doctors_id', $user->id)
                ->with(['patient', 'order'])
                ->get()
                ->map(function ($room) {
                    $lastMessage = $this->firestoreService->getLastMessageFromFirebase($room->id);
                    return [
                        'id' => $room->id,
                        'patient' => [
                            'id' => $room->patient->id,
                            'name' => $room->patient->name,
                            'image_url' => $room->patient->image_url,
                            'lastMessage' => $lastMessage['lastMessage'] ?? null,
                            'lastMessageTime' => $lastMessage['lastMessageTime'] ?? null,
                        ],
                        'created_at' => $room->created_at,
                    ];
                });
        } else {
            $chatRooms = ChatRooms::where('users_id', $user->id)
                ->with('doctor')
                ->get()
                ->map(function ($room) {
                    $lastMessage = $this->firestoreService->getLastMessageFromFirebase($room->id);

                    return [
                        'id' => $room->id,
                        'doctor' => [
                            'id' => $room->doctor->id,
                            'name' => $room->doctor->name,
                            'image_url' => $room->doctor->image_url,
                            'lastMessage' => $lastMessage['last_message'] ?? null,
                            'lastMessageTime' => $lastMessage['last_message_time'] ?? null,
                        ],
                        'created_at' => $room->created_at,
                    ];
                });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Chat rooms retrieved successfully',
            'chat_rooms' => $chatRooms
        ]);
    }
}
