<?php

namespace App\Services;

use Kreait\Firebase\Contract\Firestore;


class FirestoreService
{
    protected Firestore $firestore;

    private $collectionName = 'chat_rooms';

    public function __construct(Firestore $firestore)
    {
        try {

            $this->firestore = $firestore;
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
            $database = $this->firestore->database();
            $documents = $database->collection('chat_rooms')->document($roomId);
            $data = $documents->snapshot();
            // $snapshot = $docRef->snapshot();
            if (!$data->exists()) {
                \Log::warning('Chat room document not found', ['room_id' => $roomId]);
                return null;
            }
            $data = $data->data();
            \Log::info('Chat room data', ['room_id' => $roomId, 'data' => $data]);
            // \Log::info('Chat room data', ['room_id' => $roomId, 'data' => $docRef]);
            // if (!$snapshot->exists()) {
            //     \Log::warning('Chat room document not found', ['room_id' => $roomId]);
            //     return [
            //         'last_message' => null,
            //         'last_message_time' => null,
            //     ];
            // }
            // $data = $snapshot->data();
            // \Log::info('Chat room data', ['room_id' => $roomId, 'data' => $docRef]);

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
