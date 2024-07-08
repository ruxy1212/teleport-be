<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
        ]);
  
        if($validator->fails()){
            return ResponseHelper::validationError($validator->errors());
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);
            $user->createOrganisation();
            DB::commit();
            $token = auth('api')->login($user);
            $data = ['accessToken' => $token, 'user' => $user->getPublicColumns()];
            return ResponseHelper::response('success', "Registration successful", $data, 201);
        }catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::response('Bad request', "Registration unsuccessful".$e, null, 400);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return ResponseHelper::response('Bad request', "Authentication failed", null, 401);
        }
        $user = auth('api')->user()->getPublicColumns();
        $data = ['accessToken' => $token, 'user' => $user];
        return ResponseHelper::response('success', "Login successful", $data, 200);
    }

    public function show($userId){
        $auth_user = auth('api')->user();
        if((string)$auth_user->userId === $userId){
            return ResponseHelper::response('success', "User retrieved successfully", $auth_user->getPublicColumns(), 200);
        }else{
            if($search_user = User::with('organisations')->find($userId)){
                $sharedOrganisations = $auth_user->organisations->intersect($search_user->organisations);
                if ($sharedOrganisations->isEmpty()) {
                    return ResponseHelper::response('Unauthorized', "You do not have access to this user's record", null, 403);
                }
                return ResponseHelper::response('success', "User retrieved successfully", $search_user->getPublicColumns(), 200);
            }else{
                return ResponseHelper::response('Not found', "User not found", null, 404);
            }
        }
    }
}
