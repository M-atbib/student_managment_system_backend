<?php

use App\Http\Controllers\Api\Annonce\AnnonceController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\Document\DocumentController;
use App\Http\Controllers\Api\Emails\WelcomeEmail;
use App\Http\Controllers\Api\Etablissement\EtablissementController;
use App\Http\Controllers\Api\Group\GroupController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\Presence\PresenceController;
use App\Http\Controllers\Api\Remarque\RemarqueController;
use App\Http\Controllers\Api\Student\StudentController;
use App\Http\Controllers\Api\Timetable\TimetableController;
use App\Http\Controllers\Api\Upload\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth/login',[AuthController::class,'index'] );
Route::post('auth/student/login',[AuthController::class,'studentLogin'] );


Route::middleware(['decrypt.token','auth:sanctum'])->group(static function():void{
    
    Route::post('auth/logout', [AuthController::class, 'logout']);
    
    Route::get('auth/validate-token', [AuthController::class, 'verifyToken']);
    
    Route::get('auth/roles-permissions', [AuthController::class, 'getRolesAndPermissions']);

    Route::group(['middleware' => ['role:owner|coiffure|esthetique']], function () {

        Route::get('send-email/{username}/{loginemail}/{password}', [WelcomeEmail::class, 'sendWelcomeEmail']);
        

        Route::get('userinfo', [AuthController::class, 'userInfo']);


        Route::prefix('admin')->as('admin.')->group(function(){

            Route::get('dashboard', [DashboardController::class, 'index'])->middleware('can:view dashboardinfo');
            
            Route::prefix('etablissement')->as('etablissement.')->group(function(){
                Route::get('/', [EtablissementController::class, 'index'])->middleware('can:view etablissement');
                Route::get('/view/{etab_uuid}', [EtablissementController::class, 'view'])->middleware('can:view etablissement');
                Route::post('/create', [EtablissementController::class, 'store'])->middleware('can:manage etablissement');
                Route::put('/update/{etab_uuid}', [EtablissementController::class, 'update'])->middleware('can:manage etablissement');
                Route::delete('/delete/{etab_uuid}', [EtablissementController::class, 'delete'])->middleware('can:manage etablissement');
            });
            
            Route::post('upload/update/{action}/{student_timetable_uuid}', [UploadController::class, 'index'])->middleware('can:manage upload');

            Route::prefix('students')->as('students.')->group(function(){
                Route::get('/{etab_uuid}', [StudentController::class, 'index'])->middleware('can:view students');
                Route::get('/view/{student_uuid}', [StudentController::class, 'view'])->middleware('can:view students');
                Route::post('/create/{etab_uuid}', [StudentController::class, 'store'])->middleware('can:manage students');
                Route::put('/update/{student_uuid}', [StudentController::class, 'update'])->middleware('can:manage students');
                Route::delete('/archive/{student_uuid}', [StudentController::class, 'archive'])->middleware('can:manage students');
            });
            
            Route::prefix('groups')->as('groups.')->group(function(){
                Route::get('/{etab_uuid}', [GroupController::class, 'index'])->middleware('can:view groups');
                Route::get('/view/{group_uuid}', [GroupController::class, 'view'])->middleware('can:view groups');
                Route::post('/create', [GroupController::class, 'store'])->middleware('can:manage groups');
                Route::put('/update/{group_uuid}', [GroupController::class, 'update'])->middleware('can:manage groups');
                Route::delete('/delete/{group_uuid}', [GroupController::class, 'delete'])->middleware('can:manage groups');
            });

            Route::prefix('payments')->as('payments.')->group(function(){
                Route::post('/create', [PaymentController::class, 'store'])->middleware('can:manage payment');
            });

            Route::prefix('timetables')->as('timetables.')->group(function(){
                Route::get('/{etab_uuid}', [TimetableController::class, 'index'])->middleware('can:view timetable');
                Route::get('/view/{timetable_uuid}', [TimetableController::class, 'view'])->middleware('can:view timetable');
                Route::post('/create', [TimetableController::class, 'store'])->middleware('can:manage timetable');
                Route::put('/update/{timetable_uuid}', [TimetableController::class, 'update'])->middleware('can:manage timetable');
                Route::delete('/delete/{timetable_uuid}', [TimetableController::class, 'delete'])->middleware('can:manage timetable');
            });

            Route::prefix('annonces')->as('annonces.')->group(function(){
                Route::post('/create', [AnnonceController::class, 'store'])->middleware('can:manage annonce');
            });

            Route::prefix('remarques')->as('remarques.')->group(function(){
                Route::get('/view/{remarque_uuid}', [RemarqueController::class, 'view'])->middleware('can:view remarque');
                Route::post('/create', [RemarqueController::class, 'store'])->middleware('can:manage remarque');
                Route::put('/update/{remarque_uuid}', [RemarqueController::class, 'update'])->middleware('can:manage remarque');
                Route::delete('/delete/{remarque_uuid}', [RemarqueController::class, 'delete'])->middleware('can:manage remarque');
            });

            Route::prefix('documents')->as('documents.')->group(function(){
                Route::post('/create', [DocumentController::class, 'store'])->middleware('can:manage document');
                Route::delete('/delete/{document_uuid}', [DocumentController::class, 'delete'])->middleware('can:manage document');
            });
            
            Route::post('presences', [PresenceController::class, 'store'])->middleware('can:manage presence');
            

        });
     });
        
    
     Route::group(['middleware' => ['role:student']], function () { 
        Route::prefix('student')->as('student.')->group(function(){
            Route::get('/info', [StudentController::class, 'infostudent'])->middleware('can:view myinfo');
            Route::get('/annonces', [AnnonceController::class, 'view'])->middleware('can:view annonce');
        });

     });   

});

   
