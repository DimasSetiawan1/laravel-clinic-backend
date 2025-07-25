<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Notification;
use TaylanUnutmaz\AgoraTokenBuilder\RtcTokenBuilder;

class AgoraRoomController extends Controller
{
   

    public function getCallRooms(Request $request, int $user_id,)
    {
        $status = $request->query('status');

        $validator = validator(
            ['user_id' => $user_id, 'status' => $status],
            [
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'nullable|string|in:All,Waiting,Ongoing,Expired,Finished'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
            'error' => 'Invalid parameters',
            'details' => $validator->errors()
            ], 400);
        }

        if ($status && $status != 'All') {
            $callRooms = CallRoom::where('status', $status)
            ->where(function ($q) use ($user_id) {
                $q->where('patient_id', $user_id)
                  ->orWhere('doctor_id', $user_id);
            })
            ->with(['patient', 'doctor'])
            ->get();
        } else {
            $callRooms = CallRoom::where(function ($q) use ($user_id) {
                $q->where('patient_id', $user_id)
                  ->orWhere('doctor_id', $user_id);
            })
            ->with(['patient', 'doctor'])
            ->get();
        }

        $callRooms->makeHidden(['doctor_id', 'patient_id']);

        if ($callRooms->isEmpty()) {
            return response()->json(['error' => 'No active call rooms found'], 404);
        }

        return response()->json($callRooms, 200);
    }

    public function updateCallRoomStatus(int $id, String $status)
    {
        $validator = validator(['status' => $status, 'id' => $id], [
            'status' => 'required|in:Waiting,Close,Ongoing',
            'id' => 'required|integer|exists:call_rooms,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid user ID',
                'details' => $validator->errors()
            ], 400);
        }


        $callRoom = CallRoom::find($id);

        if (!$callRoom) {
            return response()->json(['error' => 'Call room not found'], 404);
        }

        $callRoom->status = $status;
        $callRoom->save();

        return response()->json(['message' => 'Call room status updated successfully'], 200);
    }
}
