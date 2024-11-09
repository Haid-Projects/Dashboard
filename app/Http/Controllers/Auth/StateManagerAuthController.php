<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StateManager;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StateManagerAuthController extends Controller
{
    use GeneralTrait;
    /**
     * Login
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()) {
            return  $this->returnValidationError('missing some credentials',400);
        }

        //$credentials = $request->only(['username', 'password']);

        $state_manager = StateManager::where('username', $request->username)->first();

        if(isset($state_manager)){
            if(Hash::check($request->password, $state_manager->password)){
                //create token
                $token = $state_manager->createToken('state_manager_token')->plainTextToken;

                //response
                return $this->returnSuccessData($token, 'state_manager logged in successfully', 200);
            }else{
                return $this->returnErrorMessage('password did not match', 400);
            }
        }
        $this->returnErrorMessage('state_manager not found ', 404);
    }

    /**
     * Logout
     */
    public function logout(){
        Auth::guard('state_manager')->user()->tokens()->delete();

        return $this->returnSuccessMessage('state_manager logged out successfully', 200);
    }
}
