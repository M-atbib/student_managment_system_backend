<?php

namespace App\Http\Controllers\Api\Remarque;

use App\DataTransferObjects\Remarque\RemarqueDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\RemarqueRequest;
use App\Models\Remarque;
use App\Responses\remarque\RemarqueResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class RemarqueController extends Controller
{
    public function view($remarque_uuid) {
        $this->authorize('view remarque');
    
        try {
            $remarque = Remarque::where('uuid', $remarque_uuid)->firstOrFail();
    
            return new RemarqueResponse(
                collection: collect([$remarque]),
                status: 200
            );
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Remarque non trouvée'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(RemarqueRequest $request)
    {
        $this->authorize('manage remarque');

        try {
            $data = new RemarqueDataObject($request->text, $request->student_uuid);

            Remarque::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));

            return response()->json(['message' => 'Remarque créé avec succès'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(RemarqueRequest $request, $remarque_uuid)
    {
        $this->authorize('manage remarque');

        try {
            $remarque = Remarque::where('uuid', $remarque_uuid)->firstOrFail();
            
            $data = new RemarqueDataObject($request->text, $request->student_uuid);

            $remarque->update(array_merge(
                $data->toArray(),
                [
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ])
            );

            return response()->json(['message' => 'Remarque mise à jour avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Remarque non trouvée'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($remarque_uuid)
    {
        $this->authorize('manage remarque');

        try {
            $remarque = Remarque::where('uuid', $remarque_uuid)->firstOrFail();
            $remarque->delete();

            return response()->json(['message' => 'Remarque supprimée avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Remarque non trouvée'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}
