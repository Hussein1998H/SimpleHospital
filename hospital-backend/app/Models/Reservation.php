<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'doctor_id',
         'patient_id',
         'day',
         'clock',
    ];
    public function doctors():BelongsTo{
        return $this->belongsTo(User::class,'doctor_id','id');
    }
    public function patients():BelongsTo{
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
}
