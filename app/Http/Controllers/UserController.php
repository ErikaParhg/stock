<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
    	parent::LoginValidator();
    	parent::AdminValidator();

        $user = Auth()->user();
        $permission = $user->permission;        
    	$users = User::orderBy('name', 'ASC')->get();

    	return view('user.index', compact('users', 'user', 'permission'));
    }

    public function store(Request $request)
    {
    	parent::LoginValidator();
    	parent::AdminValidator();

    	$dataUser = new User;
    	$dataUser->name = $request->name;
    	$dataUser->email = $request->email;
    	$dataUser->password = bcrypt($request->password);
    	$dataUser->permission = $request->permission;
    	$dataUser->save();

    	return redirect('/user');
    }

    public function update(Request $request)
    {
    	parent::LoginValidator();
    	parent::AdminValidator();

    	$dataUser = User::where('id', $request->id)->first();
    	$dataUser->name = $request->name;
    	$dataUser->email = $request->email;
    	$dataUser->permission = $request->permission;
    	$dataUser->save();

    	return redirect('/user');
    }

    public function disable(Request $request)
    {
    	parent::LoginValidator();
    	parent::AdminValidator();

    	$dataUser = User::where('id', $request->id)->first();
    	$dataUser->status = 'Inactive';
    	$dataUser->save();

    	return redirect('/user');
    }

    public function reactivate(Request $request)
    {
        parent::LoginValidator();
        parent::AdminValidator();

        $dataUser = User::where('id', $request->id)->first();
        $dataUser->status = 'Active';
        $dataUser->save();

        return redirect('/user');
    }
}
