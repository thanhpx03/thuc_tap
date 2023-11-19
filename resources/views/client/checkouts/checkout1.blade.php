@extends('layouts.client')
@section('content')
<style>
    .checkout-widget .payment-option li.active a::after {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        top: -15px;
        right: -15px;

    }

    .checkout-widget.checkout-card .payment-card-form .form-group.check-group label::after {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        top: 7px;
        left: 0;
        background: url(./img/check.png ) no-repeat center center;
        background-size: cover;
        display: none;
    }
</style>
<!-- ==========Banner-Section========== -->
<section class="details-banner hero-area bg_img seat-plan-banner" data-background="./assets/images/banner/banner04.jpg">
    <div class="container">
        <div class="details-banner-wrapper">
            <div class="details-banner-content style-two">
                <h3 class="title">Thanh toán</h3>
                <div class="tags">
                    {{-- <a href="#0">City Walk</a>
                        <a href="#0">English - 2D</a> --}}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==========Banner-Section========== -->

<!-- ==========Page-Title========== -->
<section class="page-title bg-one">
    <div class="container">
        <div class="page-title-area">
            <div class="item md-order-1">
                <a href="" class="custom-button back-button">
                    Quay lại</a>
            </div>


            <div class="item">
                {{-- <h5 class="title">05:00</h5>
                    <p>Mins Left</p> --}}
            </div>
        </div>
    </div>
</section>
<!-- ==========Page-Title========== -->

<!-- ==========Movie-Section========== -->
<div class="movie-facility padding-bottom padding-top">
    <div class="container">
        <form action="{{ route('checkout.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif
                    <div class="checkout-widget checkout-contact">
                        <h5 class="title">Thông tin liên hệ</h5>

                        <div class="form-group">
                            <input type="text" placeholder="Họ và tên" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Email" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Số điện thoại" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Địa chỉ" name="address" value="{{ old('address') }}">
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Ghi chú" name="note" value="{{ old('note') }}">
                        </div>
                        <input type="hidden" name="total_amount" value="">
                        <input type="hidden" name="order_items" value="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="booking-summery bg-one">
                        <h4 class="title">Chi tiết hoá đơn</h4>
                        <ul class="side-shape">
                            <hr>
                            <li id="itemList">
                                @foreach($cart as $cartItem)
                                <!-- Các món ăn được thêm vào sẽ được hiển thị ở đây -->
                                <h6 class="subtitle">
                                    <span>{{$cartItem->book->name}}</span>
                                    <!-- Add data-food-id here -->
                                    <span></span>
                                </h6>
                                <div class="info">
                                    <!-- <span data-product-id="{{ $cartItem->book->id }}"></span> -->
                                    <input type="text" value="{{ $cartItem->book->id }}" id="book_id">
                                    <span class="price">{{$cartItem->book->price}} vnd</span>
                                    <span class="quantity">{{$cartItem->quantily}}</span>
                                </div>
                                <br>
                                @endforeach
                            </li>

                        </ul>
                        <ul>
                            <li>
                                <h6 class="subtitle"><span>Phương thức thanh toán</span></h6>
                                <hr>
                                <span>
                                    <div class=" mt-2">
                                        <select class="form-select text-dark " id="paymentMethod">
                                            <option value="1">Thanh toán nội địa Napas</option>
                                            <option value="2">Thanh toán quốc tế Visa</option>
                                            <option value="3">Thanh toán khi nhận hàng</option>
                                        </select>
                                    </div>
                                </span>
                            </li>
                        </ul>
                        <ul>
                            <div>
                                <div class="coupon_code left">
                                    <h7>mã giảm giá</h7>
                                    <div class="coupon_inner">
                                        <input placeholder="mã" type="text" name="code" id="voucherCode"> <!-- Input field for voucher code -->
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                    <div class="proceed-area  text-center">
                        <h6 class="subtitle"><span>Tổng tiền</span><span id="totalAmount"></span></h6>
                        <button class="custom-button back-button" id="payButton">Thanh toán</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- ==========Movie-Section========== -->
@endsection
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        updateTotalAmount(); // Gọi hàm khi trang tải
        function updateTotalAmount() {
            var items = document.querySelectorAll('#itemList .info');
            var total = 0;
            var orderItems = [];
            items.forEach(function(item) {
                var productId = item.querySelector('#book_id').value;
                console.log(productId)
                var priceElement = item.querySelector('.price');
                var quantityElement = item.querySelector('.quantity');

                if (priceElement && quantityElement) {
                    var price = parseFloat(priceElement.textContent);
                    var quantity = parseInt(quantityElement.textContent);
                    var subtotal = price * quantity;
                    total += subtotal;

                    // Thêm thông tin sản phẩm vào mảng orderItems
                    orderItems.push({
                        product_id: parseInt(productId),
                        quantity: (quantity),
                        price: (price)
                    });
                }
            });

            var totalAmountElement = document.getElementById('totalAmount');
            if (totalAmountElement) {
                totalAmountElement.textContent = total + ' vnd';

                // Cập nhật giá trị total_amount trong input ẩn để gửi lên server
                var totalAmountInput = document.querySelector('input[name="total_amount"]');
                if (totalAmountInput) {
                    totalAmountInput.value = total;
                }

                // Cập nhật giá trị order_items trong input ẩn để gửi lên server
                var orderItemsInput = document.querySelector('input[name="order_items"]');
                if (orderItemsInput) {
                    orderItemsInput.value = JSON.stringify(orderItems);
                }
            }
        }
    });
</script>

@endpush