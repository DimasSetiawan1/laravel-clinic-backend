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
            $list_chat = $this->firestore->collection($this->collectionName)->document($roomId)->collection('messages')->documents();

            if (empty($list_chat)) {
                \Log::warning('Chat room document not found', ['room_id' => $roomId]);
                return null;
            }

            return [
                'last_message' => $list_chat[0]['message'] ?? null,
                'last_message_time' => $list_chat[0]['timestamp'] ?? null,
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching chat room: ' . $e->getMessage());
            return null;
        }
    }
}
