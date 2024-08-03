<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Exception;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('view dashboardinfo');

        try {
            $students = Student::count(); 
            $money_now = Payment::sum('montant');

            $total_students = Student::count();
            $active_students = Student::where('status', 'active')->count();
            $taux_inscription = ($total_students > 0) ? ($active_students / $total_students) * 100 : 0;

            $now = Carbon::now()->timezone('Africa/Casablanca');
            if ($now->month >= 9) {
                $start_of_school_year = Carbon::create($now->year, 9, 1);
                $end_of_school_year = Carbon::create($now->year + 1, 8, 31);
            } else {
                $start_of_school_year = Carbon::create($now->year - 1, 9, 1);
                $end_of_school_year = Carbon::create($now->year, 8, 31);
            }

            $income_year = Payment::whereBetween('date_payment',[$start_of_school_year, $end_of_school_year])->sum('montant');

            return response()->json([
                'students_count' => $students,
                'current_money' => $money_now,
                'taux_inscription' => $taux_inscription,
                'income_year' => $income_year,
                'school_year' => $start_of_school_year->format('Y')."-".$end_of_school_year->format('Y'),
                
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur interne du serveur', 'error' => $e->getMessage()], 500);
        }
    }
}
