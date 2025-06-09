<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;


class FirestoreService
{
    protected FirestoreClient $firestore;

    private $collectionName = 'chat_rooms';

    public function __construct()
    {
        try {
            $this->firestore = new FirestoreClient([
                'projectId' => env('GOOGLE_PROJECT_ID'),
                'keyFilePath' => env('FIREBASE_CREDENTIALS'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error initializing Firestore client: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createChatRoom($roomId)
    {
        try {
            $this->firestore->setDocument(
                $this->collectionName . "/$roomId",
                [
                    'id' => $roomId,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                null,
                null,
                // {

                // }
            );
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    public function getLastMessageFromFirebase(string $roomId): ?array
    {
        try {
            \Log::info('Fetching last message from Firestore', ['room_id' => $roomId]);
            $documents = $this->firestore->collection($this->collectionName)->document($roomId);
            $data = $documents->snapshot();
            // $snapshot = $docRef->snapshot();
            if (!$data->exists()) {
                \Log::warning('Chat room document not found', ['room_id' => $roomId]);
                return null;
            }
            $data = $data->data();
            \Log::info('Chat room data', ['room_id' => $roomId, 'data' => $data]);
          
            return [
                'last_message' => $data['last_message'] ?? null,
                'last_message_time' => $data['last_message_time'] ?? null,
            ];
        } catch (\Exception $e) {
            \Log::error('Error fetching chat room: ' . $e->getMessage());
            return null;
        }
    }
}
