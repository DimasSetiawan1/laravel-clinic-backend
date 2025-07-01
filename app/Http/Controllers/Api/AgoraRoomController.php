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
        Log::info('Start generate token');

        // Generate the token using Agora's SDK
        $token = RtcTokenBuilder::buildTokenWithUid(
            $appId,
            $appCertificate,
            $channelName,
            $uid,
            RtcTokenBuilder::RolePublisher,
            $privilegeExpiredTs
        );
        Log::info('Token generated');

        Log::info('Start create call room');
        CallRoom::create([
            'call_room_uid' => $uid,
            'call_channel' => $channelName,
            'call_token' => $token,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'expired_token' => date('Y-m-d H:i:s', $privilegeExpiredTs),
            'status' => 'Waiting',
        ]);
        Log::info('Call room created');

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
