<?php

namespace App\Http\Controllers\Api\Timetable;

use App\DataTransferObjects\timetable\TimetableDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimetableRequest;
use App\Http\Resources\Timetable\TimetableCollection;
use App\Http\Resources\Timetable\TimetableResource;
use App\Models\Group;
use App\Models\Timetable;
use App\Responses\timetable\TimetableResponse;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimetableController extends Controller
{
    public function index(Request $request, $etab_uuid)
    {
        $this->authorize('view timetable');
    
        try {
            $roles = $request->user()->roles->pluck('name');
            $query = Timetable::query();
            if ($roles->contains('owner')) {
                $etabId = $etab_uuid;
                $groups = Group::where('etab_uuid', $etabId)->pluck('uuid');
                $query->whereIn('group_uuid', $groups);
            } else {
                $groups = Group::where('etab_uuid', $request->user()->branch_uuid)->pluck('uuid');
                $query->whereIn('group_uuid', $groups);
            }
            $query->with('group');
            $timetables = $query->get();
            if ($timetables->isEmpty()) {
                return response()->json(['message' => 'Aucun emploi du temps trouvé'], 404);
            }
            return new TimetableResponse(
                collection: $timetables,
                status: 200
            );
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function view(Request $request,$timetable_uuid)
    {
        $this->authorize('view timetable');

        try {
            $roles = $request->user()->roles->pluck('name');
            $query = Timetable::query()->where('uuid', $timetable_uuid)->with('group');

            if(!$roles->contains('owner'))
            {
                $query->whereHas('group', function ($query) use ($request) {
                    $query->where('etab_uuid', $request->user()->branch_uuid);
                });
            }
            $timetable = $query->firstOrFail();

            return new TimetableResponse(
                collection: collect([$timetable]),
                status: 200
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Emploi du temps non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(TimetableRequest $request)
    {
        $this->authorize('manage timetable');
    
        try {
            $file = $request->file('name_file');
            $uniqueId = uniqid();
            $fileName = $uniqueId . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/timetables', $fileName, 'public');
            $data = new TimetableDataObject($request->title, $request->group_uuid, $filePath);
    
            Timetable::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));
    
            return response()->json(['message' => 'Emploi du temps créé avec succès'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function update(TimetableRequest $request, $timetable_uuid)
    {   
        $this->authorize('manage timetable');

        try {
            $timetable = Timetable::where('uuid', $timetable_uuid)->firstOrFail();

            $data = new TimetableDataObject($request->title, $request->group_uuid,$timetable->name_file);

            $timetable->update(array_merge(
                $data->toArray(),
                [
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ])
            );

            return response()->json(['message' => 'Emploi du temps mis à jour avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Emploi du temps non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    
    public function delete($timetable_uuid)
    {
        $this->authorize('manage timetable');

        try {
            $timetable = Timetable::where('uuid', $timetable_uuid)->firstOrFail();
            Storage::disk('public')->delete($timetable->name_file);
            $timetable->delete();

            return response()->json(['message' => 'Emploi du temps supprimé avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Emploi du temps non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

}
