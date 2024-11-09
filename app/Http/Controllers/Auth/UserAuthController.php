<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    use GeneralTrait;
    /**
     * Register
     */
    public function register(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required',
            'full_name' => 'required',
            'governorate' => 'required',
            'address' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError( $validator->errors()->first(),400);
        }

        //register user
        $user = User::create([
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'governorate' => $request->governorate,
            'address' => $request->address,
            'landline' => $request->landline ?? null,
            //'rate' => null,
        ]);

        //go to login
        return $this->login($request);
    }

    /**
     * Login
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }

        //$credentials = $request->only(['phone_number', 'password']);

        $user = User::where('phone_number', $request->phone_number)->first();

        if(isset($user)){
            if(Hash::check($request->password, $user->password)){
                //create token
                $token = $user->createToken('user_token')->plainTextToken;

                //response
                return $this->returnSuccessData($token, 'user logged in successfully', 200);
            }else{
                return $this->returnErrorMessage('password did not match', 400);
            }
        }
        return $this->returnErrorMessage('user not found ', 404);
    }

    /**
     * Logout
     */
    public function logout(){
        Auth::guard('user')->user()->tokens()->delete();

        return $this->returnSuccessMessage('user logged out successfully', 200);
    }

    /**
     * Profile
     */
    public function profile(){
        $user = Auth::guard('user')->user();
        return $this->returnSuccessData($user, 'profile of the user', 200);
    }
}
