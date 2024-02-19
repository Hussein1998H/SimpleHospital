<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Traits\HTTP_ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
use HTTP_ResponseTrait;
    public function login(Request $request){

        $validator=Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        if ($validator->fails())
        {
            return $this->errorResponse(false, 'validation error', $validator->errors(), 401);
        }

        if (Auth::attempt($request->only(['email', 'password']))) {

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('token-name',[$user->role])->plainTextToken;
            $role= $user->getRoleNames();
            return $this->successResponse(true, 'User Logged In Successfully', $user, $token, 200);
        }


        elseif (Auth::guard('patient')->attempt($request->only(['email','password']))){
            $patient=Patient::where('email',$request->email)->first();
            $token=$patient->createToken('API_Token_For' . $patient->name)->plainTextToken;
            return $this->successResponse(true, 'Patient Logged In Successfully', $patient, $token, 200);
        }


        else{
            return $this->errorResponse(false, 'Email & Password does not match with our record', 'ERROR', 401);

        }
    }
    public function logout(){
//        tokens
        \auth()->user()->currentAccessToken()->delete();
        return response()->json([
            'message'=>'Your logout success'
        ],200);
    }
}
