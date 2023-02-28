<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Phone;
use App\Models\Service;
use App\Models\User;

class RelationsController extends Controller
{
    public function hasOneRelation()
    {
        $user = \App\Models\User::with(['phone' => function ($q) {
            $q->select('code', 'phone','user_id');
        }])->find(31); //hna 3ayez ageeb data el user w data table el phone bta3 nfs el user..bs 3ayez code w phone bs mn table el phone

      //  return $user->phone->code;
        return response()->json($user);
    }


    public function hasOneRelationReverse(){
          $phone=Phone::with(['user'=>function($q){
              $q->select('name','id');
          }]) ->find(1);

          //make attribute visible while it's hidden like:user_id
        $phone->makeVisible(['user_id']);

        //make attribute hidden while it's visible
       // $phone->makeHidden(['code']);

       // return $phone->user; //return user of this phone

        //get all data phone+user

       return $phone;
    }

    public function getUserHasPhone(){
     // return User::whereHas('phone')->get();
        return User::whereHas(['phone',function($q){
            $q->where('code','02');
        }])->get();

    }

    public function getUserNotHasPhone(){
        return User::whereDoesntHave('phone')->get();
    }

    #####################  one to many relationship methods #################

    public function getHospitalDoctors(){
        $hospital = Hospital::find(1);  // Hospital::where('id',1) -> first();  //Hospital::first();

        // return  $hospital -> doctors;   // return hospital doctors

        $hospital = Hospital::with('doctors')->find(1);

        //return $hospital -> name;


        $doctors = $hospital->doctors;

        /* foreach ($doctors as $doctor){
            echo  $doctor -> name.'<br>';
         }*/

        $doctor = Doctor::find(3);

        return $doctor->hospital->name;
    }

    public function hospitals(){
      $hospitals=  Hospital::select('id','name','address')->get();
        return view('doctors.hospitals',compact('hospitals'));
    }

    public function doctors($hospital_id)
    {

        $hospital = Hospital::find($hospital_id);
        $doctors = $hospital->doctors;
        return view('doctors.doctors', compact('doctors'));
    }

    public function hospitalsHasDoctor(){
      return $hospital= Hospital::whereHas('doctors')->get();
    }
    public function hospitalsHasOnlyMaleDoctors(){
        return $hospital= Hospital::with('doctors')->whereHas('doctors' ,function($q){
$q->where('gender',1);
        })->get();

    }

    public function hospitals_not_has_doctors(){
      return  Hospital::whereDoesntHave('doctors')->get();
    }

    public function deleteHospital($hospital_id)
    {
        $hospital = Hospital::find($hospital_id);
        if (!$hospital)
            return abort('404');
        //delete doctors in this hospital
        $hospital->doctors()->delete();
        $hospital->delete();

        return redirect()->route('hospital.doctors');
    }

    public function getDoctorServices(){
      return $doctor=Doctor::with('services')->find(1);
    //   return $doctor->service();
    }

    public function getServiceDoctors(){
       return $doctors=Service::with('doctors')->find(1);
    }

    public function getDoctorServicesById($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        $services = $doctor->services;  //doctor services

        $doctors = Doctor::select('id', 'name')->get();
        $allServices = Service::select('id', 'name')->get(); // all db serves

        return view('doctors.services', compact('services', 'doctors', 'allServices'));
    }

    public function saveServicesToDoctors(Request $request)
    {

        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor)
            return abort('404');
        // $doctor ->services()-> attach($request -> servicesIds);  // many to many insert to database
        //$doctor ->services()-> sync($request -> servicesIds);
        $doctor->services()->syncWithoutDetaching($request->servicesIds); //bt5zn fel database w btt check law el service de mawgoda abl kda msh htsglha
        return 'success';
    }

//has one through
    public function getPatientDoctor(){
        $patient= Patient::find(1);
        return $patient->doctor;
    }

    //has many through
    public function getCountryDoctor(){
        $country=Country::find(1);
        return $country->doctors;
    }

    public function getDoctors()
    {
        return $doctors = Doctor::select('id', 'name', 'gender')->get();
        /* if (isset($doctors) && $doctors->count() > 0) {
             foreach ($doctors as $doctor) {
                 $doctor->gender = $doctor->gender == 1 ? 'male' : 'female';
                 // $doctor -> newVal = 'new';
             }
         }
         return $doctors;*/
    }
}
