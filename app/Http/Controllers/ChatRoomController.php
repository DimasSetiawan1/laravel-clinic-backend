<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    /**
     * get all chat rooms for a user
     */
    public function getChatRoomsForUser(User $user)
    {
        if ($user->role == 'patient') {
            $chatRooms = $user->getChatRoomsPatient;
        } elseif ($user->role == 'doctor') {
            $chatRooms = $user->getChatRoomsDoctor;
        } else {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($chatRooms);
    }


    
}
