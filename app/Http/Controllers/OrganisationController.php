<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganisationController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        $organisations = $user->organisationsPublicColumns;
        foreach($organisations as $key => $value){ 
            unset($organisations[$key]['pivot']);
        }
        return ResponseHelper::response('success', "Retrieved all organisations successfully", ['organisations' => $organisations], 200);
    }

    public function show($orgId)
    {
        $user = auth('api')->user();
        $organisation = $user->organisationsPublicColumns()->where('orgId', $orgId)->first();

        if (!$organisation) {
            return ResponseHelper::response('Not found', "Organisation not found", null, 404);
        }
        unset($organisation['pivot']);
        return ResponseHelper::response('success', "Retrieved organisation successfully", $organisation, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors());
        }
        $user = auth('api')->user();

        DB::beginTransaction();
        try {
            $organisation = Organisation::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $organisation->users()->attach((string)$user->userId);
            DB::commit();
            return ResponseHelper::response('success', "Organisation created successfully", $organisation->getPublicColumns(), 201);
        }catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::response('Bad request', "Client error", null, 400);
        }
    }

    public function addUser(Request $request, $orgId)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|uuid|exists:users,userId',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::validationError($validator->errors());
        }

        $user = auth('api')->user();
        $organisation = $user->organisations()->where('orgId', $orgId)->first();

        if(!User::find($request->userId)){
            return ResponseHelper::response('Not found', "User not found", null, 404);
        }

        if (!$organisation) {
            return ResponseHelper::response('Not found', "Organisation not found", null, 404);
        }

        if($organisation->users()->where('userId', $request->userId)->first()){
            return ResponseHelper::response('Already exists', "User already exists in organisation", null, 403);
        }

        $organisation->users()->attach((string)$request->userId);
        return ResponseHelper::response('success', "User added to organisation", null, 200);
    }
}
