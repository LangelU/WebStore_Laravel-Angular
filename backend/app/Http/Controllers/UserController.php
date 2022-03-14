<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;
use DB;

class UserController extends Controller
{
    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        }
        catch (JWTException $e) {
          return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $email = $request->input('email');
        $user = DB::table('users')->where('email', $email)->first();
        $add_token = ['role' => $user->ID_role, 'email' => $user->email, 'ID'=>$user->id];
        $token = JWTAuth::claims($add_token)->attempt($credentials);

        return response()->json(['status' => 'success', 'message' => 
        'Logged in', 'token' => $token]);
    }

    public function getAuthenticatedUser() {
        try {
          if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
          }
        }
        catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } 
        catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } 
        catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }


    public function register(Request $request) {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|int|max:5',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
            'email' => $request->get('email'),
            'ID_role' => $request->get('role'),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function createCustomerUser(Request $request) {
        $customerEmail = $request->input("email");
        $customerID = $request->input("id_number");
        $validateCustomer = DB::table('customers')->select("*")
        ->where('email', '=', $customerEmail)->get();

        if ($validateCustomer->isEmpty()) {
            //First: create a customer profile
            $newCustomer = new Customer;
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
            Log::info($request);
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }

            $user = User::create([
                'email' => $request->get('email'),
                'ID_role' => 3,
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);
            return response ()->json (['status'=>'success','message'=>
            'User created Successfully','response'=>['data'=>$newCustomer]], 200); 
            
        }
        else{
            return response ()->json (['status'=>'error','message'=>
            'Could not create the user','response'=> 'Alreade exists the user'], 409);
        }   
    }

    public function createStaffUser(Request $request) {
        $staffEmail = $request->input("email");
        $validateStaff = DB::table('staff')->select("*")
        ->where('email', '=', $staffEmail)->get();

        if ($validateStaff->isEmpty()) {
            //First: create a staff profile
            $newStaff = new Staff;
            $newStaff->f_name        = $request->input("f_name");
            $newStaff->s_name        = $request->input("s_name");
            $newStaff->f_lastname    = $request->input("f_lastname");
            $newStaff->s_lastname    = $request->input("s_lastname");
            $newStaff->email         = $request->input("email");
            $newStaff->save();

            //Second: create a user profile to the staff
            Log::info($request);
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }

            $user = User::create([
                'email' => $request->get('email'),
                'ID_role' => 2,
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);
            return response ()->json (['status'=>'success','message'=>
            'Staff created successfully','response'=>['data'=>$newStaff]], 200);
            
        }
        else{
            return response ()->json (['status'=>'error','message'=>
            'Could not create the staff','response'=> 'Alreade exists the staff'], 409); 
        }  
    }          
}
