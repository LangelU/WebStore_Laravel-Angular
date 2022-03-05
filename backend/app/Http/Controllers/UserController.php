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
    public function authenticate(Request $request)
    {
      $credentials = $request->only('email', 'password');
      try {
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'invalid_credentials'], 400);
          }
      } catch (JWTException $e) {
          return response()->json(['error' => 'could_not_create_token'], 500);
      }
      return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
          if (!$user = JWTAuth::parseToken()->authenticate()) {
                  return response()->json(['user_not_found'], 404);
          }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }


    public function register(Request $request) {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|int|max:5',
            'id_staff' => 'int|max:5',
            'id_customer' => 'int|max:5',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
        }

        $user = User::create([
            'email' => $request->get('email'),
            'ID_role' => $request->get('role'),
            'ID_customer' => $request->get('id_customer'),
            'ID_staff' => $request->get('id_staff'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function createCustomerUser(Request $request) {
        $customerEmail = $request->input("email");
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
            return response()->json(['successfull' => 'customer_created'], 200);
            //return response()->json(compact('user','token'),201);
        }
        return response()->json(['error' => 'could_not_create_customer'], 409);
        
     }

     public function createStaffUser(Request $request) {
        $staffEmail = $request->input("email");
        $validateStaff = DB::table('staff')
                                ->select("*")->where('email', '=', $staffEmail)->get();

        if ($validateStaff->isEmpty()) {
            $newStaff = new Staff;

            //First: create a customer profile
            $newStaff->f_name        = $request->input("f_name");
            $newStaff->s_name        = $request->input("s_name");
            $newStaff->f_lastname    = $request->input("f_lastname");
            $newStaff->s_lastname    = $request->input("s_lastname");
            $newStaff->email         = $request->input("email");
            $newStaff->save();

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
                'ID_role' => 2,
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);
            return response()->json(['successfull' => 'staff_member_created'], 200);
            //return response()->json(compact('user','token'),201);
        }
        return response()->json(['error' => 'could_not_create_staff_member'], 409);
        
     }          
}
