<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\Product;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        parent::LoginValidator();

        $user = Auth()->user();
        $permission = $user->permission;
        $suppliers = Supplier::all();
        
        return view('supplier.index', compact('suppliers', 'permission'));
    }

    public function store(Request $request)
    {
        parent::LoginValidator();

        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->cnpj = $request->cnpj;
        $supplier->save();

        return redirect('/supplier');
    }

    public function edit(Request $request)
    {
        parent::LoginValidator();

        $supplier = Supplier::where('id', $request->id)->first();
        $supplier->name = $request->name;
        $supplier->cnpj = $request->cnpj;
        $supplier->address = $request->address;
        $supplier->save();

        return redirect('/supplier');
    }

    public function search(Request $request)
    {
        parent::LoginValidator();

        $suppliers = DB::select('SELECT * FROM suppliers WHERE name LIKE ?', ['%'.$request->name.'%']);

        return view('supplier.search', compact('suppliers'));    
    }

    public function deleteIt(Request $request)
    {
        parent::LoginValidator();

        $supplier = Supplier::where('id', $request->id)->first();
        $products = DB::select('SELECT * FROM products WHERE supplier_id = ?', [$request->id]);

        if(isset($products[0]->id)){
            return redirect('/supplier');
        
        }else{
            $supplier->delete();
            
            return redirect('/supplier');
        }               
    }
}