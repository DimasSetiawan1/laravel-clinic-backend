<?php

namespace App\Http\Controllers;

use App\Models\CallRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Notification;
use TaylanUnutmaz\AgoraTokenBuilder\RtcTokenBuilder;

class AgoraRoomController extends Controller
{
    public function generateTokenCallRoom(Request $request)
    {
        $request->validate([
            'channel_name' => 'required|string',
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
        ]);
        $appId = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channel_name;
        $uid = (string) \Illuminate\Support\Str::uuid();

        $expirationTimeInSeconds = 7200; // 2 jam
        $currentTimeStamp = time();
        $privilegeExpiredTs = $currentTimeStamp + $expirationTimeInSeconds;

        // Validate inputs
        if (!$appId || !$appCertificate || !$channelName) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }
        // Generate the token using Agora's SDK
        $token = RtcTokenBuilder::buildTokenWithUid(
            $appId,
            $appCertificate,
            $channelName,
            $uid,
            RtcTokenBuilder::RolePublisher,
            $privilegeExpiredTs
        );

        CallRoom::create([
            'call_room_uid' => $uid,
            'call_channel' => $channelName,
            'call_token' => $token,
            'patient' => $request->patient_id,
            'doctor' => $request->doctor_id,
            'expired_token' => date('Y-m-d H:i:s', $privilegeExpiredTs),
            'status' => 'Waiting',
        ]);

        // OneSignal::sendNotificationToUser(
        //     "You Have a New " . $order->service . " from " . $order->patient->name,
        //     $doctor->one_signal_token,
        //     $url = null,
        //     ['order_id' => $order->id, 'chat_room_id' => $order->chat_room_id],
        //     $buttons = null,
        //     $schedule = null
        // );

        return response()->json(
            [
                'token' => $token,
                'uid' => $uid,
            ],
            200
        );
    }

    public function getCallRooms(int $user_id)
    {
        $validator = \Validator::make(['user_id' => $user_id], [
            'user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid user ID',
                'details' => $validator->errors()
            ], 400);
        }

        $callRooms = CallRoom::where(function ($query) use ($user_id) {
            $query->where('patient_id', $user_id)
                ->orWhere('doctor_id', $user_id);
        })->with(['patient', 'doctor'])->get();
        $callRooms->makeHidden(['doctor_id', 'patient_id']);

        if ($callRooms->isEmpty()) {
            return response()->json(['error' => 'No active call rooms found'], 404);
        }

        return response()->json($callRooms, 200);
    }

    public function updateCallRoomStatus(int $id, String $status)
    {
        $validator = \Validator::make(['status' => $status, 'id' => $id], [
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
