<?php

namespace App\Http\Controllers;

use App\Events\VideoViewer;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\Video;
use App\Scopes\OfferScope;
use App\Traits\OfferTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CrudController extends Controller
{

    use OfferTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getOffers()
    {
        return Offer::get();
    }

//   public function store(){
//       Offer::create([
//            'name'=>'offer3',
//            'price'=>'5000',
//            'details'=>'offer details'
//        ]);
//   }

    public function create()
    {
        return view('offers.create');
    }

    public function store(OfferRequest $request) //request de 3ashan el method de bta5od el data mn el create route
    {
        //validate data before inserting it into database
//        $rules= $this->getRules();
//        $messages= $this->getMessages();
//
//
//        //make de method bta5od kza array, awel array byta5ed feh el data elly gaya mn el request, tani array el validation rule, el talta hya el message law feh 7aga
//        $validator=Validator::make($request->all(),$rules,$messages);
//
//        if ($validator->fails()){
//            return redirect()->back()->withErrors($validator)->withInputs($request->all());
//        }

        $file_name = $this->saveImage($request->photo, 'images/offers');


        //insert
        Offer::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'price' => $request->price,
            'details_ar' => $request->details_ar,
            'details_en' => $request->details_en,
            'photo' => $file_name
        ]);

        return redirect()->back()->with(['success' => 'تم اضافه العرض بنجح']);

    }


//    protected function getMessages(){
//       return $messages=[
//           'name.required' =>__('messages.offer name required'), //messages da esm el file
//           'name.unique' => __('messages.offer name must be unique')
//       ];
//    }
//
//    protected function getRules(){
//        return $rules=[
//            'name'=>'required|max:100|unique:offer,name',
//            'price'=>'required|numeric',
//            'details'=>'required',
//        ];
//    }

    public function getAllOffers()
    {
//        $offers = Offer::select('id',
//            'price',
//            'name_' . LaravelLocalization::getCurrentLocale() . ' as name', 'details_' . LaravelLocalization::getCurrentLocale() . ' as details'
//        )->get(); //return collection
//
//        return view('offers.all', compact('offers'));
//

        ################## paginate result ####################
        $offers = Offer::select('id',
            'price',
            'name_' . LaravelLocalization::getCurrentLocale() . ' as name', 'details_' . LaravelLocalization::getCurrentLocale() . ' as details'
        )->paginate(PAGINATION_COUNT);

        return view('offers.paginations', compact('offers'));

    }

    public function editOffer($offer_id)
    {

        //awel tare2a  Offer::findOrFail($offer_id);

        $offer = Offer::find($offer_id);  //find method is used for id only

        if (!$offer)
            return redirect()->back();


        $offer = Offer::select('id', 'name_ar', 'name_en', 'details_ar', 'details_en', 'price')->find($offer_id);
        return view('offers.edit', compact('offer'));
    }

    public function delete($offer_id){
        $offer = Offer::find($offer_id);

        if (!$offer)
            return redirect()->back()->with(['error' =>__('messages.offer not exist')]);

        $offer->delete();
        return redirect()->route('offers.all',$offer_id)->with(['success'=>__('messages.offer deleted successfully')]);
    }

    public function updateOffer(OfferRequest $request, $offer_id)
    {
        //1 validation in offer request

        //2 check if offer exists
        $offer = Offer::find($offer_id);

        if (!$offer)
            return redirect()->back();

        //update data
        $offer->update($request->all()); //update kol elly gy mn el request...all de bt7otohom f array

        return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
    }

    public function getVideo()
    {
        $video=Video::first();
        event(new VideoViewer($video)); //fire event
       return view('video',compact('video'));
    }


    public function getAllInactiveOffers(){

        // where  whereNull whereNotNull whereIn
        //Offer::whereNotNull('details_ar') -> get();

        //return  $inactiveOffers = Offer::inactive()->get();  //all inactive offers

        // global scope is used
        // return  $inactiveOffers = Offer::get();  //all inactive offers


        // how to  remove global scope
        return $offer  = Offer::withoutGlobalScope(OfferScope::class)->get();

    }
}
