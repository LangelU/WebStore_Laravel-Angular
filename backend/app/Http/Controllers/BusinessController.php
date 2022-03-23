<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use DB;

class BusinessController extends Controller
{
    
    public function index() {
        //
    }

    //Create a new Business
    public function createBusiness(Request $request) {
        $validateUniqueBusiness = DB::table('businesses')->select("*")->get();
        
        if ($validateUniqueBusiness->isEmpty()) {
            $newBusiness = new Business;
            $newBusiness->name          = $request->input("bus_name");
            $newBusiness->location      = $request->input("bus_location");
            $newBusiness->mission       = $request->input("bus_mission");
            $newBusiness->vision        = $request->input("bus_vision");
            $newBusiness->about_us      = $request->input("bus_aboutUs");
            $newBusiness->email         = $request->input("bus_email");
            $newBusiness->twitter       = $request->input("bus_twitter");
            $newBusiness->instagram     = $request->input("bus_instagram");
            $newBusiness->f_phone       = $request->input("bus_fphone");
            $newBusiness->s_phone       = $request->input("bus_sphone");
            $newBusiness->cellphone     = $request->input("bus_cellphone");
            $newBusiness->save();
            $businessData = DB::table('businesses')->select("*")->get();

            return response ()->json (['status'=>'success','message'=>
            'Business created Successfully','response'=>['data'=>$businessData]], 201);
        }
        else {
            return response ()->json (['status'=>'error','message'=>
            'Could not create business','response'=> 'Already exists the business'], 409);
        }  
    }

    //Show business data
    public function showBusiness() {
        $business = DB::table('businesses')->select("*")->get();

        if ($business->isEmpty()) {
            return response ()->json (['status'=>'error','message'=>
            'Business not found', 'response'=>'Business´s data are empty'], 404);
        }
        else {
            return response ()->json (['status'=>'success','message'=>
            'Business found', 'response'=> ['data'=>$business]], 200);
        }   
    }

    //Edit business data
    public function editBusiness(Business $business) {
        $editBusiness = App\Business::findOrFail($id);
        return view('formularios.editardoctor', compact('actualizarDoctor'));
    }

    //Update business data
    public function updateBusiness(Request $request, $id) {
        $name          = $request->input("bus_name");
        $location      = $request->input("bus_location");
        $mission       = $request->input("bus_mission");
        $vision        = $request->input("bus_vision");
        $about_us      = $request->input("bus_aboutUs");
        $email         = $request->input("bus_email");
        $twitter       = $request->input("bus_twitter");
        $instagram     = $request->input("bus_instagram");
        $f_phone       = $request->input("bus_fphone");
        $s_phone       = $request->input("bus_sphone");
        $cellphone     = $request->input("bus_cellphone"); 
        $busUpdateSQL = "UPDATE businesses SET
                         name        = '$name',
                         location    = '$location',
                         mission     = '$mission',
                         vision      = '$vision',
                         about_us    = '$about_us',
                         email       = '$email',
                         twitter     = '$twitter',
                         instagram   = '$instagram',
                         f_phone     = '$f_phone',
                         s_phone     = '$s_phone',
                         cellphone   = '$cellphone'
                         WHERE ID    = $id";
        $businessUpdated = DB::select($busUpdateSQL);
        $businessData = DB::table("businesses")->select("*")->get();

        return response ()->json (['status'=>'success','message'=>
        'Business updated Successfully','response'=>['data'=>$businessData]], 200); 
    }
}
