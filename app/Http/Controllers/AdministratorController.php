<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product;

class AdministratorController extends Controller
{
    public function index()
    {
    	parent::LoginValidator();
    	parent::AdminValidator();

        $user = Auth()->user();
        $permission = $user->permission;
    	$users = User::all();
    	$products = Product::all();

    	$total = 0;
    	foreach ($products as $product) {
    		$total += ($product->cost * $product->quantity);
    	}

    	return view('user.dashboard', compact('users', 'products', 'permission', 'total'));
    }
}
