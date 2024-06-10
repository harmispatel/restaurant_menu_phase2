@php
    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);

    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');

    // Default Image
    $default_image = asset('public/client_images/not-found/no_image_1.jpg');

    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Shop Currency
    $currency = isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency']) ? $shop_settings['default_currency'] : 'EUR';

    // Name Key
    $name_key = $current_lang_code . '_name';

    // Label Key
    $label_key = $current_lang_code . '_label';

    // Price key
    $price_label_key = $current_lang_code . '_label';
    $title_key = $current_lang_code . '_title';
    $calories_key = $current_lang_code . '_calories';
    $description_key = $current_lang_code . '_description';

    // Shop Title
    $shop_subtitle = isset($shop_settings['business_subtitle']) && !empty($shop_settings['business_subtitle']) ? $shop_settings['business_subtitle'] : '';

    $waiter_call_status = isset($shop_settings['waiter_call_status']) ? $shop_settings['waiter_call_status'] : '0';

    // Total Amount
    $total_amount = 0;

    // Order Settings
    $order_settings = getOrderSettings($shop_details['id']);

    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'], 'homepage_intro');
    $homepage_intro = isset($homepage_intro[$current_lang_code . '_value']) && !empty($homepage_intro[$current_lang_code . '_value']) ? $homepage_intro[$current_lang_code . '_value'] : '';

    $min_amount_for_delivery = isset($order_settings['min_amount_for_delivery']) && !empty($order_settings['min_amount_for_delivery']) ? unserialize($order_settings['min_amount_for_delivery']) : [];

    $remain_amount = 0;

    $is_checkout = (isset($order_settings['delivery']) && $order_settings['delivery'] == 1) || (isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1) || (isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1) || (isset($order_settings['table_service']) && $order_settings['table_service'] == 1) ? 1 : 0;

    if (isset($order_settings['only_cart']) && $order_settings['only_cart'] == 1) {
        $is_checkout = 0;
    }

    $discount_per = session()->get('discount_per');
    $discount_type = session()->get('discount_type');

    $coupon_value = session()->get('coupon_value');
    $coupon_type = session()->get('coupon_type');
    $coupon_discount = 0;
    $total_discount = 0;

    $delivery_schedule = checkDeliverySchedule($shop_details['id']);

    $current_check_type = session()->get('checkout_type');

    $current_room_no = session()->get('room_no');

    $total_cart_qty = getCartQuantity();

    $car_cart_amount = 0;

    // Home Page Intro
    $service_closed_message = moreTranslations($shop_details['id'], 'service_closed_message');
    $service_closed_message = isset($service_closed_message[$current_lang_code . '_value']) && !empty($service_closed_message[$current_lang_code . '_value']) ? $service_closed_message[$current_lang_code . '_value'] : "Sorry you can't order! The store is closed during these hours.";

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);
    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';
    $effect = isset($theme_settings['slider_effect']) ? $theme_settings['slider_effect'] : 'fabe';
    $special_day_effect_box = isset($theme_settings['special_day_effect_box']) && !empty($theme_settings['special_day_effect_box']) ? $theme_settings['special_day_effect_box'] : 'blink';

    $shop_id = isset($shop_details['id']) ? $shop_details['id'] : '';

    // Get Language Settings
    $language_settings = clientLanguageSettings($shop_id);

    $google_translate = isset($language_settings['google_translate']) ? $language_settings['google_translate'] : 0;

    // Cart Quantity
    $total_quantity = getCartQuantity();

    $cart_total_amount = 0;

    // Shop Name
    $shop_name = isset($shop_details['name']) && !empty($shop_details['name']) ? $shop_details['name'] : '';

    $cat_name = isset($cat_details[$name_key]) ? $cat_details[$name_key] : '';
    $shop_title = "$shop_name | $cat_name";

    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_details['id']);

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    // Get Banner Settings
    $shop_banners = getBanners($shop_details['id']);
    $shop_banner_count = count($shop_banners) == 1 ? 'false' : 'true';
    $banner_key = $language_details['code'] . '_image';
    $banner_text_key = $language_details['code'] . '_description';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);
    $slider_buttons = isset($theme_settings['banner_slide_button']) && !empty($theme_settings['banner_slide_button']) ? $theme_settings['banner_slide_button'] : 0;
    $slider_delay_time = isset($theme_settings['banner_delay_time']) && !empty($theme_settings['banner_delay_time']) ? $theme_settings['banner_delay_time'] : 3000;
    $banner_height = isset($theme_settings['banner_height']) && !empty($theme_settings['banner_height']) ? $theme_settings['banner_height'] : 250;
    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';
    $logo_position = isset($theme_settings['logo_position']) ? $theme_settings['logo_position'] : '';
    $search_box_position = isset($theme_settings['search_box_position']) ? $theme_settings['search_box_position'] : '';
    $stiky_header = isset($theme_settings['sticky_header']) && !empty($theme_settings['sticky_header']) ? $theme_settings['sticky_header'] : '';

    // Shop Time
    $shop_start_time = isset($shop_settings['shop_start_time']) && !empty($shop_settings['shop_start_time']) ? $shop_settings['shop_start_time'] : '';
    $shop_end_time = isset($shop_settings['shop_end_time']) && !empty($shop_settings['shop_end_time']) ? $shop_settings['shop_end_time'] : '';
    $happy_start_time = isset($shop_settings['happy_start_time']) && !empty($shop_settings['happy_start_time']) ? $shop_settings['happy_start_time'] : '';
    $happy_end_time = isset($shop_settings['happy_end_time']) && !empty($shop_settings['happy_end_time']) ? $shop_settings['happy_end_time'] : '';

    // header image
    $header_img = (isset($theme_settings['header_image']) && !empty($theme_settings['header_image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image'])) ? asset('public/client_uploads/shops/' . $shop_slug . '/header_image/' . $theme_settings['header_image']) : asset('public/client/assets/images2/allo_spritz.jpg');

    // Header Text One
    $header_text_one = moreTranslations($shop_details['id'], 'header_text_1');
    $header_text_one = isset($header_text_one[$current_lang_code . '_value']) && !empty($header_text_one[$current_lang_code . '_value']) ? $header_text_one[$current_lang_code . '_value'] : '';

    // Header Text Two
    $header_text_two = moreTranslations($shop_details['id'], 'header_text_2');
    $header_text_two = isset($header_text_two[$current_lang_code . '_value']) && !empty($header_text_two[$current_lang_code . '_value']) ? $header_text_two[$current_lang_code . '_value'] : '';

    $is_sub_title = isset($shop_settings['is_sub_title']) ? $shop_settings['is_sub_title'] : '0';

    // Item id
    $itemIds = [];

    // Read More Label
    $read_more_label = moreTranslations($shop_details['id'], 'read_more_link_label');
    $read_more_label = isset($read_more_label[$current_lang_code . '_value']) && !empty($read_more_label[$current_lang_code . '_value']) ? $read_more_label[$current_lang_code . '_value'] : 'Read More';

    // Room Number
    $rooms = getShopRooms($shop_details['id']);

    // Table Number
    $tables = getShopTables($shop_details['id']);


    $coupon = App\Models\ShopCoupon::where('shop_id',$shop_details['id'])->get();

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
@endphp

@extends('shop.shop-layout')
@section('title', 'Cart')
@section('header')
    @if ($layout == 'layout_2')
        {{-- desktop view --}}
        <header class="header side_header head-2">
            <div class="header_inr">
                <div class="header_top">
                    @if (!empty($shop_start_time) && !empty($shop_end_time))
                        <div class="open_time">
                            <h4>{{ $header_text_one }}</h4>
                            <span>{{ $shop_start_time }} to {{ $shop_end_time }}</span>
                        </div>
                    @endif
                    <div class="shop_logo text-center">
                        <a href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}" height="50px" />
                            @else
                                <img src="{{ $default_logo }}" height="50px">
                            @endif
                        </a>
                    </div>
                    @if ($is_sub_title == 1)
                        <div class="happy_time text-center">
                            {!! $header_text_two !!}
                        </div>
                    @endif
                </div>
                <div class="header_img">
                    <div class="header_inr_menu">
                        <ul class="m-0 header_inr_menu_ul">
                            <li class="navlink shop_lang_box position-relative">
                                @if (count($additional_languages) > 0 || $google_translate == 1)
                                    <a class="lang_bt"> <x-dynamic-component width="35px" component="flag-language-{{ $language_details['code'] }}" /> </a>
                                @endif
                                <div class="lang_select">
                                    <ul>
                                        @if (isset($primary_language_details) && !empty($primary_language_details))
                                            <li>
                                                <x-dynamic-component width="35px" component="flag-language-{{ $primary_language_details['code'] }}" />
                                                <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')" style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                            </li>
                                        @endif
                                        @foreach ($additional_languages as $language)
                                            @php
                                                $langCode = isset($language->language['code']) ? $language->language['code'] : '';
                                            @endphp
                                            <li>
                                                <x-dynamic-component width="35px" component="flag-language-{{ $langCode }}" />
                                                <a onclick="changeLanguage('{{ $langCode }}')" style="cursor: pointer;">{{ isset($language->language['name']) ? $language->language['name'] : '' }}</a>
                                            </li>
                                        @endforeach
                                        @if ($google_translate == 1)
                                            <li>
                                                <div class="form-group">
                                                    <label class="me-2 text-dark">Auto Translate</label>
                                                    <label class="switch me-2">
                                                        <input type="checkbox" value="1" name="auto_translate" id="auto_translate_layout_two" value="1">
                                                        <span class="slider round">
                                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                                            <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-group mt-3" id="translated_languages_layout_two"></div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div id="current-order">
                                    <div class="cart_title">
                                        <h2>{{ __('Cart') }}</h2>
                                        <h6 class="total"> Total: <span id="total_amount">{{ Currency::currency($currency)->format($total_amount) }}</span></h6>
                                    </div>
                                    <div class="cart_item_detail">
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
                                                        $item_price_label = (isset($item_price_details[$price_label_key])) ? $item_price_details[$price_label_key] : '';
                                                    @endphp
                                                    <div class="cart_item">
                                                        <div class="cart_item_inr">
                                                            <div class="cart_item_name">
                                                                <h5>{{ $item_name }}</h5>
                                                            </div>
                                                            <div class="cart_item_action">
                                                                <a href="#" onclick="getCartDetails({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }}, {{ $shop_id }})"><i class="fa-solid fa-pencil"></i></a>
                                                                <a href="#" onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})" class="text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="item_attribute">
                                                            <div class="item_attribute_inr">
                                                                @if(count($categories_data) > 0)
                                                                    @foreach ($categories_data as $option_id)
                                                                        @php
                                                                            $my_opt = $option_id;
                                                                        @endphp

                                                                        @if(is_array($my_opt))
                                                                            @if(count($my_opt) > 0)
                                                                                @php
                                                                                    $options = [];
                                                                                @endphp

                                                                                @foreach ($my_opt as $optid)
                                                                                    @php
                                                                                        $opt_price_dt = App\Models\OptionPrice::where('id', $optid)->with('optionName')->first();
                                                                                        $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                        $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                        $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                                        $option_id = $opt_price_dt->option_id;
                                                                                        
                                                                                        if (!isset($options[$option_id])) {
                                                                                            $options[$option_id] = [];
                                                                                        }

                                                                                        if (!empty($opt_title_name)) {
                                                                                            $options[$option_id][] = $opt_price_name;
                                                                                        }
                                                                                    @endphp
                                                                                @endforeach

                                                                                @foreach ($options as $option)
                                                                                    <h6><span><b>{{ $opt_title_name }}: </b></span>{{ implode(', ', $option) }}</h6>
                                                                                @endforeach
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $opt_price_dt = App\Models\OptionPrice::where('id',$my_opt)->with('optionName')->first();
                                                                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                            @endphp
                                                                            <h6>
                                                                                <span><b>{{ $opt_title_name }} : </b></span>
                                                                                @if(!empty($opt_price_name))
                                                                                    {{ $opt_price_name }}
                                                                                @endif
                                                                            </h6>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="cart_item_qty_price">
                                                            <div class="cart_item_qty">
                                                                <button class="btn-number" onclick="updateCart({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'decrement')"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                                    <span id="quantity_{{ $cart_item['item_id'] }}">{{ $cart_item['quantity'] }}</span>
                                                                <button class="btn-number" onclick="updateCart({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'increment')"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                            </div>
                                                            <label>{{ $cart_item['total_amount_text'] }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @empty
                                            <h4 class="text-center">Cart is Empty</h4>
                                        @endforelse
                                    </div>
                                    <div class="cart_order_btn">
                                        <a href="{{ route('shop.cart', $shop_slug) }}" class="btn orderup_button">Complete Order</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="header_bottom">
                    <div class="cart_notification">
                        @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                            @if ($table_enable_status == 1 || $room_enable_status == 1)                            
                                <a class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
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
        <div class="layout3_header @if ($stiky_header) header-sticky @endif">
            <header class="header head-3">
                @if (isset($package_permissions['banner']) && !empty($package_permissions['banner']) && $package_permissions['banner'] == 1)
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
                                            @if (($banner->display == 'both' || $banner->display == 'image') && (isset($banner[$banner_key]) && !empty($banner[$banner_key]) && file_exists('public/client_uploads/shops/' . $shop_slug . '/banners/' . $banner[$banner_key])))
                                                <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/banners/' . $banner[$banner_key]) }}')">
                                            @else
                                                <div class="swiper-slide" style="background-color: {{ $banner->background_color }};">
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
                                <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" style="cursor: pointer;"><i class="fa-solid fa-arrow-left"></i></a>
                            </div>
                        </section>
                    @endif
                @endif
                <div class="header_inr">
                    <div class="row justify-content-between">
                        <div class="col-lg-4 col-md-6">
                            <div class="header_left">
                                <div class="shop_info_box">
                                    <a href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                                        <div class="shop_logo">
                                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}" width="70">
                                            @endif
                                        </div>
                                    </a>
                                    <div class="shop_info">
                                        <h3>{!! $shop_name !!}</h3>
                                        @if ($is_sub_title == 1)
                                            {!! $header_text_two !!}
                                        @endif
                                        @if (!empty($shop_start_time) && !empty($shop_end_time))
                                            <label><b>{{ $header_text_one }}: </b>{{ $shop_start_time }} to {{ $shop_end_time }}</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="back_service">
                                    <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" style="cursor: pointer;"><i class="fa-solid fa-arrow-left"></i></a>
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
                                                                <a class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                                    <i class="fa-solid fa-bell"></i>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                                        <li>
                                                            <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star"></i></a>
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
                                            <x-dynamic-component width="35px" component="flag-language-{{ $language_details['code'] }}" />
                                        </a>
                                        <div class="lang_select">
                                            <ul>
                                                @if (isset($primary_language_details) && !empty($primary_language_details))
                                                    <li>
                                                        <x-dynamic-component width="35px" component="flag-language-{{ $primary_language_details['code'] }}" />
                                                        <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')" style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                                    </li>
                                                @endif
                                                @foreach ($additional_languages as $language)
                                                    @php
                                                        $langCode = isset($language->language['code']) ? $language->language['code'] : '';
                                                    @endphp
                                                    <li>
                                                        <x-dynamic-component width="35px" component="flag-language-{{ $langCode }}" />
                                                        <a onclick="changeLanguage('{{ $langCode }}')" style="cursor: pointer;">{{ isset($language->language['name']) ? $language->language['name'] : '' }}</a>
                                                    </li>
                                                @endforeach
                                                @if ($google_translate == 1)
                                                    <li>
                                                        <div class="form-group">
                                                            <label class="me-2">Auto Translate</label>
                                                            <label class="switch me-2">
                                                                <input type="checkbox" value="1" name="auto_translate" id="auto_translate" value="1">
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
                                <div id="current-order">
                                    <div class="cart_title">
                                        <h2>{{ __('Cart') }}</h2>
                                        <h6 class="total"> Total: <span id="total_amount">{{ Currency::currency($currency)->format($total_amount) }}</span></h6>
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
                                                        $item_image = isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                                                        $item_name = isset($item_dt[$name_key]) ? $item_dt[$name_key] : '';
                                                        $item_price_details = App\Models\ItemPrice::where('id', $cart_item['option_id'])->first();
                                                        $item_price = isset($item_price_details['price']) ? Currency::currency($currency)->format($item_price_details['price']) : 0.0;
                                                        $item_price_label = isset($item_price_details[$price_label_key]) ? $item_price_details[$price_label_key] : '';
                                                    @endphp
                                                    <div class="cart_item">
                                                        <div class="cart_item_inr">
                                                            <div class="cart_item_name">
                                                                <h5>{{ $item_name }}</h5>
                                                            </div>
                                                            <div class="cart_item_action">
                                                                <a href="#" onclick="getCartDetails({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }}, {{ $shop_id }})"><i class="fa-solid fa-pencil"></i></a>
                                                                <a href="#" onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})" class="text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="item_attribute">
                                                            <div class="item_attribute_inr">
                                                                @if(count($categories_data) > 0)
                                                                    @foreach ($categories_data as $option_id)
                                                                        @php
                                                                            $my_opt = $option_id;
                                                                        @endphp
                                                                        @if(is_array($my_opt))
                                                                            @if(count($my_opt) > 0)
                                                                                @php
                                                                                    $options = [];
                                                                                @endphp
                                                                                @foreach ($my_opt as $optid)
                                                                                    @php
                                                                                        $opt_price_dt = App\Models\OptionPrice::where('id', $optid)->with('optionName')->first();
                                                                                        $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                        $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                        $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                                        $option_id = $opt_price_dt->option_id;

                                                                                        // Initialize an empty array for the option_id if it doesn't exist yet
                                                                                        if (!isset($options[$option_id])) {
                                                                                            $options[$option_id] = [];
                                                                                        }

                                                                                        // Add the name to the array for the option_id
                                                                                        if (!empty($opt_title_name)) {
                                                                                            $options[$option_id][] = $opt_price_name;
                                                                                        }
                                                                                    @endphp
                                                                                @endforeach
                                                                                @foreach ($options as $option)
                                                                                    <h6><span><b>{{ $opt_title_name }}: </b></span>{{ implode(', ', $option) }}</h6>
                                                                                @endforeach
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $opt_price_dt = App\Models\OptionPrice::where('id',$my_opt)->with('optionName')->first();
                                                                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                            @endphp
                                                                            <h6>
                                                                                <span><b>{{ $opt_title_name }} : </b></span>
                                                                                @if(!empty($opt_price_name))
                                                                                    {{ $opt_price_name }}
                                                                                @endif
                                                                            </h6>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="cart_item_qty_price">
                                                            <div class="cart_item_qty">
                                                                <button class="btn-number" onclick="updateCart({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'decrement')"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                                    <span id="quantity_{{ $cart_item['item_id'] }}">{{ $cart_item['quantity'] }}</span>
                                                                <button class="btn-number" onclick="updateCart({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'increment')"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                            </div>
                                                            <label>{{ $cart_item['total_amount_text'] }}</label>
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
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </div>
    @endif
@endsection

@section('content')

    {{-- Layout 1 and 2 --}}
    @if ($layout == 'layout_1' || $layout == 'layout_2')
        <input type="hidden" name="def_currency" id="def_currency" value="{{ $currency }}">

        {{-- Section --}}
        <section class="py-5">
            <div class="cart_detail">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="cart_info">
                                <div class="cart_title">
                                    <h2>{{ __('Order Options') }}</h2>
                                    <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" class="text-dark cart-back-btn" style="cursor: pointer;"><i class="fa-solid fa-arrow-left-long"></i></a>
                                </div>
                                <div class="cart_info_inr">
                                    <div class="checkout_type">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    @if ($is_checkout == 1)
                                                        <select name="checkout_type" id="checkout_type" class="form-select w-100" onchange="checkoutType(this.value)">
                                                            <option value="0"> {{ __('Select Delivery Method') }}</option>
                                                            @if (isset($order_settings['delivery']) && $order_settings['delivery'] == 1)
                                                                <option value="delivery" {{ $current_check_type == 'delivery' ? 'selected' : '' }}>{{ __('Delivery') }}</option>
                                                            @endif
                                                            @if (isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1)
                                                                <option value="takeaway" {{ $current_check_type == 'takeaway' ? 'selected' : '' }}>{{ __('Takeaway') }}</option>
                                                            @endif
                                                            @if (isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1)
                                                                <option value="room_delivery" {{ $current_check_type == 'room_delivery' ? 'selected' : '' }}>{{ __('Room Delivery') }}</option>
                                                            @endif
                                                            @if (isset($order_settings['table_service']) && $order_settings['table_service'] == 1)
                                                                <option value="table_service" {{ $current_check_type == 'table_service' ? 'selected' : '' }}>{{ __('Table Service') }}</option>
                                                            @endif
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="room_service d-none">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <select name="room" id="room" class="form-select" onchange="getRoom(this.value)">
                                                        <option value="">{{ __('Choose Your Room') }}</option>
                                                        @foreach ($rooms as $room)
                                                            <option value="{{ $room->room_no }}" {{ $current_room_no == $room->room_no ? 'selected' : '' }}>{{ $room->room_no }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table_service d-none">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <select name="table_no" id="table_no" class="form-select">
                                                        <option value="">{{ __('Choose Your Table') }}</option>
                                                        @foreach ($tables as $table)
                                                            <option value="{{ $table->id }}">{{ $table->table_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart_item_info_inr">
                                        @forelse ($cart as $cart_key => $cart_data)
                                            @foreach ($cart_data as $cart_val)
                                                @foreach ($cart_val as $cart_item_key => $cart_item)
                                                    @php
                                                        $itemIds[] = $cart_item['item_id'];
                                                        $categories_data = $cart_item['categories_data'];
                                                        $item_dt = itemDetails($cart_item['item_id']);
                                                        $item_discount = isset($item_dt['discount']) ? $item_dt['discount'] : 0;
                                                        $item_discount_type = isset($item_dt['discount_type']) ? $item_dt['discount_type'] : 'percentage';
                                                        $item_image = isset($item_dt['image']) && !empty($item_dt['image']) && file_exists( 'public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                                                        $item_name = isset($item_dt[$name_key]) ? $item_dt[$name_key] : '';
                                                        $item_price_details = App\Models\ItemPrice::where('id', $cart_item['option_id'])->first();
                                                        $item_price = isset($item_price_details['price']) ? Currency::currency($currency)->format($item_price_details['price']) : 0.0;
                                                        $item_price_label = isset($item_price_details[$label_key]) ? $item_price_details[$label_key] : '';
                                                        $cart_total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
                                                    @endphp
                                                    <div class="cart_item">
                                                        <div class="cart_item_inr">
                                                            <div class="cart_item_name">
                                                                <span>{{ $cart_item['quantity'] }}</span>
                                                                <h5>{{ $item_name }}</h5>
                                                            </div>
                                                            <label>{{ $cart_item['total_amount_text'] }}</label>
                                                        </div>
                                                        <div class="item_attribute">
                                                            <div class="item_attribute_inr">
                                                                @if(count($categories_data) > 0)
                                                                    @foreach ($categories_data as $option_id)
                                                                        @php
                                                                            $my_opt = $option_id;
                                                                        @endphp
                                                                        @if(is_array($my_opt))
                                                                            @if(count($my_opt) > 0)
                                                                                @php
                                                                                    $options = [];
                                                                                @endphp
                                                                                @foreach ($my_opt as $optid)
                                                                                    @php
                                                                                        $opt_price_dt = App\Models\OptionPrice::where('id', $optid)->with('optionName')->first();
                                                                                        $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                        $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                        $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                                        $option_id = $opt_price_dt->option_id;

                                                                                        // Initialize an empty array for the option_id if it doesn't exist yet
                                                                                        if (!isset($options[$option_id])) {
                                                                                            $options[$option_id] = [];
                                                                                        }

                                                                                        // Add the name to the array for the option_id
                                                                                        if (!empty($opt_title_name)) {
                                                                                            $options[$option_id][] = $opt_price_name;
                                                                                        }
                                                                                    @endphp
                                                                                @endforeach

                                                                                @foreach ($options as $option)
                                                                                    <h6><span><b>{{ $opt_title_name }}: </b></span>{{ implode(', ', $option) }}</h6>
                                                                                @endforeach
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $opt_price_dt = App\Models\OptionPrice::where('id',$my_opt)->with('optionName')->first();
                                                                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                            @endphp
                                                                            <h6>
                                                                                <span><b>{{ $opt_title_name }} : </b></span>
                                                                                @if(!empty($opt_price_name))
                                                                                    {{ $opt_price_name }}
                                                                                @endif
                                                                            </h6>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="cart_qty_action">
                                                            <div class="cart_item_qty">
                                                                <button class="btn-number" onclick="updateCartPage({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'decrement')"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                                <button class="btn-number" onclick="updateCartPage({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'increment')"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                            </div>
                                                            <div class="cart_item_action">
                                                                <a href="#" onclick="getCartDetails({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }}, {{ $shop_id }})"><i class="fa-solid fa-pencil"></i></a>
                                                                <a href="#" onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})" class="text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @empty
                                            <h4 class="text-center">Cart is Empty</h4>
                                        @endforelse
                                    </div>
                                    @php
                                        $categoryIds = App\Models\Items::whereIn('id', $itemIds)->pluck('recomendation_items')->toArray();
                                        $recommendationItems = [];
                                        foreach ($categoryIds as $category) {
                                            if (is_string($category) && ($unserialized = unserialize($category)) !== false && is_array($unserialized)) {
                                                $recommendationItems = array_merge($recommendationItems, $unserialized);
                                            }
                                        }
                                        $recommendationItems = array_unique($recommendationItems);
                                        $relatedItems = App\Models\Items::whereIn('id', $recommendationItems)->whereNotIn('id', $itemIds)->where('type', 1)->get();
                                    @endphp
                                    @if (count($relatedItems) > 0)
                                        <div id="recommended_items" class="recommended_items">
                                            <div class="recommended_title">
                                                <h3>{{ __('Recommended') }}</h3>
                                            </div>
                                            <div class="swiper-container h-100 position-relative">
                                                <div class="swiper-wrapper">
                                                    @foreach ($relatedItems as $item)
                                                        @php
                                                            $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                        @endphp
                                                        <div class="swiper-slide">
                                                            <div class="recommended_product" >
                                                                <a onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})">
                                                                    <div class="recommended_product_img">
                                                                        {{-- Image Section --}}
                                                                        @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}" alt="" class="w-100">
                                                                        @else
                                                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                                        @endif
                                                                        <div class="recommended_special_tag">
                                                                            {{-- New Product Image --}}
                                                                            @if ($item['is_new'] == 1)
                                                                                <span>new</span>
                                                                            @endif
                                                                            {{-- Signature Image --}}
                                                                            @if ($item['as_sign'] == 1)
                                                                                <span>Signature</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="recommended_product_info">
                                                                        {{-- Price --}}
                                                                        @php
                                                                            $price_arr = getItemPrice($item['id']);
                                                                        @endphp

                                                                        @if (count($price_arr) > 0)
                                                                            @php
                                                                                $price = Currency::currency($currency)->format($price_arr[0]['price']);
                                                                                $price_label = isset($price_arr[0][$price_label_key]) ? $price_arr[0][$price_label_key] : '';
                                                                            @endphp
                                                                            @if ($item_discount > 0)
                                                                                @php
                                                                                    if ($item_discount_type == 'fixed') {
                                                                                        $new_amount = number_format($price_arr[0]['price'] - $item_discount, 2);
                                                                                    } else {
                                                                                        $per_value = ($price_arr[0]['price'] * $item_discount) / 100;
                                                                                        $new_amount = number_format($price_arr[0]['price'] - $per_value, 2);
                                                                                    }
                                                                                @endphp
                                                                                <p>{{ $price }}</p>
                                                                            @else
                                                                                <p>{{ $price }}</p>
                                                                            @endif
                                                                        @endif
                                                                        <h3>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}</h3>
                                                                    </div>
                                                                </a>
                                                                @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1)
                                                                    <div class="recommended_product_add_cart">
                                                                        <a onclick="cartAdd({{ $item->id }},{{ $shop_details['id'] }})"><i class="fa-solid fa-plus"></i></a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="order_comment">
                                        <textarea name="instructions" id="instructions" rows="3" class="form-control" placeholder="{{ __('Order Comments') }}"></textarea>
                                    </div>
                                </div>
                                <div class="cart_footer">
                                    <div class="price_table">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    @php
                                                        $total_discount = 0;
                                                        $coupon_discount = 0;
                                                    @endphp
                                                    <td>
                                                        @if ($discount_per > 0 || $coupon_value > 0)
                                                            <h3><b>{{ __('SubTotal') }}: </b>{{ Currency::currency($currency)->format($cart_total_amount) }}</h3>
                                                        @endif
                                                    </td>
                                                    @if ($discount_per > 0 || $coupon_value > 0)
                                                        @if ($discount_per > 0)
                                                            @php
                                                                if ($discount_type == 'fixed') {
                                                                    $total_discount += $discount_per;
                                                                } else {
                                                                    $total_discount += ($cart_total_amount * $discount_per) / 100;
                                                                }
                                                            @endphp
                                                        @endif
                                                        @if ($coupon_value > 0)
                                                            @php
                                                                if ($coupon_type == 'fixed') {
                                                                    $coupon_discount += $coupon_value;
                                                                } else {
                                                                    $coupon_discount += ($cart_total_amount * $coupon_value) / 100;
                                                                }
                                                            @endphp
                                                        @endif
                                                        @php
                                                            // Calculate final amount after discounts
                                                            $final_amount = $cart_total_amount - ($total_discount + $coupon_discount);
                                                        @endphp
                                                    @endif
                                                    <td class="text-end">
                                                        @isset($final_amount)
                                                            <h3><b>{{ __('Total') }}: </b>{{ Currency::currency($currency)->format($final_amount) }}</h3>
                                                        @else
                                                            <h3><b>{{ __('Total') }}: </b>{{ Currency::currency($currency)->format($cart_total_amount) }}</h3>
                                                        @endisset
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="apply_coupon">
                                        <div id="couponInputGroup"  style="display: none;">
                                            <input type="text" name="code" id="code" class="form-control" placeholder="{{ __('Insert Coupon Number...') }}">
                                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                                            <a class="btn btn-success" id="applyCouponButton"><i class="fa-solid fa-check"></i></a>
                                        </div>
                                    </div>
                                    @if ($is_checkout == 1)
                                        <div class="place_order_btn">
                                            @if(count($coupon) > 0)
                                                <a id="couponApplyButton" class="btn coupon-btn">{{ __('Coupon') }}</a>
                                            @endif
                                            <button class="btn order-btn" id="check-btn">{{ __('Continue') }}</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="footer text-center">
            <div class="container">
                <div class="footer-inr">
                    <div class="footer_media">
                        <h3>Find Us</h3>
                        <ul>
                            {{-- Phone Link --}}
                            @if (isset($shop_settings['business_telephone']) && !empty($shop_settings['business_telephone']))
                                <li>
                                    <a href="tel:{{ $shop_settings['business_telephone'] }}"><i class="fa-solid fa-phone"></i></a>
                                </li>
                            @endif

                            {{-- Instagram Link --}}
                            @if (isset($shop_settings['instagram_link']) && !empty($shop_settings['instagram_link']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['instagram_link'] }}"><i class="fa-brands fa-square-instagram"></i></a>
                                </li>
                            @endif

                            {{-- Twitter Link --}}
                            @if (isset($shop_settings['twitter_link']) && !empty($shop_settings['twitter_link']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['twitter_link'] }}"><i class="fa-brands fa-square-twitter"></i></a>
                                </li>
                            @endif

                            {{-- Facebook Link --}}
                            @if (isset($shop_settings['facebook_link']) && !empty($shop_settings['facebook_link']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['facebook_link'] }}"><i class="fa-brands fa-square-facebook"></i></a>
                                </li>
                            @endif

                            {{-- Pinterest Link --}}
                            @if (isset($shop_settings['pinterest_link']) && !empty($shop_settings['pinterest_link']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['pinterest_link'] }}"><i class="fa-brands fa-pinterest"></i></a>
                                </li>
                            @endif

                            {{-- FourSquare Link --}}
                            @if (isset($shop_settings['foursquare_link']) && !empty($shop_settings['foursquare_link']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['foursquare_link'] }}"><i class="fa-brands fa-foursquare"></i></a>
                                </li>
                            @endif

                            {{-- TripAdvisor Link --}}
                            @if (isset($shop_settings['tripadvisor_link']) && !empty($shop_settings['tripadvisor_link']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i class="fa-solid fa-mask"></i></a></a>
                                </li>
                            @endif

                            {{-- Website Link --}}
                            @if (isset($shop_settings['website_url']) && !empty($shop_settings['website_url']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['website_url'] }}"><i class="fa-solid fa-globe"></i></a>
                                </li>
                            @endif

                            {{-- Gmap Link --}}
                            @if (isset($shop_settings['map_url']) && !empty($shop_settings['map_url']))
                                <li>
                                    <a target="_blank" href="{{ $shop_settings['map_url'] }}"><i class="fa-solid fa-location-dot"></i></a>
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
    @elseif($layout == 'layout_3')
        <div class="cart_main">
            <div class="container">
                <div class="cart_detail">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="cart_info">
                                <div class="cart_title">
                                    <h2>{{ __('Order Options') }}</h2>
                                    <a onclick="homePage('{{ $shop_details['shop_slug'] }}')" class="text-dark cart-back-btn" style="cursor: pointer;"><i class="fa-solid fa-arrow-left-long"></i></a>
                                </div>
                                <div class="cart_info_inr">
                                    <div class="checkout_type">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    @if ($is_checkout == 1)
                                                        <select name="checkout_type" id="checkout_type" class="form-select w-100" onchange="checkoutType(this.value)">
                                                            <option value="0"> {{ __('Select Delivery Method') }}</option>
                                                            @if (isset($order_settings['delivery']) && $order_settings['delivery'] == 1)
                                                                <option value="delivery"{{ $current_check_type == 'delivery' ? 'selected' : '' }}>{{ __('Delivery') }}</option>
                                                            @endif
                                                            @if (isset($order_settings['takeaway']) && $order_settings['takeaway'] == 1)
                                                                <option value="takeaway"{{ $current_check_type == 'takeaway' ? 'selected' : '' }}>{{ __('Takeaway') }}</option>
                                                            @endif
                                                            @if (isset($order_settings['room_delivery']) && $order_settings['room_delivery'] == 1)
                                                                <option value="room_delivery"{{ $current_check_type == 'room_delivery' ? 'selected' : '' }}>{{ __('Room Delivery') }}</option>
                                                            @endif
                                                            @if (isset($order_settings['table_service']) && $order_settings['table_service'] == 1)
                                                                <option value="table_service"{{ $current_check_type == 'table_service' ? 'selected' : '' }}>{{ __('Table Service') }}</option>
                                                            @endif
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="room_service d-none">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <select name="room" id="room" class="form-select" onchange="getRoom(this.value)">
                                                        <option value="">{{ __('Choose Your Room') }}</option>
                                                        @foreach ($rooms as $room)
                                                            <option value="{{ $room->room_no }}" {{ $current_room_no == $room->room_no ? 'selected' : '' }}>{{ $room->room_no }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table_service d-none">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <select name="table_no" id="table_no" class="form-select">
                                                        <option value="">{{ __('Choose Your Table') }}</option>
                                                        @foreach ($tables as $table)
                                                            <option value="{{ $table->id }}">{{ $table->table_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart_item_info_inr">
                                        @forelse ($cart as $cart_key => $cart_data)
                                            @foreach ($cart_data as $cart_val)
                                                @foreach ($cart_val as $cart_item_key => $cart_item)
                                                    @php
                                                        $itemIds[] = $cart_item['item_id'];
                                                        $categories_data = $cart_item['categories_data'];
                                                        $item_dt = itemDetails($cart_item['item_id']);
                                                        $item_discount = isset($item_dt['discount']) ? $item_dt['discount'] : 0;
                                                        $item_discount_type = isset($item_dt['discount_type']) ? $item_dt['discount_type'] : 'percentage';
                                                        $item_image = isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                                                        $item_name = isset($item_dt[$name_key]) ? $item_dt[$name_key] : '';
                                                        $item_price_details = App\Models\ItemPrice::where('id', $cart_item['option_id'])->first();
                                                        $item_price = isset($item_price_details['price']) ? Currency::currency($currency)->format($item_price_details['price']) : 0.0;
                                                        $item_price_label = isset($item_price_details[$label_key]) ? $item_price_details[$label_key] : '';
                                                        $cart_total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
                                                    @endphp
                                                    <div class="cart_item">
                                                        <div class="cart_item_inr">
                                                            <div class="cart_item_name">
                                                                <span>{{ $cart_item['quantity'] }}</span>
                                                                <h5>{{ $item_name }}</h5>
                                                            </div>
                                                            <label>{{ $cart_item['total_amount_text'] }}</label>
                                                        </div>
                                                        <div class="item_attribute">
                                                            <div class="item_attribute_inr">
                                                                @if(count($categories_data) > 0)
                                                                    @foreach ($categories_data as $option_id)
                                                                        @php
                                                                            $my_opt = $option_id;
                                                                        @endphp

                                                                        @if(is_array($my_opt))
                                                                            @if(count($my_opt) > 0)
                                                                                @php
                                                                                    $options = [];
                                                                                @endphp

                                                                                @foreach ($my_opt as $optid)
                                                                                    @php
                                                                                        $opt_price_dt = App\Models\OptionPrice::where('id', $optid)->with('optionName')->first();
                                                                                        $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                        $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                        $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                                        $option_id = $opt_price_dt->option_id;

                                                                                        // Initialize an empty array for the option_id if it doesn't exist yet
                                                                                        if (!isset($options[$option_id])) {
                                                                                            $options[$option_id] = [];
                                                                                        }

                                                                                        // Add the name to the array for the option_id
                                                                                        if (!empty($opt_title_name)) {
                                                                                            $options[$option_id][] = $opt_price_name;
                                                                                        }
                                                                                    @endphp
                                                                                @endforeach

                                                                                @foreach ($options as $option)
                                                                                    <h6><span><b>{{ $opt_title_name }}: </b></span>{{ implode(', ', $option) }}</h6>
                                                                                @endforeach
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $opt_price_dt = App\Models\OptionPrice::where('id',$my_opt)->with('optionName')->first();
                                                                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                                                $opt_title_name = (isset($opt_price_dt->optionName[$title_key])) ? $opt_price_dt->optionName[$title_key] : '';
                                                                                $opt_price = (isset($opt_price_dt['price'])) ? Currency::currency($currency)->format($opt_price_dt['price']) : 0.00;
                                                                            @endphp
                                                                            <h6>
                                                                                <span><b>{{ $opt_title_name }} : </b></span>
                                                                                @if(!empty($opt_price_name))
                                                                                    {{ $opt_price_name }}
                                                                                @endif
                                                                            </h6>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="cart_qty_action">
                                                            <div class="cart_item_qty">
                                                                <button class="btn-number" onclick="updateCartPage({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'decrement')"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                                <button class="btn-number" onclick="updateCartPage({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }},'increment')"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                            </div>
                                                            <div class="cart_item_action">
                                                                <a href="#" onclick="getCartDetails({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }}, {{ $shop_id }})"><i class="fa-solid fa-pencil"></i></a>
                                                                <a href="#" onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})" class="text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        @empty
                                            <h4 class="text-center">Cart is Empty</h4>
                                        @endforelse
                                    </div>
                                    @php
                                        $categoryIds = App\Models\Items::whereIn('id', $itemIds)->pluck('recomendation_items')->toArray();
                                        $recommendationItems = [];
                                        foreach ($categoryIds as $category) {
                                            if (is_string($category) && ($unserialized = unserialize($category)) !== false && is_array($unserialized)) {
                                                $recommendationItems = array_merge($recommendationItems, $unserialized);
                                            }
                                        }
                                        $recommendationItems = array_unique($recommendationItems);
                                        $relatedItems = App\Models\Items::whereIn('id', $recommendationItems)->whereNotIn('id', $itemIds)->where('type', 1)->get();
                                    @endphp
                                    @if (count($relatedItems) > 0)
                                        <div id="recommended_items" class="recommended_items">
                                            <div class="recommended_title">
                                                <h3>{{ __('Recommended') }}</h3>
                                            </div>
                                            <div class="swiper-container h-100 position-relative">
                                                <div class="swiper-wrapper">
                                                    @foreach ($relatedItems as $item)
                                                        @php
                                                            $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                        @endphp
                                                        <div class="swiper-slide">
                                                            <div class="recommended_product" >
                                                                <a onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})">
                                                                    <div class="recommended_product_img">
                                                                        {{-- Image Section --}}
                                                                        @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}" alt="" class="w-100">
                                                                        @else
                                                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                                        @endif
                                                                        <div class="recommended_special_tag">
                                                                            {{-- New Product Image --}}
                                                                            @if ($item['is_new'] == 1)
                                                                                <span>new</span>
                                                                            @endif
                                                                            {{-- Signature Image --}}
                                                                            @if ($item['as_sign'] == 1)
                                                                                <span>Signature</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="recommended_product_info">
                                                                        {{-- Price --}}
                                                                        @php
                                                                            $price_arr = getItemPrice($item['id']);
                                                                        @endphp

                                                                        @if (count($price_arr) > 0)
                                                                            @php
                                                                                $price = Currency::currency($currency)->format($price_arr[0]['price']);
                                                                                $price_label = isset($price_arr[0][$price_label_key]) ? $price_arr[0][$price_label_key] : '';
                                                                            @endphp
                                                                            @if ($item_discount > 0)
                                                                                @php
                                                                                    if ($item_discount_type == 'fixed') {
                                                                                        $new_amount = number_format($price_arr[0]['price'] - $item_discount, 2);
                                                                                    } else {
                                                                                        $per_value = ($price_arr[0]['price'] * $item_discount) / 100;
                                                                                        $new_amount = number_format($price_arr[0]['price'] - $per_value, 2);
                                                                                    }
                                                                                @endphp
                                                                                <p>{{ $price }}</p>
                                                                            @else
                                                                                <p>{{ $price }}</p>
                                                                            @endif
                                                                        @endif
                                                                        <h3>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}</h3>
                                                                    </div>
                                                                </a>
                                                                @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1)
                                                                    <div class="recommended_product_add_cart">
                                                                        <a onclick="cartAdd({{ $item->id }},{{ $shop_details['id'] }})"><i class="fa-solid fa-plus"></i></a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="order_comment">
                                        <textarea name="instructions" id="instructions" rows="3" class="form-control" placeholder="{{ __('Order Comments') }}"></textarea>
                                    </div>
                                </div>
                                <div class="cart_footer">
                                    <div class="price_table">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    @php
                                                        $total_discount = 0;
                                                        $coupon_discount = 0;
                                                    @endphp

                                                    <td>
                                                        @if ($discount_per > 0 || $coupon_value > 0)
                                                            <h3><b>{{ __('SubTotal') }}: </b>{{ Currency::currency($currency)->format($cart_total_amount) }}</h3>
                                                        @endif
                                                    </td>

                                                    @if ($discount_per > 0 || $coupon_value > 0)
                                                        @if ($discount_per > 0)
                                                            @php
                                                                if ($discount_type == 'fixed') {
                                                                    $total_discount += $discount_per;
                                                                } else {
                                                                    $total_discount += ($cart_total_amount * $discount_per) / 100;
                                                                }
                                                            @endphp
                                                        @endif

                                                        @if ($coupon_value > 0)
                                                            @php
                                                                if ($coupon_type == 'fixed') {
                                                                    $coupon_discount += $coupon_value;
                                                                } else {
                                                                    $coupon_discount += ($cart_total_amount * $coupon_value) / 100;
                                                                }
                                                            @endphp
                                                        @endif

                                                        @php
                                                            // Calculate final amount after discounts
                                                            $final_amount = $cart_total_amount - ($total_discount + $coupon_discount);
                                                        @endphp
                                                    @endif

                                                    <td class="text-end">
                                                        @isset($final_amount)
                                                            <h3><b>{{ __('Total') }}: </b>{{ Currency::currency($currency)->format($final_amount) }}</h3>
                                                        @else
                                                            <h3><b>{{ __('Total') }}: </b>{{ Currency::currency($currency)->format($cart_total_amount) }}</h3>
                                                        @endisset
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="apply_coupon">
                                        <div id="couponInputGroup"  style="display: none;">
                                            <input type="text" name="code" id="code" class="form-control" placeholder="{{ __('Insert Coupon Number...') }}">
                                            <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_id }}">
                                            <a class="btn btn-success" id="applyCouponButton"><i class="fa-solid fa-check"></i></a>
                                        </div>
                                    </div>
                                    @if ($is_checkout == 1)
                                        <div class="place_order_btn">
                                            @if(count($coupon) > 0)
                                                <a id="couponApplyButton" class="btn coupon-btn">{{ __('Coupon') }}</a>
                                            @endif
                                            <button class="btn order-btn" id="check-btn">{{ __('Continue') }}</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="footer text-center">
                    <div class="container">
                        <div class="footer-inr">
                            <div class="footer_media">
                                <h3>Find Us</h3>
                                <ul>
                                    {{-- Phone Link --}}
                                    @if (isset($shop_settings['business_telephone']) && !empty($shop_settings['business_telephone']))
                                        <li>
                                            <a href="tel:{{ $shop_settings['business_telephone'] }}"><i class="fa-solid fa-phone"></i></a>
                                        </li>
                                    @endif

                                    {{-- Instagram Link --}}
                                    @if (isset($shop_settings['instagram_link']) && !empty($shop_settings['instagram_link']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['instagram_link'] }}"><i class="fa-brands fa-square-instagram"></i></a>
                                        </li>
                                    @endif

                                    {{-- Twitter Link --}}
                                    @if (isset($shop_settings['twitter_link']) && !empty($shop_settings['twitter_link']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['twitter_link'] }}"><i class="fa-brands fa-square-twitter"></i></a>
                                        </li>
                                    @endif

                                    {{-- Facebook Link --}}
                                    @if (isset($shop_settings['facebook_link']) && !empty($shop_settings['facebook_link']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['facebook_link'] }}"><i class="fa-brands fa-square-facebook"></i></a>
                                        </li>
                                    @endif

                                    {{-- Pinterest Link --}}
                                    @if (isset($shop_settings['pinterest_link']) && !empty($shop_settings['pinterest_link']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['pinterest_link'] }}"><i class="fa-brands fa-pinterest"></i></a>
                                        </li>
                                    @endif

                                    {{-- FourSquare Link --}}
                                    @if (isset($shop_settings['foursquare_link']) && !empty($shop_settings['foursquare_link']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['foursquare_link'] }}"><i class="fa-brands fa-foursquare"></i></a>
                                        </li>
                                    @endif

                                    {{-- TripAdvisor Link --}}
                                    @if (isset($shop_settings['tripadvisor_link']) && !empty($shop_settings['tripadvisor_link']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i class="fa-solid fa-mask"></i></a>
                                        </li>
                                    @endif

                                    {{-- Website Link --}}
                                    @if (isset($shop_settings['website_url']) && !empty($shop_settings['website_url']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['website_url'] }}"><i class="fa-solid fa-globe"></i></a>
                                        </li>
                                    @endif

                                    {{-- Gmap Link --}}
                                    @if (isset($shop_settings['map_url']) && !empty($shop_settings['map_url']))
                                        <li>
                                            <a target="_blank" href="{{ $shop_settings['map_url'] }}"><i class="fa-solid fa-location-dot"></i></a>
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
            </div>
        </div>
    @endif

@endsection

{{-- Page JS Function --}}
@section('page-js')
    <script type="text/javascript">

        var total_qty = @json($total_cart_qty);
        var shop_slug = @json($shop_slug);
        var empty_redirect = @json(url('restaurant')) + '/' + shop_slug;
        var layout = "{{ $layout }}";
        var BannerSpeed = {{ $slider_delay_time }};

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
        function updateCart(itemID, priceID, item_key, ele) {
            var qty = $(ele).val();
            var old_qty = $(ele).attr('old-qty');
            var currency = $('#def_currency').val();

            $.ajax({
                type: "POST",
                url: "{{ route('shop.update.cart') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'quantity': qty,
                    'old_quantity': old_qty,
                    'item_id': itemID,
                    'currency': currency,
                    'price_id': priceID,
                    'item_key': item_key,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });

        }

        // Function for Remove Cart Items
        function removeCartItem(itemID, priceID, item_key) {
            $.ajax({
                type: "POST",
                url: "{{ route('shop.remove.cart.item') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'item_id': itemID,
                    'price_id': priceID,
                    'item_key': item_key,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }

        // Redirect to web Page
        $('#check-btn').on('click', function() {

            var check_type = $('#checkout_type :selected').val();
            var room = $('#room :selected').val();
            var table_no = $('#table_no :selected').val();
            var instructions = $('#instructions').val();

            var shop_slug = "{{ $shop_slug }}";


            $.ajax({
                type: "POST",
                url: "{{ route('set.checkout.type') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'check_type': check_type,
                    'room': room,
                    'table_no': table_no,
                    'instructions': instructions,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {
                        window.location.href = "cart/checkout";
                    } else {
                        toastr.error(response.message);
                        return false;
                    }
                },
                error: function(response) {
                    const validationErrors = (response?.responseJSON?.errors) ? response.responseJSON
                        .errors : '';

                    if (validationErrors != '') {
                        var checkTypeError = (validationErrors.check_type) ? validationErrors
                            .check_type : '';
                        if (checkTypeError != '') {
                            // $('#addItemForm #name').addClass('is-invalid');
                            toastr.error(checkTypeError);
                        }

                        var roomError = (validationErrors.room) ? validationErrors.room : '';
                        if (roomError != '') {
                            // $('#addItemForm #name').addClass('is-invalid');
                            toastr.error(roomError);
                        }
                        var tableError = (validationErrors.table_no) ? validationErrors.table_no : '';
                        if (tableError != '') {
                            // $('#addItemForm #name').addClass('is-invalid');
                            toastr.error(tableError);
                        }
                    }
                }
            });
        });

        $(document).ready(function() {

            if (total_qty == 0) {
                window.location.href = "";
            }

            var delNotes = $('.notes-del').length;
            if (delNotes == 0) {
                $('#del-notes').hide();
            } else {
                $('#del-notes').show();
            }

        });


        $(document).ready(function() {
            var slider_effect = "{{ $effect }}";
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

        $(document).ready(function() {
            $('#couponApplyButton').click(function(event) {
                event.preventDefault();
                $('#couponInputGroup').toggle();
            });

            var coupon_value = "{{ $coupon_value }}";

            if (coupon_value > 0) {
                $('#couponApplyButton').hide();
            } else {
                $('#couponApplyButton').show();
            }

            $('#removeCoupon').click(function(event) {
                $.ajax({
                    url: "{{ route('remove.coupon') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success == 1) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(response.message);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }
                });
            });

            $('#applyCouponButton').click(function(event) {
                event.preventDefault();
                var couponCode = $('#code').val();
                var shop_id = $('#shop_id').val();
                var cart_amount = $('#total_amount').val();


                // Send AJAX request
                $.ajax({
                    url: "{{ route('validate.coupon') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'code': couponCode,
                        'shop_id': shop_id,
                        'cart_amount': cart_amount
                    },
                    success: function(response) {
                        if (response.success == 1) {
                            console.log(response.message);
                            toastr.success(response.message);
                            $('#couponApplyButton').hide();
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            console.log(response.message);
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        // Handle error
                        alert('An error occurred while validating the coupon.');
                    }
                });
            });
        });

        function updateCart(itemID, priceID, item_key, action) {

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

        function updateCartPage(itemID, priceID, item_key, action) {

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

                        // $('#quantity_' + response.item_id).text(response.quantity);

                        // $('#total_amount_' + response.item_id).text(response.total_amount_text);
                        // $('#total_amount').text(response.final_amount_text);
                        // $('.qty-number').text(response.total_quantity);
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }

        $(document).ready(function() {
            var selectedValue = $('#checkout_type').val();
            checkoutType(selectedValue); // Call with a default value if needed
        });

        function checkoutType(selectedValue) {

            if (selectedValue == 'room_delivery') {
                $('.room_service').removeClass('d-none');
                $('.table_service').addClass('d-none');

            } else if (selectedValue == 'table_service') {
                $('.table_service').removeClass('d-none');
                $('.room_service').addClass('d-none');

            } else {
                $('.room_service').addClass('d-none');
                $('.table_service').addClass('d-none');
            }
        }

        $('#checkout_type').on('change', function() {
            const content = $('#minSpentModal .spent_notes_info').html().trim();
            if (content !== '') {
                if ($(this).val() == 'delivery') {
                    $('#minSpentModal').modal('show');
                }
            }
        });

        function getRoom(selectedValue) {

            var shop_id = {{ $shop_details['id'] }};

            $.ajax({
                type: "POST",
                url: "{{ route('set.room.no') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'room': selectedValue,
                    'shop_id': shop_id,

                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {
                        // location.reload();
                    } else {
                        toastr.error(response.message);
                        return false;
                    }
                }
            });

        }

        $('#recommended_items .slick-carousel').slick({
            arrows: true,
            centerPadding: "0px",
            dots: true,
            slidesToShow: 3,
            infinite: false
        });

        $(document).ready(function () {
            new Swiper('#recommended_items .swiper-container', {
                loop: false,
                nextButton: '.swiper-button-next',
                prevButton: '.swiper-button-prev',
                slidesPerView: 3,
                paginationClickable: true,
                spaceBetween: 20,
                breakpoints: {
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 30
                    },
                    767: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    0: {
                        slidesPerView: 2,
                        spaceBetween: 10
                    },

                }
            });
        });

        function cartAdd(id, shopId){
            $.ajax({
                type: "POST",
                url: "{{ route('add.to.cart') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'shop_id': shopId,

                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {

                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        }


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
