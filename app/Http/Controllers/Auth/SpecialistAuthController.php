<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Specialist;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SpecialistAuthController extends Controller
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

        $specialist = Specialist::where('username', $request->username)->first();

        if(isset($specialist)){
            if(Hash::check($request->password, $specialist->password)){
                //create token
                $token = $specialist->createToken('specialist_token')->plainTextToken;

                //response
                return $this->returnSuccessData($token, 'specialist logged in successfully', 200);
            }else{
                return $this->returnErrorMessage('password did not match', 400);
            }
        }
        $this->returnErrorMessage('specialist not found ', 404);
    }

    /**
     * Logout
     */
    public function logout(){
        Auth::guard('specialist')->user()->tokens()->delete();

        return $this->returnSuccessMessage('specialist logged out successfully', 200);
    }
}
