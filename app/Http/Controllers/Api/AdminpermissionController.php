<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Adminpermission;
use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;

class AdminpermissionController extends Controller
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

    public function loadAllAdminPermissions(Admin $admin){
        return response()->json($admin->permissions);
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
            'admin_id' => 'required| numeric',
            'permission_ids' => 'required',
        ]);
        $admin = Admin::findOrFail($request->admin_id);
        if($request->admin->isSuperAdmin){
            foreach ($request->permission_ids as $permission_id) {
                $permission = Permission::findOrFail($permission_id);
                $newAdminPermission = new Adminpermission();
                $newAdminPermission->admin_id = $admin->id;
                $newAdminPermission->permission_id = $permission->id;
                $newAdminPermission->save();
            }
            return response()->noContent(201);
        }
        return  response()->json('must be super admin for this route', 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Adminpermission  $adminpermission
     * @return \Illuminate\Http\Response
     */
    public function show(Adminpermission $adminpermission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Adminpermission  $adminpermission
     * @return \Illuminate\Http\Response
     */
    public function edit(Adminpermission $adminpermission)
    {
        //
    }

    public function update(Request $request, Adminpermission $adminpermission)
    {
        $this->validate($request, [
            'admin_id' => 'required| numeric',
            'permission_ids' => 'required',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $adminPermissions = $admin->adminpermissions();
        if($request->admin->isSuperAdmin){
            foreach ($request->permission_ids as $permission_id) {
                //check for deleted permissions
                $permission = Permission::findOrFail($permission_id);
                $newAdminPermission = new Adminpermission();
                $newAdminPermission->admin_id = $admin->id;
                $newAdminPermission->permission_id = $permission->id;
                $newAdminPermission->save();
            }
            return response()->noContent();
        }
        return  response()->json('must be super admin for this route', 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Adminpermission  $adminpermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adminpermission $adminpermission)
    {
        //
    }
}
