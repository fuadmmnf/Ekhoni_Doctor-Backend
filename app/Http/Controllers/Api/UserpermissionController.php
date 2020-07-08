<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Userpermission;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;

class UserpermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function loadAllUserPermissions(User $user){
        return response()->json($user->permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required| numeric',
            'permission_ids' => 'required',
        ]);
        $user = User::findOrFail($request->user_id);
        $userPermissions = array();


        if($user->isSuperAdmin){
            foreach ($request->permission_ids as $permission_id) {
                $permission = Permission::findOrFail($permission_id);
                $newAdminPermission = new Userpermission();
                $newAdminPermission->user_id = $user->id;
                $newAdminPermission->permission_id = $permission->id;
                $userPermissions[] = $newAdminPermission;
            }

            foreach ($userPermissions as $userPermission){
                $userPermission->save();
            }

            return response()->json("user permissions set successfully", 201);
        }
        return  response()->json('must be super admin for this route', 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Userpermission  $adminpermission
     * @return \Illuminate\Http\Response
     */
    public function show(Userpermission $adminpermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Userpermission  $adminpermission
     * @return \Illuminate\Http\Response
     */
    public function edit(Userpermission $adminpermission)
    {
        //
    }

    public function update(Request $request, Userpermission $adminpermission)
    {
        $this->validate($request, [
            'user_id' => 'required| numeric',
            'permission_ids' => 'required',
        ]);

        $user = User::findOrFail($request->user_id);
        $userPermissions = array();


        if($user->isSuperAdmin){
            foreach ($request->permission_ids as $permission_id) {
                $permission = Permission::findOrFail($permission_id);
                $newAdminPermission = new Userpermission();
                $newAdminPermission->user_id = $user->id;
                $newAdminPermission->permission_id = $permission->id;
                $userPermissions[] = $newAdminPermission;
            }

            foreach ($userPermissions as $userPermission){
                $userPermission->save();
            }

            return response()->json("user permissions updated successfully", 201);
        }
        return  response()->json('must be super admin for this route', 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Userpermission  $adminpermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Userpermission $adminpermission)
    {
        //
    }
}
