<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with(['permissions','users'])->paginate(50);

        set_page_title('Roles controller');
        return view('admin.roles.role-list',compact(['roles']));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug'  => 'required'
        ]);

        if($role = Role::create($validated))
        {
            return redirect('roles/'.$role->id)->with('success','Role created, you can add now assign permissions to it.');
        }

        return back()->withInput()->with('error','Error creating new role. Please try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id === 1) { // super admin
            if(!get_user()->hasRole('super-admin')) {
                abort(403);
            }
        }
        $detail = Role::with(['permissions','users'])->findOrFail($id);

        set_page_title('Viewing roles');
        return view('admin.roles.role', compact(['detail']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'slug'  => 'required'
        ]);
        //dd($permission_ids = array_values($request->input('permissions')));

        $role = Role::find($id);
        if($role->update($validated))
        {
            if($request->exists('permissions'))
            {
                $permission_ids = $request->input('permissions');
                $role->permissions()->sync($permission_ids);
            }

            return back()->with('success','Role updated');
        }

        return back()->withInput()->with('error','Failed to updated the permissions for this role.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
