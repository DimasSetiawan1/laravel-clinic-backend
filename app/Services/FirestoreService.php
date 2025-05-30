<?php

namespace App\Services;

use MrShan0\PHPFirestore\FirestoreClient;

class FirestoreService
{
    protected $firestore;

    public function __construct()
    {
        try {
            $this->firestore = new FirestoreClient(env('GOOGLE_PROJECT_ID'), env('GOOGLE_SECRET_KEY'), [
                'database' => '(default)',
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function createChatRoom($roomId)
    {
        try {
            $this->firestore->setDocument('chat_rooms' . "/$roomId", [
                'id' => $roomId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
