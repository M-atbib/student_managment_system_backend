<?php

namespace App\Http\Controllers\Api\Annonce;

use App\DataTransferObjects\Annonce\AnnonceDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnnonceRequest;
use App\Models\Annonce;
use App\Models\Group;
use App\Responses\annonce\AnnonceResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function store(AnnonceRequest $request) {
        $this->authorize('manage annonce');
    
        try {
            $data = new AnnonceDataObject($request->text, $request->etab_uuid,$request->sector,$request->date_validite);
    
            Annonce::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ])
            );
            
            return response()->json(['message' => 'Annonce créée avec succès'], 201);
    
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function view(Request $request){
        $this->authorize('view annonce');
        try {
            $group_uuid = $request->user()->group_uuid;
            
            $group = Group::where('uuid',$group_uuid)->firstOrFail();

            $annonces = Annonce::where('etab_uuid', $group->etab_uuid)
                           ->where('date_validite', '>', now()->timezone('Africa/Casablanca'))
                           ->get();
           
            
            return new AnnonceResponse(
                collection: $annonces,
                status: 200
            );
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Annonces non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}
