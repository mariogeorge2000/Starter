<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Traits\OfferTrait;
use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OfferController extends Controller
{
    use OfferTrait;

    public function create(){
        return view('ajaxoffers.create');

    }

    public function store(OfferRequest $request){
        //save offer into db using ajax
       $file_name = $this->saveImage($request->photo,'images/offers');


        //insert
       $offer= Offer::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'price' => $request->price,
            'details_ar' => $request->details_ar,
            'details_en' => $request->details_en,
            'photo' => $file_name
        ]);

        if ($offer){
            return response()->json([  //de mkan redirect back bs fel ajax
                'status'=>true,
                'msg'=> 'تم الحفظ بنجاح',
            ]);
        }
        else
            return response()->json([  //de mkan redirect back bs fel ajax
                'status'=>false,
                'msg'=> 'فشل الحفظ',
            ]);
    }

    public function all(){
        $offers = Offer::select('id',
            'price',
            'name_' . LaravelLocalization::getCurrentLocale() . ' as name', 'details_' . LaravelLocalization::getCurrentLocale() . ' as details'
        )->get(); //return collection

        return view('ajaxoffers.all', compact('offers'));
    }

    public function delete(Request $request){

        $offer = Offer::find($request -> id);   // Offer::where('id','$offer_id') -> first();

        if (!$offer)
            return redirect()->back()->with(['error' => __('messages.offer not exist')]);

        $offer->delete();

        return response()->json([
            'status' => true,
            'msg' => 'تم الحذف بنجاح',
            'id' =>  $request -> id
        ]);

    }

    public function edit(Request  $request)
    {
        $offer = Offer::find($request -> offer_id);  // search in given table id only
        if (!$offer)
            return response()->json([
                'status' => false,
                'msg' => 'هذا  العرض غير موجود',
            ]);

        $offer = Offer::select('id', 'name_ar', 'name_en', 'details_ar', 'details_en', 'price')->find($request -> offer_id);

        return view('ajaxoffers.edit', compact('offer'));

    }

    public  function update(Request $request){
        $offer = Offer::find($request -> offer_id);
        if (!$offer)
            return response()->json([
                'status' => false,
                'msg' => 'هذ العرض غير موجود',
            ]);

        //update data
        $offer->update($request->all());

        return response()->json([
            'status' => true,
            'msg' => 'تم  التحديث بنجاح',
        ]);
    }

}
