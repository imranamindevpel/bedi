<?php
    
namespace App\Http\Controllers; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Stripe;
use Stripe\Charge;
use App\Models\Product;
use App\Models\Order;

use Stripe\PaymentIntent;
class StripeController extends Controller
{
    
    public function stripePost(Request $request)
    {
        $success_url = config('app.url').'/stripe/success';
        $cancel_url = config('app.url').'/stripe/cancel';
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $products = \Cart::getContent();
        $lineItems = [];
        foreach ($products as $product) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => env('STRIPE_CURRENCY'),
                    'unit_amount' => $product['price'] * 100,
                    'product_data' => [
                        'name' => $product['name'],
                    ],
                ],
                'quantity' => $product['quantity'],
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
        $sessionId = $session->id;
        
        Session::put('sessionId', $sessionId);
        Session::save();
        header("Location: " . $payment_url);
        exit;   
    }
    
    public function handlePaymentSuccess(Request $request){
        $products = \Cart::getContent();
        foreach ($products as $product) {
            $existingQuantity = Product::where('id', $product['id'])->value('quantity');
            if ($existingQuantity !== null) {
                $newQuantity = $existingQuantity - $product['quantity'];
                Product::where('id', $product['id'])->update(['quantity' => $newQuantity]);
            }
        }
        $sessionId = Session::get('sessionId');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        $paymentIntentId = $session->payment_intent;
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
        $charge = Charge::retrieve($paymentIntent->latest_charge);
        $receiptUrl = $charge->receipt_url;
        // Saving Order in DB
        $userId = Auth::user()->id;
        $data =[
            'user_id' => $userId,
            'sessionId' => $session->id,
            'detail' => \Cart::getContent(),
            'receipt_url' => $receiptUrl,
            'total' => \Cart::getTotal(),
        ];
        Order::create($data);
        // redirecting to cart with invoice url
        session()->flash('receiptUrl', $receiptUrl);
        return redirect()->route('cart.clear')->with('receiptUrl', $receiptUrl);        
        // header("Location: " . $receiptUrl);
        // exit;
    }

    public function order_status($id)
    {
        $order = Order::find($id); // Replace $id with the actual ID of the record
        if ($order) {
            $order->status = 1;
            $order->save();
        }
        return redirect()->route('orders.index');
    }

    // $product_name = "Products 1";
    // $product_price = \Cart::getTotal() * 100;
    // $product_quantity = 1;
    // $currency = "usd";
    // $success_url = config('app.url').'/stripe/success';
    // $cancel_url = config('app.url').'/stripe/cancel';
    // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    // $session = \Stripe\Checkout\Session::create([
    //     'payment_method_types' => ['card'],
    //     'line_items' => [
    //         [
    //             'price_data' => [
    //                 'currency' => $currency,
    //                 'unit_amount' => $product_price,
    //                 'product_data' => [
    //                     'name' => $product_name,
    //                 ],
    //             ],
    //             'quantity' => $product_quantity,
    //         ],
    //     ],
    //     'mode' => 'payment',
    //     'success_url' => $success_url,
    //     'cancel_url' => $cancel_url,
    // ]);
    
    // $payment_url = $session->url;
    // echo $payment_url;
    

    // $amount = \Cart::getTotal();
    // $user = Auth::user();
    // $transactionData = [];
    // $stripe = new \Stripe\StripeClient(
    //     env('STRIPE_SECRET')
    //   );
    // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    // $customer = \Stripe\Customer::create([
    //     'email' => $user->email
    // ]);
    // $intent = \Stripe\PaymentIntent::create([
    //     'amount' => 100 * $amount,
    //     'currency' => 'usd',
    //     'customer' => $customer->id,
    //     'description' => 'Licensing Fee',
    //     'payment_method_data' => [
    //         'type' => 'card',
    //         'card' => ['token' => $request['stripeToken']]
    //     ],
    //     'payment_method_types' => ['card', 'three_d_secure'],
    //     'confirm' => true,
    // ]);
    // $transactionData = $intent->toArray();
    // $data['user_id'] = $user->id;
    // $data['amount'] = $amount;
    // $data['txn_id'] = $transactionData['id'];
    // $data['transactionData'] = $transactionData;
    // $data['status'] = $transactionData['status'];
    // $response = $stripe->charges->retrieve(
    //     $transactionData['latest_charge'],
    //     []
    //   );
    // // $this->transaction->create($data);
    // Session::flash('success', 'Payment successful!');
    // $id = $response['id'];
    // $amount = $response['amount'];
    // $invoice_url = $response['receipt_url'];
    // \Cart::clear();
    // Session::flash('success', 'Payment successful!');
    // return view('invoice', compact('invoice_url', 'id', 'amount'));


    // public function stripePost(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $amount = $request->input('amount'); // Get the amount value from the request
    //     $stripeToken = $request->input('stripeToken');
    
    //     if ($stripeToken) {
    //         $charge = Stripe\Charge::create([
    //             "amount" => $amount * 100, // Multiply by 100 to convert to cents
    //             "currency" => "usd",
    //             "source" => $stripeToken,
    //             "description" => "Test payment from tutsmake.com."
    //         ]);
    
    //         // Retrieve the charge response
    //         $response = $charge->jsonSerialize();
    //         $id = $response['id'];
    //         $amount = $response['amount'];
    //         $invoice_url = $response['receipt_url'];
    
    //         Session::flash('success', 'Payment successful!');
    //         return view('invoice', compact('invoice_url', 'id', 'amount'));
    //     } else {
    //         // Handle the case when the stripeToken is not provided
    //         Session::flash('error', 'Invalid payment source');
    //         return redirect()->back();
    //     }    
    // }    
    // public function stripePost(Request $request)
    // {
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $stripeToken = $request->input('stripeToken');
    //     $customer = Stripe\Customer::create([
    //         'source' => $stripeToken,
    //         'email' => $request->input('email'),
    //     ]);
    //     $customer->sources->create(['source' => $stripeToken]);
    //     $charge = Stripe\Charge::create ([
    //             "amount" => 100 * 100,
    //             "currency" => "usd",
    //             "customer" => $customer->id,
    //             "description" => "Test payment from tutsmake.com."
    //     ]);
    //     $response = $charge->jsonSerialize();
    //     $id = $response['id'];
    //     $amount = $response['amount'];
    //     $invoice_url = $response['receipt_url'];
    //     Session::flash('success', 'Payment successful!');
    //     return view('invoice', compact('invoice_url', 'id', 'amount'));
    // }
//     public function stripePost(Request $request)
// {
//     $user = Auth::user();
//     $amount = $request->input('amount');
//     $stripeToken = $request['stripeToken'];
//     $transactionData = [];

//     \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

//     $paymentMethod = \Stripe\PaymentMethod::create([
//         'type' => 'card',
//         'card' => [
//             'token' => $stripeToken,
//         ],
//     ]);

//     $customer = \Stripe\Customer::create([
//         'payment_method' => $paymentMethod->id,
//         'email' => $user->email,
//     ]);

//     $intent = \Stripe\PaymentIntent::create([
//         'amount' => 100 * $amount,
//         'currency' => 'usd',
//         'customer' => $customer->id,
//         'description' => 'Licensing Fee',
//         'payment_method' => $paymentMethod->id,
//         'confirm' => true,
//     ]);

//     $transactionData = $intent->toArray();
//     $data['user_id'] = $user->id;
//     $data['amount'] = $amount;
//     $data['txn_id'] = $transactionData['id'];
//     $data['transactionData'] = $transactionData;
//     $data['status'] = $transactionData['status'];

//     Session::flash('success', 'Payment successful!');
//     return redirect()->route('users.competition')->with('data', $data);
// }

}