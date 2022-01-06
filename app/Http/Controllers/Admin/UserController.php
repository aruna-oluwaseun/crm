<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendStaffNewPassword;
use App\Mail\WelcomeNewStaff;
use App\Models\Holiday;
use App\Models\NationalHoliday;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(get_user()->cannot('view-users')) {
            abort(403);
        }

        $users = User::all();

        set_page_title('Staff list');
        return view('admin.staff.staff-list',compact(['users']));
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role_id'   => 'required',
            'email' => 'required|email|unique:App\Models\User,email',
            'status' => 'required',
            'password' => 'required'
        ]);

        $role_id = $request->input('role_id');
        $data = $request->except(['_token','_method','role_id']);

        if($data['password'] == 'GENERATE_ME_ONE') {
            $data['password'] = Str::random(8);
        }
        $password = $data['password'];

        $data['franchise_id'] = current_user_franchise_id();

        DB::beginTransaction();
        try {
            if(! $user = User::create($data) )
            {
                Throw new \Exception('Error creating staff user ');
            }

            if(! RoleUser::create([
                'user_id' => $user->id,
                'role_id' => $role_id
            ])) {
                Throw new \Exception('Error creating role for user');
            };

            if($password)
            {
                $user->temp_password = $password;
            }

            Mail::to($data['email'])->send(new WelcomeNewStaff($user));

            DB::commit();

            if($request->ajax())
            {
                return response()->json(['success' => true]);
            }

            return redirect('users/'.$user->id)->with('success', 'New staff member added');

        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

        if($request->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Error adding, please re-fresh your page and try again.']);
        }

        return back()->withInput()->with('error','Error adding, please re-fresh your page and try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(get_user()->id != $id && get_user()->cannot('view-users')) {
            abort(403);
        }

        $detail = User::with(['permissions','holidays' => function($holidays) {
            $holidays->currentYear();
        }, 'absent' => function($absent) {
            $absent->currentYear();
        }])->find($id);

        $national_holiday_zone = current_user_franchise() ? current_user_franchise()->national_holiday : 'england-and-wales';
        $national_holidays = NationalHoliday::where('division',$national_holiday_zone)->currentYear()->get();

        set_page_title('Staff '.$detail->getFullNameAttribute());
        return view('admin.staff.staff', compact(['detail','national_holidays']));
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
        $user = User::find($id);

        if( $user->email == $request->input('email')) {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'status' => 'required',
                'password' => 'exclude_unless:change_password,1|required'
            ]);
        } else {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:App\Models\User,email',
                'status' => 'required',
                'password' => 'exclude_unless:change_password,1|required'
            ]);
        }

        $role_id = false;
        if($request->exists('role_id'))
        {
            $role_id = $request->input('role_id');
        }

        $data = $request->except(['_token','_method', 'role_id']);

        // Send password via email
        $send_password = false;
        if(isset($data['change_password']) && isset($data['send_password_email'])) {
            $send_password = true;
        }

        $password = $data['password'];
        if(isset($data['change_password'])) {
            if($data['password'] == 'GENERATE_ME_ONE')
            {
                $data['password'] = Str::random(8);
                $password = $data['password'];
            }
        } else {
            // dont update password
            unset($data['password']);
        }

        unset($data['change_password']);
        unset($data['send_password_email']);

        //dd($data);
        DB::beginTransaction();
        try {
            if(! $user->update($data) )
            {
                Throw new \Exception('Failed to update the user');
            }

            if($role_id) {
                $role = RoleUser::where('user_id',$id)->first();

                if(!$role) {
                    if(! RoleUser::create([
                        'user_id' => $user->id,
                        'role_id' => $role_id
                    ])) {
                        Throw new \Exception('Error creating role for user');
                    };
                } else {

                    if($role->role_id != $role_id) {
                        $role->role_id = $role_id;
                        $role->save();
                    }

                }

            }

            if($send_password)
            {
                $user->temp_password = $password;
                // Send email // Cant queue because temp pass is omitted
                Mail::to($data['email'])->send(new SendStaffNewPassword($user));
            }

            DB::commit();

            if($request->ajax())
            {
                return response()->json(['success' => true]);
            }

            return back()->with('success','User updated.');

        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

        if($request->ajax())
        {
            return response()->json(['success' => false, 'message' => 'Error updating, please re-fresh your page and try again.']);
        }

        return back()->withInput()->with('error','Error updating, please re-fresh your page and try again.');
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


    /**
     * Update the users permissions
     * @param Request $request
     * @param $id
     */
    public function permissions(Request $request, $id)
    {
        $user = User::find($id);

        // Remove all permissions
        $user->permissions()->detach();

        // attach permissions
        $permissions = $request->input('permissions');

        $user->givePermissionsTo($permissions);

        return back()->with('success','Permissions updated');
    }

    /**/
    public function calendar()
    {
        $url = request()->url();

        $holiday_requests = Holiday::requests();

        set_page_title('Calendar');
        return view('admin.staff.staff-calendar',compact(['holiday_requests']));
    }
}
