<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Mail\NotifyEmail;
use Illuminate\Support\Facades\Mail;


define('PAGINATION_COUNT',3);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
//Route::get('/', function () {
//
//    $data = [];
//    $data['id'] = 5;
//    $data['name'] = 'ahmed emam';
//    return view('welcome', $data);
//});
//
//Route::get('index', 'Front\UserController@getIndex');
//
//Route::get('/test1', function () {
//    return ('welcome');
//});
//
//Route::get('/landing', function () {
//    return view('landing');
//});
//
////route parameters
//
//Route::get('/show-number/{id}', function ($id) {
//    return $id;
//})->name('a'); //da esm 3ashan n7oto fel view badal ma t7ot el url kolo
//
//////route name
//Route::namespace('Front')->group(function () {
//    //all route only access controller or methods in folder name front
//    Route::get('users', 'UserController @showAdminName');
//});
//
//
//Route::resource('news', 'NewsController');

Route::get('/dashboard', function () {
    return 'not adult';
})->name('not.adult');

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');

Route::get('/redirect/{service}', 'App\Http\Controllers\SocialController@redirect');

Route::get('/callback/{service}', 'App\Http\Controllers\SocialController@callback');


Route::get('/fillable', 'App\Http\Controllers\CrudController@getOffers');


Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    Route::group(['prefix' => 'offers'], function () {
        //   Route::get('store', 'CrudController@store');
        Route::get('create', 'App\Http\Controllers\CrudController@create');
        Route::post('store', 'App\Http\Controllers\CrudController@store')->name('offers.store');

        Route::get('edit/{offer_id}', 'App\Http\Controllers\CrudController@editOffer');
        Route::post('update/{offer_id}', 'App\Http\Controllers\CrudController@updateOffer')->name('offers.update');
        Route::get('delete/{offer_id}', 'App\Http\Controllers\CrudController@delete')->name('offers.delete');


        Route::get('all', 'App\Http\Controllers\CrudController@getAllOffers')->name('offers.all');
        Route::get('get-all-inactive-offers', 'App\Http\Controllers\CrudController@getAllInactiveOffers');

    });

    Route::get('youtube','App\Http\Controllers\CrudController@getVideo');

});



############################ Begin ajax routes #######################################
Route::group(['prefix'=>'ajax-offers'],function(){
    Route::get('create','App\Http\Controllers\OfferController@create');
    Route::post('store','App\Http\Controllers\OfferController@store')->name('ajax.offers.store');
    Route::get('all', 'App\Http\Controllers\OfferController@all')->name('ajax.offers.all');
    Route::post('delete', 'App\Http\Controllers\OfferController@delete')->name('ajax.offers.delete');
    Route::get('edit/{offer_id}', 'App\Http\Controllers\OfferController@edit')->name('ajax.offers.edit');
    Route::post('update', 'App\Http\Controllers\OfferController@update')->name('ajax.offers.update');

});

############################ end ajax routes #######################################


############################ Authentication && Guards ###############################
Route::group(['middleware'=>'CheckAge'],function (){
    Route::get('adults','App\Http\Controllers\Auth\CustomAuthController@adult')->name('adult');

});
                                                                                        //middleware('auth:web')=('auth')
Route::get('site','App\Http\Controllers\Auth\CustomAuthController@site')->middleware('auth')->name('site');
Route::get('admin','App\Http\Controllers\Auth\CustomAuthController@admin')->middleware('auth:admin')->name('admin');

Route::get('admin/login','App\Http\Controllers\Auth\CustomAuthController@adminLogin')->name('admin.login');
Route::post('admin/login','App\Http\Controllers\Auth\CustomAuthController@checkadminLogin')->name('save.admin.login');


############################ End Authentication && Guards ###############################




###################### Begin relations ######################
Route::get('has-one','App\Http\Controllers\Relation\RelationsController@hasOneRelation');

Route::get('has-one-reverse','App\Http\Controllers\Relation\RelationsController@hasOneRelationReverse');

Route::get('get-user-has-phone','App\Http\Controllers\Relation\RelationsController@getUserHasPhone');

Route::get('get-user-not-has-phone','App\Http\Controllers\Relation\RelationsController@getUserNotHasPhone');

##################### begin one to many relationship#################
Route::get('hospital-has-many','App\Http\Controllers\Relation\RelationsController@getHospitalDoctors');

Route::get('hospitals','App\Http\Controllers\Relation\RelationsController@hospitals')->name('hospital.all');

Route::get('doctors/{hospital_id}','App\Http\Controllers\Relation\RelationsController@doctors')->name('hospital.doctors');

Route::get('hospitals/{hospital_id}','App\Http\Controllers\Relation\RelationsController@deleteHospital')->name('hospital.delete');

Route::get('hospitals_has_doctors','App\Http\Controllers\Relation\RelationsController@hospitalsHasDoctor');

Route::get('hospitals_has_doctors','App\Http\Controllers\Relation\RelationsController@hospitalsHasOnlyMaleDoctors');

Route::get('hospitals_not_has_doctors','App\Http\Controllers\Relation\RelationsController@hospitals_not_has_doctors');

##################### End one to many relationship#################



##################### Begin many to many relationship#################
Route::get('doctors-services','App\Http\Controllers\Relation\RelationsController@getDoctorServices');

Route::get('services-doctors','App\Http\Controllers\Relation\RelationsController@getServiceDoctors');

Route::get('doctors/services/{doctor_id}','App\Http\Controllers\Relation\RelationsController@getDoctorServicesById')->name('doctors.services');

Route::post('saveServices-to-doctor','App\Http\Controllers\Relation\RelationsController@saveServiceToDoctors')->name('save.doctors.services');

##################### End many to many relationship#################


##################### has one & many through ##########################
Route::get('has-one-through','App\Http\Controllers\Relation\RelationsController@getPatientDoctor');

Route::get('has-many-through','App\Http\Controllers\Relation\RelationsController@getCountryDoctor');


########################## End relations ##############################


########################## accessors and mutators ######################
Route::get('accessors','App\Http\Controllers\Relation\RelationsController@getDoctors'); //get data

