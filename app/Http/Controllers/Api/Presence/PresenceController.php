<?php

namespace App\Http\Controllers\Api\Presence;

use App\Http\Controllers\Controller;
use App\DataTransferObjects\Presence\PresenceDataObject;
use App\Http\Requests\PresenceRequest;
use App\Models\Presence;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Str;

class PresenceController extends Controller
{
    public function store(PresenceRequest $request): JsonResponse
    {
        $this->authorize('manage presence');

        try {
            $data = new PresenceDataObject(
                title: $request->title,
                type: $request->type,
                justification: $request->justification,
                remarque: $request->remarque,
                date: $request->date,
                studentUuids: $request->student_uuids
            );

            foreach ($data->studentUuids as $studentUuid) {
              
                Presence::create([
                    'uuid' => (string) Str::uuid(),
                    'title' => $data->title,
                    'type' => $data->type,
                    'justification' => $data->justification,
                    'remarque' => $data->remarque,
                    'date' => $data->date,
                    'student_uuid' => $studentUuid,
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]);
            }
            
            return response()->json(['message' => 'Présence créée avec succès'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}