<?php

namespace App\Http\Controllers;
    
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
    
class ProductController extends Controller
{ 
    function __construct()
    {
         $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
         $this->middleware('permission:product-create', ['only' => ['create','store']]);
         $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    
    public function index(): View
    {
        $products = Product::latest()->paginate(5);
        return view('backend.products.index',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function create(): View
    {
        return view('backend.products.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'detail' => 'required',
        ]);
        $data = [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'detail' => $request->input('detail'),
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=989&q=80', // Replace with your hardcoded image URL
        ];
        // Product::create($request->all());
        Product::create($data);
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');                        
    }
    
    public function show(Product $product): View
    {
        return view('backend.products.show',compact('product'));
    }
    
    public function edit(Product $product): View
    {
        return view('backend.products.edit',compact('product'));
    }
    
    public function update(Request $request, Product $product): RedirectResponse
    {
         request()->validate([
            // 'image' => 'required',
            'name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'detail' => 'required',
        ]);
        $product->update($request->all());
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }
    
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}