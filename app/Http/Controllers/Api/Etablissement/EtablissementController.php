<?php

namespace App\Http\Controllers\Api\Etablissement;

use App\DataTransferObjects\etablissement\EtablissementDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\EtablissementRequest;
use Illuminate\Support\Str;
use App\Models\Etablissement;
use App\Responses\etablissement\EtablissementResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{
    public function index(Request $request) {

        $this->authorize('view etablissement');

        try {
            if ($request->user()->branch_uuid) {
                $etablissement = Etablissement::where('uuid', $request->user()->branch_uuid)->firstOrFail();
                
                return new EtablissementResponse(
                    collection: collect([$etablissement]),
                    status: 200
                );

            } else {
                $etablissements = Etablissement::orderBy('updated_at', 'desc')->get();
                if ($etablissements->isEmpty()) {
                    return response()->json(['message' => 'Établissements non trouvés'], 404);
                }
                return new EtablissementResponse(
                    collection: $etablissements,
                    status: 200
                );
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Établissement non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function view(Request $request,$etab_uuid) {
        $this->authorize('view etablissement');
    
        try {
            $query = Etablissement::query();
            if ($request->user()->branch_uuid) {
                $query->where('uuid', $request->user()->branch_uuid);
            }else{
                $query->where('uuid', $etab_uuid);
            }
            $etablissement = $query->firstOrFail();
    
            return new EtablissementResponse(
                collection: collect([$etablissement]),
                status: 200
            );
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Établissements non trouvés'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(EtablissementRequest $request) {

        $this->authorize('manage etablissement');

        try {
            $data = new EtablissementDataObject(
                branch_name: $request->branch_name,
                branch_logo: $request->branch_logo,
            );

            Etablissement::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));

            return response()->json(['message' => 'L\'établissement a été enregistré avec succès'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(EtablissementRequest $request, $etab_uuid) {

        $this->authorize('manage etablissement');

        try {
            $etablissement = Etablissement::where('uuid', $etab_uuid)->firstOrFail();

            $data = new EtablissementDataObject(
                branch_name: $request->branch_name,
                branch_logo: $request->branch_logo,
            );

            $etablissement->update($data->toArray());

            return response()->json(['message' => 'L\'établissement a été mis à jour avec succès'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Établissement non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($etab_uuid) {

        $this->authorize('manage etablissement');

        try {
            $etablissement = Etablissement::where('uuid', $etab_uuid)->firstOrFail();

            $etablissement->delete();

            return response()->json(['message' => 'L\'établissement a été supprimé avec succès'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Établissement non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    
}
