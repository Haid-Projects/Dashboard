<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    use GeneralTrait;
    /**
     * Login
     */
    public function loginPage(){
        return view('auth');
    }
    public function login(Request $request){
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

         if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('main');
         }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


//  public function login(Request $request){
//        $validator = Validator::make($request->all(), [
//            'username' => 'required',
//            'password' => 'required',
//        ]);
//        if($validator->fails()) {
//            return  $this->returnValidationError('missing some credentials',400);
//        }
//
//        //$credentials = $request->only(['username', 'password']);
//
//        $admin = Admin::where('username', $request->username)->first();
//
//        if(isset($admin)){
//            if(Hash::check($request->password, $admin->password)){
//                //create token
//                //$token = $admin->createToken('admin_token')->plainTextToken;
//               // $request->authenticate();
//            //    $request->session()->regenerate();
//                Session::token();
//                return redirect()->intended(RouteServiceProvider::HOME);
//                //response
//                return $this->returnSuccessData($token, 'admin logged in successfully', 200);
//            }else{
//                return $this->returnErrorMessage('password did not match', 400);
//            }
//        }
//        $this->returnErrorMessage('admin not found ', 404);
//    }

    /**
     * Logout
     */
    public function logout(Request $request){
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function test(Request $request){
        return auth('admin')->user();
    }
}
