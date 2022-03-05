<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class CustomerController extends Controller
{
    public function index()
    {
        //
    }

    public function createCustomer(Request $request) {
        $customerEmail = $request->input("cus_email");
        $validateCustomer = DB::table('customers')
                                ->select("*")->where('email', '=', $customerEmail)->get();

        if ($validateCustomer->isEmpty()) {
            $newCustomer = new Customer;

            //First: create a customer profile
            $newCustomer->f_name        = $request->input("f_name");
            $newCustomer->s_name        = $request->input("s_name");
            $newCustomer->f_lastname    = $request->input("f_lastname");
            $newCustomer->s_lastname    = $request->input("s_lastname");
            $newCustomer->email         = $request->input("email");
            $newCustomer->home          = $request->input("home");
            $newCustomer->id_number     = $request->input("id_number");
            $newCustomer->cellphone     = $request->input("cellphone");
            $newCustomer->save();

            //Second: create a user profile to the customer


            return response()->json(['successfull' => 'customer_created'], 200);
        }
        return response()->json(['error' => 'could_not_create_customer'], 409);
    }

    public function showCustomers(Customer $customer){
        $customers = DB::table('customers')->select("*")->get();      

        if ($customers->isEmpty()) {
             return response()->json(['customers_not_found'], 404);
        }
        return response()->json($customers);
    }

    public function edit(Customer $customer)
    {
        //
    }

    public function updateCustomerProfile(Request $request, $id) {
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
                        WHERE ID = $id";
        $customerUpdate = DB::select($customerSQL);
        return response()->json(['successfull' => 'customer_updated'], 200);
    }

    public function destroy() {
        
    }
}
