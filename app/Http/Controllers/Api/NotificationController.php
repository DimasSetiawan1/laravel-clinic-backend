<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Log;
use OneSignal;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'sender_name' => 'nullable|string',
            'recipient_id' => 'required|exists:users,id',
            'sender_id' => 'required|exists:users,id',
        ]);

        $sender = User::find($request->sender_id);
        $recipient = User::find($request->recipient_id);
        if (!$sender || !$recipient) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Verifikasi recipient memiliki onesignal token
        if (!$recipient->one_signal_token) {
            return response()->json(['message' => 'Recipient has no notification token'], 400);
        }
        Log::info('Sending notification', [
            'message' => $request->message,
            'sender_id' => $sender->id,
            'sender_name' => $sender->name,
            'sender_role' => $sender->role,
            'recipient_id' => $recipient->id,
            'recipient_role' => $recipient->role,
            'one_signal_token' => $recipient->one_signal_token,
        ]);

        try {


            // Data tambahan yang bisa digunakan oleh aplikasi client
            $additionalData = [
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'sender_role' => $sender->role,
                'recipient_id' => $recipient->id,
                'type' => $request->type ?? 'message',
            ];

            OneSignal::sendNotificationToUser(
                $request->message,
                $recipient->one_signal_token,
                $url = null,
                $additionalData,
                $buttons = null,
                $schedule = null,
                $request->sender_name ? $request->sender_name : 'System',
            );


            return response()->json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            Log::error('OneSignal Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Notification sent successfully'], 200);
    }
}
