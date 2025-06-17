<?php

namespace App\Services;

use App\Models\User;
use Google\Cloud\Firestore\FirestoreClient;
use Symfony\Polyfill\Uuid\Uuid;

class FirestoreService
{
    protected FirestoreClient $firestore;

    private $collectionName = 'chat_rooms';

    public function __construct()
    {
        try {
            $this->firestore = new FirestoreClient([
                'projectId' => env('GOOGLE_PROJECT_ID'),
                'keyFilePath' => storage_path(env('FIREBASE_CREDENTIALS')),
                // 'transport' => 'rest',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error initializing Firestore client: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createChatRoom(string $roomId, int $patientId, int $doctorId): bool
    {
        try {
            $this->firestore
                ->collection($this->collectionName)
                ->document((string) $roomId)
                ->set([
                    'participants' => [
                        'patient_id' => $patientId,
                        'doctor_id' => $doctorId,
                    ],
                    'last_message' => "",
                    'last_message_time' => "",
                    'doctor_name' => User::find($doctorId)->name ?? 'Unknown Doctor',
                    'doctor_image' => User::find($doctorId)->image ?? null,
                    'patient_name' => User::find($patientId)->name ?? 'Unknown Patient',
                    'patient_image' => User::find($patientId)->image ?? null,
                    'created_at' => now()->toDateTimeString(),
                ]);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
