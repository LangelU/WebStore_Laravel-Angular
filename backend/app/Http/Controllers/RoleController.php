<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use DB;

class RoleController extends Controller
{
    public function index() {
        //
    }

    public function create() {
        //
    }

    public function createRoles(Request $request){
        $newRoleAdmin = new Role;
        $newRoleStaff = new Role;
        $newRoleClient = new Role;

        $newRoleAdmin->name = "Admin";
        $newRoleAdmin->save();
        $newRoleStaff->name = "Staff";
        $newRoleStaff->save();
        $newRoleClient->name = "Client";
        $newRoleClient->save();
        $roles = DB::table("roles")->select("*")->get();

        return response ()->json(['status'=>'success', 'message'=>
        'Roles created successfully', 'response'=>['data'=>$roles]], 200);
        
    }

    public function showRoles(){
       $rolesSQL = DB::table("roles")->select("*")->get();

       return response ()->json(['status'=>'success', 'message'=>
       'Roles found', 'response'=>['data'=>$rolesSQL]], 200);
    }

    public function updateRole(Request $request, $idRole){
        $roleName = $request->input("rol_name");
        $nameSQL = "SELECT * FROM roles
                    WHERE ID = $idRole
                    AND name = '$roleName'";
        $roleUpdated = DB::select($nameSQL);
        $previousRole = DB::table("roles")->select("*")->where('ID', '=', $idRole)->get();

        if (empty($roleUpdated)) {
            $updateRolSQL = "UPDATE roles SET
                             name = '$roleName'
                             WHERE ID = $idRole";
            $roleUpdated = DB::select($updateRolSQL);
            $roleData = DB::table("roles")->select("*")
            ->where('ID', '=', $idRole)->get();
            return response ()->json(['status'=>'success', 'message'=>
            'Role updated successfully', 'response'=>
            ['data'=>['previous'=>$previousRole, 'new'=>$roleData]]]);
        }
        else{
            return response ()->json(['status'=>'error', 'message'=>
            'Could not update the role', 
            'response'=>'The role already have same name'], 409);
        }
        //return $roleUpdated;
    }
}
