<?php

namespace App\Http\Controllers;

use App\Models\Specialize;
use App\Models\User;
use App\Traits\HTTP_ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HTTP_ResponseTrait;
    public function index(){

        $doctors=User::with('specialize')->Role('doctor')->get();
        return $this->returndata(true,$doctors,200);

    }

    public function store(Request $request){
        $validator=Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:patients,email',
                'phone' => 'required|digits_between:8,15',
                'password' => 'required|min:8',
            ]);
        if ($validator->fails())
        {
            return $this->errorResponse(false, 'validation error', $validator->errors(), 401);
        }
        $spicl=Specialize::where('name',$request->specialize)->first();
        if ($spicl){
            $doctor=User::create([
                'special_id'=>$spicl->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            $doctor->assignRole('doctor');

            return $this->returndata(true,$doctor,200);
        }
        else{
            return $this->errorResponse(false,'We Dont Found Specialize');
        }

    }
}
