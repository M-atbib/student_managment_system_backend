<?php

namespace App\Http\Controllers\Api\Student;

use App\DataTransferObjects\student\StudentDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\Group;
use App\Models\Student;
use App\Models\StudentNumInscription;
use Illuminate\Support\Str;
use App\Responses\student\StudentResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request,$etab_uuid) {

        $this->authorize('view students');

        try {
            // ?name=John&sector=coiffure&training_level=Level%201&status=active&date_start_at=2024-06-08
            $roles = $request->user()->roles->pluck('name');
            $query = Student::query();
    
            if ($roles->contains('owner')) {
                $etabId = $etab_uuid; 
                $groups = Group::where('etab_uuid', $etabId)->pluck('uuid');
                $query->whereIn('group_uuid', $groups);

                if ($request->filled('sector')) {
                    $query->where('sector', $request->query('sector'));
                }

            } else {
                $groups = Group::where('etab_uuid', $request->user()->branch_uuid)->pluck('uuid');
                $query->whereIn('group_uuid', $groups)->whereIn('sector', $roles);
            }
    
            if ($request->filled('group_uuid')) {
                $query->where('group_uuid', $request->query('group_uuid'));
            }
            
            if ($request->filled('name')) {
                $query->where('full_name', 'like', '%' . $request->query('name') . '%');
            }
    
            if ($request->filled('training_level')) {
                $query->where('training_level', $request->query('training_level'));
            }
    
            if ($request->filled('status')) {
                $query->where('status', $request->query('status'));
            }
    
            if ($request->filled('date_start_at')) {
                $dateRange = explode('/', $request->query('date_start_at'));
                if (count($dateRange) == 2) {
                    $startDate = date('Y-m-d', strtotime('09/01/' . $dateRange[0]));
                    $endDate = date('Y-m-d', strtotime('08/31/' . $dateRange[1]));
                    $query->whereBetween('date_start_at', [$startDate, $endDate]);
                }
            }
            $students = $query->orderBy('updated_at', 'desc')->paginate(25);
    
            if ($students->isEmpty()) {
                return response()->json(['message' => 'Étudiants non trouvés'], 404);
            }
    
            return new StudentResponse(
                collection: $students,
                status: 200
            );
        }catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function view(Request $request, $student_uuid) {
        $this->authorize('view students');
    
        try {
            $roles = $request->user()->roles->pluck('name');
            $query = Student::where('uuid', $student_uuid)->with(['groups','payments','remarques','documents','presences']);
            if ($roles->contains('owner')) {
                $student = $query->firstOrFail();
            }else{
                $student = $query->whereIn('sector', $roles)->firstOrFail();
            }
            
            return new StudentResponse(
                collection: collect([$student]),
                status: 200
            );
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Étudiant non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StudentRequest $request, $etab_uuid)
    {
        $this->authorize('manage students');

        try {
            $filePath = null;
            
            if ($request->file('photo')) {
                $file = $request->file('photo');
                $uniqueId = uniqid();
                $fileName = $uniqueId . '_' . $file->getClientOriginalName();
                $filePathurl = 'uploads/student/document/' . $fileName;
                $filePath = Storage::disk('b2')->put($filePathurl, $file);
                
            }

            $responsableJson = json_encode($request->responsable);

            $data = new StudentDataObject(
                $request->inscription_number,
                $request->CIN,
                $request->id_massar,
                $request->full_name,
                $request->birth_date,
                $request->birth_place,
                $request->gender,
                $request->school_level,
                $request->phone_number,
                $request->address,
                $request->email,
                $responsableJson,
                $filePath,  
                $request->training_duration,
                $request->sector,
                $request->filières_formation,
                $request->training_level,
                $request->group_uuid,
                $request->monthly_amount,
                $request->registration_fee,
                $request->product,
                $request->frais_diplôme,
                $request->annual_amount,
                $request->status,
                $request->date_start_at,
                $request->date_fin_at
            );

            $student = Student::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));

            $student->assignRole('student');

            // $numInscription->update([
            //     'inscription_num' => $num,
            //     'updated_at' => now()->timezone('Africa/Casablanca')
            // ]);

            return response()->json(['message' => 'L\'étudiant a été enregistré avec succès'], 200);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(StudentRequest $request, $student_uuid) {

        $this->authorize('manage students');
    
        try {
            $student = Student::where('uuid', $student_uuid)->firstOrFail();
            
            $inscription_number = $student->inscription_number;

            $responsableJson = json_encode($request->responsable);

            $data = new StudentDataObject(
                $inscription_number,
                $request->CIN,
                $request->id_massar,
                $request->full_name,
                $request->birth_date,
                $request->birth_place,
                $request->gender,
                $request->school_level,
                $request->phone_number,
                $request->address,
                $request->email,
                $responsableJson,
                $student->photo,
                $request->training_duration,
                $request->sector,
                $request->filières_formation,
                $request->training_level,
                $request->group_uuid,
                $request->monthly_amount,
                $request->registration_fee,
                $request->product,
                $request->frais_diplôme,
                $request->annual_amount,
                $request->status,
                $request->date_start_at,
                $request->date_fin_at
            );
    
            $student->update(array_merge(
                $data->toArray(),
                [
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));
    
            return response()->json(['message' => 'Étudiant mis à jour avec succès'], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Étudiant non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }    

    public function archive($student_uuid) {
        $this->authorize('manage students');
    
        try {
            $student = Student::where('uuid', $student_uuid)->firstOrFail();
            $student->update(['status'=>'archive']);
    
            return response()->json(['message' => 'Étudiant supprimé avec succès'], 200);
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Étudiant non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function infostudent(Request $request){
        $this->authorize('view myinfo');
        try {
            
            $student = Student::where('uuid', $request->user()->uuid)
            ->with(['groups','payments', 'remarques', 'documents','presences'])
            ->firstOrFail();
            
            return new StudentResponse(
                collection: collect([$student]),
                status: 200
            );
    
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Info Étudiant non trouvé'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    
}
