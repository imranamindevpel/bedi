<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller{
    public function cartList()
    {
        $receiptUrl = session()->get('receiptUrl'); // Retrieve the URL from the session
        $cartItems = \Cart::getContent();
        // echo '<pre>' . var_export($cartItems, true) . '</pre>'; die();
        return view('cart')
            ->with('cartItems', $cartItems)
            ->with('receiptUrl', $receiptUrl);
    }

    public function addToCart(Request $request)
    {
        $cartItem = \Cart::get($request->id);
        if ($cartItem) {
            $quantity = $cartItem->quantity;
        } else {
            $quantity = 0;
        }
        
        if(intval($quantity < $request->stock)){
            \Cart::add([
                'id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => intval($request->quantity),
                'attributes' => array(
                    'image' => $request->image,
                    'stock' => intval($request->stock),
                    )
                ]);
                // echo '<pre>' . var_export(intval($request->stock)) . '</pre>';
                // $cartItems = \Cart::getContent();
                // echo '<pre>' . var_export($cartItems, true) . '</pre>'; die();
            session()->flash('success', 'Product is Added to Cart Successfully !');
            return redirect()->route('cart.list');
        }else{
            session()->flash('false', $cartItem->name.' is not available !');
            return redirect()->route('products.list');
        }
    }

    public function updateCart(Request $request)
    {
        $cartItem = \Cart::get($request->id);
        if ($cartItem->quantity) {
            $quantity = $cartItem->quantity;
        } else {
            $quantity = 0;
        }
        
        // dd($quantity, intval($request->stock));
            if($quantity <=intval($request->stock)){
            \Cart::update(
                $request->id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => intval($request->quantity)
                    ],
                ]
            );
            session()->flash('success', 'Item Cart is Updated Successfully !');
            return redirect()->route('cart.list');
        }else{
            session()->flash('false', $cartItem->name.' is Limited in Stock !');
            return redirect()->route('cart.list');
        }
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Item Cart Remove Successfully !');
        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        $receiptUrl = session()->get('receiptUrl'); // Retrieve the URL from the session
        // dd($receiptUrl);
        \Cart::clear();
        session()->flash('success', 'All Item Cart Cleared Successfully!');
        return redirect()->route('cart.list')->with('receiptUrl', $receiptUrl);
    }
}
