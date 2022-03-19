<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(){
        //
    }

    //Show staff (Business view)
    public function showStaff(){
        $staff = DB::table("staff")->select("*")->get();
        
        if ($staff->isEmpty()) {
            return response ()->json(['status'=>'error', 'message'=>
            'Staff not found', 'response'=>'The staff table are empty'], 404);
        } 
        else {
            return response ()->json(['status'=>'success', 'message'=>
            'Staff found', 'response'=>['data'=>$staff]], 200);
        }
        
    }

    public function edit(Staff $staff){
        //
    }

    //Update staff profile data
    public function updateStaffProfile(Request $request, $idStaff){
        $f_name        = $request->input("f_name");
        $s_name        = $request->input("s_name");
        $f_lastname    = $request->input("f_lastname");
        $s_lastname    = $request->input("s_lastname");
        $id_number     = $request->input("id_number");

        $staffSQL = "UPDATE customers SET
                     f_name = '$f_name',
                     s_name = '$s_name',
                     f_lastname = '$f_lastname',
                     s_lastname = '$s_lastname',
                     id_number = '$id_number',
                     WHERE ID = $idStaff";
        $staffUpdate = DB::select($staffSQL);
        $satffData = DB::table("staff")->select("*")->where('ID', '=', $idStaff)->get();

        return response ()->json(['status'=>'success', 'message'=>
        'Staff profile update successfully', 'response'=>['data'=>$satffData]], 200);
    }

    public function destroy(Staff $staff){
        //
    }

    //Show staff profile data (Staff view)
    public function staffProfile($idStaff){
        $staff = DB::table("staff")->select("*")->where('ID', '=', $idStaff)->get();

        return response ()->json(['status'=>'success', 'message'=>
        'Staff profile found', 'response'=>['data'=>$staff]], 200);
    }
}
