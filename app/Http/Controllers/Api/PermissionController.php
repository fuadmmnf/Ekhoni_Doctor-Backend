<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Userpermission;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required| unique:permissions',
        ]);

        $admins = Admin::withCount('permissions')->get();


        $newPermission = new Permission();
        $newPermission->name = $request->name;
        $newPermission->save();

        $numPermissions = Permission::count();
        foreach ($admins as $admin){
            if($admin->permissions_count == $numPermissions){
                $newAdminPermission = new Userpermission();
                $newAdminPermission->admin_id = $admin->id;
                $newAdminPermission->permission_id = $newPermission->id;
                $newAdminPermission->save();
            }
        }



        return response()->json($newPermission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
