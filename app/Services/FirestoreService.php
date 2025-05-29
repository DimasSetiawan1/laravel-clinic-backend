<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;

class FirestoreService
{
    protected $firestore;

    public function __construct()
    {

        $credentialPath = storage_path('app/clinic-apps.json');
        \Log::info('Credential path: ' . $credentialPath);
        \Log::info('File exists: ' . (file_exists($credentialPath) ? 'Yes' : 'No'));

        if (file_exists($credentialPath)) {
            \Log::info('File size: ' . filesize($credentialPath) . ' bytes');
        }

        try {
            $this->firestore = new FirestoreClient([
                'projectId' => 'clinic-apps-25116',
                'keyFilePath' => $credentialPath,
            ]);
            \Log::info('Firestore client created successfully');
        } catch (\Exception $e) {
            \Log::error('Firestore connection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createChatRoom($roomId, $doctorId, $patientId)
    {
        try {
            $docRef = $this->firestore->collection('chat_rooms')->document($roomId);
            $result = $docRef->set([
                'doctors_id' => $doctorId,
                'users_id' => $patientId,
                'created_at' => now()->toDateTimeString(),
            ]);

            \Log::info('Chat room created: ' . $roomId);
            return $result;
        } catch (\Exception $e) {
            \Log::error('Error creating chat room: ' . $e->getMessage());
            throw $e;
        }
    }
}
