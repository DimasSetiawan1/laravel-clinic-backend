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
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
        ]);

        $patient = User::find($request->patient_id);
        $doctor = User::find($request->doctor_id);

        if (!$patient || !$doctor) {
            return response()->json(['message' => 'User not found'], 404);
        }
        Log::info('Sending notification', [
            'message' => $request->message,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'one_signal_token' => $doctor->one_signal_token,
            'patient_one_signal_token' => $patient->one_signal_token,
        ]);

        try {
            $response = OneSignal::sendNotificationToUser(
                $request->message,
                $doctor->one_signal_token,
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings =  "Message from " . $patient->name,
            );
            \Log::info('OneSignal Response: ', (array)$response);

            return response()->json([
                'message' => 'Notification sent',
                'onesignal_response' => $response
            ], 200);
        } catch (\Exception $e) {
            \Log::error('OneSignal Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Notification sent successfully'], 200);
    }
}
