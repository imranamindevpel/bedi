<?php

namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Order;
use Session;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{ 
    function __construct()
    {
         $this->middleware('permission:order-list|order-create|order-edit|order-delete', ['only' => ['index','show']]);
         $this->middleware('permission:order-create', ['only' => ['create','store']]);
         $this->middleware('permission:order-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    }
    
    public function index(): View
    {
        $orders = Order::latest()->paginate(5);
        return view('backend.orders.index',compact('orders'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function create(): View
    {
        $success_url = config('app.url').'/stripe/success';
        $cancel_url = config('app.url').'/stripe/cancel';
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $orders = \Cart::getContent();
        $lineItems = [];
        foreach ($orders as $order) {
            // $lineItems[] = [
            //     'price_data' => [
            //         'currency' => env('STRIPE_CURRENCY'),
            //         'unit_amount' => $order['price'] * 100,
            //         'order_data' => [
            //             'name' => $order['name'],
            //         ],
            //     ],
            //     'quantity' => $order['quantity'],
            // ];
            $lineItems = [
                [
                    'price_data' => [
                        'currency' => env('STRIPE_CURRENCY'),
                        'product_data' => [
                            'name' => $order['name'],
                        ],
                        'unit_amount' => $order['price'] * 100,
                    ],
                    'quantity' => $order['quantity'],
                ],
            ];        
        }
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
        ]);

        $payment_url = $session->url;
        $userId = Auth::user()->id;
        $data =[
            'user_id' => $userId,
            'sessionId' => $session->id,
            'detail' => \Cart::getContent(),
            'payment_url' => $payment_url,
            'total' => \Cart::getTotal(),
        ];
        Order::create($data);
        \Cart::clear();
        session()->flash('success', 'Order Booked Successfully! ');

        $orders = Order::latest()->paginate(5);
        return view('backend.orders.index', compact('orders', 'payment_url'))
                ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function store(Request $request): RedirectResponse
    {}
    
    public function show(Order $order): View
    {
        return view('backend.orders.show',compact('order'));
    }
    
    public function edit(Order $order): View
    {
        return view('backend.orders.edit',compact('order'));
    }
    
    public function update(Request $request, Order $order): RedirectResponse
    {
         request()->validate([
            'status' => 1,
        ]);
        $order->update($request->all());
        return redirect()->route('orders.index')
                        ->with('success','Order updated successfully');
    }
    
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return redirect()->route('orders.index')
                        ->with('success','Order deleted successfully');
    }
}