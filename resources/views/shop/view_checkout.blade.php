@php

    $admin_settings = getAdminSettings();
    $google_map_api = (isset($admin_settings['google_map_api'])) ? $admin_settings['google_map_api'] : '';

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

    // Delivery Message
    $delivery_message = moreTranslations($shop_details['id'],'delivery_message');
    $delivery_message = (isset($delivery_message[$current_lang_code."_value"]) && !empty($delivery_message[$current_lang_code."_value"])) ? $delivery_message[$current_lang_code."_value"] : 'Sorry your address is out of our delivery range.';

    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'],'homepage_intro');
    $homepage_intro = (isset($homepage_intro[$current_lang_code."_value"]) && !empty($homepage_intro[$current_lang_code."_value"])) ? $homepage_intro[$current_lang_code."_value"] : '';

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
    $discount_type = session()->get('discount_type');

    // Cust Lat,Long & Address
    $cust_lat = session()->get('cust_lat');
    $cust_lng = session()->get('cust_long');
    $cust_address = session()->get('cust_address');
    $cust_street = session()->get('cust_street');
@endphp

@extends('shop.shop-layout')

@section('title', 'Checkout')

@section('content')

    {{-- Delivery Message Modal --}}
    <div class="modal fade" id="deliveyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deliveyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row delivery-message" style="display: none;">
                        <div class="col-md-12 mb-2">
                            {!! $delivery_message !!}
                        </div>
                    </div>
                    <div class="row street_no mt-2" style="display: none;">
                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label for="street_no" class="form-label">{{ __("Street Number") }}</label>
                                <input type="text" name="street_no" id="street_no" class="form-control" placeholder="{{ __('You forgot the Street Number!') }}">
                            </div>
                            <div class="form-group">
                                <a class="btn btn-success street-btn btn-sm">{{ __('Submission') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distance Message Modal --}}
    <div class="modal fade" id="distanceMessageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="distanceMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">

                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="def_currency" id="def_currency" value="{{ $currency }}">

    <section class="mt-5 mb-5">
        <div class="container px-3 my-5 clearfix">
            <form action="{{ route('shop.cart.processing',$shop_slug) }}" id="checkoutForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="checkout_type" id="checkout_type" value="{{ $checkout_type }}">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Checkout') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($checkout_type == 'takeaway')
                                <div class="col-md-6 mb-2">
                                    <label for="firstname" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}">
                                    @if($errors->has('firstname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('firstname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="lastname" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}">
                                    @if($errors->has('lastname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('lastname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="phone" class="form-label">{{ __('Mobile No.') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="phone" id="phone" class="form-control {{ ($errors->has('phone')) ? 'is-invalid' : '' }}" value="{{ old('phone') }}">
                                    @if($errors->has('phone'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                            @elseif($checkout_type == 'table_service')
                                <div class="col-md-6 mb-2">
                                    <label for="table" class="form-label">{{ __('Table No.') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="table" id="table" class="form-control {{ ($errors->has('table')) ? 'is-invalid' : '' }}">
                                    @if($errors->has('table'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('table') }}
                                        </div>
                                    @endif
                                </div>
                            @elseif($checkout_type == 'room_delivery')
                                <div class="col-md-6 mb-2">
                                    <label for="firstname" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}">
                                    @if($errors->has('firstname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('firstname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="lastname" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}">
                                    @if($errors->has('lastname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('lastname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="room" class="form-label">{{ __('Room No.') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="room" id="room" class="form-control {{ ($errors->has('room')) ? 'is-invalid' : '' }}" value="{{ old('room') }}">
                                    @if($errors->has('room'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('room') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="delivery_time" class="form-label">{{ __('Delivery Time') }}</label>
                                    <input type="text" name="delivery_time" id="delivery_time" class="form-control" value="{{ old('delivery_time') }}">
                                    <code>Ex:- 9:30-10:00</code>
                                </div>
                            @elseif ($checkout_type == 'delivery')
                                <input type="hidden" name="street_number" id="street_number" class="form-control " value="{{ $cust_street }}">
                                <div class="col-md-12 mb-2">
                                    <label for="address" class="form-label">{{ __('Address') }} <span class="text-danger">*</span></label>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ $cust_lat }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ $cust_lng }}">
                                    <input type="text" name="address" id="address" class="form-control {{ ($errors->has('address')) ? 'is-invalid' : '' }}" value="{{ $cust_address }}">
                                    @if($errors->has('address'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('address') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="firstname" class="form-label">{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}">
                                    @if($errors->has('firstname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('firstname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="lastname" class="form-label">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}">
                                    @if($errors->has('lastname'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('lastname') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="email" class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="phone" class="form-label">{{ __('Mobile No.') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="phone" id="phone" class="form-control {{ ($errors->has('phone')) ? 'is-invalid' : '' }}" value="{{ old('phone') }}">
                                    @if($errors->has('phone'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-4">
                                    <div id="map" style="height: 500px;"></div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="floor" class="form-label">{{ __('Floor') }}</label>
                                    <input type="text" name="floor" id="floor" class="form-control" value="{{ old('floor') }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="door_bell" class="form-label">{{ __('Door Bell') }}</label>
                                    <input type="text" name="door_bell" id="door_bell" class="form-control" value="{{ old('door_bell') }}">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="instructions" class="form-label">{{ __('Instructions') }}</label>
                                    <textarea name="instructions" id="instructions" rows="3" class="form-control">{{ old('instructions') }}</textarea>
                                </div>
                            @endif
                            <div class="col-md-6 mb-2 tip-div" style="display: none;">
                                <label for="tip" class="form-label">{{ __('Tip') }}</label>
                                <input type="text" id="tip" name="tip" value="" class="form-control" placeholder="Enter Tip Price">
                            </div>
                            <div class="col-md-6">
                                <label for="payment_method" class="form-label">{{ __('Payment Method') }}</label>
                                <select name="payment_method" id="payment_method" class="form-select">
                                    @if(isset($payment_settings['cash']) && $payment_settings['cash'] == 1)
                                        <option value="cash" {{ (old('payment_method') == 'cash') ? 'selected' : '' }}>Cash</option>
                                    @endif
                                    @if(isset($payment_settings['cash_pos']) && $payment_settings['cash_pos'] == 1)
                                        <option value="cash_pos" {{ (old('payment_method') == 'cash_pos') ? 'selected' : '' }}>Cash POS</option>
                                    @endif
                                    @if(isset($payment_settings['paypal']) && $payment_settings['paypal'] == 1)
                                        <option value="paypal" {{ (old('payment_method') == 'paypal') ? 'selected' : '' }}>PayPal</option>
                                    @endif
                                    @if(isset($payment_settings['every_pay']) && $payment_settings['every_pay'] == 1)
                                        <option value="every_pay" {{ (old('payment_method') == 'every_pay') ? 'selected' : '' }}>Credit/Debit Card</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @if(count($cart) > 0)
                                    @foreach ($cart as $cart_data)
                                        @if(count($cart_data) > 0)
                                            @foreach ($cart_data as $cart_val)
                                                @if(count($cart_val) > 0)
                                                    @foreach ($cart_val as $cart_item)
                                                        @php
                                                            $categories_data = $cart_item['categories_data'];

                                                            $item_dt = itemDetails($cart_item['item_id']);
                                                            $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');

                                                            $item_name = (isset($item_dt[$name_key])) ? $item_dt[$name_key] : '';

                                                            $item_price_details = App\Models\ItemPrice::where('id',$cart_item['option_id'])->first();
                                                            $item_price = (isset($item_price_details['price'])) ? Currency::currency($currency)->format($item_price_details['price']) : 0.00;
                                                            $item_price_label = (isset($item_price_details[$label_key])) ? $item_price_details[$label_key] : '';

                                                            $total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
                                                        @endphp

                                                        <div class="row align-items-center mb-2 bg-light p-2 m-2">
                                                            <div class="col-md-2 text-center">
                                                                <span class="me-2">{{ $cart_item['quantity'] }} <span> x </span></span>
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
                                                                <b>{{ __('Sub Total') }} : </b>{{ $cart_item['total_amount_text'] }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-4 bg-light p-3">
                                <table class="table">
                                    <tr>
                                        <td><b>{{ __('Total Amount') }}</b></td>
                                        <td class="text-end">{{ Currency::currency($currency)->format($total_amount) }}</td>
                                    </tr>
                                    @if($discount_per > 0)
                                        <tr>
                                            <td><b>{{ __('Discount') }}</b></td>
                                            @if($discount_type == 'fixed')
                                                <td class="text-end">- {{ Currency::currency($currency)->format($discount_per) }}</td>
                                            @else
                                                <td class="text-end">- {{ $discount_per }}%</td>
                                            @endif
                                        </tr>
                                        <tr class="text-end">
                                            @php
                                                if($discount_type == 'fixed')
                                                {
                                                    $discount_amount = $discount_per;
                                                }
                                                else
                                                {
                                                    $discount_amount = ($total_amount * $discount_per) / 100;
                                                }
                                                $total_amount = $total_amount - $discount_amount;
                                            @endphp
                                            <td colspan="2"><strong class="final-amount-div">{{ Currency::currency($currency)->format($total_amount) }}</strong></td>
                                        </tr>
                                    @else
                                        <tr class="text-end">
                                            <td colspan="2"><strong class="final-amount-div">{{ Currency::currency($currency)->format($total_amount) }}</strong></td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="t_amount" id="t_amount" value="{{ $total_amount }}">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button class="btn btn-success">{{ __('Continue') }}</button>
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

                @if (isset($homepage_intro) && !empty($homepage_intro))
                    <p>{!! $homepage_intro !!}</p>
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

    <a class="back_bt" href="{{ route('restaurant', $shop_details['shop_slug']) }}"><i class="fa-solid fa-chevron-left"></i></a>

@endsection

{{-- Page JS Function --}}
@section('page-js')

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $google_map_api }}&libraries=places"></script>

    <script type="text/javascript">

        $(document).ready(function ()
        {
            const payment_method = $('#payment_method :selected').val();
            if(payment_method == 'paypal' || payment_method == 'every_pay')
            {
                $('.tip-div').show();
            }
            else
            {
                $('.tip-div').hide();
            }
        });


        // Toggle Tip Div
        $('#payment_method').on('change',function()
        {
            const payment_method = $('#payment_method :selected').val();
            if(payment_method == 'paypal' || payment_method == 'every_pay')
            {
                $('.tip-div').show();
            }
            else
            {
                $('.tip-div').hide();
            }
        });


        // Map Functionality
        var lat = "{{ $cust_lat }}";
        var lng = "{{ $cust_lng }}";
        var check_type = "{{ $checkout_type }}";

        navigator.geolocation.getCurrentPosition(
            function (position)
            {
                if(lat == '' || lng == '')
                {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;
                }

                if(check_type == 'delivery')
                {
                    initMap(lat,lng);
                }

            },
            function errorCallback(error)
            {
                console.log(error)
            }
        );

        function initMap(lat,long)
        {
            const myLatLng = { lat: parseFloat(lat), lng: parseFloat(long) };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: myLatLng,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
            });
        }

        if(check_type == 'delivery')
        {
            google.maps.event.addDomListener(window, 'load', initialize);

            function initialize()
            {
                var input = document.getElementById('address');
                var autocomplete = new google.maps.places.Autocomplete(input);

                $('#address').keydown(function (e)
                {
                    if (e.keyCode == 13)
                    {
                        e.preventDefault();
                        return false;
                    }
                });

                autocomplete.addListener('place_changed', function ()
                {
                    var place = autocomplete.getPlace();
                    if(place != '')
                    {
                        initMap(place.geometry['location'].lat(),place.geometry['location'].lng());
                        $('#latitude').val(place.geometry['location'].lat());
                        $('#longitude').val(place.geometry['location'].lng());

                        var streetNumber = '';
                        place.address_components.forEach(function (component) {
                            if (component.types.includes('street_number')) {
                                streetNumber = component.long_name;
                            }
                        });

                        if(!streetNumber){
                            $('#deliveyModal').modal('show');
                            $('.street_no').show();
                            $('#street_no').val('');
                            $('.delivery-message').hide();
                        }else{
                            $('#street_number').val(streetNumber);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('set.delivery.address') }}",
                                data: {
                                    "_token" : "{{ csrf_token() }}",
                                    "latitude" : place.geometry['location'].lat(),
                                    "longitude" : place.geometry['location'].lng(),
                                    "address" : $('#address').val(),
                                    "street_number" : streetNumber,
                                    "shop_id" : "{{ $shop_details['id'] }}",
                                },
                                dataType: "JSON",
                                success: function (response)
                                {
                                    if(response.success == 1)
                                    {
                                        if(response.available == 0)
                                        {
                                            $('#street_no').val('');
                                            $('.street_no').hide();
                                            $('.delivery-message').show();
                                            $('#deliveyModal').modal('show');
                                        }
                                        else
                                        {
                                            $('#street_no').val('');
                                            $('.street_no').hide();
                                            $('#deliveyModal').modal('hide');

                                            $.ajax({
                                                type: "POST",
                                                url: "{{ route('check.min_amount_for_delivery') }}",
                                                data: {
                                                    "_token" : "{{ csrf_token() }}",
                                                    "latitude" : place.geometry['location'].lat(),
                                                    "longitude" : place.geometry['location'].lng(),
                                                    "address" : $('#address').val(),
                                                    "shop_id" : "{{ $shop_details['id'] }}",
                                                    "total_amount" : $('#t_amount').val(),
                                                    "currency" : "{{ $currency }}",
                                                },
                                                dataType: "JSON",
                                                success: function (response)
                                                {
                                                    if (response.success == 0)
                                                    {
                                                        $('#distanceMessageModal .modal-body').html('');
                                                        $('#distanceMessageModal .modal-body').append(response.message);
                                                        $('#distanceMessageModal').modal('show');
                                                    }
                                                }
                                            });
                                        }
                                    }
                                    else
                                    {
                                        console.error(response.message);
                                    }
                                }
                            });
                        }
                    }
                });
            }

            $('.street-btn').on('click',function(){
                var street = $('#street_no').val();
                if(!street){
                    alert('Please Enter Street Number');
                }else{
                    var address = $('#address').val();
                    var commaIndex = address.indexOf(',');

                    if (commaIndex !== -1)
                    {
                        var firstPart = address.slice(0, commaIndex);
                        var secondPart = address.slice(commaIndex);
                        address = firstPart +" "+ street + secondPart;
                    }

                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        'address': address
                    },
                    function(results, status) {
                        if (status === 'OK')
                        {
                            $('#street_number').val(street);
                            $('#address').val(address);
                            var latitude = results[0].geometry.location.lat();
                            var longitude = results[0].geometry.location.lng();

                            $('#latitude').val(latitude);
                            $('#longitude').val(longitude);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('set.delivery.address') }}",
                                data: {
                                    "_token" : "{{ csrf_token() }}",
                                    "latitude" : latitude,
                                    "longitude" : longitude,
                                    "address" : $('#address').val(),
                                    "street_number" : street,
                                    "shop_id" : "{{ $shop_details['id'] }}",
                                },
                                dataType: "JSON",
                                success: function (response)
                                {
                                    if(response.success == 1)
                                    {
                                        if(response.available == 0)
                                        {
                                            $('#street_no').val('');
                                            $('.street_no').hide();
                                            $('.delivery-message').show();
                                            $('#deliveyModal').modal('show');
                                        }
                                        else
                                        {
                                            $('#street_no').val('');
                                            $('.street_no').hide();
                                            $('#deliveyModal').modal('hide');

                                            $.ajax({
                                                type: "POST",
                                                url: "{{ route('check.min_amount_for_delivery') }}",
                                                data: {
                                                    "_token" : "{{ csrf_token() }}",
                                                    "latitude" : latitude,
                                                    "longitude" : longitude,
                                                    "address" : $('#address').val(),
                                                    "shop_id" : "{{ $shop_details['id'] }}",
                                                    "total_amount" : $('#t_amount').val(),
                                                    "currency" : "{{ $currency }}",
                                                },
                                                dataType: "JSON",
                                                success: function (response)
                                                {
                                                    if (response.success == 0)
                                                    {
                                                        $('#distanceMessageModal .modal-body').html('');
                                                        $('#distanceMessageModal .modal-body').append(response.message);
                                                        $('#distanceMessageModal').modal('show');
                                                    }
                                                }
                                            });
                                        }
                                    }
                                    else
                                    {
                                        console.error(response.message);
                                    }
                                }
                            });
                        }
                    });
                }
            });
        }
        // End Map Functionality

        // Toastr
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

        $('#tip').on('change',function()
        {
            const tip = $(this).val();
            var total_text = '';
            var amount = 0;
            var curr_symbol = @json(Currency::currency($currency)->currencyOptions['symbol']);

            if(tip != '' && tip > 0)
            {
                var tamount = $('#t_amount').val();
                amount = parseFloat(tamount) + parseFloat(tip);
                total_text += curr_symbol+" "+parseFloat(amount).toFixed(2);
                $('.final-amount-div').html('');
                $('.final-amount-div').append(total_text)
            }
            else
            {
                var tamount = $('#t_amount').val();
                amount = parseFloat(tamount) + parseFloat(0);
                total_text += curr_symbol+" "+parseFloat(amount).toFixed(2);
                $('.final-amount-div').html('');
                $('.final-amount-div').append(total_text)
            }
        });

    </script>

@endsection
