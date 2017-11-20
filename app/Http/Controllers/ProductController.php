<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Supplier;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        parent::LoginValidator();

        $user = Auth()->user();
        $permission = $user->permission;
        $products = Product::all();
        $suppliers = supplier::orderBy('name', 'DESC')->get();

        return view('product.index', compact('products', 'suppliers', 'permission'));
    }
    

        public function store(Request $request)
    {
        parent::LoginValidator();

        $product = new Product;
        $product->supplier_id = $request->supplier;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->quantity = $request->quantity;

        if (!empty($request->supplier)) {
            $product->save();

        }
        return redirect()->back();
    }

    public function edit(Request $request)
    {
        parent::LoginValidator();

        $product = Product::find($request->id);        
        $product->supplier_id = $request->supplier;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->quantity = $request->quantity;
        
        if (!empty($request->supplier)) {
            $product->save();
        
        }
        return redirect()->back();
    }

    public function search(Request $request)
    {
        parent::LoginValidator();

        $suppliers = Supplier::all();
        $products = DB::select('SELECT * FROM products WHERE name LIKE ?', ['%'.$request->name.'%']);

        return view('product.search', compact('products', 'suppliers'));
    }

    public function debit(Request $request)
    {
        parent::LoginValidator();

        $product = Product::find($request->id);
        $product->quantity -= $request->quantity;
        $product->save();

        return redirect()->back();
    }

    public function listSupplier($id)
    {
        parent::LoginValidator();

        $bySupplier = Supplier::where('id', $id)->first();
        $suppliers = Supplier::all();
        $products = DB::select('SELECT * FROM products WHERE supplier_id = ?', [$id]);

        return view('supplier.products', compact('products', 'suppliers', 'bySupplier'));
    }
}
