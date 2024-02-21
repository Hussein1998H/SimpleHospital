<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Traits\HTTP_ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    use HTTP_ResponseTrait;
    public function index(){

        $patients=Patient::get();
        return $this->returndata(true,$patients,200);

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
      $patient=Patient::create([
          'name' => $request->name,
          'email' => $request->email,
          'phone' => $request->phone,
          'password' => Hash::make($request->password),
        ]);

        $patient->assignRole('user');

        return $this->returndata(true,$patient,200);
    }
}
