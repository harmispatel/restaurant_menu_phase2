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

    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'],'homepage_intro');
    $homepage_intro = (isset($homepage_intro[$current_lang_code."_value"]) && !empty($homepage_intro[$current_lang_code."_value"])) ? $homepage_intro[$current_lang_code."_value"] : '';

    $min_amount_for_delivery = (isset($order_settings['min_amount_for_delivery']) && !empty($order_settings['min_amount_for_delivery'])) ? unserialize($order_settings['min_amount_for_delivery']) : [];

    $remain_amount = 0;

    $is_checkout = ((isset($order_settings['delivery']) && $order_settings['delivery'] == 1) || (isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1) || (isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1) || (isset($order_settings['table_service']) && $order_settings['table_service'] == 1)) ? 1 : 0;

    if(isset($order_settings['only_cart']) && $order_settings['only_cart'] == 1)
    {
        $is_checkout = 0;
    }

    $discount_per = session()->get('discount_per');
    $discount_type = session()->get('discount_type');

    $delivery_schedule = checkDeliverySchedule($shop_details['id']);

    $current_check_type = session()->get('checkout_type');

    $total_cart_qty = getCartQuantity();

    // Home Page Intro
    $service_closed_message = moreTranslations($shop_details['id'],'service_closed_message');
    $service_closed_message = (isset($service_closed_message[$current_lang_code."_value"]) && !empty($service_closed_message[$current_lang_code."_value"])) ? $service_closed_message[$current_lang_code."_value"] : "Sorry you can't order! The store is closed during these hours.";

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
                            <h2>{{ __('Shopping Cart') }}</h2>
                        </div>
                        @if($is_checkout == 1)
                            <div class="col-md-4">
                                <select name="checkout_type" id="checkout_type" class="form-control">
                                    @if(isset($order_settings['delivery']) && $order_settings['delivery'] == 1)
                                        <option value="delivery" {{ ($current_check_type == 'delivery') ? 'selected' : '' }}>{{ __('Delivery') }}</option>
                                    @endif
                                    @if(isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1)
                                        <option value="takeaway" {{ ($current_check_type == 'takeaway') ? 'selected' : '' }}>{{ __('Takeaway') }}</option>
                                    @endif
                                    @if(isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1)
                                        <option value="room_delivery" {{ ($current_check_type == 'room_delivery') ? 'selected' : '' }}>{{ __('Room Delivery') }}</option>
                                    @endif
                                    @if(isset($order_settings['table_service']) && $order_settings['table_service'] == 1)
                                        <option value="table_service" {{ ($current_check_type == 'table_service') ? 'selected' : '' }}>{{ __('Table Service') }}</option>
                                    @endif
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body view_cart">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            @forelse ($cart as $cart_key => $cart_data)
                                @foreach ($cart_data as $cart_val)
                                    @foreach ($cart_val as $cart_item_key => $cart_item)
                                        @php
                                            $categories_data = $cart_item['categories_data'];

                                            $item_dt = itemDetails($cart_item['item_id']);
                                            $item_discount = (isset($item_dt['discount'])) ? $item_dt['discount'] : 0;
                                            $item_discount_type = (isset($item_dt['discount_type'])) ? $item_dt['discount_type'] : 'percentage';
                                            $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');

                                            $item_name = (isset($item_dt[$name_key])) ? $item_dt[$name_key] : '';

                                            $item_price_details = App\Models\ItemPrice::where('id',$cart_item['option_id'])->first();
                                            $item_price = (isset($item_price_details['price'])) ? Currency::currency($currency)->format($item_price_details['price']) : 0.00;
                                            $item_price_label = (isset($item_price_details[$label_key])) ? $item_price_details[$label_key] : '';

                                            $total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
                                        @endphp
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <a class="d-block text-dark text-decoration-none" style="font-size:1.4rem;"><b>{{ $item_name }}</b></a>
                                                <img src="{{ $item_image }}" class="w-25">
                                            </div>
                                            <div class="col-md-2 text-center">
                                                @if(!empty($item_price_label))
                                                    {{ $item_price_label }} -
                                                @endif
                                                @if($item_discount > 0)
                                                    @php
                                                        if($item_discount_type == 'fixed')
                                                        {
                                                            $new_price = number_format($item_price_details['price'] - $item_discount,2);
                                                        }
                                                        else
                                                        {
                                                            $dis_per = $item_price_details['price'] * $item_discount / 100;
                                                            $new_price = number_format($item_price_details['price'] - $dis_per,2);
                                                        }
                                                    @endphp
                                                    <span class="text-decoration-line-through">{{ $item_price }}</span>
                                                    <span>{{ Currency::currency($currency)->format($new_price) }}</span>
                                                @else
                                                    <span>{{ $item_price }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-3 text-center mt-1">
                                                <div class="row">
                                                    @if(count($categories_data) > 0)
                                                        <div class="col-md-12">
                                                            <strong>{{ __('Order Options') }}</strong>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <ul class="my-0 list-unstyled">
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
                                                                                <li style="font-size: 14px">
                                                                                    @if(!empty($opt_price_name))
                                                                                        {{ $opt_price_name }} -
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
                                                                        <li style="font-size: 14px">
                                                                            @if(!empty($opt_price_name))
                                                                                {{ $opt_price_name }} -
                                                                            @endif
                                                                            {{ $opt_price }}
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="view_cart_qty d-flex align-items-center justify-content-between">
                                                    <div class="d-flex w-100"><b>{{ __('Value') }} : </b>&nbsp;{{ $cart_item['total_amount_text'] }}</div>
                                                    <div class="d-flex align-items-center justify-content-end qty-m-view">
                                                        <input type="number" onchange="updateCart({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},this)" class="form-control me-2 text-center" value="{{ $cart_item['quantity'] }}" old-qty="{{ $cart_item['quantity'] }}">
                                                        <a onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                @endforeach
                            @empty
                                <h4 class="text-center">Cart is Empty</h4>
                            @endforelse
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table">
                                <tr>
                                    <td><b>{{ __('Total Amount') }}</b></td>
                                    <td class="text-end">{{ Currency::currency($currency)->format($total_amount) }}</td>
                                    <input type="hidden" name="total_cart_amount" id="total_cart_amount" value="{{ $total_amount }}">
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
                                            $discount_amount = $total_amount - $discount_amount;
                                        @endphp
                                        <td colspan="2"><strong>{{ Currency::currency($currency)->format($discount_amount) }}</strong></td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($is_checkout == 1 && $delivery_schedule == 1)
                        <div class="row">
                            <div class="col-md-12 mb-2" id="del-notes" style="display: none;">
                                @if(count($min_amount_for_delivery) > 0 && $current_check_type == 'delivery')
                                    <code>{{ __('Notes:') }}</code><br>
                                    @foreach ($min_amount_for_delivery as $min_key => $min_amount)
                                        @php
                                            $minAmount = (isset($min_amount['amount']) && !empty($min_amount['amount'])) ? $min_amount['amount'] : 0;
                                            // Distance Message
                                            $distance_message = moreTranslations($shop_details['id'],'distance_message');
                                            $distance_message = (isset($distance_message[$current_lang_code."_value"]) && !empty($distance_message[$current_lang_code."_value"])) ? $distance_message[$current_lang_code."_value"] : 'Distance from our store up ({from}) to ({to}) Km The lowest order price is ({amount}).';
                                            $distance_message = str_replace('{from}',$min_amount['from'],$distance_message);
                                            $distance_message = str_replace('{to}',$min_amount['to'],$distance_message);
                                            $distance_message = str_replace('{amount}',Currency::currency($currency)->format($minAmount),$distance_message);
                                        @endphp
                                        @if($minAmount > 0)
                                            <code class="notes-del"> - {{ $distance_message }}</code> <br>
                                        @endif
                                    @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <button type="button" id="check-btn" class="btn btn-lg btn-primary mt-2">{{ __('Checkout') }}</button>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12 text-danger" style="font-size:20px;">
                                {{-- {{ __("Sorry you can't order!") }} <br>
                                {{ __("The store is closed during these hours.") }} --}}
                                {!! $service_closed_message !!}
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

    <script type="text/javascript">

        var total_qty = @json($total_cart_qty);
        var shop_slug = @json($shop_slug);
        var empty_redirect = @json(url('restaurant'))+'/'+shop_slug;

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
        function updateCart(itemID,priceID,item_key,ele)
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
                    'price_id' : priceID,
                    'item_key' : item_key,
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
        function removeCartItem(itemID,priceID,item_key)
        {
            $.ajax({
                type: "POST",
                url: "{{ route('shop.remove.cart.item') }}",
                data: {
                    "_token" : "{{ csrf_token() }}",
                    'item_id' : itemID,
                    'price_id' : priceID,
                    'item_key' : item_key,
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


        $('#checkout_type').on('change',function(){
            var check_type = $(this).val();

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
                        location.reload();
                    }
                    else
                    {
                        toastr.error(response.message);
                        return false;
                    }
                }
            });
        });


        $(document).ready(function ()
        {
            if(total_qty == 0)
            {
                window.location.href = "";
            }

            var checkout_type = "{{ $current_check_type }}";

            if(checkout_type == '' || checkout_type == null)
            {
                $('#checkout_type').trigger('change');
            }

            var delNotes = $('.notes-del').length;
            if(delNotes == 0)
            {
                $('#del-notes').hide();
            }
            else
            {
                $('#del-notes').show();
            }

        });

    </script>

@endsection
