<?php

namespace App\Http\Controllers\Api\Upload;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Models\Student;
use App\Models\Timetable;
use Exception;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index(UploadRequest $request,$action, $student_timetable_uuid)
    {
        $this->authorize('manage upload');

        try {
            if ($request->hasFile('name_file')) {
                $file = $request->file('name_file');
                $uniqueId = uniqid();
                $fileName = $uniqueId . '_' . $file->getClientOriginalName();
            }
            if($action == "student")
            {
                $data = Student::where('uuid', $student_timetable_uuid)->firstOrFail();

                $filePath = $file->storeAs('uploads/student/image', $fileName, 'public');

                if ($data->photo && Storage::disk('public')->exists($data->photo)) {
                    Storage::disk('public')->delete($data->photo);
                }

                $data->update(['photo' => $filePath,'updated_at' => now()->timezone('Africa/Casablanca')]);

            }elseif($action == "timetable"){
                $data = Timetable::where('uuid', $student_timetable_uuid)->firstOrFail();

                $filePath = $file->storeAs('uploads/timetables', $fileName, 'public');

                if ($data->name_file && Storage::disk('public')->exists($data->name_file)) {
                    Storage::disk('public')->delete($data->name_file);
                }

                $data->update(['name_file' => $filePath,'updated_at' => now()->timezone('Africa/Casablanca')]);
            }
            
            return response()->json(['message' => 'Mis à jour avec succès'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    
}
