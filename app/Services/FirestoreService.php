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
    public function getLastMessageFromFirebase(string $roomId)
    {
        try {
            $list_chat = $this->firestore->collection($this->collectionName)
            ->document($roomId)
            ->collection('messages')
            ->orderBy('timestamp', 'DESC')
            ->limit(1);
            $query = $list_chat;

            $documents = $query->documents();

            if (empty($documents)) {
                \Log::warning('Chat room document not found', ['room_id' => $roomId]);
                return null;
            }
            foreach ($documents as $document) {
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
