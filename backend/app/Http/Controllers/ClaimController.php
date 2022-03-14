<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use DB;

class ClaimController extends Controller
{
    public function index(){
        //
    }

    //Number generator to Request unique number
     public function reqNumberGenerator(){
        $number = rand(0000000001, 9999999999);
        return $number;
    }
    
    //Create a new request (client)
    public function createRequest(Request $request){
        $invoiceNumber = $request->input("req_invNumber");
        $requestNumber = $this->reqNumberGenerator();
        $validateExistence = DB::table("invoices")->select("*")
        ->where('invoice_number', '=', $invoiceNumber)->get();
        

        if ($validateExistence->isEmpty()) {
            return response ()->json(['status'=>'error', 'message'=>
            'Purchase not found', 
            'response'=>'The number does not exist in the invoice table'],404);
        }
        else {
            $validateReqNumber = DB::table("claims")->select("*")
            ->where('requestNumber', '=', $requestNumber)->get();

            if ($validateReqNumber->isEmpty()) {
                $newRequest = new Claim;
                $newRequest->requestNumber  = $requestNumber;
                $newRequest->invoice_number = $request->input("req_invNumber");
                $newRequest->idnumber       = $request->input("req_idNumber");
                $newRequest->f_name         = $request->input("req_fName");
                $newRequest->f_lastname     = $request->input("req_fLastname");
                $newRequest->request_type   = $request->input("req_type");
                $newRequest->details        = $request->input("req_details");
                $newRequest->state          = 0;
                $newRequest->save();
            
                return response ()->json(['status'=> 'success', 'message'=>
                'Request generated', 'response'=>['data'=>$newRequest]]);
            }
            else{
                return response ()->json(['status'=>'error', 'message'=>
                'Could not procesate request', 'response'=>'internal error'], 500);
            }
        }   
    }

    //Attend request (Staff)
    public function attendRequest(Request $request, $idRequest){
        $answer = $request->input("att_answer");
        $staffName = $request->input("att_staff");
        $attendSQL = "UPDATE claims SET
                      answer = '$answer',
                      attended_by = '$staffName',
                      state = 1
                      WHERE ID = $idRequest";
        $attended = DB::select($attendSQL);
        $claimData = DB::table("claims")->select("*")->where('ID', '=', $idRequest)->get();

        return response ()->json(['status'=>'success', 'message'=>
        'Request attended successfully', 'response'=>['data'=>$claimData]], 200);
    }

    //Show all claims (Staff)
    public function showClaims(Claim $claim){
        $claims = DB::table("claims")->select("*")->get();

        if ($claims->isEmpty()) {
            return response ()->json(['status'=>'error', 'message'=>
            'Claims not found', 'response'=>'The claims table are empty'], 404);
        }
        else {
            return response ()->json(['status'=>'success', 'message'=>
            'Claims found', 'response'=>['data'=>$claims]], 200);
        }
    }

    public function deleteRequest($idRequest){
        $deleteSQL = "DELETE FROM claims WHERE ID = $id";
        $deleteRequest = DB::select($deleteSQL);
        
        return response ()->json (['status'=>'success','message'=>
        'Claim deleted successfully'], 200);
    }
}
