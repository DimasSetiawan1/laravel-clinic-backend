<?php

namespace App\Services;

use Kreait\Firebase\Contract\Database;

class FirebaseChatService
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function createRoom($chat_rooms_id, $doctorId, $patientId)
    {
        $this->database->getReference('chat_rooms/' . $chat_rooms_id)->set([
            'doctor_id' => $doctorId,
            'patient_id' => $patientId,
            'created_at' => now()->toDateTimeString(),
        ]);
        return $chat_rooms_id;
    }
}
