<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\User;
use App\Mail\EmailVerification;
use DB;
use Mail;

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
        $saleNumber     = $request->input("req_invNumber");
        $email          = $request->input("req_email");
        $idNumber       = $request->input("req_idNumber");
        $fName          = $request->input("req_fName");
        $f_lastName     = $request->input("req_fLastName");
        $claimType      = $request->input("req_type");
        $claimDetails   = $request->input("req_details");

        $claimNumber = $this->reqNumberGenerator();
        $validateExistence = DB::table("sale_histories")->select("*")
        ->where('saleNumber', '=', $saleNumber)->get();
        

        if ($validateExistence->isEmpty()) {
            
            
            return response ()->json(['status'=>'error', 'message'=>
            'Purchase not found', 
            'response'=>'The sale number does not exist'],404);
        }
        else {
            $validateReqNumber = DB::table("claims")->select("*")
            ->where('requestNumber', '=', $claimNumber)->get();

            if ($validateReqNumber->isEmpty()) {
                $newRequest = new Claim;
                $newRequest->requestNumber  = $claimNumber;
                $newRequest->invoice_number = $request->input("req_invNumber");
                $newRequest->idnumber       = $idNumber;
                $newRequest->f_name         = $fName;
                $newRequest->f_lastname     = $f_lastName;
                $newRequest->request_type   = $claimType;
                $newRequest->details        = $claimDetails;
                $newRequest->state          = 0;
                $newRequest->save();
                

                $claimDate = Claim::where('requestNumber', '=', $claimNumber)->get();
                
                Mail::send('emailNotifications.claim_notification', 
                          ['customerName'=>$fName,
                           'customerLastName'=>$f_lastName,
                           'claimNumber'=>$claimNumber,
                           'claimDate'=>$claimDate], 
                           function($message)
                use($fName, $f_lastName, $claimNumber, $claimDate, $email) {
                $message->to($email)->subject('Solicitud radicada');
                $message->from('WebStore@gmail.com','Equipo Webstore');
                });

                return response ()->json(['status'=> 'success', 'message'=>
                'Request generated', 'response'=>['data'=>$newRequest]], 201);
            }
            else{
                return response ()->json(['status'=>'error', 'message'=>
                'Could not procesate request',
                'response'=>'Internal error, try again'], 500);
            }
        }   
    }

    //Attend request (Staff)
    public function attendRequest(Request $request, $idRequest){
        $answer = $request->input("att_answer");
        $userEmail = $request->input("user_email");
        $staffName = $request->input("att_staff");
        $attendSQL = "UPDATE claims SET
                      answer = '$answer',
                      attended_by = '$staffName',
                      state = 1
                      WHERE ID = $idRequest";
        $attended = DB::select($attendSQL);
        $claimData = DB::table('claims')->select("*")->where('ID', '=', $idRequest)->get();
        
        $userEmail = DB::table('claims')
        ->join('customers', 'customers.id_number', '=', 'claims.idnumber')
        ->where('claims.ID', '=', $idRequest)
        ->get();

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

    public function test($idRequest) {
        $userEmail = DB::table('claims')
        ->join('customers', 'customers.id_number', '=', 'claims.idnumber')
        ->where('claims.ID', '=', $idRequest)
        ->select('customers.email')
        ->get();
        
        //$email = $userEmail[0]['email'];
        $email = $userEmail->email;
        return $email;
    }
}
