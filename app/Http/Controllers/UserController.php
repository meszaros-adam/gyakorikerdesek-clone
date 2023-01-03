<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registration(Request $request)
    {
        $this->validate($request, [
            'nickname' => 'required|min:3',
            'email' => 'required|email|confirmed',
            'password' => 'required|confirmed|min:6',
        ]);

        return User::create([
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'rememberMe' => 'required|boolean'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->rememberMe)) {
            return response('Succesfull login!');
        } else {
            return response('Login failed', 422);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function get(Request $request)
    {
        return User::orderBy($request->orderBy, $request->ordering)->paginate($request->itemPerPage);
    }
    public function edit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'nickname' => 'required|min:3',
            'admin' => 'required|boolean',
        ]);

        return User::where('id', $request->id)->update([
            'nickname' => $request->nickname,
            'admin' => $request->admin,
        ]);
    }
    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        return User::where('id', $request->id)->delete();
    }
    public function getUserData()
    {
        return Auth::user();
    }
    public function editMyProfile(Request $request)
    {
        if (Hash::check($request->password, Auth::user()->password)) {
            return User::where('id', $request->id)->update([
                'nickname' => $request->nickname,
                'email' => $request->email,
            ]);
        } else {
            return response('Authorization failed', 401);
        }
    }
}
