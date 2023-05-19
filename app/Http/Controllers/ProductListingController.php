<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductListingController extends Controller
{ 
    public function productList()
    {
        $products = Product::all();
        // $products = Product::where('quantity', '>', 0)->get();
        return view('product_listing', compact('products'));
    }
}
