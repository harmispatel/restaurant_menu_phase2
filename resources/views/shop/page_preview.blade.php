@php

    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Shop Currency
    $currency = isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency']) ? $shop_settings['default_currency'] : 'EUR';

    // Shop Name
    $shop_name = isset($shop_details['name']) && !empty($shop_details['name']) ? $shop_details['name'] : '';
    $shop_desc = isset($shop_details['description']) && !empty($shop_details['description']) ? strip_tags($shop_details['description']) : '';

    // Shop Title
    $shop_subtitle = isset($shop_settings['business_subtitle']) && !empty($shop_settings['business_subtitle']) ? $shop_settings['business_subtitle'] : '';

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');

    // Default Image
    $default_image = asset('public/client_images/not-found/no_image_1.jpg');

    // Shop Time
    $shop_start_time = isset($shop_settings['shop_start_time']) && !empty($shop_settings['shop_start_time']) ? $shop_settings['shop_start_time'] : '';
    $shop_end_time = isset($shop_settings['shop_end_time']) && !empty($shop_settings['shop_end_time']) ? $shop_settings['shop_end_time'] : '';

    $waiter_call_status = isset($shop_settings['waiter_call_status']) ?  $shop_settings['waiter_call_status'] : '0';


    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Name Key
    $name_key = $current_lang_code . '_name';

    // Description Key
    $description_key = $current_lang_code . '_description';

    // Price Key
    $price_label_key = $current_lang_code . '_label';
    $title_key = $current_lang_code."_title";


    // Current Category
    $current_cat_id = isset($cat_details['id']) ? $cat_details['id'] : '';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);

    // Total Amount
    $total_amount = 0;


    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'], 'homepage_intro');
    $homepage_intro = isset($homepage_intro[$current_lang_code . '_value']) && !empty($homepage_intro[$current_lang_code . '_value']) ? $homepage_intro[$current_lang_code . '_value'] : '';

    // Item Devider
    $item_devider = isset($theme_settings['item_divider']) && !empty($theme_settings['item_divider']) ? $theme_settings['item_divider'] : 0;

    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

    $effect = isset($theme_settings['slider_effect']) ? $theme_settings['slider_effect'] : 'fabe';

    $category_effect = (isset($theme_settings['category_slider_effect']) && !empty($theme_settings['category_slider_effect'])) ? $theme_settings['category_slider_effect'] : 'default';

    $banner_height = isset($theme_settings['banner_height']) && !empty($theme_settings['banner_height']) ? $theme_settings['banner_height'] : 250;
    $slider_buttons = isset($theme_settings['banner_slide_button']) && !empty($theme_settings['banner_slide_button']) ? $theme_settings['banner_slide_button'] : 0;
    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';
    $logo_position = isset($theme_settings['logo_position']) ? $theme_settings['logo_position'] : '';
    $search_box_position = isset($theme_settings['search_box_position']) ? $theme_settings['search_box_position'] : '';
    $slider_delay_time = isset($theme_settings['banner_delay_time']) && !empty($theme_settings['banner_delay_time']) ? $theme_settings['banner_delay_time'] : 3000;
    $stiky_header =  isset($theme_settings['sticky_header']) && !empty($theme_settings['sticky_header']) ? $theme_settings['sticky_header']  : '';


    // Admin Settings
    $admin_settings = getAdminSettings();
    $default_special_image = isset($admin_settings['default_special_item_image']) ? $admin_settings['default_special_item_image'] : '';
    $shop_desc = html_entity_decode($shop_desc);

    $cat_name = isset($cat_details[$name_key]) ? $cat_details[$name_key] : '';
    $shop_title = "$shop_name | $cat_name";

    // shopId
    $shop_id = isset($shop_details['id']) ? $shop_details['id'] : '';
    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_id);

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    // Header Text One
    $header_text_one = moreTranslations($shop_details['id'], 'header_text_1');
    $header_text_one = isset($header_text_one[$current_lang_code. '_value']) && !empty($header_text_one[$current_lang_code . '_value']) ? $header_text_one[$current_lang_code . '_value'] : '';

    $shop_banners = getBanners($shop_id);
    $shop_banner_count = count($shop_banners) == 1 ? 'false' : 'true';
    $banner_key = $language_details['code'] . '_image';
    $banner_text_key = $language_details['code'] . '_description';

    // Get Language Settings
    $language_settings = clientLanguageSettings($shop_id);

    $google_translate = isset($language_settings['google_translate']) ? $language_settings['google_translate'] : 0;
    // Cart Quantity
    $total_quantity = getCartQuantity();

    $is_sub_title = isset($shop_settings['is_sub_title']) ? $shop_settings['is_sub_title'] : '0';

    $header_text_two = moreTranslations($shop_details['id'], 'header_text_2');
    $header_text_two = isset($header_text_two[$current_lang_code. '_value']) && !empty($header_text_two[$current_lang_code . '_value']) ? $header_text_two[$current_lang_code . '_value'] : '';


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


    // Cart Item
    $cart = session()->get('cart', []);

    foreach ($cart as $cart_key => $cart_data) {
        foreach ($cart_data as $cart_val) {
            foreach ($cart_val as $cart_item_key => $cart_item) {
                $total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
            }
        }
    }

    $table_enable_status = (isset($shop_settings['table_enable_status']) && !empty($shop_settings['table_enable_status'])) ? $shop_settings['table_enable_status'] : 0;
    $room_enable_status = (isset($shop_settings['room_enable_status']) && !empty($shop_settings['room_enable_status'])) ? $shop_settings['room_enable_status'] : 0;

    $total_grading = App\Models\ShopRateServies::where('shop_id', $shop_id)->where('status', 1)->count();

@endphp


@extends('shop.shop-layout')

@section('title', $shop_title)

@section('header')
@if ($layout == 'layout_2')

{{-- mobile view --}}
    <header class="header_preview header-sticky mobile-header">
        <nav class="navbar navbar-light bg-light">
            <div class="container">

                @if (
                    $language_bar_position != $logo_position &&
                        $language_bar_position != $search_box_position &&
                        $logo_position != $search_box_position &&
                        $logo_position != $language_bar_position &&
                        $search_box_position != $language_bar_position &&
                        $search_box_position != $logo_position)

                    {{-- Left Position --}}
                    @if ($language_bar_position == 'left')
                    @if (count($additional_languages) > 0 || $google_translate == 1)

                        <div class="lang_select">
                            <a class="lang_bt"> <x-dynamic-component width="35px"
                                    component="flag-language-{{ $language_details['code'] }}" /> </a>
                            {{-- <a class="lang_bt" style="text-decoration: none; color:black; font-weight:700;cursor: pointer;"><i class="fa-solid fa-language"></i> {{ isset($language_details['name']) ? strtoupper($language_details['name']) : "" }}</a> --}}

                                <div class="lang_inr">
                                    <div class="text-end">
                                        <button class="btn close_bt"><i class="fa-solid fa-chevron-left"></i></button>
                                    </div>
                                    <ul class="lang_ul">
                                        @if (isset($primary_language_details) && !empty($primary_language_details))
                                            <li>
                                                <x-dynamic-component width="35px"
                                                    component="flag-language-{{ $primary_language_details['code'] }}" />
                                                <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')"
                                                    style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                            </li>
                                        @endif
                                        @if (count($additional_languages) > 0)
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
                                        @endif
                                        @if ($google_translate == 1)
                                            <li>
                                                <div class="form-group">
                                                    <label class="me-2">Auto Translate</label>
                                                    <label class="switch me-2">
                                                        <input type="checkbox" value="1" name="auto_translate"
                                                            id="auto_translate" value="1">
                                                        <span class="slider round">
                                                            <i class="fa-solid fa-circle-check check_icon"></i>
                                                            <i
                                                                class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
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
                    @elseif ($logo_position == 'left')
                        <a class="navbar-brand m-0" href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                    class="top-shop-logo">
                            @else
                                <img src="{{ $default_logo }}" class="top-shop-logo">
                            @endif
                        </a>
                    @elseif ($search_box_position == 'left')
                        <div>
                            @if (isset($package_permissions['ordering']) &&
                                    !empty($package_permissions['ordering']) &&
                                    $package_permissions['ordering'] == 1)

                                @if ($total_quantity > 0)
                                    <!-- <a href="{{ route('shop.cart', $shop_slug) }}"
                                        class="cart-btn me-1 mt-2 fs-4 text-white text-decoration-none">
                                        <div class="position-relative"><i class="bi bi-cart4"></i> <span
                                                class="qty-number">{{ $total_quantity }}</span></div>
                                    </a> -->
                                    <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                        <span class="qty-number">{{ $total_quantity }}</span>
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
                                                                    <h6><span><b>{{ $opt_title_name }} : </b></span>
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
                                @endif
                            @endif
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

                        </div>
                    @endif

                    {{-- Center Position --}}
                    @if ($logo_position == 'center')
                        <a class="navbar-brand m-0" href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                    class="top-shop-logo">
                            @else
                                <img src="{{ $default_logo }}" class="top-shop-logo">
                            @endif
                        </a>
                    @elseif ($search_box_position == 'center')
                        <div>
                            @if (isset($package_permissions['ordering']) &&
                                    !empty($package_permissions['ordering']) &&
                                    $package_permissions['ordering'] == 1)
                                @if ($total_quantity > 0)
                                    <a href="{{ route('shop.cart', $shop_slug) }}"
                                        class="cart-btn me-1 mt-2 fs-4 text-white text-decoration-none">
                                        <div class="position-relative"><i class="bi bi-cart4"></i> <span
                                                class="qty-number">{{ $total_quantity }}</span></div>
                                    </a>
                                @endif
                            @endif
                            @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                <div class="barger_menu_main">
                                        <div class="barger_menu_inner">
                                            <div class="barger_menu_icon">
                                                <i class="fa-solid fa-bars"></i>
                                            </div>
                                            <div class="barger_menu_list">
                                                <ul>
                                                    <!-- <li>
                                                        <div class="barger_menu_box">
                                                            <button class="btn search_bt" id="openSearchBox">
                                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                            </button>
                                                            <button class="btn search_bt d-none" id="closeSearchBox">
                                                                <i class="fa-solid fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </li> -->
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

                        </div>
                    @endif

                    {{-- Right Position --}}
                    @if ($language_bar_position == 'right')
                        @if (count($additional_languages) > 0 || $google_translate == 1)

                            <div class="lang_select">
                                <a class="lang_bt"> <x-dynamic-component width="35px"
                                        component="flag-language-{{ $language_details['code'] }}" /> </a>

                                    <div class="lang_inr">
                                        <div class="text-end">
                                            <button class="btn close_bt"><i class="fa-solid fa-chevron-left"></i></button>
                                        </div>
                                        <ul class="lang_ul">
                                            @if (isset($primary_language_details) && !empty($primary_language_details))
                                                <li>
                                                    <x-dynamic-component width="35px"
                                                        component="flag-language-{{ $primary_language_details['code'] }}" />
                                                    <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')"
                                                        style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                                </li>
                                            @endif
                                            @if (count($additional_languages) > 0)
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
                                            @endif
                                            @if ($google_translate == 1)
                                                <li>
                                                    <div class="form-group">
                                                        <label class="me-2">Auto Translate</label>
                                                        <label class="switch me-2">
                                                            <input type="checkbox" value="1" name="auto_translate"
                                                                id="auto_translate" value="1">
                                                            <span class="slider round">
                                                                <i class="fa-solid fa-circle-check check_icon"></i>
                                                                <i
                                                                    class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
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
                    @elseif ($logo_position == 'right')
                        <a class="navbar-brand m-0" href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                    class="top-shop-logo">
                            @else
                                <img src="{{ $default_logo }}" class="top-shop-logo">
                            @endif
                        </a>
                    @elseif ($search_box_position == 'right')
                        <div>
                            @if (isset($package_permissions['ordering']) &&
                                    !empty($package_permissions['ordering']) &&
                                    $package_permissions['ordering'] == 1)
                                @if ($total_quantity > 0)
                                    <!-- <a href="{{ route('shop.cart', $shop_slug) }}"
                                        class="cart-btn me-1 mt-2 fs-4 text-white text-decoration-none">
                                        <div class="position-relative"><i class="bi bi-cart4"></i> <span
                                                class="qty-number">{{ $total_quantity }}</span></div>
                                    </a> -->
                                    <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    <span class="qty-number">{{ $total_quantity }}</span>
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
                                                                    <h6><span><b>{{ $opt_title_name }} : </b></span>
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
                                @endif
                            @endif
                            @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                <div class="barger_menu_main">
                                        <div class="barger_menu_inner">
                                            <div class="barger_menu_icon">
                                                <i class="fa-solid fa-bars"></i>
                                            </div>
                                            <div class="barger_menu_list">
                                                <ul>
                                                    <!-- <li>
                                                        <div class="barger_menu_box">
                                                            <button class="btn search_bt" id="openSearchBox">
                                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                            </button>
                                                            <button class="btn search_bt d-none" id="closeSearchBox">
                                                                <i class="fa-solid fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </li> -->
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

                        </div>
                    @endif

                    <div class="search_input">
                        <input type="text" class="form-control w-100" name="search" id="search">
                    </div>
                @else
                @if (count($additional_languages) > 0 || $google_translate == 1)
                    <div class="lang_select">
                        <a class="lang_bt"> <x-dynamic-component width="35px"
                                component="flag-language-{{ $language_details['code'] }}" /> </a>
                        {{-- <a class="lang_bt" style="text-decoration: none; color:black; font-weight:700;cursor: pointer;"><i class="fa-solid fa-language"></i> {{ isset($language_details['name']) ? strtoupper($language_details['name']) : "" }}</a> --}}

                            <div class="lang_inr">
                                <div class="text-end">
                                    <button class="btn close_bt"><i class="fa-solid fa-chevron-left"></i></button>
                                </div>
                                <ul class="lang_ul">
                                    @if (isset($primary_language_details) && !empty($primary_language_details))
                                        <li>
                                            <x-dynamic-component width="35px"
                                                component="flag-language-{{ $primary_language_details['code'] }}" />
                                            <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')"
                                                style="cursor: pointer;">{{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                        </li>
                                    @endif
                                    @if (count($additional_languages) > 0)
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
                                    @endif
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
                    <a class="navbar-brand m-0" href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                        @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                class="top-shop-logo">
                        @else
                            <img src="{{ $default_logo }}" class="top-shop-logo">
                        @endif
                    </a>
                    <div>
                        @if (isset($package_permissions['ordering']) &&
                                !empty($package_permissions['ordering']) &&
                                $package_permissions['ordering'] == 1)
                            @if ($total_quantity > 0)
                                <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                    <span class="qty-number">{{ $total_quantity }}</span>
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
                                                                        <h6><span><b>{{ $opt_title_name }} : </b></span>
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
                            @endif
                        @endif
                        @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                            <div class="barger_menu_main">
                                <div class="barger_menu_inner">
                                    <div class="barger_menu_icon">
                                        <i class="fa-solid fa-bars"></i>
                                    </div>
                                    <div class="barger_menu_list">
                                        <ul>
                                            <!-- <li>
                                                <div class="barger_menu_box">
                                                    <button class="btn search_bt" id="openSearchBox">
                                                        <i class="fa-solid fa-magnifying-glass"></i>
                                                    </button>
                                                    <button class="btn search_bt d-none" id="closeSearchBox">
                                                        <i class="fa-solid fa-times"></i>
                                                    </button>
                                                </div>
                                            </li> -->
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
                    </div>
                    <div class="search_input">
                        <input type="text" class="form-control w-100" name="search" id="search">
                    </div>
                @endif
                @if (isset($package_permissions['ordering']) &&
                                     !empty($package_permissions['ordering']) &&
                                     $package_permissions['ordering'] == 1)
                                    @if ($total_quantity > 0)
                                            <div class="mobile_cart">
                                                            <a href="{{ route('shop.cart', $shop_slug) }}" class="btn mobile_cart_btn">
                                                                <span>{{ $total_quantity }}</span>
                                                                <h4>{{ __('Cart') }}</h4>
                                                                <p>{{ Currency::currency($currency)->format($total_amount) }}</p>
                                                            </a>
                                            </div>
                                    @endif
                    @endif

            </div>
        </nav>
    </header>

    {{-- desktop view --}}
    <header class="header side_header sidebar_header">
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

            <div class="category_ul">
                <ul>
                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            @php
                                $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                $name_code = $current_lang_code . '_name';
                                $nameId = str_replace(' ', '_', $category->en_name);
                                $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                            @endphp
                            @if ($active_cat == 1)
                                @if ($check_cat_type_permission == 1)
                                    <li>
                                        @if ($category->category_type == 'link')
                                            <a href="{{ $category->link_url }}" target="_blank" class="cat-btn">
                                            @else
                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id]) }}"
                                                    class="cat-btn {{ $category->id == $current_cat_id ? 'active' : '' }}">
                                        @endif
                                        {{-- <a href="#{{$nameId}}"> --}}
                                        {{ isset($category->$name_code) ? $category->$name_code : '' }}
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </ul>

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

                    <li class="navlink">
                        <a href="#" class="btn search_bt" id="openSearch"><i
                                class="fa-solid fa-search"></i></a>
                        <a href="#" class="btn search_bt d-none" id="closeSearch"><i
                                class="fa-solid fa-times"></i></a>
                    </li>
                    @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
                    <li class="navlink">
                                <a href="#" class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                    </li>
                    @endif
                </ul>

                <div class="d-none" id="search_input">
                    <input type="text" class="form-control mb-2" name="search" id="search_layout"
                        placeholder="Search Items">
                    <button class="btn btn-secondary" id="search_btn">search</button>
                    <button class="btn btn-danger" id="clear_btn">clear</button>
                </div>
            </div>
            <div class="header_bottom">

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
                            <a onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                                    class="fa-solid fa-arrow-left"></i></a>
                        </div>
                        @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                            @if ($table_enable_status == 1 || $room_enable_status == 1)                                
                                <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                <i class="fa-solid fa-bell"></i>
                                            </a>
                            @endif
                        @endif

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
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}"
                                    width="70">
                            @endif
                        </div>
                    </a>

                    <div class="shop_info">
                        <h3>{!! $shop_name !!}</h3>
                        @if($is_sub_title == 1)
                                {!! $header_text_two !!}
                        @endif
                        @if(!empty($shop_start_time) && !empty($shop_end_time))
                                <label><b>{{ $header_text_one }}: </b>{{ $shop_start_time }} to {{ $shop_end_time }}</label>
                        @endif

                    </div>

                </div>
                <div class="back_service">
                    <a onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
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
                <!-- <a href="{{ route('shop.cart', $shop_slug) }}"
                    class="cart-btn  text-white text-decoration-none position-relative">
                    <i class="fa-solid fa-basket-shopping"></i>
                    <span class="qty-number">{{ $total_quantity }}</span>
                </a> -->
            </div>
        </div>
    </div>
</div>
</header>
</div>

@endif
@endsection

@section('content')

    <input type="hidden" name="tag_id" id="tag_id" value="">
    @if ($layout == 'layout_1')
        <section class="item_sec_main">
            <div class="container">
                <div class="item_box_main">

                    {{-- Categories Tabs --}}
                    @if ($category_effect == 'default')
                    <div class="category_slider position-relative">
                                <ul class="slider-item" id="myTab" role="tablist">

                                    @if (count($categories) > 0)
                                        @foreach ($categories as $cat)
                                            @php
                                                $active_cat = checkCategorySchedule($cat['id'], $cat['shop_id']);
                                                $check_cat_type_permission = checkCatTypePermission($cat['category_type'], $shop_details['id']);
                                            @endphp

                                            @if ($active_cat == 1)
                                                @if ($check_cat_type_permission == 1)
                                                    <li>
                                                        @if ($cat['category_type'] == 'link')
                                                            <a href="{{ $cat['link_url'] }}" target="_blank"
                                                                class="nav-link cat-btn">
                                                            @else
                                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $cat['id']]) }}"
                                                                    class="nav-link cat-btn {{ $cat['id'] == $current_cat_id ? 'active' : '' }}">
                                                        @endif
                                                        <div class="img_box text-center">
                                                            {{-- Img Section --}}
                                                            @if (
                                                                $cat['category_type'] == 'page' ||
                                                                    $cat['category_type'] == 'gallery' ||
                                                                    $cat['category_type'] == 'link' ||
                                                                    $cat['category_type'] == 'check_in' ||
                                                                    $cat['category_type'] == 'parent_category' ||
                                                                    $cat['category_type'] == 'pdf_page')
                                                                @if (!empty($cat['cover']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat->cover))
                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat['cover']) }}"
                                                                        class="w-100 mb-2">
                                                                @else
                                                                    <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}"
                                                                        class="w-100 mb-2">
                                                                @endif
                                                            @else
                                                                @php
                                                                    $cat_image = isset($cat['categoryImages'][0]['image']) ? $cat['categoryImages'][0]['image'] : '';
                                                                @endphp

                                                                @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                                        class="w-100 mb-2">
                                                                @else
                                                                    <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}"
                                                                        class="w-100 mb-2">
                                                                @endif
                                                            @endif
                                                            <span>{{ isset($cat[$name_key]) ? $cat[$name_key] : '' }}</span>
                                                        </div>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                                <button class="prev"><i class="fa-solid fa-angle-left"></i></button>
                                <button class="next"><i class="fa-solid fa-angle-right"></i></button>
                            </div>
                    @elseif(
                        $category_effect == 'wheel' ||
                            $category_effect == 'coverflow' ||
                            $category_effect == 'carousel' ||
                            $category_effect == 'flat')
                        <div id="coverflow">
                            <ul class="flip-items">
                                @if (count($categories) > 0)
                                    @foreach ($categories as $cat)
                                        @php
                                            $active_cat = checkCategorySchedule($cat['id'], $cat['shop_id']);
                                            $check_cat_type_permission = checkCatTypePermission($cat['category_type'], $shop_details['id']);
                                            $categoryImage = App\Models\CategoryImages::where('category_id',$cat['id'])->first();
                                        @endphp
                                        @if ($active_cat == 1)
                                            @if ($check_cat_type_permission == 1)
                                                <li data-flip-title="{{ $cat[$name_key] }}">
                                                    @if ($cat['category_type'] == 'link')
                                                        <a href="{{ $cat['link_url'] }}" target="_blank"
                                                            class="cate_item">
                                                        @else
                                                            <a href="{{ route('items.preview', [$shop_details['shop_slug'], $cat['id']]) }}"
                                                                class="cate_item">
                                                    @endif
                                                    @if (
                                                        $cat['category_type'] == 'page' ||
                                                            $cat['category_type'] == 'gallery' ||
                                                            $cat['category_type'] == 'link' ||
                                                            $cat['category_type'] == 'check_in' ||
                                                            $cat['category_type'] == 'parent_category' ||
                                                            $cat['category_type'] == 'pdf_page')
                                                        @if (!empty($cat['cover']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat['cover']))
                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat['cover']) }}"
                                                                class="w-100">
                                                        @else
                                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}"
                                                                class="w-100">
                                                        @endif
                                                    @else
                                                        @php
                                                        $cat_image = isset($categoryImage) ? $categoryImage->image : '';
                                                        @endphp

                                                        @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                                class="w-100">
                                                        @else
                                                            <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}"
                                                                class="w-100">
                                                        @endif
                                                    @endif
                                                    <span>{{ isset($cat[$name_key]) ? $cat[$name_key] : '' }}</span>

                                                    </a>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div class="page-div-main mt-4">
                        @if ($cat_details->category_type == 'page')
                            <div class="page-details">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        @php
                                            $page_cover_img = isset($cat_details->categoryImages[0]->image) ? $cat_details->categoryImages[0]->image : '';
                                        @endphp
                                        @if (
                                            !empty($page_cover_img) &&
                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $page_cover_img))
                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $page_cover_img) }}"
                                                class="w-100">
                                        @endif
                                    </div>
                                    <div class="col-md-12 mt-3 text-center">
                                        <h3>{{ $cat_details->$name_key }}</h3>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        {!! $cat_details->$description_key !!}
                                    </div>
                                </div>
                            </div>
                        @elseif($cat_details->category_type == 'gallery')
                            @php
                                $gallery_images = isset($cat_details->categoryImages) ? $cat_details->categoryImages : [];
                            @endphp
                            <div class="gallary-details">
                                <div class="row">
                                    <div class="col-md-12 mb-3 text-center">
                                        <h3>{{ $cat_details->$name_key }}</h3>
                                    </div>
                                    @if (count($gallery_images) > 0)
                                        @foreach ($gallery_images as $album)
                                            @if (!empty($album->image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image))
                                                {{-- <div class="col-md-3 mb-3">
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$album->image) }}" class="w-100">
                                            </div> --}}
                                                <div class="col-lg-3 col-md-6 image">
                                                    <div class="img-wrapper">
                                                        <a
                                                            href="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image) }}"><img
                                                                src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image) }}"
                                                                class="img-responsive"></a>
                                                        <div class="img-overlay">
                                                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @elseif($cat_details->category_type == 'pdf_page')
                            @php
                                $pdf_file = !empty($cat_details->file) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->file) ? asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->file) : '';
                            @endphp
                            <input type="hidden" name="pdf_url" id="pdf_url" value="{{ $pdf_file }}">
                            <div class="pdf-view">
                                <div class="row">
                                    <div class="col-md-12 mb-3 text-center">
                                        <h3>{{ $cat_details->$name_key }}</h3>
                                    </div>
                                    <div class="col-md-12" id="canvas_container">
                                    </div>
                                </div>
                            </div>
                        @elseif($cat_details->category_type == 'check_in')
                            <div class="check-in-page">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 mb-3 text-center">
                                        <h3>{{ $cat_details->$name_key }}</h3>
                                        <div class="check-in-page-desc">
                                            {!! $cat_details->$description_key !!}
                                        </div>
                                    </div>
                                    @php
                                        $check_page_styles = isset($cat_details->styles) && !empty($cat_details->styles) ? unserialize($cat_details->styles) : '';
                                    @endphp
                                    <div class="col-md-8 mb-3">
                                        <div class="check-in-form"
                                            style="background-color: {{ isset($check_page_styles['background_color']) ? $check_page_styles['background_color'] : '' }}">
                                            <form action="{{ route('do.check.in') }}" method="POST"
                                                enctype="multipart/form-data">
                                                <input type="hidden" name="store_id" id="store_id"
                                                    value="{{ $shop_details['id'] }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label for="firstname" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">First
                                                            Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="firstname" id="firstname"
                                                            placeholder="Enter Your First Name"
                                                            class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}">
                                                        @if ($errors->has('firstname'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('firstname') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="lastname" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Last
                                                            Name <span class="text-danger">*</span></label>
                                                        <input type="text" name="lastname" id="lastname"
                                                            placeholder="Enter Your Last Name"
                                                            class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}">
                                                        @if ($errors->has('lastname'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('lastname') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="email" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Email
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" name="email" id="email"
                                                            placeholder="Enter Your Email"
                                                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                                        @if ($errors->has('email'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('email') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="phone" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Phone
                                                            No. <span class="text-danger">*</span></label>
                                                        <input type="number" name="phone" id="phone"
                                                            placeholder="Enter Your Phone No."
                                                            class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                                                        @if ($errors->has('phone'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('phone') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label for="passport" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Passport
                                                            No. <span class="text-danger">*</span></label>
                                                        <input type="text" name="passport" id="passport"
                                                            placeholder="Enter Your Passport No."
                                                            class="form-control {{ $errors->has('passport') ? 'is-invalid' : '' }}">
                                                        @if ($errors->has('passport'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('passport') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="room_number" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Room
                                                            No. <span class="text-danger">*</span></label>
                                                        <input type="text" name="room_number" id="room_number"
                                                            class="form-control {{ $errors->has('room_number') ? 'is-invalid' : '' }}"
                                                            placeholder="Enter Your Room No.">
                                                        @if ($errors->has('room_number'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('room_number') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <label for="nationality" class="form-label"
                                                            style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Nationality
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" name="nationality" id="nationality"
                                                            placeholder="Enter Your Nationality"
                                                            class="form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}">
                                                        @if ($errors->has('nationality'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('nationality') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-2">
                                                                    <label for="date_of_birth" class="form-label"
                                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Date
                                                                        of Birth <span class="text-danger">*</span></label>
                                                                    <input type="date" name="date_of_birth"
                                                                        id="date_of_birth"
                                                                        class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}">
                                                                    @if ($errors->has('date_of_birth'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('date_of_birth') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group mb-2">
                                                                    <label for="arrival_date" class="form-label"
                                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Arrival
                                                                        Date <span class="text-danger">*</span></label>
                                                                    <input type="datetime-local" name="arrival_date"
                                                                        id="arrival_date"
                                                                        class="form-control {{ $errors->has('arrival_date') ? 'is-invalid' : '' }}">
                                                                    @if ($errors->has('arrival_date'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('arrival_date') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group mb-2">
                                                                    <label for="departure_date" class="form-label"
                                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Departure
                                                                        Date <span class="text-danger">*</span></label>
                                                                    <input type="datetime-local" name="departure_date"
                                                                        id="departure_date"
                                                                        class="form-control {{ $errors->has('departure_date') ? 'is-invalid' : '' }}">
                                                                    @if ($errors->has('departure_date'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('departure_date') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="residence_address" class="form-label"
                                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Residence
                                                                        Address <span class="text-danger">*</span></label>
                                                                    <input type="text" name="residence_address"
                                                                        id="residence_address"
                                                                        class="form-control {{ $errors->has('residence_address') ? 'is-invalid' : '' }}"
                                                                        placeholder="Enter Your Residence Address.">
                                                                    @if ($errors->has('residence_address'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('residence_address') }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <div class="form-group">
                                                                    <label for="message" class="form-label"
                                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Message</label>
                                                                    <textarea placeholder="Write Your Message here..." name="message" id="message" class="w-100 form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-center mt-2">
                                                        <button class="btn btn-sm"
                                                            style="background-color: {{ isset($check_page_styles['button_color']) ? $check_page_styles['button_color'] : '#198754' }}; color: {{ isset($check_page_styles['button_text_color']) ? $check_page_styles['button_text_color'] : '#fff' }}">SUBMIT</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                    <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><a
                                            target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i
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

        <a class="back_bt" onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                class="fa-solid fa-chevron-left"></i></a>
    @elseif($layout == 'layout_2')
        <section class="category_section_inr">

            <div class="owl-carousel owl-carousel-stacked" id="owl-carousel">
                @if (count($categories) > 0)
                    @foreach ($categories as $cat)
                    @php
                            $active_cat = checkCategorySchedule($cat->id, $cat->shop_id);
                            $check_cat_type_permission = checkCatTypePermission($cat->category_type, $shop_details['id']);
                    @endphp
                    @if($active_cat == 1)
                        @if($check_cat_type_permission == 1)

                            <div class="item" data-flip-title="{{ $cat->$name_key }}">
                                @if ($cat->category_type == 'link')
                                    <a href="{{ $cat->link_url }}" target="_blank" class="cate_item">
                                @else
                                        <a href="{{ route('items.preview', [$shop_details['shop_slug'], $cat->id]) }}" class="cate_item">
                                @endif
                                @if (
                                            $cat->category_type == 'page' ||
                                                $cat->category_type == 'gallery' ||
                                                $cat->category_type == 'link' ||
                                                $cat->category_type == 'check_in' ||
                                                $cat->category_type == 'parent_category' ||
                                                $cat->category_type == 'pdf_page')
                                                @if (!empty($cat->cover) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat->cover))
                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat->cover) }}">
                                            @else
                                                <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}">
                                            @endif
                                @else
                                        @php
                                                $cat_image = isset($cat->categoryImages[0]['image']) ? $cat->categoryImages[0]['image'] : '';
                                            @endphp

                                            @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}">
                                            @else
                                                <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}">
                                            @endif

                                @endif
                                        <span>{{ isset($cat->$name_key) ? $cat->$name_key : '' }}</span>
                                </a>
                            </div>
                        @endif
                    @endif
                    @endforeach
                @endif

            </div>

            <div class="page-div-main mt-4">
                @if ($cat_details->category_type == 'page')
                    <div class="page-details">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                @php
                                    $page_cover_img = isset($cat_details->categoryImages[0]->image) ? $cat_details->categoryImages[0]->image : '';
                                @endphp
                                @if (
                                    !empty($page_cover_img) &&
                                        file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $page_cover_img))
                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $page_cover_img) }}"
                                        class="w-100">
                                @endif
                            </div>
                            <div class="col-md-12 mt-3 text-center">
                                <h3>{{ $cat_details->$name_key }}</h3>
                            </div>
                            <div class="col-md-12 mt-3">
                                {!! $cat_details->$description_key !!}
                            </div>
                        </div>
                    </div>
                @elseif($cat_details->category_type == 'gallery')
                    @php
                        $gallery_images = isset($cat_details->categoryImages) ? $cat_details->categoryImages : [];
                    @endphp
                    <div class="gallary-details">
                        <div class="row">
                            <div class="col-md-12 mb-3 text-center">
                                <h3>{{ $cat_details->$name_key }}</h3>
                            </div>
                            @if (count($gallery_images) > 0)
                                @foreach ($gallery_images as $album)
                                    @if (!empty($album->image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image))
                                        {{-- <div class="col-md-3 mb-3">
                                                <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$album->image) }}" class="w-100">
                                            </div> --}}
                                        <div class="col-lg-3 col-md-6 image">
                                            <div class="img-wrapper">
                                                <a
                                                    href="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image) }}"><img
                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image) }}"
                                                        class="img-responsive"></a>
                                                <div class="img-overlay">
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @elseif($cat_details->category_type == 'pdf_page')
                    @php
                        $pdf_file = !empty($cat_details->file) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->file) ? asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->file) : '';
                    @endphp
                    <input type="hidden" name="pdf_url" id="pdf_url" value="{{ $pdf_file }}">
                    <div class="pdf-view">
                        <div class="row">
                            <div class="col-md-12 mb-3 text-center">
                                <h3>{{ $cat_details->$name_key }}</h3>
                            </div>
                            <div class="col-md-12" id="canvas_container">
                            </div>
                        </div>
                    </div>
                @elseif($cat_details->category_type == 'check_in')
                    <div class="check-in-page">
                        <div class="row justify-content-center">
                            <div class="col-md-12 mb-3 text-center">
                                <h3>{{ $cat_details->$name_key }}</h3>
                                <div class="check-in-page-desc">
                                    {!! $cat_details->$description_key !!}
                                </div>
                            </div>
                            @php
                                $check_page_styles = isset($cat_details->styles) && !empty($cat_details->styles) ? unserialize($cat_details->styles) : '';
                            @endphp
                            <div class="col-md-8 mb-3">
                                <div class="check-in-form"
                                    style="background-color: {{ isset($check_page_styles['background_color']) ? $check_page_styles['background_color'] : '' }}">
                                    <form action="{{ route('do.check.in') }}" method="POST"
                                        enctype="multipart/form-data">
                                        <input type="hidden" name="store_id" id="store_id"
                                            value="{{ $shop_details['id'] }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label for="firstname" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">First
                                                    Name <span class="text-danger">*</span></label>
                                                <input type="text" name="firstname" id="firstname"
                                                    placeholder="Enter Your First Name"
                                                    class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}">
                                                @if ($errors->has('firstname'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('firstname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="lastname" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Last
                                                    Name <span class="text-danger">*</span></label>
                                                <input type="text" name="lastname" id="lastname"
                                                    placeholder="Enter Your Last Name"
                                                    class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}">
                                                @if ($errors->has('lastname'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('lastname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="email" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Email
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" name="email" id="email"
                                                    placeholder="Enter Your Email"
                                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="phone" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Phone
                                                    No. <span class="text-danger">*</span></label>
                                                <input type="number" name="phone" id="phone"
                                                    placeholder="Enter Your Phone No."
                                                    class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                                                @if ($errors->has('phone'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('phone') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="passport" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Passport
                                                    No. <span class="text-danger">*</span></label>
                                                <input type="text" name="passport" id="passport"
                                                    placeholder="Enter Your Passport No."
                                                    class="form-control {{ $errors->has('passport') ? 'is-invalid' : '' }}">
                                                @if ($errors->has('passport'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('passport') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="room_number" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Room
                                                    No. <span class="text-danger">*</span></label>
                                                <input type="text" name="room_number" id="room_number"
                                                    class="form-control {{ $errors->has('room_number') ? 'is-invalid' : '' }}"
                                                    placeholder="Enter Your Room No.">
                                                @if ($errors->has('room_number'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('room_number') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="nationality" class="form-label"
                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Nationality
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" name="nationality" id="nationality"
                                                    placeholder="Enter Your Nationality"
                                                    class="form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}">
                                                @if ($errors->has('nationality'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('nationality') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-2">
                                                            <label for="date_of_birth" class="form-label"
                                                                style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Date
                                                                of Birth <span class="text-danger">*</span></label>
                                                            <input type="date" name="date_of_birth" id="date_of_birth"
                                                                class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}">
                                                            @if ($errors->has('date_of_birth'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('date_of_birth') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label for="arrival_date" class="form-label"
                                                                style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Arrival
                                                                Date <span class="text-danger">*</span></label>
                                                            <input type="datetime-local" name="arrival_date"
                                                                id="arrival_date"
                                                                class="form-control {{ $errors->has('arrival_date') ? 'is-invalid' : '' }}">
                                                            @if ($errors->has('arrival_date'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('arrival_date') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group mb-2">
                                                            <label for="departure_date" class="form-label"
                                                                style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Departure
                                                                Date <span class="text-danger">*</span></label>
                                                            <input type="datetime-local" name="departure_date"
                                                                id="departure_date"
                                                                class="form-control {{ $errors->has('departure_date') ? 'is-invalid' : '' }}">
                                                            @if ($errors->has('departure_date'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('departure_date') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="residence_address" class="form-label"
                                                                style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Residence
                                                                Address <span class="text-danger">*</span></label>
                                                            <input type="text" name="residence_address"
                                                                id="residence_address"
                                                                class="form-control {{ $errors->has('residence_address') ? 'is-invalid' : '' }}"
                                                                placeholder="Enter Your Residence Address.">
                                                            @if ($errors->has('residence_address'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('residence_address') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <div class="form-group">
                                                            <label for="message" class="form-label"
                                                                style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Message</label>
                                                            <textarea placeholder="Write Your Message here..." name="message" id="message" class="w-100 form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center mt-2">
                                                <button class="btn btn-sm"
                                                    style="background-color: {{ isset($check_page_styles['button_color']) ? $check_page_styles['button_color'] : '#198754' }}; color: {{ isset($check_page_styles['button_text_color']) ? $check_page_styles['button_text_color'] : '#fff' }}">SUBMIT</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
                                        <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><a
                                                target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i
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

            {{-- <a class="back_bt" onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                    class="fa-solid fa-chevron-left"></i></a> --}}

        </section>
    @else
        <section class="sec_main menu_section">
            <div class="container">

                <div class="page-div-main mt-4">
                    @if ($cat_details->category_type == 'page')
                        <div class="page-details">
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    @php
                                        $page_cover_img = isset($cat_details->categoryImages[0]->image) ? $cat_details->categoryImages[0]->image : '';
                                    @endphp
                                    @if (
                                        !empty($page_cover_img) &&
                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $page_cover_img))
                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $page_cover_img) }}"
                                            class="w-100">
                                    @endif
                                </div>
                                <div class="col-md-12 mt-3 text-center">
                                    <h3>{{ $cat_details->$name_key }}</h3>
                                </div>
                                <div class="col-md-12 mt-3">
                                    {!! $cat_details->$description_key !!}
                                </div>
                            </div>
                        </div>
                    @elseif($cat_details->category_type == 'gallery')
                        @php
                            $gallery_images = isset($cat_details->categoryImages) ? $cat_details->categoryImages : [];
                        @endphp
                        <div class="gallary-details">
                            <div class="row">
                                <div class="col-md-12 mb-3 text-center">
                                    <h3>{{ $cat_details->$name_key }}</h3>
                                </div>
                                @if (count($gallery_images) > 0)
                                    @foreach ($gallery_images as $album)
                                        @if (!empty($album->image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image))
                                            <div class="col-lg-3 col-md-6 image">
                                                <div class="img-wrapper">
                                                    <a
                                                        href="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image) }}"><img
                                                            src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $album->image) }}"
                                                            class="img-responsive"></a>
                                                    <div class="img-overlay">
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @elseif($cat_details->category_type == 'pdf_page')
                        @php
                            $pdf_file = !empty($cat_details->file) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->file) ? asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->file) : '';
                        @endphp
                        <input type="hidden" name="pdf_url" id="pdf_url" value="{{ $pdf_file }}">
                        <div class="pdf-view">
                            <div class="title text-center">
                                <h3>{{ $cat_details->$name_key }}</h3>
                            </div>
                            <div class="row">
                                <div class="col-md-12" id="canvas_container">
                                </div>
                            </div>
                        </div>
                    @elseif($cat_details->category_type == 'check_in')
                        <div class="check-in-page">
                            <div class="row justify-content-center">
                                <div class="col-md-12 mb-3 text-center">
                                    <h3>{{ $cat_details->$name_key }}</h3>
                                    <div class="check-in-page-desc">
                                        {!! $cat_details->$description_key !!}
                                    </div>
                                </div>
                                @php
                                    $check_page_styles = isset($cat_details->styles) && !empty($cat_details->styles) ? unserialize($cat_details->styles) : '';
                                @endphp
                                <div class="col-md-8 mb-3">
                                    <div class="check-in-form"
                                        style="background-color: {{ isset($check_page_styles['background_color']) ? $check_page_styles['background_color'] : '' }}">
                                        <form action="{{ route('do.check.in') }}" method="POST"
                                            enctype="multipart/form-data">
                                            <input type="hidden" name="store_id" id="store_id"
                                                value="{{ $shop_details['id'] }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label for="firstname" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">First
                                                        Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="firstname" id="firstname"
                                                        placeholder="Enter Your First Name"
                                                        class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('firstname'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('firstname') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="lastname" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Last
                                                        Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="lastname" id="lastname"
                                                        placeholder="Enter Your Last Name"
                                                        class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('lastname'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('lastname') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="email" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Email
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" name="email" id="email"
                                                        placeholder="Enter Your Email"
                                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('email'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('email') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="phone" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Phone
                                                        No. <span class="text-danger">*</span></label>
                                                    <input type="number" name="phone" id="phone"
                                                        placeholder="Enter Your Phone No."
                                                        class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('phone'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('phone') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label for="passport" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Passport
                                                        No. <span class="text-danger">*</span></label>
                                                    <input type="text" name="passport" id="passport"
                                                        placeholder="Enter Your Passport No."
                                                        class="form-control {{ $errors->has('passport') ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('passport'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('passport') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="room_number" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Room
                                                        No. <span class="text-danger">*</span></label>
                                                    <input type="text" name="room_number" id="room_number"
                                                        class="form-control {{ $errors->has('room_number') ? 'is-invalid' : '' }}"
                                                        placeholder="Enter Your Room No.">
                                                    @if ($errors->has('room_number'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('room_number') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label for="nationality" class="form-label"
                                                        style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Nationality
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" name="nationality" id="nationality"
                                                        placeholder="Enter Your Nationality"
                                                        class="form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}">
                                                    @if ($errors->has('nationality'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('nationality') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-2">
                                                                <label for="date_of_birth" class="form-label"
                                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Date
                                                                    of Birth <span class="text-danger">*</span></label>
                                                                <input type="date" name="date_of_birth"
                                                                    id="date_of_birth"
                                                                    class="form-control {{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}">
                                                                @if ($errors->has('date_of_birth'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('date_of_birth') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <label for="arrival_date" class="form-label"
                                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Arrival
                                                                    Date <span class="text-danger">*</span></label>
                                                                <input type="datetime-local" name="arrival_date"
                                                                    id="arrival_date"
                                                                    class="form-control {{ $errors->has('arrival_date') ? 'is-invalid' : '' }}">
                                                                @if ($errors->has('arrival_date'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('arrival_date') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <label for="departure_date" class="form-label"
                                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Departure
                                                                    Date <span class="text-danger">*</span></label>
                                                                <input type="datetime-local" name="departure_date"
                                                                    id="departure_date"
                                                                    class="form-control {{ $errors->has('departure_date') ? 'is-invalid' : '' }}">
                                                                @if ($errors->has('departure_date'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('departure_date') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="residence_address" class="form-label"
                                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Residence
                                                                    Address <span class="text-danger">*</span></label>
                                                                <input type="text" name="residence_address"
                                                                    id="residence_address"
                                                                    class="form-control {{ $errors->has('residence_address') ? 'is-invalid' : '' }}"
                                                                    placeholder="Enter Your Residence Address.">
                                                                @if ($errors->has('residence_address'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('residence_address') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label for="message" class="form-label"
                                                                    style="color: {{ isset($check_page_styles['font_color']) ? $check_page_styles['font_color'] : '' }}">Message</label>
                                                                <textarea placeholder="Write Your Message here..." name="message" id="message" class="w-100 form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-center mt-2">
                                                    <button class="btn btn-sm"
                                                        style="background-color: {{ isset($check_page_styles['button_color']) ? $check_page_styles['button_color'] : '#198754' }}; color: {{ isset($check_page_styles['button_text_color']) ? $check_page_styles['button_text_color'] : '#fff' }}">SUBMIT</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                                            <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><a
                                                    target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i
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
            </div>
        </section>
    @endif
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>

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

        @if (Session::has('errors'))
            toastr.error('Please Check Form Carefully!')
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif


        // PDF Load
        var pdf_url = $('#pdf_url').val();
        var pdfFile = null;
        var scale = 1.7;

        if (pdf_url != undefined) {
            loadPdf();
        }

        function loadPdf() {
            pdfjsLib.getDocument($('#pdf_url').val()).then((pdf) => {
                pdfFile = pdf;
                viewer = document.getElementById('canvas_container');
                for (page = 1; page <= pdf.numPages; page++) {
                    canvas = document.createElement("canvas");
                    canvas.className = 'pdf-page-canvas';
                    canvas.id = 'pdf-page-canvas-' + page;
                    viewer.appendChild(canvas);
                    render(page, canvas);
                }
            });
        }

        // Rendering Pdf Pages
        function render(currPage, currCanvas) {
            pdfFile.getPage(currPage).then((page) => {
                var mycan = document.getElementById("pdf-page-canvas-" + currPage);
                var ctx = mycan.getContext('2d');

                var viewport = page.getViewport(scale);
                mycan.width = viewport.width;
                mycan.height = viewport.height;

                page.render({
                    canvasContext: ctx,
                    viewport: viewport
                });
            });
        }


        $(function() {

            var category_effect = "{{ $category_effect }}";

            var category_effect = "{{ $category_effect }}";
            if(category_effect == 'coverflow'){
                var spacing = -0.7;
            }else if(category_effect == 'wheel'){
                var spacing = -0.065;
            }else if(category_effect == 'carousel'){
                var spacing = -0.5;
            }else if(category_effect == 'flat'){
                var spacing = -0.25;
            }

            var selectedCategoryId = "{{ $current_cat_id }}";

            // Find the index of the selected category in the array
            $("#coverflow").flipster({

                // Container for the flippin' items.
                itemContainer: 'ul',
                // Selector for children of itemContainer to flip
                itemSelector: 'li',
                style: category_effect,
                // start:'center',
                // start: selectedIndex,
                // Fading speed
                fadeIn: 400,
                loop: false,
                autoplay: false,
                pauseOnHover: true,
                spacing: spacing,
                click: false,
                keyboard: true,
                scrollwheel: true,
                touch: true,
                enableTouch: true,
                nav: false,
                buttons: true,
                buttonPrev: 'Previous',
                buttonNext: 'next',
                onItemSwitch: $.noop

            });

            const slider = $(".slider-item");
                slider.slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 1,
                prevArrow: $('.prev'),
                nextArrow: $('.next'),
                responsive: [
                    {
                        breakpoint: 991,
                        settings: {
                        slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                        slidesToShow: 2,
                        }
                    }
                    ]

                });

                    slider.on("wheel", function (e) {
                    e.preventDefault();

                    if (e.originalEvent.deltaY < 0) {
                        $(this).slick("slickPrev");
                    } else {
                        $(this).slick("slickNext");
                    }
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




        $(document).ready(function() {

            @if ($layout == 'layout_2')
                var selectedIndex = {{ json_encode(array_keys($categories->pluck('id')->toArray(), $current_cat_id)[0] ?? 0) }};
            @else
                var selectedIndex = 0;
            @endif

            $(".owl-carousel-stacked").on(
                "dragged.owl.carousel translated.owl.carousel initialized.owl.carousel",
                function(e) {
                    $(".center").prev().addClass("left-of-center");
                    $(".center").next().addClass("right-of-center");
            });

            $(".owl-carousel-stacked").on("drag.owl.carousel", function(e) {
                $(".left-of-center").removeClass("left-of-center");
                $(".right-of-center").removeClass("right-of-center");
            });


            $(".owl-carousel-stacked").owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                items: 3,
                center: true,
                mouseDrag: true,
                touchDrag: false,
                click: true,
                keyboard: true,
                scrollwheel: true,
                touch: true,
                pullDrag: false,
                autoplay: false,
                navText: [
                    '<span class="fa-stack fa-lg"><i class="fa fa-circle-thin fa-stack-2x" ></i><i class="fa fa-caret-left fa-stack-1x"></i></span>',
                    '<span class="fa-stack fa-lg"><i class="fa fa-circle-thin fa-stack-2x" ></i><i class="fa fa-caret-right fa-stack-1x"></i></span>'
                ],
                startPosition: selectedIndex
            });

            $(".owl-carousel-stacked").on("translate.owl.carousel", function(e) {
                $(".left-of-center").removeClass("left-of-center");
                $(".right-of-center").removeClass("right-of-center");
            });

            $(".owl-carousel-stacked").on("mousewheel", function (event) {
                if (event.originalEvent.deltaY < 0) {
                    $(this).trigger("next.owl.carousel");
                } else {
                    $(this).trigger("prev.owl.carousel");
                }
                event.preventDefault();
            });
            });


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

    </script>

@endsection
