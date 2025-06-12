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
                    'participant_id' => [
                        'patient_id' => $patientId,
                        'doctor_id' => $doctorId,
                    ],
                    'created_at' => now()->toDateTimeString(),
                ]);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function getLastMessageFromFirebase(string $roomId): ?array
    {
        try {
            $list_room = $this->firestore->collection($this->collectionName)->document($roomId)->collection('messages')->documents();
            $data = $list_room->snapshot();

            if (!$data->exists()) {
                \Log::warning('Chat room document not found', ['room_id' => $roomId]);
                return null;
            }
            $data = $data->data();

            return [
                'last_message' => $data[0]['message'] ?? null,
                'last_message_time' => $data[0]['timestamp'] ?? null,
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching chat room: ' . $e->getMessage());
            return null;
        }
    }
}
