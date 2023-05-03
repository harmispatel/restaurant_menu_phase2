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

    $is_checkout = ((isset($order_settings['delivery']) && $order_settings['delivery'] == 1) || (isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1) || (isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1) || (isset($order_settings['table_service']) && $order_settings['table_service'] == 1)) ? 1 : 0;

    if(isset($order_settings['only_cart']) && $order_settings['only_cart'] == 1)
    {
        $is_checkout = 0;
    }

    $discount_per = session()->get('discount_per');

@endphp

@extends('shop.shop-layout')

@section('title', 'Cart')

@section('content')

    <input type="hidden" name="def_currency" id="def_currency" value="{{ $currency }}">

    <section class="mt-5 mb-5">
        <div class="container px-3 my-5 clearfix">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <h2>Shopping Cart</h2>
                        </div>
                        @if($is_checkout == 1)
                            <div class="col-md-4">
                                <select name="checkout_type" id="checkout_type" class="form-control">
                                    {{-- @if(isset($order_settings['delivery']) && $order_settings['delivery'] == 1)
                                        <option value="delivery">Delivery</option>
                                    @endif --}}
                                    @if(isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1)
                                        <option value="takeaway">Takeaway</option>
                                    @endif
                                    @if(isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1)
                                        <option value="room_delivery">Room Delivery</option>
                                    @endif
                                    @if(isset($order_settings['table_service']) && $order_settings['table_service'] == 1)
                                        <option value="table_service">Table Service</option>
                                    @endif
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            @forelse ($cart as $cart_val)
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

                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="cart-media">
                                            <img src="{{ $item_image }}" width="100" height="100">
                                            <div class="media-body ms-3">
                                                <a class="d-block text-dark text-decoration-none fs-2"><b>{{ $item_name }}</b></a>
                                                @if(count($categories_data) > 0)
                                                    <ul class="my-2">
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
                                                                        <li>
                                                                            @if(!empty($opt_price_name))
                                                                                <b>{{ $opt_price_name }} - </b>
                                                                            @endif
                                                                            {{ $opt_price }}
                                                                        </li>
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                @php
                                                                    $opt_price_dt = App\Models\OptionPrice::where('id',$my_opt)->first();
                                                                    $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                    $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                @endphp
                                                                <li>
                                                                    @if(!empty($opt_price_name))
                                                                        <b>{{ $opt_price_name }} - </b>
                                                                    @endif
                                                                    {{ $opt_price }}
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        @if(!empty($item_price_label))
                                            <b>{{ $item_price_label }} - </b>
                                        @endif
                                        {{ $item_price }}
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <b>Sub Total : </b>{{ $cart_val['total_amount_text'] }}
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center qty-m-view">
                                            <input type="number" onchange="updateCart({{ $cart_val['item_id'] }},this)" class="form-control me-2 text-center" value="{{ $cart_val['quantity'] }}" old-qty="{{ $cart_val['quantity'] }}">
                                            <a onclick="removeCartItem({{ $cart_val['item_id'] }})" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @empty
                                <div class="col-md-12 text-center">
                                    <h4>Cart is Empty</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
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
                        {{-- <div class="col-md-12">
                            <label class="text-muted font-weight-normal m-0">Total Amount</label>
                            <div class="text-large"><strong>{{ Currency::currency($currency)->format($total_amount) }}</strong></div>
                        </div>
                        @if($discount_per > 0)
                            <div class="col-md-12">
                                <label class="text-muted font-weight-normal m-0">Discount</label>
                                <div class="text-large"><strong> - {{ $discount_per }}</strong></div>
                            </div>
                        @endif --}}
                    </div>

                    @if($is_checkout == 1)
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" id="check-btn" class="btn btn-lg btn-primary mt-2">Checkout</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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

    <a class="back_bt" href="{{ route('restaurant', $shop_details['shop_slug']) }}"><i class="fa-solid fa-chevron-left"></i></a>

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

        // Function for Update Cart
        function updateCart(itemID,ele)
        {
            var qty = $(ele).val();
            var old_qty = $(ele).attr('old-qty');
            var currency = $('#def_currency').val();

            $.ajax({
                type: "POST",
                url: "{{ route('shop.update.cart') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    'quantity' : qty,
                    'old_quantity' : old_qty,
                    'item_id' : itemID,
                    'currency' : currency,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });

        }

        // Function for Remove Cart Items
        function removeCartItem(itemID)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('shop.remove.cart.item') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    'item_id' : itemID,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }

        // Redirect to Checkout Page
        $('#check-btn').on('click',function()
        {
            var check_type = $('#checkout_type :selected').val();
            var shop_slug = "{{ $shop_slug }}";

            $.ajax({
                type: "POST",
                url: "{{ route('set.checkout.type') }}",
                data: {
                    '_token' : "{{ csrf_token() }}",
                    'check_type' : check_type,
                },
                dataType: "JSON",
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        window.location.href = "cart/checkout";
                    }
                    else
                    {
                        toastr.error(response.message);
                        return false;
                    }
                }
            });
        });

    </script>

@endsection
