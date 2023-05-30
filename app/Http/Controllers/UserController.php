<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeamMemberRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;
use Illuminate\Support\Arr;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return  redirect()->route('index')
            ->with('error', 'Access Denied');
        if (!auth()->user()->hasPermissionTo('user-list-hide')) {
            return  redirect()->route('index')
                ->with('error', 'Access Denied');
        }
        $data = User::orderBy('id', 'DESC')->get();
        $count = 1;
        return view('admin.users.index', compact('data', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  redirect()->route('index')
            ->with('error', 'Access Denied');
        if (!auth()->user()->hasPermissionTo('user-create-hide')) {
            return  redirect()->route('index')
                ->with('error', 'Access Denied');
        }
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        return  redirect()->route('index')
            ->with('error', 'Access Denied');
        if (!auth()->user()->hasPermissionTo('user-list-hide')) {
            return  redirect()->route('index')
                ->with('error', 'Access Denied');
        }
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        return  redirect()->route('index')
            ->with('error', 'Access Denied');
        if (!auth()->user()->hasPermissionTo('user-edit-hide')) {
            return  redirect()->route('index')
                ->with('error', 'Access Denied');
        }
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        return  redirect()->route('index')
            ->with('error', 'Access Denied');
        if (!auth()->user()->hasPermissionTo('user-delete-hide')) {
            return  redirect()->route('index')
                ->with('error', 'Access Denied');
        }
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User Deleted Successfully');
    }

    public function profile($id)
    {
        $curUserId = Auth::user()->id;
        if ($curUserId == $id) {
            $user = User::find($id);
            return view('admin.users.profile', compact('user'));
        } else {
            return redirect()->route('dashboard')
                ->with('error', 'Something went wrong. Try again...');
        }
    }

    public function profileUpdate(Request $request)
    {
        $input = $request->all();
        $id = $input['xxzyzz'];
        if (!empty($id)) {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL,' . $id,
                'password' => 'same:confirm-password',
            ]);
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }
            $user = User::find($id);
            $user->update($input);
            return redirect()->route('edit-profile', $id)
                ->with('success', 'Profile Updated Successfully');
        } else {
            return redirect()->route('edit-profile', $id)
                ->with('error', 'Something went wrong. Try again...');
        }
    }

    public function registerTeam($member)
    {
        if (User::whereUserUniid($member)->exists()) {
            $member_data = User::whereUserUniid($member)->first();
            return view('admin.members.register', compact('member_data'));
        } else {
            $member_data = '';
            return view('admin.members.register', compact('member_data'))->with('message', 'You cant submit your registration.');
        }
    }

    public function updateCoachData(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:50|min:2|regex:/^[a-zA-Z ]*$/',
            'last_name' => 'required|max:50|min:2|regex:/^[a-zA-Z ]*$/',
            'phone' => 'min:10|max:15|regex:/^\+[1-9]\d{10,14}$/|unique:users,phone,' . $id . ',id,deleted_at,NULL',
            'sports' => 'required|max:50|min:2',
            'school' => 'required|max:50|min:2',
        ], [
            'school.required' => 'School/Organization required'
        ]);

        $input = $request->all();
        $user = User::find($id);
        $input = Arr::except($input, 'email');
        $input = Arr::add($input, 'is_completed', 'true');
        $input = Arr::add($input, 'is_verified', 1);
        $user->update($input);
        return redirect()->back()->with('success', 'Your data has been saved.');
    }

    public function confirmTeam($member_request)
    {
        if (TeamMemberRequest::whereRequestUniid($member_request)->exists()) {
            $m_request_data = TeamMemberRequest::with('parent')->whereRequestUniid($member_request)->first();
            if ($m_request_data->status == 'Approved') {
                $request_data = '';
                return view('admin.members.approve', compact('request_data'))->with('message', 'Already approve your account.');
            } else if ($m_request_data->status == 'Rejected') {
                $request_data = '';
                return view('admin.members.approve', compact('request_data'))->with('message', 'You can\'t approve your account.');
            } else {
                if (User::whereUserUniid($m_request_data->user_id)->exists()) {
                    $request_data = $m_request_data;
                    // dd($request_data);
                    return view('admin.members.approve', compact('request_data'));
                } else {
                    $request_data = '';
                    return view('admin.members.approve', compact('request_data'))->with('message', 'You can\'t approve your account.');
                }
            }
        } else {
            $request_data = '';
            return view('admin.members.approve', compact('request_data'))->with('message', 'You can\'t approve your account.');
        }
    }

    public function updateTeam($member_request)
    {
        if (TeamMemberRequest::whereRequestUniid($member_request)->exists()) {
            $m_request_data = TeamMemberRequest::with('parent')->whereRequestUniid($member_request)->first();
            if ($m_request_data->status == 'Approved') {
                $request_data = '';
                return view('admin.members.approve', compact('request_data'))->with('message', 'Already approve your account.');
            } else if ($m_request_data->status == 'Rejected') {
                $request_data = '';
                return view('admin.members.approve', compact('request_data'))->with('message', 'You can\'t approve your account.');
            } else {
                if (User::whereUserUniid($m_request_data->user_id)->exists()) {
                    $email_data = User::whereUserUniid($m_request_data->user_id)->first();
                    $member_update = [
                        'is_verified' => 0,
                        'is_completed' => 'true',
                        'parent_id' => $m_request_data->coach_id,
                    ];
                    $email_data->update($member_update);
                    $role = Role::findByName('Team Member', 'web');
                    $email_data->syncRoles($role);
                    $m_request_data->update(['status' => 'Approved']);
                    $request_data = '';
                    return back()->with('success', 'Request has been approved.');
                } else {
                    $request_data = '';
                    return view('admin.members.approve', compact('request_data'))->with('message', 'You can\'t approve your account.');
                }
            }
        } else {
            $request_data = '';
            return view('admin.members.approve', compact('request_data'))->with('message', 'You can\'t approve your account.');
        }
    }
}
