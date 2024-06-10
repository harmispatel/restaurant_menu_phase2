@php

    $admin_settings = getAdminSettings();
    $google_map_api = (isset($admin_settings['google_map_api'])) ? $admin_settings['google_map_api'] : '';

    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_id = isset($shop_details['id']) ? $shop_details['id'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');

    // Default Image
    $default_image = asset('public/client_images/not-found/no_image_1.jpg');

    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Shop Currency
    $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

    // Shop Time
    $shop_start_time = isset($shop_settings['shop_start_time']) && !empty($shop_settings['shop_start_time']) ? $shop_settings['shop_start_time'] : '';
    $shop_end_time = isset($shop_settings['shop_end_time']) && !empty($shop_settings['shop_end_time']) ? $shop_settings['shop_end_time'] : '';

    // Delivery Message
    $delivery_message = moreTranslations($shop_details['id'],'delivery_message');
    $delivery_message = (isset($delivery_message[$current_lang_code."_value"]) && !empty($delivery_message[$current_lang_code."_value"])) ? $delivery_message[$current_lang_code."_value"] : 'Sorry your address is out of our delivery range.';

    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'],'homepage_intro');
    $homepage_intro = (isset($homepage_intro[$current_lang_code."_value"]) && !empty($homepage_intro[$current_lang_code."_value"])) ? $homepage_intro[$current_lang_code."_value"] : '';

    // Get Banner Settings
    $shop_banners = getBanners($shop_details['id']);
    $shop_banner_count = (count($shop_banners) == 1) ? 'false' : 'true';
    $banner_key = $language_details['code'] . '_image';
    $banner_text_key = $language_details['code'] . '_description';

    // Shop Title
    $shop_subtitle = isset($shop_settings['business_subtitle']) && !empty($shop_settings['business_subtitle']) ? $shop_settings['business_subtitle'] : '';

    $waiter_call_status = isset($shop_settings['waiter_call_status']) ?  $shop_settings['waiter_call_status'] : '0';



    // Name Key
    $name_key = $current_lang_code."_name";

    // Price key
    $price_label_key = $current_lang_code . '_label';

    // Label Key
    $label_key = $current_lang_code."_label";

    // Title Key
    $title_key = $current_lang_code . '_title';


    // Total Amount
    $total_amount = 0;

    // Order Settings
    $order_settings = getOrderSettings($shop_details['id']);

    // Payment Settings
    $payment_settings = getPaymentSettings($shop_details['id']);



    $total_amount = 0;

    $discount_per = session()->get('discount_per');
    $discount_type = session()->get('discount_type');

      $coupon_value = session()->get('coupon_value');
    $coupon_type = session()->get('coupon_type');

    $coupon_discount = 0;
    $total_discount = 0;

    // Cust Lat,Long & Address
    $cust_lat = session()->get('cust_lat');
    $cust_lng = session()->get('cust_long');
    $cust_address = session()->get('cust_address');
    $cust_street = session()->get('cust_street');

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);
    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';
    $effect = isset($theme_settings['slider_effect']) ? $theme_settings['slider_effect'] : 'fabe';
    $slider_buttons = isset($theme_settings['banner_slide_button']) && !empty($theme_settings['banner_slide_button']) ? $theme_settings['banner_slide_button'] : 0;
    $slider_delay_time = isset($theme_settings['banner_delay_time']) && !empty($theme_settings['banner_delay_time']) ? $theme_settings['banner_delay_time'] : 3000;
    $banner_height = isset($theme_settings['banner_height']) && !empty($theme_settings['banner_height']) ? $theme_settings['banner_height'] : 250;
    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';
    $logo_position = isset($theme_settings['logo_position']) ? $theme_settings['logo_position'] : '';
    $search_box_position = isset($theme_settings['search_box_position']) ? $theme_settings['search_box_position'] : '';
    $stiky_header =  isset($theme_settings['sticky_header']) && !empty($theme_settings['sticky_header']) ? $theme_settings['sticky_header']  : '';



    // header image
    $header_img = (isset($theme_settings['header_image']) && !empty($theme_settings['header_image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image']) : asset('public/client/assets/images2/allo_spritz.jpg');
    // Get Language Settings
    $language_settings = clientLanguageSettings($shop_details['id']);

    $google_translate = isset($language_settings['google_translate']) ? $language_settings['google_translate'] : 0;

    // Cart Quantity
    $total_quantity = getCartQuantity();

    $final_amount = 0;

    // Header Text One
    $header_text_one = moreTranslations($shop_details['id'], 'header_text_1');
    $header_text_one = isset($header_text_one[$current_lang_code. '_value']) && !empty($header_text_one[$current_lang_code . '_value']) ? $header_text_one[$current_lang_code . '_value'] : '';

    $header_text_two = moreTranslations($shop_details['id'], 'header_text_2');
    $header_text_two = isset($header_text_two[$current_lang_code. '_value']) && !empty($header_text_two[$current_lang_code . '_value']) ? $header_text_two[$current_lang_code . '_value'] : '';

    $is_sub_title = isset($shop_settings['is_sub_title']) ? $shop_settings['is_sub_title'] : '0';


    // Shop Name
    $shop_name = isset($shop_details['name']) && !empty($shop_details['name']) ? $shop_details['name'] : '';

    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_details['id']);

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    //Get Room
    $room = session()->get('room_no');
    $floor = session()->get('floor');
    $table = session()->get('table');

    $table_enable_status = (isset($shop_settings['table_enable_status']) && !empty($shop_settings['table_enable_status'])) ? $shop_settings['table_enable_status'] : 0;
    $room_enable_status = (isset($shop_settings['room_enable_status']) && !empty($shop_settings['room_enable_status'])) ? $shop_settings['room_enable_status'] : 0;

    if($layout == 'layout_1'){
        // Shop Logo
        $shop_logo = (isset($shop_settings['logo_layout_1']) && !empty($shop_settings['logo_layout_1'])) ? $shop_settings['logo_layout_1'] : '';
    }elseif($layout == 'layout_2'){
        // Shop Logo
        $shop_logo = (isset($shop_settings['logo_layout_2']) && !empty($shop_settings['logo_layout_2'])) ? $shop_settings['logo_layout_2'] : '';
    }elseif($layout == 'layout_3'){
        // Shop Logo
        $shop_logo = (isset($shop_settings['logo_layout_3']) && !empty($shop_settings['logo_layout_3'])) ? $shop_settings['logo_layout_3'] : '';
    }else{
        $shop_logo = "";
    }

    $total_grading = App\Models\ShopRateServies::where('shop_id', $shop_details['id'])->where('status', 1)->count();

@endphp

@extends('shop.shop-layout')

@section('title', 'Checkout')

@section('header')
@if ($layout == 'layout_2')

    {{-- desktop view --}}
    <header class="header side_header">
        <div class="header_inr">
            <div class="header_top">
        @if(!empty($shop_start_time) && !empty($shop_end_time))
                <div class="open_time">
                    <h4>{{ $header_text_one }}</h4>
                    <span>{{ $shop_start_time }} to {{ $shop_end_time }}</span>
                </div>
        @endif
                <div class="shop_logo text-center">
                    <a href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                        @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                height="50px" />
                        @else
                            <img src="{{ $default_logo }}" height="50px">
                        @endif
                    </a>
                </div>
                @if($is_sub_title)
                <div class="happy_time text-center">
                    {!!  $header_text_two !!}
                </div>
                @endif
            </div>
            <div class="header_img">





            </div>
            <div class="header_inr_menu">
                <ul class="m-0 header_inr_menu_ul">

                    <li class="navlink shop_lang_box position-relative">
                        @if (count($additional_languages) > 0 || $google_translate == 1)
                        <a class="lang_bt"> <x-dynamic-component width="35px"
                            component="flag-language-{{ $language_details['code'] }}" /> </a>
                            @endif
                            <div class="lang_select">
                                <ul>
                                    @if (isset($primary_language_details) && !empty($primary_language_details))
                                        <li>
                                            <x-dynamic-component width="35px"
                                                component="flag-language-{{ $primary_language_details['code'] }}" />
                                            <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')"
                                                style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                        </li>
                                    @endif
                                    @foreach ($additional_languages as $language)
                                        @php
                                            $langCode = isset($language->language['code']) ? $language->language['code'] : '';
                                        @endphp
                                        <li>
                                            <x-dynamic-component width="35px"
                                                component="flag-language-{{ $langCode }}" />
                                            <a onclick="changeLanguage('{{ $langCode }}')"
                                                style="cursor: pointer;">{{ isset($language->language['name']) ? $language->language['name'] : '' }}</a>
                                        </li>
                                    @endforeach
                                    @if ($google_translate == 1)
                                    <li>
                                        <div class="form-group">
                                            <label class="me-2 text-dark">Auto Translate</label>
                                            <label class="switch me-2">
                                                <input type="checkbox" value="1"
                                                    name="auto_translate"  id="auto_translate_layout_two"
                                                    value="1">
                                                <span class="slider round">
                                                    <i class="fa-solid fa-circle-check check_icon"></i>
                                                    <i
                                                        class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-group mt-3"  id="translated_languages_layout_two"></div>
                                    </li>
                                    @endif
                                </ul>

                            </div>

                    </li>


                </ul>


            </div>
            <div class="header_bottom">
                {{-- <div class="social_media">
                <ul>
                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-foursquare"></i></a></li>
                    <li><a href="#"><i class="fa-solid fa-mask"></i></a></li>
                </ul>
            </div> --}}
                <div class="cart_notification">
                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                    @if ($table_enable_status == 1 || $room_enable_status == 1)                        
                        <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                            <i class="fa-solid fa-bell"></i>
                        </a>
                    @endif
                    @endif
                    <a @if($cart != []) href="{{ route('shop.cart', $shop_slug) }}" @endif class="text-white text-decoration-none">
                        <div class="cart_box">
                            <div class="cart_box_inr @if($cart != []) cart_active @endif">
                                <div class="cart_icon">
                                    <h4>{{ __('Cart') }}</h4>
                                    <i class="fa-solid fa-basket-shopping"></i>
                                </div>
                                <div class="cart_count">
                                    <span>{{ $total_quantity }}</span>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </header>

@elseif($layout == 'layout_3')
    <div class="layout3_header @if($stiky_header) header-sticky @endif">
        <header class="header">
            @if (isset($package_permissions['banner']) &&
                    !empty($package_permissions['banner']) &&
                    $package_permissions['banner'] == 1)
                @if (count($shop_banners) > 0)
                    <section class="banner home_main_slider" style="height: {{ $banner_height }}px;">
                    @if (count($shop_banners) == 1)
                                @if (($shop_banners[0]->display == 'both' || $shop_banners[0]->display == 'image') && (isset($shop_banners[0][$banner_key]) && !empty($shop_banners[0][$banner_key]) && file_exists('public/client_uploads/shops/' . $shop_slug . '/banners/' . $shop_banners[0][$banner_key])))
                                    <div class="single_img_banner h-100" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/banners/' . $shop_banners[0][$banner_key]) }}')">
                                @else
                                    <div class="single_img_banner h-100" style="background-color: {{ $shop_banners[0]->background_color }};">
                                @endif
                                @if ($shop_banners[0]->display == 'both' || $shop_banners[0]->display == 'description')
                                        @if (isset($shop_banners[0][$banner_text_key]) && !empty($shop_banners[0][$banner_text_key]))
                                                <div class="swiper-text p-3">
                                                        {!! $shop_banners[0][$banner_text_key] !!}
                                                </div>
                                        @endif
                                @endif
                                </div>
                                @else
                                <div class="swiper-container h-100 position-relative">
                            <div class="swiper-wrapper">
                                @foreach ($shop_banners as $key => $banner)

                                    @if (
                                        ($banner->display == 'both' || $banner->display == 'image') &&
                                            (isset($banner[$banner_key]) &&
                                                !empty($banner[$banner_key]) &&
                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/banners/' . $banner[$banner_key])))

                                        <div class="swiper-slide"
                                            style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/banners/' . $banner[$banner_key]) }}')">
                                        @else
                                            <div class="swiper-slide"
                                                style="background-color: {{ $banner->background_color }};">
                                    @endif
                                    @if ($banner->display == 'both' || $banner->display == 'description')
                                        @if (isset($banner[$banner_text_key]) && !empty($banner[$banner_text_key]))
                                            <div class="swiper-text">
                                                {!! $banner[$banner_text_key] !!}
                                            </div>
                                        @endif
                                    @endif
                            </div>
                @endforeach
    </div>
    @if ($slider_buttons == 1)
    <div class="swiper-button-next swiper-btn"><i class="fa-solid fa-angle-right"></i></div>
    <div class="swiper-button-prev swiper-btn"><i class="fa-solid fa-angle-left"></i></div>
    @endif
    </div>
    @endif
    <div class="back_service">
        <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" style="cursor: pointer;"><i
                class="fa-solid fa-arrow-left"></i></a>
    </div>

    </section>
@endif
@endif
<div class="header_inr">
    {{-- <div class="container"> --}}
    <div class="row justify-content-between">
        <div class="col-lg-4 col-md-6">
            <div class="header_left">
                <div class="shop_info_box">
                    <a href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                        <div class="shop_logo">
                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                    width="70">
                            @endif
                        </div>
                    </a>

                    <div class="shop_info">
                        <h3>{!! $shop_name !!}</h3>
                        @if($is_sub_title == 1)
                                         {!! $header_text_two !!}
                                    <!-- <span>{{ $shop_subtitle }}</span> -->
                                    @endif
                                    @if(!empty($shop_start_time) && !empty($shop_end_time))
                                        <label><b>{{ $header_text_one }}: </b>{{ $shop_start_time }} to {{ $shop_end_time }}</label>
                                    @endif

                    </div>

                </div>
                <div class="back_service">
                    <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" style="cursor: pointer;"><i
                            class="fa-solid fa-arrow-left"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="header_right">
                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                    <div class="barger_menu_main">
                        <div class="barger_menu_inner">
                            <div class="barger_menu_icon">
                                <i class="fa-solid fa-bars"></i>
                            </div>
                            <div class="barger_menu_list">
                                <ul>
                                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                                    @if ($table_enable_status == 1 || $room_enable_status == 1)                                        
                                        <li>
                                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                <i class="fa-solid fa-bell"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @endif
                                    @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
                                    <li>
                                        <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if (count($additional_languages) > 0 || $google_translate == 1)
                    <div class="shop_lang_box">
                        <a class="lang_bt">
                            <span>{{ $language_details['code'] }}</span>
                            <x-dynamic-component width="35px"
                                component="flag-language-{{ $language_details['code'] }}" />
                        </a>
                        <div class="lang_select">
                            <ul>
                                @if (isset($primary_language_details) && !empty($primary_language_details))
                                    <li>
                                        <x-dynamic-component width="35px"
                                            component="flag-language-{{ $primary_language_details['code'] }}" />
                                        <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')"
                                            style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                    </li>
                                @endif
                                @foreach ($additional_languages as $language)
                                    @php
                                        $langCode = isset($language->language['code']) ? $language->language['code'] : '';
                                    @endphp
                                    <li>
                                        <x-dynamic-component width="35px"
                                            component="flag-language-{{ $langCode }}" />
                                        <a onclick="changeLanguage('{{ $langCode }}')"
                                            style="cursor: pointer;">{{ isset($language->language['name']) ? $language->language['name'] : '' }}</a>
                                    </li>
                                @endforeach
                                @if ($google_translate == 1)
                                    <li>
                                        <div class="form-group">
                                            <label class="me-2">Auto Translate</label>
                                            <label class="switch me-2">
                                                <input type="checkbox" value="1" name="auto_translate"
                                                    id="auto_translate" value="1">
                                                <span class="slider round">
                                                    <i class="fa-solid fa-circle-check check_icon"></i>
                                                    <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-group mt-3" id="translated_languages"></div>
                                    </li>
                                @endif
                            </ul>

                        </div>
                    </div>
                @endif
                <!-- <div id="cart-box" class="cart-btn text-decoration-none position-relative">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <span class="qty-number">{{ $total_quantity }}</span>
                </div> -->
                <div id="current-order">
                    <div class="cart_title">
                        <h2>{{ __('Cart') }}</h2>
                        <h6 class="total"> Total: <span
                                class="">{{ Currency::currency($currency)->format($total_amount) }}</span></h6>
                    </div>
                    <div class="cart_item_detail">
                        @forelse ($cart as $cart_key => $cart_data)
                            @foreach ($cart_data as $cart_val)
                                @foreach ($cart_val as $cart_item_key => $cart_item)

                                    @php
                                        $categories_data = $cart_item['categories_data'];

                                        $item_dt = itemDetails($cart_item['item_id']);
                                        $item_discount = isset($item_dt['discount']) ? $item_dt['discount'] : 0;
                                        $item_discount_type = isset($item_dt['discount_type']) ? $item_dt['discount_type'] : 'percentage';
                                        $item_image = isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_dt['image']) ? asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');

                                        $item_name = isset($item_dt[$name_key]) ? $item_dt[$name_key] : '';

                                        $item_price_details = App\Models\ItemPrice::where('id', $cart_item['option_id'])->first();
                                        $item_price = isset($item_price_details['price']) ? Currency::currency($currency)->format($item_price_details['price']) : 0.0;
                                        $item_price_label = isset($item_price_details[$price_label_key]) ? $item_price_details[$price_label_key] : '';
                                    @endphp
                                    <div class="cart_item">
                                        <div class="cart_item_inr">
                                            <div class="cart_item_name">
                                                <span>{{ $cart_item['quantity'] }}</span>
                                                <h5>{{ $item_name }}</h5>
                                            </div>
                                            <div class="cart_item_action">
                                                 <a href="#" onclick="getCartDetails({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }}, {{ $shop_id }})"><i class="fa-solid fa-pencil"></i></a>
                                                <a href="#"
                                                    onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})"
                                                    class="text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
                                            </div>
                                        </div>
                                        <div class="item_attribute">
                                            <div class="item_attribute_inr">

                                                @if (count($categories_data) > 0)
                                                    @foreach ($categories_data as $option_id)
                                                        @php
                                                            $my_opt = $option_id;
                                                        @endphp
                                                        @if (is_array($my_opt))
                                                            @if (count($my_opt) > 0)
                                                                @foreach ($my_opt as $optid)
                                                                    @php
                                                                        $opt_price_dt = App\Models\OptionPrice::where('id', $optid)
                                                                            ->with('optionName')
                                                                            ->first();
                                                                        $opt_price_name = isset($opt_price_dt[$name_key]) ? $opt_price_dt[$name_key] : '';
                                                                        $opt_title_name = isset($opt_price_dt->optionName[$title_key]) ? $opt_price_dt->optionName[$title_key] : '';
                                                                        $opt_price = isset($opt_price_dt['price']) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.0;

                                                                    @endphp
                                                                    <h6><span><b>{{ $opt_title_name }} : </b></span>
                                                                        @if (!empty($opt_price_name))
                                                                            {{ $opt_price_name }}
                                                                        @endif
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            @php
                                                                $opt_price_dt = App\Models\OptionPrice::where('id', $my_opt)
                                                                    ->with('optionName')
                                                                    ->first();
                                                                $opt_price_name = isset($opt_price_dt[$name_key]) ? $opt_price_dt[$name_key] : '';
                                                                $opt_title_name = isset($opt_price_dt->optionName[$title_key]) ? $opt_price_dt->optionName[$title_key] : '';
                                                                $opt_price = isset($opt_price_dt['price']) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.0;
                                                            @endphp
                                                            <h6><span><b>{{ $opt_title_name }} : </b></span>
                                                                @if (!empty($opt_price_name))
                                                                    {{ $opt_price_name }}
                                                                @endif
                                                            </h6>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <label id="total_amount_{{ $cart_item['item_id'] }}">{{ $cart_item['total_amount_text'] }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @empty
                            <h4 class="text-center">Cart is Empty</h4>
                        @endforelse

                    </div>
                    <div class="cart_order_btn">
                        {{-- <button class="btn orderup_button">Complete Order</button> --}}
                        <a href="{{ route('shop.cart', $shop_slug) }}" class="btn orderup_button">Complete Order</a>
                    </div>
                </div>
                {{-- <a href="{{ route('shop.cart', $shop_slug) }}"
                class="cart-btn  text-white text-decoration-none position-relative">
                <i class="fa-solid fa-basket-shopping"></i>
                <span class="qty-number">{{ $total_quantity }}</span>
            </a> --}}
            </div>
        </div>
    </div>
    {{-- </div> --}}
</div>
</header>
</div>

@endif
@endsection

@section('content')

    {{-- Delivery Message Modal --}}
    <div class="modal fade delivery_modal" id="deliveyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deliveyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                <div class="modal-body">
                    <div class="delivery_message_box">
                        <div class="delivery-message" style="display: none;">
                                {!! $delivery_message !!}
                        </div>
                        <div class="street_no mt-2" style="display: none;">
                            <input type="text" name="street_no" id="street_no" class="form-control" placeholder="{{ __('Street Number') }}">
                            <a class="btn btn-success street-btn"><i class="fa-solid fa-check"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Distance Message Modal --}}
    <div class="modal fade delivery_modal" id="distanceMessageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="distanceMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                    <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                <div class="modal-body text-center">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="def_currency" id="def_currency" value="{{ $currency }}">


    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <form action="{{ route('shop.cart.processing',$shop_slug) }}" id="checkoutForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="checkout_type" id="checkout_type" value="{{ $checkout_type }}">
                        <div class="card checkout_box">
                            <div class="card-header position-relative">
                                <h2>{{ __('Checkout') }}</h2>
                                <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" class="text-dark cart-back-btn" style="cursor: pointer;"><i
                                    class="fa-solid fa-arrow-left-long"></i></a>
                                <a href="{{ url()->previous() }}" class="checkout_close_btn"><i class="fa-solid fa-xmark"></i></a>
                            </div>

                            <div class="card-body">
                                <div class="checkout_box_info">
                                    <div class="row">

                                        @if($checkout_type == 'takeaway')
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}" placeholder="{{ __('First Name') }}">
                                                @if($errors->has('firstname'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('firstname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}" placeholder="{{ __('Last Name') }}">
                                                @if($errors->has('lastname'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('lastname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="email" id="email" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('Email') }}">
                                                @if($errors->has('email'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="phone" id="phone" class="form-control {{ ($errors->has('phone')) ? 'is-invalid' : '' }}" value="{{ old('phone') }}" inputmode="numeric" placeholder="{{ __('Mobile No.') }}">
                                                @if($errors->has('phone'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('phone') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($checkout_type == 'table_service')
                                            <input type="hidden" name="table" id="table" value="{{ $table }}">                                           
                                        @elseif($checkout_type == 'room_delivery')
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}" placeholder="{{ __('First Name') }}">
                                                @if($errors->has('firstname'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('firstname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}" placeholder="{{ __('Last Name') }}">
                                                @if($errors->has('lastname'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('lastname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="hidden" name="room" id="room" value="{{ $room }}">
                                            <input type="hidden" name="floor" id="floor" value="{{ $floor }}">

                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="delivery_time" id="delivery_time" class="form-control" value="{{ old('delivery_time') }}" placeholder="{{ __('Delivery Time') }}">
                                                <code>Ex:- 9:30-10:00</code>
                                            </div>
                                        @elseif ($checkout_type == 'delivery')
                                            <input type="hidden" name="street_number" id="street_number" class="form-control " value="{{ $cust_street }}">

                                            <div class="col-md-12 mb-3">
                                                <input type="hidden" name="latitude" id="latitude" value="{{ $cust_lat }}">
                                                <input type="hidden" name="longitude" id="longitude" value="{{ $cust_lng }}">
                                                <input type="text" name="address" id="address" class="form-control {{ ($errors->has('address')) ? 'is-invalid' : '' }}" value="{{ $cust_address }}" placeholder="{{ __('Your Address') }}">
                                                @if($errors->has('address'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('address') }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="firstname" id="firstname" class="form-control {{ ($errors->has('firstname')) ? 'is-invalid' : '' }}" value="{{ old('firstname') }}" placeholder="{{ __('First Name') }}">
                                                @if($errors->has('firstname'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('firstname') }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="lastname" id="lastname" class="form-control {{ ($errors->has('lastname')) ? 'is-invalid' : '' }}" value="{{ old('lastname') }}" placeholder="{{ __('Last Name') }}">
                                                @if($errors->has('lastname'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('lastname') }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="email" id="email" class="form-control {{ ($errors->has('email')) ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ __('Email') }}">
                                                @if($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="phone" id="phone" class="form-control {{ ($errors->has('phone')) ? 'is-invalid' : '' }}" value="{{ old('phone') }}" inputmode="numeric" placeholder="{{ __('Mobile No.') }}">
                                                @if($errors->has('phone'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('phone') }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                    <div class="d-flex justify-content-between aling-items-center">
                                                        <label for="">{{ __('Map')}}</label>
                                                        <a class="btn btn-success btn-sm fa-solid fa-eye" id="togglemap"></a>
                                                    </div>
                                                    <div id="map" class="closemap"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="floor" id="floor" class="form-control" value="{{ old('floor') }}" placeholder="{{ __('Floor') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" name="door_bell" id="door_bell" class="form-control" value="{{ old('door_bell') }}" placeholder="{{ __('Door Bell') }}">
                                            </div>
                                        @endif
                                        <div class="col-md-6 mb-3">
                                            <select name="payment_method" id="payment_method" class="form-select">
                                                <option value="">{{ __('Payment Method') }}</option>
                                                @if(isset($payment_settings['cash']) && $payment_settings['cash'] == 1)
                                                    <option value="cash" {{ (old('payment_method') == 'cash') ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                                @endif
                                                @if(isset($payment_settings['cash_pos']) && $payment_settings['cash_pos'] == 1)
                                                    <option value="cash_pos" {{ (old('payment_method') == 'cash_pos') ? 'selected' : '' }}>{{ __('Cash POS') }}</option>
                                                @endif
                                                @if(isset($payment_settings['paypal']) && $payment_settings['paypal'] == 1)
                                                    <option value="paypal" {{ (old('payment_method') == 'paypal') ? 'selected' : '' }}>{{ __('PayPal') }}</option>
                                                    @endif
                                                @if(isset($payment_settings['every_pay']) && $payment_settings['every_pay'] == 1)
                                                    <option value="every_pay" {{ (old('payment_method') == 'every_pay') ? 'selected' : '' }}>{{ __('Credit/Debit Card') }}</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3 tip-div" style="display: none;">
                                            <input type="text" id="tip" name="tip" value="" class="form-control" placeholder="{{ __('Tip') }}">
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
                                                                        <div class="col-md-3 text-center text-dark">
                                                                            <span class="me-2">{{ $cart_item['quantity'] }} <span> x </span></span>
                                                                            <img src="{{ $item_image }}" width="40" height="40" class="rounded-circle">
                                                                        </div>
                                                                        <div class="col-md-6 text-center text-dark">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <b>{{ $item_name }}</b>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mt-1">
                                                                                <div class="col-md-12">
                                                                                    <p class="m-0">
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
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-md-3 text-center text-dark">
                                                                            <b>{{ __('Sub Total') }} : </b><span>{{ $cart_item['total_amount_text'] }}</span>
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
                                </div>
                                <div class="checkout_box_footer">
                                    <div class="row p-3">
                                        <div class="col-md-5 bg-light p-3">
                                            <table class="table">
                                                <tr>
                                                    <td><b>{{ __('Total Amount') }}</b></td>
                                                    <td class="text-end">{{ Currency::currency($currency)->format($total_amount) }}</td>
                                                </tr>

                                                @if ($discount_per > 0 || $coupon_value > 0)
                                                    @if ($discount_per > 0)
                                                        <tr>
                                                            <td><b>{{ __('Discount') }}</b></td>
                                                            @php
                                                                // Calculate total discount based on discount type
                                                                if ($discount_type == 'fixed') {
                                                                    $total_discount += $discount_per;
                                                                } else {
                                                                    $total_discount += ($total_amount * $discount_per) / 100;
                                                                }
                                                            @endphp
                                                            @if ($discount_type == 'fixed')
                                                                <td class="text-end">-
                                                                    {{ Currency::currency($currency)->format($discount_per) }}</td>
                                                            @else
                                                                <td class="text-end">- {{ $discount_per }}%</td>
                                                            @endif
                                                            {{-- <td class="text-end">- {{ Currency::currency($currency)->format($total_discount) }}</td> --}}
                                                        </tr>
                                                    @endif

                                                    @if ($coupon_value > 0)
                                                        <tr>
                                                            @php
                                                                $coupon_discount = 0;

                                                                // Calculate coupon discount based on coupon type
                                                                if ($coupon_type == 'fixed') {
                                                                    $coupon_discount += $coupon_value;
                                                                } else {
                                                                    $coupon_discount += ($total_amount * $coupon_value) / 100;
                                                                }
                                                                @endphp
                                                            @if ($coupon_type == 'fixed')
                                                            <td><b>{{ __('Coupon Discount') }}</b></td>
                                                            <td class="text-end">-
                                                                {{ Currency::currency($currency)->format($coupon_value) }} <a
                                                                id="removeCoupon" class="btn text-danger p-0"><i
                                                                class="fa-solid fa-circle-xmark"></i></a></td>
                                                                @else
                                                                <td><b>{{ __('Coupon Discount') }}</b>(- {{ $coupon_value }}%)</td>
                                                                <td class="text-end"> {{ Currency::currency($currency)->format($coupon_discount) }} <a
                                                                    id="removeCoupon" class="btn text-danger p-0"><i
                                                                        class="fa-solid fa-circle-xmark"></i></a></td>
                                                            @endif
                                                        </tr>
                                                    @endif

                                                    <tr class="text-end">
                                                        @php
                                                            $final_amount = $total_amount - ($total_discount + $coupon_discount);
                                                        @endphp
                                                        <td colspan="2">
                                                            <strong>{{ Currency::currency($currency)->format($final_amount) }}</strong>
                                                        </td>
                                                    </tr>
                                                    @endif
                                            </table>
                                        </div>
                                    </div>
                                        @if($final_amount)
                                            <input type="hidden" name="t_amount" id="t_amount" value="{{ $final_amount }}">
                                        @else
                                            <input type="hidden" name="t_amount" id="t_amount" value="{{ $total_amount }}">
                                        @endif
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-success con_btn">{{ __('Send Order') }}</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </form>
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

    @if ($layout == 'layout_1')
        <a class="back_bt" onclick="homePage('{{ $shop_details['shop_slug'] }}')" style="cursor: pointer;"><i class="fa-solid fa-chevron-left"></i></a>
    @endif

@endsection

{{-- Page JS Function --}}
@section('page-js')

    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ $google_map_api }}&libraries=places"></script>

    <script type="text/javascript">

        var layout = "{{ $layout }}";


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

            // Allow only Number
            $('#phone').on('input', function(e) {
                var inputValue = $(this).val().replace(/[^0-9]/g, '');
                $(this).val(inputValue);
            });

        });

        $('#removeCoupon').click(function(event) {
                $.ajax({
                    url: "{{ route('remove.coupon') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if(response.success == 1){
                            toastr.success(response.message);
                            setTimeout(() => {
                            location.reload();
                            }, 1000);
                        }else{
                            toastr.error(response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
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
            var curr_symbol = @json(Currency::currency($currency));

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

        $(document).ready(function() {
            var slider_effect = "{{ $effect }}";
            var BannerSpeed = {{ $slider_delay_time }};
            var swiper = new Swiper(".home_main_slider .swiper-container", {
	            slidesPerView: 1,
	            effect: slider_effect,
                navigation: {
                    nextEl: ".home_main_slider .swiper-button-next",
                    prevEl: ".home_main_slider .swiper-button-prev"
                    },
                loop: true,
                autoplay: {
                    delay: BannerSpeed,
                    disableOnInteraction: false
                },
	            speed: 2000
            });

        });



        @if ($layout == 'layout_2')
            $(window).resize(function() {
                if ($(window).width() < 991) {
                    $('.header').hide();
                } else {
                    $('.header').show();
                }
            });
        @endif

        // Attach change event listener to the room select input


                    function updateCart(itemID, priceID, item_key,action) {

                    var currency = @json($currency);
                    var quantityElement = $('#quantity_' + itemID);
                    var currentQuantity = parseInt(quantityElement.text());
                    var newQuantity = action == 'increment' ? currentQuantity + 1 : currentQuantity - 1;

                    $.ajax({
                        type: "POST",
                        url: "{{ route('shop.update.cart') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'quantity': newQuantity,
                            'item_id': itemID,
                            'currency': currency,
                            'price_id': priceID,
                            'item_key': item_key,

                        },
                        dataType: "JSON",
                        success: function(response) {
                            if (response.success == 1) {

                                $('#quantity_' + response.item_id).text(response.quantity);

                                $('#total_amount_' + response.item_id).text(response.total_amount_text);
                                $('#total_amount').text(response.final_amount_text);
                                $('.qty-number').text(response.total_quantity);
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                                // setTimeout(() => {
                                //     location.reload();
                                // }, 1000);
                            }
                        }
                    });

                    }

            // Get the map and toggle button elements
            var map = document.getElementById("map");
            var toggleButton = document.getElementById("togglemap");

            // Function to toggle the class of the map
            function toggleMapClass() {
                map.classList.toggle("closemap");
                map.classList.toggle("openmap");
                toggleButton.classList.toggle("fa-eye");
                toggleButton.classList.toggle("fa-eye-slash");
            }

            // Add click event listener to the toggle button
            toggleButton.addEventListener("click", toggleMapClass);



            $(function() {
            var windowWidth = $(window).width();
            if(windowWidth < 991){
                $('.footer').hide();
                $('.main').addClass("shop_cart");
                $('.header').hide();
                $('.header_preview').hide();
            } else {
                $('.footer').show();
                $('.main').removeClass("shop_cart");
                $('.header').show();
                $('.header_preview').show();

            }
        });
    </script>

@endsection
