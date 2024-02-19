<?php

namespace App\Http\Controllers;

use App\Models\Specialize;
use App\Traits\HTTP_ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecializeController extends Controller
{
    use HTTP_ResponseTrait;
    public function index(){
        $specializes=Specialize::get();
        return $this->returndata(true,$specializes,200);

    }
    public function store(Request $request){
        $validator=Validator::make($request->all(),
            [
                'name' => 'required'
            ]);
        if ($validator->fails())
        {
            return $this->errorResponse(false, 'validation error', $validator->errors(), 401);
        }

        $specialize=Specialize::create([
            'name'=>$request->name,
        ]);
        return $this->returndata(true,$specialize,200);

    }
}
