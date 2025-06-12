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
    public function getLastMessageFromFirebase(string $roomId)
    {
        try {
            $list_chat = $this->firestore->collection($this->collectionName)->document($roomId)->collection('messages');
            $query = $list_chat->orderBy('created_at', 'desc')->limit(1);
            \Log::info('query :', ['query' => $query]);

            $documents = $query->documents();
            \Log::info('Documents fetched from Firestore', ['documents_count' => $documents]);

            if (empty($documents)) {
                \Log::warning('Chat room document not found', ['room_id' => $roomId]);
                return null;
            }
            foreach ($documents as $document) {
                \Log::info('Fetching last message from chat room', [
                    'room_id' => $roomId,
                    'document' => $document,
                ]);
                if ($document->exists()) {
                    $data = $document->data();
                    return [
                        'last_message' => $data['message'] ?? null,
                        'last_message_time' => $data['timestamp'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching chat room: ' . $e->getMessage());
            return null;
        }
    }
}
