<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Reservation;
use App\Models\Specialize;
use App\Models\User;
use App\Traits\HTTP_ResponseTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    use HTTP_ResponseTrait,SoftDeletes;
    public function index(){
       $reservations=Reservation::with(['doctors','patients'])->get();
       return $this->returndata(true,$reservations,200);
    }
    public function store(Request $request){




        $validator=Validator::make($request->all(),
            [
                'doctorname' => 'required',
                'clock' => 'required',
            ]);
        if ($validator->fails())
        {
            return $this->errorResponse(false, 'validation error', $validator->errors(), 401);
        }

        $doctor=User::Role('doctor')->where('name',$request->doctorname)->first();
        $user=Auth::user();
        $today = Carbon::now()->toDateString();
//        $formattedDate = Carbon::parse($today)->format('Y/m/d');
        $reservation=Reservation::with(['doctors','patients'])
            ->where('doctor_id',$doctor->id)
            ->where('day',$today)
            ->where('clock',$request->clock)
            ->first();

        if ($reservation ==null){
            $reserv=Reservation::create([
                'doctor_id' =>$doctor->id,
                'patient_id' => $user->id,
                'day' => $today,
                'clock' => $request->clock,
            ]);
            return $this->returndata(true,$reserv,200);
        }
       else{

           return response()->json([
               'message'=>'this time is Reservated'
           ]);

       }
    }
    public function reservDoctortime(Request $request){

        $time=['1:00 am','2:00 am','3:00 am','4:00 am','5:00 am','6:00 am',
            '7:00 am','8:00 am','9:00 am','10:00 am','11:00 am','12:00 am',
            '1:00 pm','2:00 pm','3:00 pm','4:00 pm','5:00 pm','6:00 pm',
            '7:00 pm','8:00 pm','9:00 pm','10:00 pm','11:00 pm','12:00 pm'];
        $today = Carbon::now()->toDateString();

        $doctor=User::Role('doctor')->where('name',$request->doctorname)->first();
        $reservation=Reservation::with(['doctors','patients'])
            ->where('doctor_id',$doctor->id)
            ->where('day',$today)
            ->withTrashed()
            ->get();
        $doctorRtime=[];
        $freetime=[];
        foreach ($reservation as $res){
            array_push($doctorRtime,$res->clock);
        }
        foreach ($time as $ftime){
            if (!in_array($ftime, $doctorRtime))
            {
                array_push($freetime,$ftime);

            }
        }
        return $this->returndata(true,$freetime,200);

    }

    public function DoctorReservation(){

        $doctor=Auth::user();
        $reservation=Reservation::with('patients')->where('doctor_id',$doctor->id)->get();
        return $this->returndata(true,$reservation,200);
    }

    public function AcceptDoctorReservation(){

        $doctor=Auth::user();
        $reservation=Reservation::with('patients')->where('doctor_id',$doctor->id)
            ->onlyTrashed()
            ->get();
        return $this->returndata(true,$reservation,200);
    }

    public function deleteReservation( $id){
        $reservation=Reservation::where('id',$id)->first();
//       $reservation->delete();
//        $soft= $reservation->fresh();
        $reservation->forceDelete();

        return response()->json([
            'message'=>'done'
        ]);
    }

    public function deleteacceptReservation( $id)
    {
        $reservation = Reservation::withTrashed()->where('id', $id)->forceDelete();
//       $reservation->delete();
//        $soft= $reservation->fresh();


        return response()->json([
            'message' => 'done'
        ]);
    }
    public function acceptReservation( $id){
        $reservation=Reservation::where('id',$id)->first();
       $reservation->delete();
         $reservation->fresh();
        return response()->json([
            'message'=>'done'
        ]);
    }

}
