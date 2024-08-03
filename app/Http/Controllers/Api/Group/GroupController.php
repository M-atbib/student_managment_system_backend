<?php

namespace App\Http\Controllers\Api\Group;

use App\DataTransferObjects\group\GroupDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Responses\group\GroupResponse;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request, $etab_uuid){
        $this->authorize('view groups');

        try {
            $roles = $request->user()->roles->pluck('name');
            
            if($roles->contains('owner'))
            {
                $etabId = $etab_uuid;
                $groups = Group::where('etab_uuid', $etabId)->orderBy('updated_at', 'desc')->get();
            }else{
                $groups = Group::where('etab_uuid', $request->user()->branch_uuid)->orderBy('updated_at', 'desc')->get();
            }
                if ($groups->isEmpty()) {
                    return response()->json(['message' => 'Aucun groupe trouvé pour cet établissement.'], 404);
                }
                return new GroupResponse(
                    collection: $groups,
                    status: 200
                );
        }catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }
    public function view(Request $request,$group_uuid) {
        $this->authorize('view groups');
    
        try {
            $roles = $request->user()->roles->pluck('name');
            $query = Group::query()->where('uuid', $group_uuid);
            if(!$roles->contains('owner'))
            {
                $query->where('etab_uuid', $request->user()->branch_uuid);
            }
            
            $group = $query->firstOrFail();
            
            return new GroupResponse(
                collection: collect([$group]),
                status: 200
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Groupe non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function store(GroupRequest $request) {
        $this->authorize('manage groups');
    
        try {
            $data = new GroupDataObject($request->name, $request->etab_uuid);
    
            Group::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ])
            );
            
            return response()->json(['message' => 'Groupe créé avec succès'], 201);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Groupe non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function update(GroupRequest $request, $group_uuid) {
        $this->authorize('manage groups');
    
        try {
            $group = Group::where('uuid', $group_uuid)->firstOrFail();
    
            $data = new GroupDataObject($request->name, $request->etab_uuid);
    
            $group->update(array_merge(
                $data->toArray(),
                [
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ])
            );
            
            return response()->json(['message' => 'Groupe mis à jour avec succès'], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Groupe non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function delete($group_uuid) {
        $this->authorize('manage groups');
    
        try {
            $group = Group::where('uuid', $group_uuid)->firstOrFail();
            $group->delete();
            
            return response()->json(['message' => 'Groupe supprimé avec succès'], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Groupe non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}
