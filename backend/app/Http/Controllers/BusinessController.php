<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use DB;

class BusinessController extends Controller
{
    
    public function index()
    {
        //
    }

    public function createBusiness(Request $request) {
        $sqlBusiness = "SELECT * FROM businesses";
        $validateUniqueBusiness = DB::select($sqlBusiness);

        if (empty($validateUniqueBusiness)) {
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
            return response()->json(['successfull' => 'business_created'], 200);
        }
        return response()->json(['error' => 'could_not_create_business'], 409);
    }

    public function showBusiness(Business $business) {
        $sqlBusiness = "SELECT * FROM businesses";
        $validateUniqueBusiness = DB::select($sqlBusiness);       

        if (empty($validateUniqueBusiness)) {
             return response()->json(['business_not_found'], 404);
        }
        return response()->json($validateUniqueBusiness);
    }

    public function editBusiness(Business $business) {
        $editBusiness = App\Business::findOrFail($id);
        return view('formularios.editardoctor', compact('actualizarDoctor'));
    }


    public function updateBusiness(Request $request, $id) {
        $updateBusiness = \App\Doctor::findOrFail($id);

        $updateBusiness->name          = $request->input("bus_name");
        $updateBusiness->location      = $request->input("bus_location");
        $updateBusiness->mission       = $request->input("bus_mission");
        $updateBusiness->vision        = $request->input("bus_vision");
        $updateBusiness->about_us      = $request->input("bus_aboutUs");
        $updateBusiness->email         = $request->input("bus_email");
        $updateBusiness->twitter       = $request->input("bus_twitter");
        $updateBusiness->instagram     = $request->input("bus_instagram");
        $updateBusiness->f_phone       = $request->input("bus_fphone");
        $updateBusiness->s_phone       = $request->input("bus_sphone");
        $updateBusiness->cellphone     = $request->input("bus_cellphone");  
        $updateBusiness->save();
        
        return response()->json(['successfull' => 'business_updated'], 200);
    }

    public function destroy(Business $business)
    {
        //
    }
}
