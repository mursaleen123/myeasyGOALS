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
        // return  redirect()->route('index')
        //     ->with('error', 'Access Denied');
        // if (!auth()->user()->hasPermissionTo('user-list-hide')) {
        //     return  redirect()->route('index')
        //         ->with('error', 'Access Denied');
        // }
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
        // return  redirect()->route('index')
        //     ->with('error', 'Access Denied');
        // if (!auth()->user()->hasPermissionTo('user-create-hide')) {
        //     return  redirect()->route('index')
        //         ->with('error', 'Access Denied');
        // }
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
        // return  redirect()->route('index')
        //     ->with('error', 'Access Denied');
        // if (!auth()->user()->hasPermissionTo('user-list-hide')) {
        //     return  redirect()->route('index')
        //         ->with('error', 'Access Denied');
        // }
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
        // return  redirect()->route('index')
        //     ->with('error', 'Access Denied');
        // if (!auth()->user()->hasPermissionTo('user-edit-hide')) {
        //     return  redirect()->route('index')
        //         ->with('error', 'Access Denied');
        // }
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
        // return  redirect()->route('index')
        //     ->with('error', 'Access Denied');
        // if (!auth()->user()->hasPermissionTo('user-delete-hide')) {
        //     return  redirect()->route('index')
        //         ->with('error', 'Access Denied');
        // }
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



}
