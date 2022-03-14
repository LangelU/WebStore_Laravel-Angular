<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class CustomerController extends Controller
{
    public function index() {
        //
    }

    public function create(Request $request) {
        //
    }

    //Show all customers
    public function showCustomers(){
        $customers = DB::table('customers')->select("*")->get();      

        if ($customers->isEmpty()) {
            return response()->json(['status'=>'error', 'message'=>
            'Customers not found', 'response'=>'The customers table are empty'], 404);
        }
        else{
            return response ()->json(['status'=>'success', 'message'=>
            'Customers found', 'response'=>['data'=>$customers]]);
        }    
    }

    public function edit(Customer $customer) {
        //
    }

    //Update customer profile
    public function updateCustomerProfile(Request $request, $idUser) {
        $f_name        = $request->input("f_name");
        $s_name        = $request->input("s_name");
        $f_lastname    = $request->input("f_lastname");
        $s_lastname    = $request->input("s_lastname");
        $home          = $request->input("home");
        $id_number     = $request->input("id_number");
        $cellphone     = $request->input("cellphone");

        $customerSQL = "UPDATE customers SET
                        f_name = '$f_name',
                        s_name = '$s_name',
                        f_lastname = '$f_lastname',
                        s_lastname = '$s_lastname',
                        home = '$home',
                        id_number = '$id_number',
                        cellphone = '$cellphone'
                        WHERE ID = $idUser";
        $customerUpdate = DB::select($customerSQL);

        return response()->json(['status'=>'success', 'message'=>
        'Customer updated successfully', 'response'=>['data'=>$customerUpdate]], 200);
    }
}
