<?php

namespace App\Http\Controllers\Api\Document;

use App\DataTransferObjects\Document\DocumentDataObject;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Document;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(DocumentRequest $request)
    {
        $this->authorize('manage document');

        try {
            $file = $request->file('name_file');
            $uniqueId = uniqid();
            $fileName = $uniqueId . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/student/document/' . $fileName;

            Storage::disk('b2')->put($filePath, file_get_contents($file));

            $data = new DocumentDataObject($filePath, $request->student_uuid);

            Document::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));

            return response()->json(['message' => 'Document créé avec succès'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($document_uuid)
    {
        $this->authorize('manage remarque');

        try {
            $document = Document::where('uuid', $document_uuid)->firstOrFail();
            Storage::disk('b2')->delete($document->name_file);
            $document->delete();

            return response()->json(['message' => 'Document supprimée avec succès'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Document non trouvée'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}