<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table='doctors';
    protected $fillable=['name','title','hospital_id','medical_id','created_at','updated_at'];
    protected $hidden=['created_at','updated_at'];


    public function hospital(){
        return $this->belongsTo('App\Models\Hospital','hospital_id');
    }

    public function service()
    {
        return $this -> belongsToMany('App\Models\Service','doctor_service','doctor_id','service_id');
    }


    //accessors
    public function getGenderAttribute($val){

        return $val == 1 ? 'male' : 'female';
    }
}
