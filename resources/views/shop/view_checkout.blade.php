@php

    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');

    // Default Image
    $default_image = asset('public/client_images/not-found/no_image_1.jpg');

    // Shop Logo
    $shop_logo = isset($shop_settings['shop_view_header_logo']) && !empty($shop_settings['shop_view_header_logo']) ? $shop_settings['shop_view_header_logo'] : '';

    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

    // Name Key
    $name_key = $current_lang_code."_name";

    // Label Key
    $label_key = $current_lang_code."_label";

    // Total Amount
    $total_amount = 0;

    // Order Settings
    $order_settings = getOrderSettings($shop_details['id']);

    // Payment Settings
    $payment_settings = getPaymentSettings($shop_details['id']);

    $total_amount = 0;

    $discount_per = session()->get('discount_per');

@endphp

@extends('shop.shop-layout')

@section('title', 'Checkout')

@section('content')

    <input type="hidden" name="def_currency" id="def_currency" value="{{ $currency }}">

    <section class="mt-5 mb-5">
        <div class="container px-3 my-5 clearfix">
            <form action="{{ route('shop.cart.processing',$shop_slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="checkout_type" id="checkout_type" value="{{ $checkout_type }}">
                <div class="card">
                    <div class="card-header">
                        <h3>Checkout</h3>
                    </div>
                    <div class="card-body">
                        @if($checkout_type == 'takeaway')
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}">
                                    @if($errors->has('firstname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('firstname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}">
                                    @if($errors->has('lastname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('lastname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="phone" class="form-label">Phone No. <span class="text-danger">*</span></label>
                                    <input type="number" name="phone" id="phone" class="form-control {{ ($errors->has('phone')) ? 'is-invalid' : '' }}" value="{{ old('phone') }}">
                                    @if($errors->has('phone'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="cash" {{ (old('payment_method') == 'cash') ? 'selected' : '' }}>Cash</option>
                                        @if(isset($payment_settings['paypal']) && $payment_settings['paypal'] == 1)
                                            <option value="paypal" {{ (old('payment_method') == 'paypal') ? 'selected' : '' }}>PayPal</option>
                                        @endif
                                        @if(isset($payment_settings['every_pay']) && $payment_settings['every_pay'] == 1)
                                            <option value="every_pay" {{ (old('payment_method') == 'every_pay') ? 'selected' : '' }}>EveryPay Credit Card</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @elseif($checkout_type == 'table_service')
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="table" class="form-label">Table <span class="text-danger">*</span></label>
                                    <input type="number" name="table" id="table" class="form-control {{ ($errors->has('table')) ? 'is-invalid' : '' }}">
                                    @if($errors->has('table'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('table') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="cash" {{ (old('payment_method') == 'cash') ? 'selected' : '' }}>Cash</option>
                                        @if(isset($payment_settings['paypal']) && $payment_settings['paypal'] == 1)
                                            <option value="paypal" {{ (old('payment_method') == 'paypal') ? 'selected' : '' }}>PayPal</option>
                                        @endif
                                        @if(isset($payment_settings['every_pay']) && $payment_settings['every_pay'] == 1)
                                            <option value="every_pay" {{ (old('payment_method') == 'every_pay') ? 'selected' : '' }}>EveryPay Credit Card</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @elseif($checkout_type == 'room_delivery')
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}">
                                    @if($errors->has('firstname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('firstname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}">
                                    @if($errors->has('lastname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('lastname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="room" class="form-label">Room <span class="text-danger">*</span></label>
                                    <input type="number" name="room" id="room" class="form-control {{ ($errors->has('room')) ? 'is-invalid' : '' }}" value="{{ old('room') }}">
                                    @if($errors->has('room'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('room') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="delivery_time" class="form-label">Delivery Time</label>
                                    <input type="text" name="delivery_time" id="delivery_time" class="form-control" value="{{ old('delivery_time') }}">
                                    <code>Ex:- 9:30-10:00</code>
                                </div>
                                <div class="col-md-6">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="cash" {{ (old('payment_method') == 'cash') ? 'selected' : '' }}>Cash</option>
                                        @if(isset($payment_settings['paypal']) && $payment_settings['paypal'] == 1)
                                            <option value="paypal" {{ (old('payment_method') == 'paypal') ? 'selected' : '' }}>PayPal</option>
                                        @endif
                                        @if(isset($payment_settings['every_pay']) && $payment_settings['every_pay'] == 1)
                                            <option value="every_pay" {{ (old('payment_method') == 'every_pay') ? 'selected' : '' }}>EveryPay Credit Card</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        @endif
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @if(count($cart) > 0)
                                    @foreach ($cart as $cart_val)
                                        @php
                                            $categories_data = $cart_val['categories_data'];

                                            $item_dt = itemDetails($cart_val['item_id']);
                                            $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');

                                            $item_name = (isset($item_dt[$name_key])) ? $item_dt[$name_key] : '';

                                            $item_price_details = App\Models\ItemPrice::where('id',$cart_val['option_id'])->first();
                                            $item_price = (isset($item_price_details['price'])) ? Currency::currency($currency)->format($item_price_details['price']) : 0.00;
                                            $item_price_label = (isset($item_price_details[$label_key])) ? $item_price_details[$label_key] : '';

                                            $total_amount += isset($cart_val['total_amount']) ? $cart_val['total_amount'] : 0;
                                        @endphp

                                        <div class="row align-items-center mb-2 bg-light p-2 m-2">
                                            <div class="col-md-2 text-center">
                                                <span class="me-2">{{ $cart_val['quantity'] }} <span> x </span></span>
                                                <img src="{{ $item_image }}" width="40" height="40">
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <b>{{ $item_name }}</b>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <div class="col-md-12">
                                                        @if(!empty($item_price_label))
                                                            {{ $item_price_label }},
                                                        @endif

                                                        @if(count($categories_data) > 0)
                                                            @foreach ($categories_data as $option_id)
                                                                @php
                                                                    $my_opt = $option_id;
                                                                @endphp

                                                                @if(is_array($my_opt))
                                                                    @if(count($my_opt) > 0)
                                                                        @foreach ($my_opt as $optid)
                                                                            @php
                                                                                $opt_price_dt = App\Models\OptionPrice::where('id',$optid)->first();
                                                                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                            @endphp
                                                                            @if(!empty($opt_price_name))
                                                                                {{ $opt_price_name }},
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @else
                                                                    @php
                                                                        $opt_price_dt = App\Models\OptionPrice::where('id',$my_opt)->first();
                                                                        $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                        $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                    @endphp
                                                                    @if(!empty($opt_price_name))
                                                                        {{ $opt_price_name }},
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-3 text-center">
                                                <b>Sub Total : </b>{{ $cart_val['total_amount_text'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-4 bg-light p-3">
                                <table class="table">
                                    <tr>
                                        <td><b>Total Amount</b></td>
                                        <td class="text-end">{{ Currency::currency($currency)->format($total_amount) }}</td>
                                    </tr>
                                    @if($discount_per > 0)
                                        <tr>
                                            <td><b>Discount</b></td>
                                            <td class="text-end">- {{ $discount_per }}%</td>
                                        </tr>
                                        <tr class="text-end">
                                            @php
                                                $discount_amount = ($total_amount * $discount_per) / 100;
                                                $discount_amount = $total_amount - $discount_amount;
                                            @endphp
                                            <td colspan="2"><strong>{{ Currency::currency($currency)->format($discount_amount) }}</strong></td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-success">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <footer class="footer text-center">
        <div class="container">
            <div class="footer-inr">
                <div class="footer_media">
                    <h3>Find Us</h3>
                    <ul>
                        {{-- Phone Link --}}
                        @if (isset($shop_settings['business_telephone']) && !empty($shop_settings['business_telephone']))
                            <li>
                                <a href="tel:{{ $shop_settings['business_telephone'] }}"><i
                                        class="fa-solid fa-phone"></i></a>
                            </li>
                        @endif

                        {{-- Instagram Link --}}
                        @if (isset($shop_settings['instagram_link']) && !empty($shop_settings['instagram_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['instagram_link'] }}"><i
                                        class="fa-brands fa-square-instagram"></i></a>
                            </li>
                        @endif

                        {{-- Twitter Link --}}
                        @if (isset($shop_settings['twitter_link']) && !empty($shop_settings['twitter_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['twitter_link'] }}"><i
                                        class="fa-brands fa-square-twitter"></i></a>
                            </li>
                        @endif

                        {{-- Facebook Link --}}
                        @if (isset($shop_settings['facebook_link']) && !empty($shop_settings['facebook_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['facebook_link'] }}"><i
                                        class="fa-brands fa-square-facebook"></i></a>
                            </li>
                        @endif

                        {{-- Pinterest Link --}}
                        @if (isset($shop_settings['pinterest_link']) && !empty($shop_settings['pinterest_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['pinterest_link'] }}"><i
                                        class="fa-brands fa-pinterest"></i></a>
                            </li>
                        @endif

                        {{-- FourSquare Link --}}
                        @if (isset($shop_settings['foursquare_link']) && !empty($shop_settings['foursquare_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['foursquare_link'] }}"><i
                                        class="fa-brands fa-foursquare"></i></a>
                            </li>
                        @endif

                        {{-- TripAdvisor Link --}}
                        @if (isset($shop_settings['tripadvisor_link']) && !empty($shop_settings['tripadvisor_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><a target="_blank"
                                        href="{{ $shop_settings['tripadvisor_link'] }}"><i
                                            class="fa-solid fa-mask"></i></a></a>
                            </li>
                        @endif

                        {{-- Website Link --}}
                        @if (isset($shop_settings['website_url']) && !empty($shop_settings['website_url']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['website_url'] }}"><i
                                        class="fa-solid fa-globe"></i></a>
                            </li>
                        @endif

                        {{-- Gmap Link --}}
                        @if (isset($shop_settings['map_url']) && !empty($shop_settings['map_url']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['map_url'] }}"><i
                                        class="fa-solid fa-location-dot"></i></a>
                            </li>
                        @endif

                    </ul>
                </div>

                @if (isset($shop_settings['homepage_intro']) && !empty($shop_settings['homepage_intro']))
                    <p>{!! $shop_settings['homepage_intro'] !!}</p>
                @else
                    @php
                        $current_year = \Carbon\Carbon::now()->format('Y');
                        $settings = getAdminSettings();
                        $copyright_text = isset($settings['copyright_text']) && !empty($settings['copyright_text']) ? $settings['copyright_text'] : '';
                        $copyright_text = str_replace('[year]', $current_year, $copyright_text);
                    @endphp
                    <p>{!! $copyright_text !!}</p>
                @endif

            </div>
        </div>
    </footer>

    <a class="back_bt" href="{{ route('restaurant', $shop_details['shop_slug']) }}"><i
            class="fa-solid fa-chevron-left"></i></a>

@endsection

{{-- Page JS Function --}}
@section('page-js')

    <script type="text/javascript">

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": 4000
        }

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif

    </script>

@endsection
