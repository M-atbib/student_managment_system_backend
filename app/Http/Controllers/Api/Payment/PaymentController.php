<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\DataTransferObjects\payment\PaymentDataObject;
use Illuminate\Support\Str;
use Exception;

class PaymentController extends Controller
{
    public function store(PaymentRequest $request)
    {
        $this->authorize('manage payment');
        try {
            $data = new PaymentDataObject(
                student_uuid: $request->student_uuid,
                type: $request->type,
                methode: $request->methode,
                montant: $request->montant,
                month: $request->month,
                date_payment: $request->date_payment
            );

            Payment::create(array_merge(
                ['uuid' => (string) Str::uuid()],
                $data->toArray(),
                [
                    'created_at' => now()->timezone('Africa/Casablanca'),
                    'updated_at' => now()->timezone('Africa/Casablanca')
                ]
            ));

            return response()->json(['message' => 'Le paiement a été enregistré avec succès'], 200);

        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}
