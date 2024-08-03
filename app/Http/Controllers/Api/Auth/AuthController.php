<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Group;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(LoginRequest $request) : JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Les informations d\'identification fournies sont incorrectes.'], 401);
            }

            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->plainTextToken;

            $tokenModel = $tokenResult->accessToken;
            $tokenModel->expires_at = now()->timezone('Africa/Casablanca')->addDays(30);
            $tokenModel->last_used_at = now()->timezone('Africa/Casablanca');
            $tokenModel->save();

            $roles = $user->roles->pluck('name');
            $permissions = $user->getAllPermissions()->pluck('name');

            $encryptedToken = Crypt::encrypt(base64_encode($token));

            return response()->json([
                'token' => $encryptedToken,
                'roles' => $roles,
                'etab_uuid' => $user->branch_uuid,
                'permissions' => $permissions
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function studentLogin(LoginRequest $request) : JsonResponse
    {
        try {
            $student = Student::where('email', $request->email)->first();

            if (!$student || !Hash::check($request->password, $student->password)) {
                return response()->json(['message' => 'Les informations d\'identification fournies sont incorrectes.'], 401);
            }
            
            $tokenResult = $student->createToken('authToken');
            $token = $tokenResult->plainTextToken;

            $tokenModel = $tokenResult->accessToken;
            $tokenModel->expires_at = now()->timezone('Africa/Casablanca')->addDays(30);
            $tokenModel->last_used_at = now()->timezone('Africa/Casablanca');
            $tokenModel->save();

            $etab_uuid = Group::where('uuid',$student->group_uuid)->first();
            $roles = $student->roles->pluck('name');
            $permissions = $student->getAllPermissions()->pluck('name');

            $encryptedToken = Crypt::encrypt(base64_encode($token));

            return response()->json([
                'token' => $encryptedToken,
                'roles' => $roles,
                'etab_uuid' => $etab_uuid->etab_uuid,
                'permissions' => $permissions
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request) : JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function userInfo(Request $request) : JsonResponse
    {
        try {
            $user = $request->user();
            $roles = $user->roles->pluck('name');
            $permissions = $user->getAllPermissions()->pluck('name');

            return response()->json([
                'user' => [
                    'uuid' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'branch_uuid' => $user->branch_uuid,
                    'roles' => $roles,
                    'permissions' => $permissions
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function verifyToken(Request $request)
    {
        return response()->json(['message' => 'Token is valid'], 200);
    }
    public function getRolesAndPermissions(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles->pluck('name');
        $permissions = $user->getAllPermissions()->pluck('name');
        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

}
