<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderDetail;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;

class CheckoutControllerClient extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch cart items for display on the checkout page
        $cartItems = Cart::all(); // Adjust the query based on your application logic

        // You might also calculate the total amount, apply discounts, etc.
        $cart = Cart::with('book')->orderBy("id", "DESC")->get();
        return view('Client.checkouts.checkout1', compact('cartItems', 'cart'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // CheckoutController.php

    public function store(Request $request)
    {
        // Validate the form data

        // Check the voucher code
        $voucherCode = $request->input('code');
        $voucher = Voucher::where('code', $voucherCode)->first();

        if ($voucher) {
            // Apply the discount to the total order amount
            $discount = $voucher->value;
            $totalOrder = $request->input('total_amount');
            $discountedTotal = max(0, $totalOrder - $discount);

            // Update the total order amount in the request
            $request->merge(['total_order' => $discountedTotal]);
        }

        // Create a new order
        $order = new Order();
        $order->user_id = auth()->id();
        $order->name = $request->input('name');
        $order->email = $request->input('email');
        $order->phone = $request->input('phone');
        $order->address = $request->input('address');
        $order->note = $request->input('note');
        $order->total_order = $request->input('total_order'); // Update to use the adjusted total_order value
        $order->order_date = now();
        $order->status = 1;

        // Save the order
        $order->save();

        $itemsJson = $request->input('order_items');
        $items = json_decode($itemsJson, true);

        if (is_array($items)) {
            foreach ($items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'total_product' => $item['price'] * $item['quantity'],
                ]);

                $product = Book::find($item['product_id']);
                $product->quantily -= $item['quantity'];
                $product->save();
            }
        } else {
            return redirect()->back()->with('error', 'Invalid JSON format');
        }

        Cart::truncate();

        return redirect()->route('cart.view')->with('success', 'Order placed successfully!');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
