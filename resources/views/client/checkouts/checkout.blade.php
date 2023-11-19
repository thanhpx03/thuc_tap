<!-- resources/views/client/checkout.blade.php -->

@extends('layouts.client')
@section('content')
<!-- ==========Banner-Section========== -->
<section class="main-page-header speaker-banner bg_img" data-background="./assets/images/banner/banner07.jpg">
    <div class="container">

    </div>
</section>
<!-- ==========Banner-Section========== -->
@include('client.ticket')
<!-- shopping cart area start -->
<!-- Your existing HTML content -->

<section class="checkout_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Checkout</h2>
                <form action="{{ route('checkout.process') }}" method="post">
                    @csrf

                    <!-- Display cart items -->
                    @foreach($cartItems as $cartItem)
                        <div class="cart-item">
                            <hr>
                            <p>{{ $cartItem->book->name }}</p>
                            <p>Quantity: {{ $cartItem->quantily }}</p>
                            <p>Price: £{{ $cartItem->book->price }}</p>
                            <!-- Add more details as needed -->
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="cash">Cash on Delivery</option>
                            <!-- Add other payment methods as needed -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
