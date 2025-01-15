<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', 'available')->inRandomOrder()->limit(4)->get();
        return view('home', compact('products'));
    }


    public function products()
    {
        $products = Product::all();
        return view('ManageProduct.viewProduct', compact('products'));
    }
}
