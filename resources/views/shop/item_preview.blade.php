@php
    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);

    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Intro Second
    $intro_second = isset($shop_settings['intro_icon_duration']) && !empty($shop_settings['intro_icon_duration']) ? $shop_settings['intro_icon_duration'] : '';

    // Shop Currency
    $currency = isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency']) ? $shop_settings['default_currency'] : 'EUR';

    // Shop Name
    $shop_name = isset($shop_details['name']) && !empty($shop_details['name']) ? $shop_details['name'] : '';
    $shop_desc = isset($shop_details['description']) && !empty($shop_details['description']) ? strip_tags($shop_details['description']) : '';

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');

    // Default Image
    $default_image = asset('public/client_images/not-found/no_image_1.jpg');

    // Shop Logo
    $shop_logo = isset($shop_settings['shop_view_header_logo']) && !empty($shop_settings['shop_view_header_logo']) ? $shop_settings['shop_view_header_logo'] : '';

    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Column Keys
    $name_key = $current_lang_code . '_name';
    $description_key = $current_lang_code . '_description';
    $calories_key = $current_lang_code . '_calories';
    $price_label_key = $current_lang_code . '_label';
    $title_key = $current_lang_code."_title";

    // Header Text One
    $header_text_one = moreTranslations($shop_details['id'], 'header_text_1');
    $header_text_one = isset($header_text_one[$current_lang_code. '_value']) && !empty($header_text_one[$current_lang_code . '_value']) ? $header_text_one[$current_lang_code . '_value'] : '';

    $header_text_two = moreTranslations($shop_details['id'], 'header_text_2');
    $header_text_two = isset($header_text_two[$current_lang_code. '_value']) && !empty($header_text_two[$current_lang_code . '_value']) ? $header_text_two[$current_lang_code . '_value'] : '';

    //shop time
    $shop_start_time = isset($shop_settings['shop_start_time']) && !empty($shop_settings['shop_start_time']) ? $shop_settings['shop_start_time'] : '';
    $shop_end_time = isset($shop_settings['shop_end_time']) && !empty($shop_settings['shop_end_time']) ? $shop_settings['shop_end_time'] : '';


    // Shop Title
    $shop_subtitle = isset($shop_settings['business_subtitle']) && !empty($shop_settings['business_subtitle']) ? $shop_settings['business_subtitle'] : '';
    // Current Category
    $current_cat_id = isset($cat_details['id']) ? $cat_details['id'] : '';

    $cat_parent_id = isset($cat_details['parent_id']) ? $cat_details['parent_id'] : '';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);
    $slider_buttons = isset($theme_settings['banner_slide_button']) && !empty($theme_settings['banner_slide_button']) ? $theme_settings['banner_slide_button'] : 0;
    $slider_delay_time = isset($theme_settings['banner_delay_time']) && !empty($theme_settings['banner_delay_time']) ? $theme_settings['banner_delay_time'] : 3000;
    $banner_height = isset($theme_settings['banner_height']) && !empty($theme_settings['banner_height']) ? $theme_settings['banner_height'] : 250;
    $special_day_effect_box = isset($theme_settings['special_day_effect_box']) && !empty($theme_settings['special_day_effect_box']) ? $theme_settings['special_day_effect_box'] : 'blink';

    // Get Banner Settings
    $shop_banners = getBanners($shop_details['id']);
    $shop_banner_count = (count($shop_banners) == 1) ? 'false' : 'true';
    $banner_key = $language_details['code'] . '_image';
    $banner_text_key = $language_details['code'] . '_description';

    // Layout
    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

    $effect = isset($theme_settings['slider_effect']) ? $theme_settings['slider_effect'] : 'fabe';

    $category_effect = (isset($theme_settings['category_slider_effect']) && !empty($theme_settings['category_slider_effect'])) ? $theme_settings['category_slider_effect'] : 'default';

    // Total Amount
    $total_amount = 0;

    // Read More Label
    $read_more_label = moreTranslations($shop_details['id'], 'read_more_link_label');
    $read_more_label = isset($read_more_label[$current_lang_code . '_value']) && !empty($read_more_label[$current_lang_code . '_value']) ? $read_more_label[$current_lang_code . '_value'] : 'Read More';

    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'], 'homepage_intro');
    $homepage_intro = isset($homepage_intro[$current_lang_code . '_value']) && !empty($homepage_intro[$current_lang_code . '_value']) ? $homepage_intro[$current_lang_code . '_value'] : '';

    // Item Devider
    $item_devider = isset($theme_settings['item_divider']) && !empty($theme_settings['item_divider']) ? $theme_settings['item_divider'] : 0;
     $stiky_header =  isset($theme_settings['sticky_header']) && !empty($theme_settings['sticky_header']) ? $theme_settings['sticky_header']  : '';

    // Today Special Icon
    $today_special_icon = moreTranslations($shop_details['id'], 'today_special_icon');
    $today_special_icon = isset($today_special_icon[$current_lang_code . '_value']) && !empty($today_special_icon[$current_lang_code . '_value']) ? $today_special_icon[$current_lang_code . '_value'] : '';

    // Admin Settings
    $admin_settings = getAdminSettings();
    $default_special_image = isset($admin_settings['default_special_item_image']) ? $admin_settings['default_special_item_image'] : '';
    $shop_desc = html_entity_decode($shop_desc);

    $cat_name = isset($cat_details[$name_key]) ? $cat_details[$name_key] : '';
    $shop_title = "$shop_name | $cat_name";

    $shop_id = isset($shop_details['id']) ? $shop_details['id'] : '';

    // Get Language Settings
    $language_settings = clientLanguageSettings($shop_id);

    $google_translate = isset($language_settings['google_translate']) ? $language_settings['google_translate'] : 0;
    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_details['id']);

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';
    $logo_position = isset($theme_settings['logo_position']) ? $theme_settings['logo_position'] : '';
    $search_box_position = isset($theme_settings['search_box_position']) ? $theme_settings['search_box_position'] : '';

    // Shop Logo
    $shop_logo = isset($shop_settings['shop_view_header_logo']) && !empty($shop_settings['shop_view_header_logo']) ? $shop_settings['shop_view_header_logo'] : '';

    // Cart Quantity
    $total_quantity = getCartQuantity();

    // Cart Item
    $cart = session()->get('cart', []);

    foreach ($cart as $cart_key => $cart_data) {
        foreach ($cart_data as $cart_val) {
            foreach ($cart_val as $cart_item_key => $cart_item) {
                $total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
            }
        }
    }

     $order_settings = getOrderSettings($shop_id);

     $special_discount_type = isset($order_settings['discount_type']) ? $order_settings['discount_type'] : '';
     $special_discount_percentage = isset($order_settings['discount_percentage']) ? $order_settings['discount_percentage'] : '';


     if ($special_discount_type == 'percentage') {
            $special_discount = $special_discount_percentage . '%';
    } else {
        // Assuming $special_discount_percentage is a string representing the discount amount
        $special_discount_float = (float)$special_discount_percentage;

        // Format the float value and add the currency symbol using Currency::format()
        $special_discount = '-' . Currency::currency($currency)->format($special_discount_float);
    }


    $waiter_call_status = isset($shop_settings['waiter_call_status']) ?  $shop_settings['waiter_call_status'] : '0';
    $is_sub_title = isset($shop_settings['is_sub_title']) ? $shop_settings['is_sub_title'] : '0';

    $table_enable_status = (isset($shop_settings['table_enable_status']) && !empty($shop_settings['table_enable_status'])) ? $shop_settings['table_enable_status'] : 0;
    $room_enable_status = (isset($shop_settings['room_enable_status']) && !empty($shop_settings['room_enable_status'])) ? $shop_settings['room_enable_status'] : 0;

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
                                                    <a href="{{ route('shop.cart', $shop_slug) }}" class="btn orderup_button">Complete Order</a>
                                                </div>
                                            </div>
                                        @endif
                                @endif
                                <div class="barger_menu_main">
                                    <div class="barger_menu_inner">
                                        <div class="barger_menu_icon">
                                            <i class="fa-solid fa-bars"></i>
                                        </div>
                                        <div class="barger_menu_list">
                                            <ul>
                                                <li>
                                                    <div class="barger_menu_box">
                                                        <button class="btn search_bt openSearchBox" id="openSearchBox">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn search_bt closeSearchBox d-none" id="closeSearchBox">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                                                    @if ($table_enable_status == 1 || $room_enable_status == 1)                                                        
                                                        <li>
                                                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                                <i class="fa-solid fa-bell"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                                <li>
                                                    <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                                </li>
                                            @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- <button class="btn search_bt" id="openSearchBox">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button class="btn search_bt d-none" id="closeSearchBox">
                                    <i class="fa-solid fa-times"></i>
                                </button> -->
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
                                                    <a href="{{ route('shop.cart', $shop_slug) }}" class="btn orderup_button">Complete Order</a>
                                                </div>
                                            </div>
                                        @endif
                                @endif

                                <div class="barger_menu_main">
                                    <div class="barger_menu_inner">
                                        <div class="barger_menu_icon">
                                            <i class="fa-solid fa-bars"></i>
                                        </div>
                                        <div class="barger_menu_list">
                                            <ul>
                                                <li>
                                                    <div class="barger_menu_box">
                                                        <button class="btn search_bt openSearchBox" id="openSearchBox">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn search_bt closeSearchBox d-none" id="closeSearchBox">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                                                    @if ($table_enable_status == 1 || $room_enable_status == 1)                                                        
                                                        <li>
                                                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                                <i class="fa-solid fa-bell"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                                    <li>
                                                        <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- <button class="btn search_bt" id="openSearchBox">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button class="btn search_bt d-none" id="closeSearchBox">
                                    <i class="fa-solid fa-times"></i>
                                </button> -->
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
                                                        <a  onclick="getCartDetails({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }}, {{ $shop_id }})"><i class="fa-solid fa-pencil"></i></a>
                                                        <a onclick="removeCartItem({{ $cart_item['item_id'] }},{{ $cart_item['option_id'] }},{{ $cart_item_key }})" class="text-danger"><i class="fa-solid fa-circle-xmark"></i></a>
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
                                <div class="barger_menu_main">
                                    <div class="barger_menu_inner">
                                        <div class="barger_menu_icon">
                                            <i class="fa-solid fa-bars"></i>
                                        </div>
                                        <div class="barger_menu_list">
                                            <ul>
                                                <li>
                                                    <div class="barger_menu_box">
                                                        <button class="btn search_bt openSearchBox" id="openSearchBox">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn search_bt closeSearchBox d-none" id="closeSearchBox">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                                                    @if ($table_enable_status == 1 || $room_enable_status == 1)                                                        
                                                        <li>
                                                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                                <i class="fa-solid fa-bell"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                                    <li>
                                                        <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- <button class="btn search_bt" id="openSearchBox">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button class="btn search_bt d-none" id="closeSearchBox">
                                    <i class="fa-solid fa-times"></i>
                                </button> -->
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
                            <div class="barger_menu_main">
                                    <div class="barger_menu_inner">
                                        <div class="barger_menu_icon">
                                            <i class="fa-solid fa-bars"></i>
                                        </div>
                                        <div class="barger_menu_list">
                                            <ul>
                                                <li>
                                                    <div class="barger_menu_box">
                                                        <button class="btn search_bt openSearchBox" id="openSearchBox">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn search_bt closeSearchBox d-none" id="closeSearchBox">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                                                    @if ($table_enable_status == 1 || $room_enable_status == 1)                                                        
                                                        <li>
                                                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                                <i class="fa-solid fa-bell"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                                    <li>
                                                        <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <!-- <button class="btn search_bt" id="openSearchBox">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <button class="btn search_bt d-none" id="closeSearchBox">
                                <i class="fa-solid fa-times"></i>
                            </button> -->
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
                                <img src="{{ $default_logo }}" class="50px">
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

                <div class="category_ul">
                        <ul>
                            @if (count($categories_parent) > 0)
                                @foreach ($categories_parent as $parent_category)
                                    @php
                                        $active_parent_cat = checkCategorySchedule($parent_category->id, $parent_category->shop_id);
                                        $parent_name_code = $current_lang_code . '_name';
                                        $parent_nameId = str_replace(' ', '_', $parent_category->en_name);
                                        $check_parent_cat_type_permission = checkCatTypePermission($parent_category->category_type, $shop_details['id']);
                                        $child_categories = getChildCategories($parent_category->id); // Assuming a function to get child categories
                                    @endphp
                                    @if ($active_parent_cat == 1 && $check_parent_cat_type_permission == 1)
                                        <li>
                                            @if ($parent_category->category_type == 'link')
                                                <a href="{{ $parent_category->link_url }}" target="_blank" class="cat-btn">
                                            @elseif($parent_category->category_type == 'parent_category')
                                                <a href="{{ route('restaurant', [$shop_slug, $parent_category->id]) }}">
                                            @else
                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $parent_category->id]) }}"
                                                    class="cat-btn {{ $parent_category->id == $current_cat_id ? 'active' : '' }}">
                                            @endif
                                            {{ isset($parent_category->$parent_name_code) ? $parent_category->$parent_name_code : '' }}
                                            </a>
                                            @if(count($child_categories) > 0)
                                                <ul class="sub_category"> <!-- Start Child Categories -->
                                                    @foreach($child_categories as $child_category)
                                                        @php
                                                            $active_child_cat = checkCategorySchedule($child_category->id, $child_category->shop_id);
                                                            $child_name_code = $current_lang_code . '_name';
                                                            $child_nameId = str_replace(' ', '_', $child_category->en_name);
                                                            $check_child_cat_type_permission = checkCatTypePermission($child_category->category_type, $shop_details['id']);
                                                        @endphp
                                                        @if ($active_child_cat == 1 && $check_child_cat_type_permission == 1)
                                                            <li>
                                                                @if ($child_category->category_type == 'link')
                                                                    <a href="{{ $child_category->link_url }}" target="_blank" class="cat-btn">
                                                                @else
                                                                    <a href="{{ route('items.preview', [$shop_details['shop_slug'], $child_category->id]) }}"
                                                                        class="cat-btn {{ $child_category->id == $current_cat_id ? 'active' : '' }}">
                                                                @endif
                                                                -  {{ isset($child_category->$child_name_code) ? $child_category->$child_name_code : '' }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul> <!-- End Child Categories -->
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                <!-- <div class="category_ul">
                    <ul>
                        @if (count($categories_parent) > 0)
                            @foreach ($categories_parent as $category)
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
                </div> -->
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
                                            <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')"
                                                    style="cursor: pointer;"> <x-dynamic-component width="35px"
                                                    component="flag-language-{{ $primary_language_details['code'] }}" />
                                                {{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}</a>
                                            </li>
                                        @endif
                                        @foreach ($additional_languages as $language)
                                            @php
                                                $langCode = isset($language->language['code']) ? $language->language['code'] : '';
                                            @endphp
                                            <li>
                                            <a onclick="changeLanguage('{{ $langCode }}')"
                                                    style="cursor: pointer;">  <x-dynamic-component width="35px"
                                                    component="flag-language-{{ $langCode }}" />
                                                {{ isset($language->language['name']) ? $language->language['name'] : '' }}</a>
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
                            <a href="#" class="btn search_bt openSearchInnerPage" id="openSearchInnerPage"><i
                                    class="fa-solid fa-search"></i></a>
                            <a href="#" class="btn search_bt d-none" id="closeSearchInnerPage"><i
                                    class="fa-solid fa-times"></i></a>
                        </li>
                        @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                        <li class="navlink">
                                <a href="#" class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                        </li>
                        @endif
                    </ul>
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
        <!-- <a class="waiter_notification">
                            <i class="fa-solid fa-bell"></i>
                        </a> -->
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
                                <a onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                                        class="fa-solid fa-arrow-left"></i></a>
                            </div>
                            <!-- <a href="#" class="waiter_notification">
                                        <i class="fa-solid fa-bell"></i>
                                    </a> -->
                            <!-- </div> -->
                        </section>
            @endif
        @endif
        <div class="header_inr ">
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
                            <a onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                                    class="fa-solid fa-arrow-left"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="header_right">
                    <div class="barger_menu_main">
                                    <div class="barger_menu_inner">
                                        <div class="barger_menu_icon">
                                            <i class="fa-solid fa-bars"></i>
                                        </div>
                                        <div class="barger_menu_list">
                                            <ul>
                                                <li>
                                                    <div class="barger_menu_box">
                                                        <button class="btn search_bt openSearchInnerPage" id="openSearchInnerPage">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn search_bt closeSearchInnerPage d-none" id="closeSearchInnerPage">
                                                            <i class="fa-solid fa-times"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                                @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                                                    @if ($table_enable_status == 1 || $room_enable_status == 1)                                                        
                                                        <li>
                                                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                                                <i class="fa-solid fa-bell"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                                    <li>
                                                        <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                        </div>
                                    <!-- <a href="#" class="btn search_bt" id="openSearchInnerPage"><i
                                    class="fa-solid fa-search"></i></a>
                            <a href="#" class="btn search_bt d-none" id="closeSearchInnerPage"><i
                                    class="fa-solid fa-times"></i></a> -->
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
                        <div class="header_cart_box">
                            <div id="cart-box" class="cart-btn text-decoration-none position-relative">
                                <i class="fa-solid fa-basket-shopping"></i>
                                <span class="qty-number">{{ $total_quantity }}</span>
                            </div>
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
                                    <a @if($cart != []) href="{{ route('shop.cart', $shop_slug) }}" @endif class="btn orderup_button">Complete Order</a>
                                </div>
                        </div>

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

    <input type="hidden" name="current_category_id" value="{{ $current_cat_id }}" id="current_category_id">
    <input type="hidden" name="current_tab_id" id="current_tab_id" value="no_tab">
    <input type="hidden" name="is_cat_tab" id="is_cat_tab" value="{{ count($cat_tags) }}">
    <input type="hidden" name="shop_id" id="shop_id" value="{{ $shop_details['id'] }}">
    <input type="hidden" name="tag_id" id="tag_id" value="">
    <input type="hidden" name="parent_id" id="parent_id" value="{{ $cat_parent_id }}">

    @if ($layout == 'layout_1')
        <section class="item_sec_main">
            <div class="container">
                <div class="item_box_main d-none">

                    {{-- Categories Tabs --}}
                    @if ($category_effect == 'default')
                            <div class="category_slider position-relative">
                                <ul class="slider-item" id="myTab" role="tablist">
                                    @php
                                        $categories_data = ($cat_parent_id == null) ? $categories_parent : $categories;
                                    @endphp
                                    @if (count($categories_data) > 0)
                                        @foreach ($categories_data as $cat)
                                            @php

                                                $active_cat = checkCategorySchedule($cat['id'], $cat['shop_id']);
                                                $check_cat_type_permission = checkCatTypePermission($cat['category_type'], $shop_details['id']);
                                                $categoryImage = App\Models\CategoryImages::where('category_id',$cat['id'])->first();

                                            @endphp

                                            @if ($active_cat == 1)
                                                @if ($check_cat_type_permission == 1)
                                                    <li>
                                                        @if ($cat['category_type'] == 'link')
                                                            <a href="{{ $cat['link_url'] }}" target="_blank"
                                                                class="nav-link cat-btn">
                                                            @elseif($cat['category_type'] == 'parent_category')
                                                                <a href="{{ route('restaurant', [$shop_slug, $cat['id']]) }}"  class="nav-link cat-btn {{ $cat['id'] == $current_cat_id ? 'active' : '' }}">
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
                                                                    $cat_image = isset($categoryImage) ? $categoryImage->image : '';
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
                                <button class="prev slick-arrow"><i class="fa-solid fa-angle-left"></i></button>
                                <button class="next slick-arrow"><i class="fa-solid fa-angle-right"></i></button>
                            </div>

                    @elseif(
                        $category_effect == 'wheel' ||
                            $category_effect == 'coverflow' ||
                            $category_effect == 'carousel' ||
                            $category_effect == 'flat')
                        <div id="coverflow">
                            <ul class="flip-items">
                            @php
                                $categories_data = ($cat_parent_id == null) ? $categories_parent : $categories;
                            @endphp
                                @if (count($categories_data) > 0)
                                    @foreach ($categories_data as $cat)
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
                                                            @elseif($cat['category_type'] == 'parent_category')
                                                                <a href="{{ route('restaurant', [$shop_slug, $cat['id']]) }}" class="cate_item">
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

                    <div class="item_list_div">
                        <h3 class="mb-3 cat_name text-center">
                        @if($cat_parent_id != null)
                            <a href="{{ route('restaurant', [$shop_details['shop_slug'],$cat_parent_id]) }}" class="text-decoration-none"><i class="fa-solid fa-circle-chevron-left me-2"></i><span>{{ isset($cat_details[$name_key]) ? $cat_details[$name_key] : '' }}</span></a>
                            @else
                            {{ isset($cat_details[$name_key]) ? $cat_details[$name_key] : '' }}
                        @endif
                            </h3>
                        <div class="mb-3">{!! isset($cat_details[$description_key]) ? $cat_details[$description_key] : '' !!}</div>

                        <div class="item_inr_info">

                            @if (count($cat_tags) > 0)
                                {{-- Tags Section --}}
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button onclick="setTabKey('all','')" class="nav-link active tags-btn"
                                            id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button"
                                            role="tab" aria-controls="all" aria-selected="true">{{ __('All')}}</button>
                                    </li>

                                    @foreach ($cat_tags as $tag)
                                        @php
                                            $tag_items = getTagsProducts($tag['tag_id'], $cat_details['id']);
                                        @endphp

                                        @if (count($tag_items) > 0)
                                            <li class="nav-item" role="presentation">
                                                <button onclick="setTabKey('{{ $tag['id'] }}','{{ $tag['tag_id'] }}')"
                                                    class="nav-link tags-btn" id="{{ $tag['id'] }}-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tag{{ $tag['id'] }}"
                                                    type="button" role="tab" aria-controls="tag{{ $tag['id'] }}"
                                                    aria-selected="false">{{ isset($tag[$name_key]) ? $tag[$name_key] : '' }}</button>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="all" role="tabpanel"
                                        aria-labelledby="all-tab">
                                        <div class="item_inr_info_sec">
                                            <div class="row">
                                                @if (count($all_items) > 0)
                                                    @foreach ($all_items as $item)
                                                        @php
                                                            $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                            $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                            $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                        @endphp
                                                        @if ($item['type'] == 1)
                                                            <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
                                                                <div class="item_detail single_item_inr devider-border @if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink')
                                                                    special_day_blink
                                                                    @elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate')
                                                                    special_day_rotate
                                                                    @endif"                                                                   
                                                                    >
                                                                    <div class="special">
                                                                        <label ></label>
                                                                        <label ></label>
                                                                        <label ></label>
                                                                        <label ></label>
                                                                    </div>

                                                                    {{-- Image Section --}}
                                                                    <div class="item_image">
                                                                        @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}" onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer">
                                                                        @endif
                                                                        <a  class="review_btn" onclick="openRatingModel({{ $item['id'] }})">
                                                                            <i class="fa-solid fa-star"></i>
                                                                            <i class="fa-solid fa-star"></i>
                                                                            <i class="fa-solid fa-star"></i>
                                                                        </a>
                                                                    </div>

                                                                    {{-- Name Section --}}
                                                                    <h3 onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer;">{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                    </h3>

                                                                    {{-- Calories Section --}}
                                                                    @if (isset($item[$calories_key]) && !empty($item[$calories_key]))
                                                                        <p class="m-0 p-0"><strong>Cal:
                                                                            </strong>{{ isset($item[$calories_key]) && !empty($item[$calories_key]) ? $item[$calories_key] : '' }}
                                                                        </p>
                                                                    @endif

                                                                    {{-- New Product Image --}}
                                                                    @if ($item['is_new'] == 1)
                                                                        <img class="is_new tag-img"
                                                                            src="{{ asset('public/client_images/bs-icon/new.png') }}">
                                                                    @endif

                                                                    {{-- Signature Image --}}
                                                                    @if ($item['as_sign'] == 1)
                                                                        <img class="is_sign tag-img"
                                                                            src="{{ asset('public/client_images/bs-icon/signature.png') }}">
                                                                    @endif

                                                                    {{-- Day Special Image --}}
                                                                    {{-- @if ($item['day_special'] == 1)
                                                                            <img class="is_special tag-img" src="{{ asset('public/client_images/bs-icon/today_special.gif') }}">
                                                                        @endif --}}

                                                                    {{-- Ingredient Section --}}
                                                                    @php
                                                                        $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                    @endphp

                                                                    @if (count($ingrediet_arr) > 0)
                                                                        <div>
                                                                            @foreach ($ingrediet_arr as $val)
                                                                                @php
                                                                                    $ingredient = getIngredientDetail($val);
                                                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                    $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                @endphp

                                                                                @if (
                                                                                    (isset($package_permissions['special_icons']) &&
                                                                                        !empty($package_permissions['special_icons']) &&
                                                                                        $package_permissions['special_icons'] == 1) ||
                                                                                        $parent_ing_id != null)
                                                                                    @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                            width="60px" height="60px">
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @endif

                                                                    {{-- Description Section --}}
                                                                    @php
                                                                        $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';
                                                                    @endphp
                                                                    @if (strlen(strip_tags($desc)) > 180)
                                                                        <div class="item-desc">
                                                                            <p>
                                                                                {!! substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 150), "\n")) !!}... <br>
                                                                                <a
                                                                                    class="read-more-desc">{{ $read_more_label }}</a>
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div class="item-desc">
                                                                            <p>{!! $desc !!}</p>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Price Section --}}
                                                                    <ul class="price_ul">
                                                                        @php
                                                                            $price_arr = getItemPrice($item['id']);
                                                                        @endphp

                                                                        @if (count($price_arr) > 0)
                                                                            @foreach ($price_arr as $key => $value)
                                                                                @php
                                                                                    $price = Currency::currency($currency)->format($value['price']);
                                                                                    $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';
                                                                                @endphp
                                                                                <li>
                                                                                    @if ($item_discount > 0)
                                                                                        @php
                                                                                            if ($item_discount_type == 'fixed') {
                                                                                                $new_amount = number_format($value['price'] - $item_discount, 2);
                                                                                            } else {
                                                                                                $per_value = ($value['price'] * $item_discount) / 100;
                                                                                                $new_amount = number_format($value['price'] - $per_value, 2);
                                                                                            }
                                                                                        @endphp
                                                                                        <p>
                                                                                            {{ $price_label }} <span
                                                                                                class="text-decoration-line-through">{{ $price }}</span>
                                                                                            <span>{{ Currency::currency($currency)->format($new_amount) }}</span>
                                                                                        </p>
                                                                                    @else
                                                                                        <p>
                                                                                            {{ $price_label }}
                                                                                            <span>{{ $price }}</span>
                                                                                        </p>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        @endif
                                                                    </ul>

                                                                    @if ($item['day_special'] == 1)
                                                                        @if (
                                                                            !empty($today_special_icon) &&
                                                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon))
                                                                            <img width="170" class="mt-4"
                                                                                src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon) }}">
                                                                        @else
                                                                            @if (!empty($default_special_image) && file_exists($default_special_image))
                                                                                <img width="170" class="mt-4"
                                                                                    src="{{ $default_special_image }}">
                                                                            @else
                                                                                <img width="170" class="mt-4"
                                                                                    src="{{ asset('public/client_images/bs-icon/today_special.gif') }}">
                                                                            @endif
                                                                        @endif
                                                                    @endif

                                                                    @if (isset($package_permissions['ordering']) &&
                                                                            !empty($package_permissions['ordering']) &&
                                                                            $package_permissions['ordering'] == 1 &&
                                                                            count($price_arr) > 0 &&
                                                                            $item_delivery == 1)
                                                                        <div class="cart-symbol" onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer;"><i
                                                                                class="bi bi-cart4"></i></div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            @if ($item_devider == 1)
                                                                <div class="col-md-12 mb-3">
                                                                    <div class="single_item_inr devider">

                                                                        {{-- Image Section --}}
                                                                        @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                            <div class="item_image">
                                                                                <img
                                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}">
                                                                            </div>
                                                                        @endif

                                                                        {{-- Name Section --}}
                                                                        <h3>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                        </h3>


                                                                        {{-- Ingredient Section --}}
                                                                        @php
                                                                            $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                        @endphp

                                                                        @if (count($ingrediet_arr) > 0)
                                                                            <div>
                                                                                @foreach ($ingrediet_arr as $val)
                                                                                    @php
                                                                                        $ingredient = getIngredientDetail($val);
                                                                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                        $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                    @endphp

                                                                                    @if (
                                                                                        (isset($package_permissions['special_icons']) &&
                                                                                            !empty($package_permissions['special_icons']) &&
                                                                                            $package_permissions['special_icons'] == 1) ||
                                                                                            $parent_ing_id != null)
                                                                                        @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                                width="60px"
                                                                                                height="60px">
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @endif

                                                                        {{-- Description Section --}}
                                                                        <div>{!! isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '' !!}</div>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @foreach ($cat_tags as $tag)
                                        @php
                                            $tag_items = getTagsProducts($tag['tag_id'], $cat_details['id']);
                                        @endphp

                                        <div class="tab-pane fade show" id="tag{{ $tag['id'] }}" role="tabpanel"
                                            aria-labelledby="{{ $tag['id'] }}-tab">
                                            <div class="item_inr_info_sec">
                                                <div class="row">
                                                    @if (count($tag_items) > 0)
                                                        @foreach ($tag_items as $item)
                                                            @php
                                                                $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                                $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                            @endphp
                                                            @if ($item['type'] == 1)
                                                                <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
                                                                    <div class="item_detail single_item_inr devider-border @if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink')
                                                                    special_day_blink
                                                                    @elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate')
                                                                    special_day_rotate
                                                                    @endif "
                                                                        >

                                                                        <div class="special">
                                                                            <label ></label>
                                                                            <label ></label>
                                                                            <label ></label>
                                                                            <label ></label>
                                                                        </div>


                                                                        {{-- Image Section --}}
                                                                        <div class="item_image">
                                                                            @if (!empty($item->product['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']))
                                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']) }}" onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer">
                                                                            @endif
                                                                            <a  class="review_btn" onclick="openRatingModel({{ $item['id'] }})">
                                                                                <i class="fa-solid fa-star"></i>
                                                                                <i class="fa-solid fa-star"></i>
                                                                                <i class="fa-solid fa-star"></i>
                                                                            </a>
                                                                        </div>

                                                                        {{-- Name Section --}}
                                                                        <h3 onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer">{{ isset($item->product[$name_key]) && !empty($item->product[$name_key]) ? $item->product[$name_key] : '' }}
                                                                        </h3>

                                                                        {{-- Calories Section --}}
                                                                        @if (isset($item->product[$calories_key]) && !empty($item->product[$calories_key]))
                                                                            <p class="m-0 p-0"><strong>Cal :
                                                                                </strong>{{ isset($item->product[$calories_key]) && !empty($item->product[$calories_key]) ? $item->product[$calories_key] : '' }}
                                                                            </p>
                                                                        @endif

                                                                        {{-- New Product Image --}}
                                                                        @if ($item->product['is_new'] == 1)
                                                                            <img class="is_new tag-img"
                                                                                src="{{ asset('public/client_images/bs-icon/new.png') }}">
                                                                        @endif

                                                                        {{-- Signature Image --}}
                                                                        @if ($item->product['as_sign'] == 1)
                                                                            <img class="is_sign tag-img"
                                                                                src="{{ asset('public/client_images/bs-icon/signature.png') }}">
                                                                        @endif

                                                                        {{-- Day Special Image --}}
                                                                        {{-- @if ($item->product['day_special'] == 1)
                                                                                <img class="is_special tag-img" src="{{ asset('public/client_images/bs-icon/special.png') }}">
                                                                            @endif --}}

                                                                        {{-- Ingredient Section --}}
                                                                        @php
                                                                            $ingrediet_arr = isset($item->product['ingredients']) && !empty($item->product['ingredients']) ? unserialize($item->product['ingredients']) : [];
                                                                        @endphp

                                                                        @if (count($ingrediet_arr) > 0)
                                                                            <div>
                                                                                @foreach ($ingrediet_arr as $val)
                                                                                    @php
                                                                                        $ingredient = getIngredientDetail($val);
                                                                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                        $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                    @endphp

                                                                                    @if (
                                                                                        (isset($package_permissions['special_icons']) &&
                                                                                            !empty($package_permissions['special_icons']) &&
                                                                                            $package_permissions['special_icons'] == 1) ||
                                                                                            $parent_ing_id != null)
                                                                                        @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                                width="60px"
                                                                                                height="60px">
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @endif

                                                                        {{-- Description Section --}}
                                                                        @php
                                                                            $desc = isset($item->product[$description_key]) && !empty($item->product[$description_key]) ? $item->product[$description_key] : '';
                                                                        @endphp
                                                                        @if (strlen(strip_tags($desc)) > 180)
                                                                            <div class="item-desc">
                                                                                <p>
                                                                                    {!! substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 150), "\n")) !!}... <br>
                                                                                    <a
                                                                                        class="read-more-desc">{{ $read_more_label }}</a>
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            <div class="item-desc">
                                                                                <p>{!! $desc !!}</p>
                                                                            </div>
                                                                        @endif

                                                                        {{-- Price Section --}}
                                                                        <ul class="price_ul">
                                                                            @php
                                                                                $price_arr = getItemPrice($item['id']);
                                                                            @endphp

                                                                            @if (count($price_arr) > 0)
                                                                                @foreach ($price_arr as $key => $value)
                                                                                    @php
                                                                                        $price = Currency::currency($currency)->format($value['price']);
                                                                                        $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';
                                                                                    @endphp
                                                                                    <li>
                                                                                        @if ($item_discount > 0)
                                                                                            @php
                                                                                                if ($item_discount_type == 'fixed') {
                                                                                                    $new_amount = number_format($value['price'] - $item_discount, 2);
                                                                                                } else {
                                                                                                    $per_value = ($value['price'] * $item_discount) / 100;
                                                                                                    $new_amount = number_format($value['price'] - $per_value, 2);
                                                                                                }
                                                                                            @endphp
                                                                                            <p>
                                                                                                {{ $price_label }} <span
                                                                                                    class="text-decoration-line-through">{{ $price }}</span>
                                                                                                <span>{{ Currency::currency($currency)->format($new_amount) }}</span>
                                                                                            </p>
                                                                                        @else
                                                                                            <p>
                                                                                                {{ $price_label }}
                                                                                                <span>{{ $price }}</span>
                                                                                            </p>
                                                                                        @endif
                                                                                    </li>
                                                                                @endforeach
                                                                            @endif
                                                                        </ul>

                                                                        @if ($item['day_special'] == 1)
                                                                            @if (
                                                                                !empty($today_special_icon) &&
                                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon))
                                                                                <img width="170" class="mt-4"
                                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon) }}">
                                                                            @else
                                                                                @if (!empty($default_special_image) && file_exists($default_special_image))
                                                                                    <img width="170" class="mt-4"
                                                                                        src="{{ $default_special_image }}">
                                                                                @else
                                                                                    <img width="170" class="mt-4"
                                                                                        src="{{ asset('public/client_images/bs-icon/today_special.gif') }}">
                                                                                @endif
                                                                            @endif
                                                                        @endif

                                                                        @if (isset($package_permissions['ordering']) &&
                                                                                !empty($package_permissions['ordering']) &&
                                                                                $package_permissions['ordering'] == 1 &&
                                                                                count($price_arr) > 0 &&
                                                                                $item_delivery == 1)
                                                                            <div class="cart-symbol" onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer"><i
                                                                                    class="bi bi-cart4"></i></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                @if ($item_devider == 1)
                                                                    <div class="col-md-12 mb-3">
                                                                        <div class="single_item_inr devider">

                                                                            {{-- Image Section --}}
                                                                            @if (
                                                                                !empty($item->product['image']) &&
                                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']))
                                                                                <div class="item_image">
                                                                                    <img
                                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']) }}">
                                                                                </div>
                                                                            @endif

                                                                            {{-- Name Section --}}
                                                                            <h3>{{ isset($item->product[$name_key]) && !empty($item->product[$name_key]) ? $item->product[$name_key] : '' }}
                                                                            </h3>

                                                                            {{-- Ingredient Section --}}
                                                                            @php
                                                                                $ingrediet_arr = isset($item->product['ingredients']) && !empty($item->product['ingredients']) ? unserialize($item->product['ingredients']) : [];
                                                                            @endphp

                                                                            @if (count($ingrediet_arr) > 0)
                                                                                <div>
                                                                                    @foreach ($ingrediet_arr as $val)
                                                                                        @php
                                                                                            $ingredient = getIngredientDetail($val);
                                                                                            $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                            $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                        @endphp

                                                                                        @if (
                                                                                            (isset($package_permissions['special_icons']) &&
                                                                                                !empty($package_permissions['special_icons']) &&
                                                                                                $package_permissions['special_icons'] == 1) ||
                                                                                                $parent_ing_id != null)
                                                                                            @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                                    width="60px"
                                                                                                    height="60px">
                                                                                            @endif
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            @endif

                                                                            {{-- Description Section --}}
                                                                            <div class="item-desc">{!! isset($item->product[$description_key]) && !empty($item->product[$description_key])
                                                                                ? $item->product[$description_key]
                                                                                : '' !!}
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="item_inr_info_sec">
                                    <div class="row">
                                        @if (count($all_items) > 0)
                                            @foreach ($all_items as $item)
                                                @php
                                                    $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                    $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                    $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                @endphp
                                                @if ($item['type'] == 1)
                                                    <div class="col-md-6 col-lg-6 col-xl-3 mb-3">
                                                        <div class="item_detail single_item_inr devider-border">

                                                            {{-- Image Section --}}
                                                            <div class="item_image">
                                                                @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}" onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer">
                                                                @endif
                                                                <a  class="review_btn" onclick="openRatingModel({{ $item['id'] }})">
                                                                    <i class="fa-solid fa-star"></i>
                                                                    <i class="fa-solid fa-star"></i>
                                                                    <i class="fa-solid fa-star"></i>
                                                                </a>
                                                            </div>                                                            

                                                            {{-- Name Section --}}
                                                            <h3 onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer">{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                            </h3>

                                                            {{-- Calories Section --}}
                                                            @if (isset($item[$calories_key]) && !empty($item[$calories_key]))
                                                                <p class="m-0 p-0"><strong>Calories :
                                                                    </strong>{{ isset($item[$calories_key]) && !empty($item[$calories_key]) ? $item[$calories_key] : '' }}
                                                                </p>
                                                            @endif

                                                            {{-- New Product Image --}}
                                                            @if ($item['is_new'] == 1)
                                                                <img class="is_new tag-img"
                                                                    src="{{ asset('public/client_images/bs-icon/new.png') }}">
                                                            @endif

                                                            {{-- Signature Image --}}
                                                            @if ($item['as_sign'] == 1)
                                                                <img class="is_sign tag-img"
                                                                    src="{{ asset('public/client_images/bs-icon/signature.png') }}">
                                                            @endif

                                                            {{-- Day Special Image --}}
                                                            {{-- @if ($item['day_special'] == 1)
                                                                    <img class="is_special tag-img" src="{{ asset('public/client_images/bs-icon/special.png') }}">
                                                                @endif --}}

                                                            {{-- Ingredient Section --}}
                                                            @php
                                                                $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                            @endphp

                                                            @if (count($ingrediet_arr) > 0)
                                                                <div>
                                                                    @foreach ($ingrediet_arr as $val)
                                                                        @php
                                                                            $ingredient = getIngredientDetail($val);
                                                                            $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                            $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                        @endphp

                                                                        @if (
                                                                            (isset($package_permissions['special_icons']) &&
                                                                                !empty($package_permissions['special_icons']) &&
                                                                                $package_permissions['special_icons'] == 1) ||
                                                                                $parent_ing_id != null)
                                                                            @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                    width="60px" height="60px">
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            {{-- Description Section --}}
                                                            @php
                                                                $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';
                                                            @endphp
                                                            @if (strlen(strip_tags($desc)) > 180)
                                                                <div class="item-desc">
                                                                    <p>
                                                                        {!! substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 150), "\n")) !!}... <br>
                                                                        <a
                                                                            class="read-more-desc">{{ $read_more_label }}</a>
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <div class="item-desc">
                                                                    <p>{!! $desc !!}</p>
                                                                </div>
                                                            @endif

                                                            {{-- Price Section --}}
                                                            <ul class="price_ul">
                                                                @php
                                                                    $price_arr = getItemPrice($item['id']);
                                                                @endphp

                                                                @if (count($price_arr) > 0)
                                                                    @foreach ($price_arr as $key => $value)
                                                                        @php
                                                                            $price = Currency::currency($currency)->format($value['price']);
                                                                            $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';
                                                                        @endphp
                                                                        <li>
                                                                            @if ($item_discount > 0)
                                                                                @php
                                                                                    if ($item_discount_type == 'fixed') {
                                                                                        $new_amount = number_format($value['price'] - $item_discount, 2);
                                                                                    } else {
                                                                                        $per_value = ($value['price'] * $item_discount) / 100;
                                                                                        $new_amount = number_format($value['price'] - $per_value, 2);
                                                                                    }
                                                                                @endphp
                                                                                <p>
                                                                                    {{ $price_label }} <span
                                                                                        class="text-decoration-line-through">{{ $price }}</span>
                                                                                    <span>{{ Currency::currency($currency)->format($new_amount) }}</span>
                                                                                </p>
                                                                            @else
                                                                                <p>
                                                                                    {{ $price_label }}
                                                                                    <span>{{ $price }}</span>
                                                                                </p>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                @endif
                                                            </ul>

                                                            @if ($item['day_special'] == 1)
                                                                @if (
                                                                    !empty($today_special_icon) &&
                                                                        file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon))
                                                                    <img width="170" class="mt-4"
                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon) }}">
                                                                @else
                                                                    @if (!empty($default_special_image) && file_exists($default_special_image))
                                                                        <img width="170" class="mt-4"
                                                                            src="{{ $default_special_image }}">
                                                                    @else
                                                                        <img width="170" class="mt-4"
                                                                            src="{{ asset('public/client_images/bs-icon/today_special.gif') }}">
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            @if (isset($package_permissions['ordering']) &&
                                                                    !empty($package_permissions['ordering']) &&
                                                                    $package_permissions['ordering'] == 1 &&
                                                                    count($price_arr) > 0 &&
                                                                    $item_delivery == 1)
                                                                <div class="cart-symbol" onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})" style="cursor: pointer"><i class="bi bi-cart4"></i></div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($item_devider == 1)
                                                        <div class="col-md-12 mb-3">
                                                            <div class="single_item_inr devider">

                                                                {{-- Image Section --}}
                                                                @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                    <div class="item_image">
                                                                        <img
                                                                            src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}">
                                                                    </div>
                                                                @endif

                                                                {{-- Name Section --}}
                                                                <h3>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                </h3>

                                                                {{-- Ingredient Section --}}
                                                                @php
                                                                    $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                @endphp

                                                                @if (count($ingrediet_arr) > 0)
                                                                    <div>
                                                                        @foreach ($ingrediet_arr as $val)
                                                                            @php
                                                                                $ingredient = getIngredientDetail($val);
                                                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                            @endphp

                                                                            @if (
                                                                                (isset($package_permissions['special_icons']) &&
                                                                                    !empty($package_permissions['special_icons']) &&
                                                                                    $package_permissions['special_icons'] == 1) ||
                                                                                    $parent_ing_id != null)
                                                                                @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                        width="60px" height="60px">
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                                {{-- Description Section --}}
                                                                <div class="item-desc">{!! isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '' !!}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @else
                                            <h3 class="text-center">Items Not Found !</h3>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @elseif ($layout == 'layout_2')
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

            <div class="category_header">
                <div class="row align-item-center">
                    <div class="col-md-4">
                        <div class="category_back_btn">
                            @if($cat_details['parent_id'] != 0)
                                <a class="back_btn" href="{{ route('restaurant', [$shop_details['shop_slug'],$cat_details['parent_id']]) }}">
                            @else
                                <a class="back_btn" onclick="homePage('{{ $shop_details['shop_slug'] }}')">
                            @endif
                             <i
                                    class="fa-solid fa-circle-chevron-left me-2"></i>
                            <span>{{isset($cat_details[$name_key]) ? $cat_details[$name_key] : ''}}</span>
                                @php
                                 $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                @endphp
                                 @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                 <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}" width="30" class="rounded-circle ms-2">
                             @endif
                            </a>
                        </div>
                    </div>
                    @if($special_discount_percentage != 0 && $special_discount_percentage != '')
                    <div class="col-md-4">
                        <div class="item_discount">
                            <label class="discount_btn">Special Discount  {{ $special_discount }}</label>
                        </div>
                    </div>
                    @endif
                </div>

            </div>


            {{-- Category --}}
            <div class="category_item_list">
                    @php
                        $first_array = (isset($all_items[0]['type']) && $all_items[0]['type'] == 2) ? $all_items[0]['type'] : '';
                    @endphp
                <div class="category_list_item_inr">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            @if ($first_array)
                                <div class="category_name_title">
                                    <div class="row aline-items-center mb-3">
                                        <div class="col-md-3">
                                                <div class="category_back_btn">
                                                <a class="back_btn" onclick="homePage('{{ $shop_details['shop_slug'] }}')"> <i
                                                        class="fa-solid fa-circle-chevron-left me-2"></i>
                                                    <span>{{isset($cat_details[$name_key]) ? $cat_details[$name_key] : ''}}</span>
                                                    @php
                                                    $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                                    @endphp
                                                    @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}" width="30" class="rounded-circle ms-2">
                                                @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-none search_input_inner">
                                                            <input type="text" class="form-control search_layout" name="search" id="search_layout" placeholder="Search Items">
                                                            <button class="btn btn-secondary src_btn_inner"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                            <button class="btn btn-danger clr_btn_inner"><i class="fa-solid fa-x"></i></button>
                                                </div>
                                        </div>
                                        <div class="col-md-3"></div>

                                    </div>
                                        <div class="category_title">
                                            <div class="category_title_img">
                                                @if (!empty($all_items[0]['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $all_items[0]['image']))
                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $all_items[0]['image']) }}" class="w-100">
                                                @else
                                                        <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                @endif
                                            </div>
                                            <div class="category_title_name">
                                                <h3>{{ isset($all_items[0][$name_key]) && !empty($all_items[0][$name_key]) ? $all_items[0][$name_key] : '' }}</h3>
                                            </div>
                                        </div>
                                </div>
                            @else
                            <div class="category_name_title">
                            <div class="row aline-items-center mb-3">
                                        <div class="col-md-3">
                                                <div class="category_back_btn">
                                                    @if($cat_details['parent_id'] != 0)
                                                    <a class="back_btn" href="{{ route('restaurant', [$shop_details['shop_slug'],$cat_details['parent_id']]) }}">
                                                    @else
                                                    <a class="back_btn" onclick="homePage('{{ $shop_details['shop_slug'] }}')">
                                                    @endif
                                                <i class="fa-solid fa-circle-chevron-left me-2"></i>
                                                   <span>{{isset($cat_details[$name_key]) ? $cat_details[$name_key] : ''}}</span>
                                                    @php
                                                    $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                                    @endphp
                                                    @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}" width="30" class="rounded-circle ms-2">
                                                @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-none search_input_inner">
                                                            <input type="text" class="form-control search_layout" name="search" id="search_layout" placeholder="Search Items">
                                                            <button class="btn btn-secondary src_btn_inner"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                            <button class="btn btn-danger clr_btn_inner"><i class="fa-solid fa-x"></i></button>

                                                </div>
                                        </div>
                                        <div class="col-md-3"></div>

                                    </div>
                                    <div class="category_title">
                                        <div class="category_title_img">
                                            @if (
                                                $cat_details->category_type == 'page' ||
                                                    $cat_details->category_type == 'gallery' ||
                                                    $cat_details->category_type == 'link' ||
                                                    $cat_details->category_type == 'check_in' ||
                                                    $cat_details->category_type == 'parent_category' ||
                                                    $cat_details->category_type == 'pdf_page')
                                                @if (
                                                    !empty($cat_details->cover) &&
                                                        file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->cover))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->cover) }}"
                                                        class="w-100">
                                                @else
                                                    <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                @endif
                                            @else
                                                @php
                                                    $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                                @endphp
                                                @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                        class="w-100">
                                                @else
                                                    <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                @endif
                                            @endif
                                        </div>

                                        <div class="category_title_name">
                                            <h3>{{ isset($cat_details[$name_key]) ? $cat_details[$name_key] : '' }}</h3>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="category_header">
                                @if (count($cat_tags) > 0)                                
                                    <div class="row justify-content-center">
                                        <div class="col-md-7">
                                            <div class="category_inr_tag">
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button  class="nav-link active tags-btn all-tab" id="all-tab"
                                                            data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab"
                                                            aria-controls="all" aria-selected="true">{{ __('All') }}</button>
                                                    </li>
                                                    @foreach ($cat_tags as $inner_tag)
                                                        @php
                                                            $tag_items_inner = getTagsProducts($inner_tag['tag_id'], $cat_details['id']);
                                                        @endphp

                                                        @if (count($tag_items_inner) > 0)
                                                            <li class="nav-item" role="presentation">
                                                                <button
                                                                    class="nav-link tags-btn {{ $inner_tag['id'] }}-tab" id="{{ $inner_tag['id'] }}-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tag{{ $inner_tag['id'] }}"
                                                                    type="button" role="tab" aria-controls="tag{{ $inner_tag['id'] }}"
                                                                    aria-selected="false">{{ isset($inner_tag[$name_key]) ? $inner_tag[$name_key] : '' }}</button>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="category_inr_list_item">
                                <div class="row">

                                    @php
                                        if($first_array == 2) {
                                            unset($all_items[0]);
                                        }
                                    @endphp
                                    @if (count($all_items) > 0)
                                        @foreach ($all_items as  $item)
                                            @php
                                                $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                $tag_name = getTagName($item->id);
                                                $tagName = isset($tag_name->hasOneTag->$name_key) ? $tag_name->hasOneTag->$name_key : '';
                                            @endphp
                                            @if ($item['type'] == 1)
                                                <div class="col-xl-4 col-lg-6 col-md-6">
                                                    <div class="item_detail single_item_inr devider-border  @if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink')
                                                    special_day_blink
                                                    @elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate')
                                                    special_day_rotate
                                                     @endif @if($item['is_new'] == 1 || $item['as_sign'] == 1) tag_item_detail @endif">
                                                        <div class="special">
                                                            <label ></label>
                                                            <label ></label>
                                                            <label ></label>
                                                            <label ></label>
                                                        </div>
                                                            @if ($item['is_new'] == 1)
                                                                    <img class="is_new tag-img position-absolute"
                                                                        src="{{ asset('public/client_images/bs-icon/new.png') }}" style="top:0; left:0; width:70px;">
                                                                @endif

                                                                {{-- Signature Image --}}
                                                                @if ($item['as_sign'] == 1)
                                                                    <img class="is_sign tag-img position-absolute"
                                                                        src="{{ asset('public/client_images/bs-icon/signature.png') }}" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">
                                                                @endif
                                                        {{-- name --}}
                                                        <div class="category_item_name">
                                                                    <h3 onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})"
                                                                        style="cursor: pointer">
                                                                        {{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                    </h3>
                                                            </div>
                                                            <div class="item_detail_inr {{ (!empty($item['image']) && file_exists(public_path('client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))) ? '' : 'no_img_item_detail' }}">
                                                                <div class="item_info">

                                                                    <!-- {{-- name --}}
                                                                    <h3 onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})"
                                                                        style="cursor: pointer">
                                                                        {{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                    </h3> -->

                                                                    {{-- Description Section --}}
                                                                    @php
                                                                        $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';
                                                                    @endphp
                                                                    @if (strlen(strip_tags($desc)) > 100)
                                                                        <div class="item-desc">
                                                                            <p>
                                                                                {!! substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 100), "\n")) !!}... <br>
                                                                                <a class="read-more-desc text-white"
                                                                                    onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})"
                                                                                    style="cursor: pointer">{{ $read_more_label }}</a>
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div class="item-desc">
                                                                            <p>{!! $desc !!}</p>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Ingredient Section --}}
                                                                    @php
                                                                        $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                    @endphp

                                                                    @if (count($ingrediet_arr) > 0)
                                                                        <div class="item_tag">
                                                                            @foreach ($ingrediet_arr as $val)
                                                                                @php
                                                                                    $ingredient = getIngredientDetail($val);
                                                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                    $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                @endphp

                                                                                @if (
                                                                                    (isset($package_permissions['special_icons']) &&
                                                                                        !empty($package_permissions['special_icons']) &&
                                                                                        $package_permissions['special_icons'] == 1) ||
                                                                                        $parent_ing_id != null)
                                                                                    @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                            width="45px" height="45px">
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @endif

                                                                    {{-- Calories Section --}}
                                                                    @if (isset($item[$calories_key]) && !empty($item[$calories_key]))
                                                                        <p class="m-0 p-2"><strong>Cal:
                                                                            </strong>{{ isset($item[$calories_key]) && !empty($item[$calories_key]) ? $item[$calories_key] : '' }}
                                                                        </p>
                                                                    @endif

                                                                </div>
                                                                <div class="item_image">
                                                                    @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}"
                                                                            onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})"
                                                                            style="cursor: pointer">
                                                                    @endif
                                                                    <a  class="review_btn"
                                                                        onclick="openRatingModel({{ $item['id'] }})"><i
                                                                            class="fa-solid fa-star"></i> <i
                                                                            class="fa-solid fa-star"></i> <i
                                                                            class="fa-solid fa-star"></i></a>
                                                                </div>
                                                            </div>
                                                            <div class="special_day_item_gif text-center">
                                                                @if ($item['day_special'] == 1)
                                                                                @if (
                                                                                    !empty($today_special_icon) &&
                                                                                        file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon))
                                                                                    <img width="170"
                                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon) }}">
                                                                                @else
                                                                                    @if (!empty($default_special_image) && file_exists($default_special_image))
                                                                                        <img width="170"
                                                                                            src="{{ $default_special_image }}">
                                                                                    @else
                                                                                        <img width="170"
                                                                                            src="{{ asset('public/client_images/bs-icon/today_special.gif') }}">
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                            </div>
                                                            <div class="item_footer">
                                                                @if($tagName)
                                                                    <span>{{ $tagName }}</span>
                                                                @endif
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
                                                                        <h4>
                                                                            {{ $price_label }}
                                                                            {{ Currency::currency($currency)->format($new_amount) }}
                                                                            <span>{{ $price }}</span>
                                                                        </h4>
                                                                    @else
                                                                        <h4>
                                                                            {{ $price_label }} {{ $price }}
                                                                        </h4>
                                                                    @endif
                                                                @endif

                                                                @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1)
                                                                    <button class="item_cart_btn"
                                                                        onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})"
                                                                        style="cursor: pointer"><i
                                                                            class="fa-solid fa-cart-plus"></i></button>
                                                                @endif
                                                            </div>
                                                    </div>
                                                </div>
                                            @else
                                                @if ($item_devider == 1)
                                                        <div class="category_title">
                                                            <div class="category_title_img">
                                                                @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                      <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}" class="w-100">
                                                                @else
                                                                      <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                                @endif
                                                            </div>
                                                            <div class="category_title_name">
                                                                <h3>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}</h3>
                                                            </div>
                                                        </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        @foreach ($cat_tags as $tag)
                            @php

                                $tag_items = getTagsProducts($tag['tag_id'], $cat_details['id']);

                                $tag_array = (isset($tag_items[0]['type']) && $tag_items[0]['type'] == 2) ? $tag_items[0]['type'] : '';
                            @endphp

                            <div class="tab-pane fade show" id="tag{{ $tag['id'] }}" role="tabpanel"
                                aria-labelledby="{{ $tag['id'] }}-tab">

                                @if ($tag_array)
                                    <div class="category_name_title">
                                    <div class="row aline-items-center mb-3">
                                        <div class="col-md-3">
                                                <div class="category_back_btn">
                                                <a class="back_btn" onclick="homePage('{{ $shop_details['shop_slug'] }}')"> <i
                                                        class="fa-solid fa-circle-chevron-left me-2"></i>
                                                   <span>{{isset($cat_details[$name_key]) ? $cat_details[$name_key] : ''}}</span>
                                                    @php
                                                    $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                                    @endphp
                                                    @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}" width="30" class="rounded-circle ms-2">
                                                @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-none search_input_inner" id="search_input">
                                                            <input type="text" class="form-control search_layout" name="search" id="search_layout" placeholder="Search Items">
                                                            <button class="btn btn-secondary src_btn_inner"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                            <button class="btn btn-danger clr_btn_inner"><i class="fa-solid fa-x"></i></button>
                                                </div>
                                        </div>
                                        <div class="col-md-3"></div>

                                    </div>
                                        <div class="category_title">
                                            <div class="category_title_img">
                                                @if (!empty($tag_items[0]['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $tag_items[0]['image']))
                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $tag_items[0]['image']) }}" class="w-100">
                                                @else
                                                        <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                @endif
                                            </div>
                                            <div class="category_title_name">
                                                <h3>{{ isset($tag_items[0][$name_key]) && !empty($tag_items[0][$name_key]) ? $tag_items[0][$name_key] : '' }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="category_name_title">
                                    <div class="row aline-items-center mb-3">
                                        <div class="col-md-3">
                                                <div class="category_back_btn">
                                                <a class="back_btn" onclick="homePage('{{ $shop_details['shop_slug'] }}')"> <i
                                                        class="fa-solid fa-circle-chevron-left me-2"></i>
                                                    {{isset($cat_details[$name_key]) ? $cat_details[$name_key] : ''}}
                                                    @php
                                                    $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                                    @endphp
                                                    @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}" width="30" class="rounded-circle ms-2">
                                                @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-none search_input_inner">
                                                            <input type="text" class="form-control search_layout" name="search" id="search_layout" placeholder="Search Items">
                                                            <button class="btn btn-secondary src_btn_inner"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                            <button class="btn btn-danger clr_btn_inner"><i class="fa-solid fa-x"></i></button>
                                                </div>
                                        </div>
                                        <div class="col-md-3"></div>

                                    </div>
                                        <div class="category_title">
                                            <div class="category_title_img">
                                                @if (
                                                    $cat_details->category_type == 'page' ||
                                                        $cat_details->category_type == 'gallery' ||
                                                        $cat_details->category_type == 'link' ||
                                                        $cat_details->category_type == 'check_in' ||
                                                        $cat_details->category_type == 'parent_category' ||
                                                        $cat_details->category_type == 'pdf_page')
                                                    @if (
                                                        !empty($cat_details->cover) &&
                                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->cover))
                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_details->cover) }}"
                                                            class="w-100">
                                                    @else
                                                        <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                    @endif
                                                @else
                                                    @php
                                                        $cat_image = isset($cat_details->categoryImages[0]['image']) ? $cat_details->categoryImages[0]['image'] : '';
                                                    @endphp
                                                    @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                            class="w-100">
                                                    @else
                                                        <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="category_title_name">
                                                <h3>{{ isset($cat_details[$name_key]) ? $cat_details[$name_key] : '' }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="category_header">
                                    <div class="row justify-content-center">
                                        <div class="col-md-7">
                                            <div class="category_inr_tag">
                                                @if (count($cat_tags) > 0)
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <button  class="nav-link active tags-btn all-tab" id="all-tab"
                                                                data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab"
                                                                aria-controls="all" aria-selected="true">{{ __('All') }}</button>
                                                        </li>
                                                        @foreach ($cat_tags as $inner_tag)
                                                            @php
                                                                $tag_items_inner = getTagsProducts($inner_tag['tag_id'], $cat_details['id']);
                                                            @endphp

                                                            @if (count($tag_items_inner) > 0)
                                                                <li class="nav-item" role="presentation">
                                                                    <button
                                                                        class="nav-link tags-btn {{ $inner_tag['id'] }}-tab" id="{{ $inner_tag['id'] }}-tab"
                                                                        data-bs-toggle="tab" data-bs-target="#tag{{ $inner_tag['id'] }}"
                                                                        type="button" role="tab" aria-controls="tag{{ $inner_tag['id'] }}"
                                                                        aria-selected="false">{{ isset($inner_tag[$name_key]) ? $inner_tag[$name_key] : '' }}</button>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="category_inr_list_item">
                                    <div class="row">
                                        @php
                                            if($tag_array == 2) {
                                            unset($tag_items[0]);
                                        }
                                        @endphp
                                        @if (count($tag_items) > 0)

                                            @foreach ($tag_items as $item)
                                                @php
                                                    $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                    $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                    $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                    $tag_name = getTagName($item['id']);
                                                    $tagName = isset($tag_name->hasOneTag->$name_key) ? $tag_name->hasOneTag->$name_key : '';
                                                @endphp
                                                @if ($item['type'] == 1)
                                                    <div class="col-xl-4 col-lg-6 col-md-6">
                                                        <div class="item_detail single_item_inr devider-border  @if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink')
                                                        special_day_blink
                                                        @elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate')
                                                        special_day_rotate
                                                    @endif @if($item['is_new'] == 1 || $item['as_sign'] == 1) tag_item_detail @endif">
                                                            <div class="special">
                                                                <label ></label>
                                                                <label ></label>
                                                                <label ></label>
                                                                <label ></label>
                                                            </div>
                                                             @if ($item['is_new'] == 1)
                                                                    <img class="is_new tag-img position-absolute"
                                                                        src="{{ asset('public/client_images/bs-icon/new.png') }}" style="top:0; left:0; width:70px;">
                                                                @endif

                                                                {{-- Signature Image --}}
                                                                @if ($item['as_sign'] == 1)
                                                                    <img class="is_sign tag-img position-absolute"
                                                                        src="{{ asset('public/client_images/bs-icon/signature.png') }}" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">
                                                                @endif
                                                            <div class="category_item_name">
                                                                {{-- name --}}
                                                                    <h3 onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})"
                                                                        style="cursor: pointer">
                                                                        {{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                    </h3>
                                                            </div>
                                                            <div class="item_detail_inr {{ (!empty($item->product['image']) && file_exists(public_path('client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))) ? '' : 'no_img_item_detail' }} ">
                                                                <div class="item_info">
                                                                    {{-- Description Section --}}
                                                                    @php
                                                                        $desc = isset($item->product[$description_key]) && !empty($item->product[$description_key]) ? $item->product[$description_key] : '';
                                                                    @endphp
                                                                    @if (strlen(strip_tags($desc)) > 100)
                                                                        <div class="item-desc">
                                                                            <p>
                                                                                {!! substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 120), "\n")) !!}... <br>
                                                                                <a class="read-more-desc text-white"
                                                                                    onclick="getItemDetails({{ $item['id'] }},{{ $shop_details['id'] }})"
                                                                                    style="cursor: pointer">{{ $read_more_label }}</a>
                                                                            </p>
                                                                        </div>
                                                                    @else
                                                                        <div class="item-desc">
                                                                            <p>{!! $desc !!}</p>
                                                                        </div>
                                                                    @endif


                                                                    {{-- Ingredient Section --}}
                                                                    @php
                                                                        $ingrediet_arr = isset($item->product['ingredients']) && !empty($item->product['ingredients']) ? unserialize($item->product['ingredients']) : [];
                                                                    @endphp

                                                                    @if (count($ingrediet_arr) > 0)
                                                                        <div class="item_tag">
                                                                            @foreach ($ingrediet_arr as $val)
                                                                                @php
                                                                                    $ingredient = getIngredientDetail($val);
                                                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                    $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                @endphp

                                                                                @if (
                                                                                    (isset($package_permissions['special_icons']) &&
                                                                                        !empty($package_permissions['special_icons']) &&
                                                                                        $package_permissions['special_icons'] == 1) ||
                                                                                        $parent_ing_id != null)
                                                                                    @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                            width="45px" height="45px">
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    @endif

                                                                    {{-- Calories Section --}}
                                                                    @if (isset($item->product[$calories_key]) && !empty($item->product[$calories_key]))
                                                                        <p class="m-0 p-2"><strong>Cal:
                                                                            </strong>{{ isset($item->product[$calories_key]) && !empty($item->product[$calories_key]) ? $item->product[$calories_key] : '' }}
                                                                        </p>
                                                                    @endif
                                                                </div>

                                                                <div class="item_image">
                                                                    @if (
                                                                        !empty($item->product['image']) &&
                                                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']))
                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']) }}"
                                                                            onclick="getItemDetails({{ $item['id'] }},{{ $shop_details['id'] }})"
                                                                            style="cursor: pointer">
                                                                        <a  class="review_btn"
                                                                            onclick="openRatingModel({{ $item['id'] }})"><i
                                                                                class="fa-solid fa-star"></i> <i
                                                                                class="fa-solid fa-star"></i> <i
                                                                                class="fa-solid fa-star"></i></a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="special_day_item_gif text-center">
                                                                @if ($item['day_special'] == 1)
                                                                            @if (
                                                                                !empty($today_special_icon) &&
                                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon))
                                                                                <img width="170"
                                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon) }}">
                                                                            @else
                                                                                @if (!empty($default_special_image) && file_exists($default_special_image))
                                                                                    <img width="170"
                                                                                        src="{{ $default_special_image }}">
                                                                                @else
                                                                                    <img width="170"
                                                                                        src="{{ asset('public/client_images/bs-icon/today_special.gif') }}">
                                                                                @endif
                                                                            @endif
                                                                    @endif
                                                                </div>
                                                            <div class="item_footer">
                                                                @if($tagName)
                                                                    <span>{{ $tagName }}</span>
                                                                @endif
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
                                                                        <h4>
                                                                            {{ $price_label }}
                                                                            {{ Currency::currency($currency)->format($new_amount) }}
                                                                            <span>{{ $price }}</span>
                                                                        </h4>
                                                                    @else
                                                                        <h4>
                                                                            {{ $price_label }} {{ $price }}
                                                                        </h4>
                                                                    @endif
                                                                @endif
                                                            @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1)
                                                                <button class="item_cart_btn"
                                                                    onclick="getItemDetails({{ $item['id'] }},{{ $shop_details['id'] }})"
                                                                    style="cursor: pointer"><i
                                                                        class="fa-solid fa-cart-plus"></i></button>
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($item_devider == 1)
                                                        <div class="category_title">
                                                            <div class="category_title_img">
                                                                @if (!empty($item->product['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']))
                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item->product['image']) }}" class="w-100">
                                                                @else
                                                                <img src="{{ asset('public/client_images/not-found/no_image_1.jpg') }}" class="w-100">
                                                                @endif
                                                            </div>
                                                            <div class="category_title_name">
                                                                <h3>{{ isset($item->product[$name_key]) && !empty($item->product[$name_key]) ? $item->product[$name_key] : '' }}</h3>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @else
        <input type="hidden" name="def_cat" id="def_cat" value="{{ $cat_en_name }}">
        <div class="sec_main menu_section">
            <div class="container">
                <div class="row d-none search_input_inner justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="text-center position-relative">
                            <input type="text" class="form-control search_layout" name="search" id="search_layout" placeholder="Search Items">
                            <button class="btn btn-secondary src_btn_inner"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <button class="btn btn-danger clr_btn_inner_layout_three"><i class="fa-solid fa-x"></i></button>
                        </div>
                    </div>

                </div>
                <div class="row justify-content-center">
                    <div class="col-md-10 append_cat_src">
                        <div class="menu_info">
                            @if (count($categories) > 0)

                                @foreach ($categories as $category)
                                    @if ($category->category_type == 'product_category')
                                        @php
                                            $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                            $name_code = $current_lang_code . '_name';
                                            $nameId = str_replace(' ', '_', $category->en_name);
                                            $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                            $all_items = getAllItems($category->id);
                                            $category_tags = App\Models\CategoryProductTags::join('tags', 'tags.id', 'category_product_tags.tag_id')
                                                ->orderBy('tags.order')
                                                ->where('category_id', $category->id)
                                                ->where('tags.shop_id', $category->shop_id)
                                                ->get()
                                                ->unique('tag_id');
                                            $cat_name = $nameId . 'all';

                                        @endphp

                                        @if ($active_cat == 1)
                                            @if ($check_cat_type_permission == 1)
                                                <section class="menu_info_inr" id="{{ $nameId }}">
                                                    <div class="menu_title">
                                                        {{-- Category Box --}}
                                                        <h3>{{ isset($category->$name_code) ? $category->$name_code : '' }}
                                                        </h3>

                                                        @if (count($category_tags) > 0)
                                                            <!-- Mobile view: Select dropdown -->
                                                            <div class="d-md-none mb-3">
                                                                <select class="form-select tagSelectBox">
                                                                    <option value="{{ $cat_name }}">{{ __('All') }}</option>
                                                                    @foreach ($category_tags as $tag)
                                                                        @php
                                                                            $tag_items = getTagsProducts($tag['tag_id'], $category->id);
                                                                        @endphp
                                                                        @if (count($tag_items) > 0)
                                                                            <option
                                                                                value="tag{{ $tag['id'] }}{{ $category->id }}">
                                                                                {{ isset($tag[$name_key]) ? $tag[$name_key] : '' }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- Desktop view: Tabs -->
                                                            <div class="d-none d-md-block">
                                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                    <li class="nav-item" role="presentation">
                                                                        <button
                                                                            onclick="setTabKey('{{ $cat_name }}','')"
                                                                            class="nav-link active tags-btn"
                                                                            id="{{ $cat_name }}-tab"
                                                                            data-bs-toggle="tab"
                                                                            data-bs-target="#{{ $cat_name }}"
                                                                            type="button" role="tab"
                                                                            aria-controls="{{ $cat_name }}"
                                                                            aria-selected="true">{{ __('All') }}</button>
                                                                    </li>
                                                                    @foreach ($category_tags as $tag)
                                                                        @php
                                                                            $tag_items = getTagsProducts($tag['tag_id'], $category->id);
                                                                        @endphp
                                                                        @if (count($tag_items) > 0)
                                                                            <li class="nav-item" role="presentation">
                                                                                <button
                                                                                    onclick="setTabKey('{{ $tag['id'] }}','{{ $tag['tag_id'] }}')"
                                                                                    class="nav-link tags-btn"
                                                                                    id="{{ $tag['id'] }}-{{ $category->id }}-tab"
                                                                                    data-bs-toggle="tab"
                                                                                    data-bs-target="#tag{{ $tag['id'] }}{{ $category->id }}"
                                                                                    type="button" role="tab"
                                                                                    aria-controls="tag{{ $tag['id'] }}{{ $category->id }}"
                                                                                    aria-selected="false">{{ isset($tag[$name_key]) ? $tag[$name_key] : '' }}</button>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </div>

                                                    </div>
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="{{ $cat_name }}"
                                                            role="tabpanel" aria-labelledby="{{ $cat_name }}-tab">
                                                            @if (count($all_items) > 0)


                                                                @foreach ($all_items as $item)
                                                                    @php
                                                                        $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                                        $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                                        $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                                        $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';

                                                                    @endphp
                                                                    @if($item->type == 1)
                                                                        <div class="menu_item_list">
                                                                            <a href="#" data-bs-toggle="modal"
                                                                                onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})">
                                                                                <div class="menu_item_box @if($item['is_new'] == 1 || $item['as_sign'] == 1) new_item_box_icon @endif">
                                                                                {{-- New Product Image --}}
                                                                                    @if ($item['is_new'] == 1)
                                                                                        <img class="is_new tag-img position-absolute"
                                                                                            src="{{ asset('public/client_images/bs-icon/new.png') }}" style="top:0; left:0; width:50px;">
                                                                                    @endif

                                                                                    {{-- Signature Image --}}
                                                                                    @if ($item['as_sign'] == 1)
                                                                                        <img class="is_sign tag-img position-absolute"
                                                                                            src="{{ asset('public/client_images/bs-icon/signature.png') }}" style="top:0; right:40px; width:50px;">
                                                                                    @endif
                                                                                    <div class="menu_item_name">
                                                                                        {{-- Item Name --}}
                                                                                        <h4>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                                        </h4>
                                                                                        {{-- Item Description --}}
                                                                                        <p>{!! $desc !!}</p>
                                                                                        <div class="d-flex align-items-center mb-2">

                                                                                            {{-- Calories Section --}}
                                                                                                @if (isset($item[$calories_key]) && !empty($item[$calories_key]))
                                                                                                    <p class="m-0 me-3"><strong>Cal:
                                                                                                        </strong>{{ isset($item[$calories_key]) && !empty($item[$calories_key]) ? $item[$calories_key] : '' }}
                                                                                                    </p>
                                                                                                @endif
                                                                                                {{-- Ingredient Section --}}
                                                                                                @php
                                                                                                    $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                                                @endphp

                                                                                                @if (count($ingrediet_arr) > 0)
                                                                                                    <div class="item_tag">
                                                                                                        @foreach ($ingrediet_arr as $val)
                                                                                                            @php
                                                                                                                $ingredient = getIngredientDetail($val);
                                                                                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                                                $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                                            @endphp

                                                                                                            @if (
                                                                                                                (isset($package_permissions['special_icons']) &&
                                                                                                                    !empty($package_permissions['special_icons']) &&
                                                                                                                    $package_permissions['special_icons'] == 1) ||
                                                                                                                    $parent_ing_id != null)
                                                                                                                @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                                                        width="45px" height="45px">
                                                                                                                @endif
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                @endif
                                                                                        </div>
                                                                                        {{-- Price --}}
                                                                                        <ul class="menu_item_price_ul">
                                                                                            @php
                                                                                                $price_arr = getItemPrice($item['id']);
                                                                                            @endphp
                                                                                            @if (count($price_arr) > 0)
                                                                                                @foreach ($price_arr as $key => $value)
                                                                                                    @php
                                                                                                        $price = Currency::currency($currency)->format($value['price']);

                                                                                                        $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';

                                                                                                    @endphp
                                                                                                    <li>
                                                                                                        @if ($item_discount > 0)
                                                                                                            @php
                                                                                                                if ($item_discount_type == 'fixed') {
                                                                                                                    $new_amount = number_format($value['price'] - $item_discount, 2);
                                                                                                                } else {
                                                                                                                    $per_value = ($value['price'] * $item_discount) / 100;
                                                                                                                    $new_amount = number_format($value['price'] - $per_value, 2);
                                                                                                                }
                                                                                                            @endphp
                                                                                                            <span>
                                                                                                                {{ $price_label }}
                                                                                                                <span
                                                                                                                    class="text-decoration-line-through">{{ $price }}</span>
                                                                                                                <span>{{ Currency::currency($currency)->format($new_amount) }}</span>
                                                                                                            </span>
                                                                                                        @else
                                                                                                            <span>
                                                                                                                {{ $price_label }}
                                                                                                                <span>{{ $price }}</span>
                                                                                                            </span>
                                                                                                        @endif
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </ul>
                                                                                    </div>
                                                                                    @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                                    <div class="menu_item_img_inner">
                                                                                        <div class="menu_item_image @if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink')
                                                                                            special_day_blink
                                                                                            @elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate')
                                                                                            special_day_rotate
                                                                                            @endif">
                                                                                            <div class="special">
                                                                                                <label ></label>
                                                                                                <label ></label>
                                                                                                <label ></label>
                                                                                                <label ></label>
                                                                                            </div>
                                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}"
                                                                                                alt="" srcset=""
                                                                                                class="w-100">
                                                                                            </div>
                                                                                            <a  class="review_btn" onclick="openRatingModel({{ $item['id'] }})">
                                                                                                <i class="fa-solid fa-star"></i>
                                                                                                <i class="fa-solid fa-star"></i>
                                                                                                <i class="fa-solid fa-star"></i>
                                                                                            </a>
                                                                                    </div>
                                                                                    @endif
                                                                                </div>
                                                                            </a>
                                                                        </div>

                                                                    @else
                                                                    <div class="menu_item_list">
                                                                         <div class="menu_item_box" style="justify-content: center">
                                                                            <div class="menu_item_name">
                                                                                     {{-- Item Name --}}
                                                                                <h4>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                                </h4>
                                                                                    {{-- Item Description --}}
                                                                                <p>{!! $desc !!}</p>
                                                                            </div>
                                                                         </div>
                                                                    </div>
                                                                    @endif


                                                                @endforeach
                                                            @else
                                                                <div class="menu_item_box">
                                                                    <div class="menu_item_name">
                                                                        <h4>Items Not Found !</h4>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @foreach ($category_tags as $tag)

                                                            @php
                                                                $tag_items = getTagsProducts($tag['tag_id'], $category->id);
                                                            @endphp
                                                            <div class="tab-pane fade show"
                                                                id="tag{{ $tag['id'] }}{{ $category->id }}"
                                                                role="tabpanel"
                                                                aria-labelledby="{{ $tag['id'] }}-{{ $category->id }}-tab">
                                                                @if (count($tag_items) > 0)
                                                                    @foreach ($tag_items as $item)
                                                                        @php
                                                                            $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                                            $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                                            $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                                            $tag_desc = isset($item[$description_key]) ? $item[$description_key] : '';
                                                                        @endphp
                                                                        @if($item->type == 1)
                                                                        <div class="menu_item_list">
                                                                            <a href="#" data-bs-toggle="modal"
                                                                                onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})">
                                                                                <div class="menu_item_box @if($item['is_new'] == 1 || $item['as_sign'] == 1) new_item_box_icon @endif">
                                                                                    {{-- New Product Image --}}
                                                                                    @if ($item['is_new'] == 1)
                                                                                        <img class="is_new tag-img position-absolute"
                                                                                            src="{{ asset('public/client_images/bs-icon/new.png') }}" style="top:0; left:0; width:50px;">
                                                                                    @endif

                                                                                    {{-- Signature Image --}}
                                                                                    @if ($item['as_sign'] == 1)
                                                                                        <img class="is_sign tag-img position-absolute"
                                                                                            src="{{ asset('public/client_images/bs-icon/signature.png') }}" style="top:0; right:20px;  width:50px;">
                                                                                    @endif
                                                                                    <div class="menu_item_name">
                                                                                        {{-- Item Name --}}
                                                                                        <h4>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                                        </h4>
                                                                                        {{-- Item Description --}}
                                                                                        <p>{!! $tag_desc !!}</p>
                                                                                        <div class="d-flex align-items-center mb-2">
                                                                                        {{-- Calories Section --}}
                                                                                                @if (isset($item[$calories_key]) && !empty($item[$calories_key]))
                                                                                                    <p class="m-0 me-3"><strong>Cal:
                                                                                                        </strong>{{ isset($item[$calories_key]) && !empty($item[$calories_key]) ? $item[$calories_key] : '' }}
                                                                                                    </p>
                                                                                                @endif
                                                                                                {{-- Ingredient Section --}}
                                                                                                @php
                                                                                                    $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                                                @endphp

                                                                                                @if (count($ingrediet_arr) > 0)
                                                                                                    <div class="item_tag">
                                                                                                        @foreach ($ingrediet_arr as $val)
                                                                                                            @php
                                                                                                                $ingredient = getIngredientDetail($val);
                                                                                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                                                $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                                            @endphp

                                                                                                            @if (
                                                                                                                (isset($package_permissions['special_icons']) &&
                                                                                                                    !empty($package_permissions['special_icons']) &&
                                                                                                                    $package_permissions['special_icons'] == 1) ||
                                                                                                                    $parent_ing_id != null)
                                                                                                                @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                                                        width="45px" height="45px">
                                                                                                                @endif
                                                                                                            @endif
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                @endif

                                                                                        </div>
                                                                                        {{-- Price --}}
                                                                                        <ul class="menu_item_price_ul">
                                                                                            @php
                                                                                                $price_arr = getItemPrice($item['id']);
                                                                                            @endphp
                                                                                            @if (count($price_arr) > 0)
                                                                                                @foreach ($price_arr as $key => $value)
                                                                                                    @php
                                                                                                        $price = Currency::currency($currency)->format($value['price']);

                                                                                                        $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';

                                                                                                    @endphp
                                                                                                    <li>
                                                                                                        @if ($item_discount > 0)
                                                                                                            @php
                                                                                                                if ($item_discount_type == 'fixed') {
                                                                                                                    $new_amount = number_format($value['price'] - $item_discount, 2);
                                                                                                                } else {
                                                                                                                    $per_value = ($value['price'] * $item_discount) / 100;
                                                                                                                    $new_amount = number_format($value['price'] - $per_value, 2);
                                                                                                                }
                                                                                                            @endphp
                                                                                                            <span>
                                                                                                                {{ $price_label }}
                                                                                                                <span
                                                                                                                    class="text-decoration-line-through">{{ $price }}</span>
                                                                                                                <span>{{ Currency::currency($currency)->format($new_amount) }}</span>
                                                                                                            </span>
                                                                                                        @else
                                                                                                            <span>
                                                                                                                {{ $price_label }}
                                                                                                                <span>{{ $price }}</span>
                                                                                                            </span>
                                                                                                        @endif
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </ul>
                                                                                    </div>
                                                                                    @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                                    <div class="menu_item_img_inner">
                                                                                        <div class="menu_item_image @if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink')
                                                                                            special_day_blink
                                                                                            @elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate')
                                                                                            special_day_rotate
                                                                                            @endif">
                                                                                            <div class="special">
                                                                                                <label ></label>
                                                                                                <label ></label>
                                                                                                <label ></label>
                                                                                                <label ></label>
                                                                                            </div>
                                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}"
                                                                                                alt=""
                                                                                                srcset=""
                                                                                                class="w-100">
                                                                                            </div>
                                                                                            <a  class="review_btn" onclick="openRatingModel({{ $item['id'] }})">
                                                                                                <i class="fa-solid fa-star"></i>
                                                                                                <i class="fa-solid fa-star"></i>
                                                                                                <i class="fa-solid fa-star"></i>
                                                                                            </a>
                                                                                    </div>
                                                                                    @endif
                                                                                </div>
                                                                            </a>
                                                                        </div>
                                                                        @else
                                                                          <div class="menu_item_list">
                                                                            <div class="menu_item_box justify-content-center">
                                                                            <div class="menu_item_name">
                                                                                        {{-- Item Name --}}
                                                                                <h4>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                                </h4>
                                                                                    {{-- Item Description --}}
                                                                                    @if ($tag_desc)
                                                                                        <p>{!! $tag_desc !!}</p>
                                                                                    @endif
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                @if (count($all_items) > 0)
                                                </div>
                                                @foreach ($all_items as $item)
                                                    @php
                                                        $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                        $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                                        $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                                        $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';

                                                    @endphp
                                                    @if($item->type == 1)
                                                        <div class="menu_item_list">
                                                            <a href="#" data-bs-toggle="modal"
                                                                onclick="getItemDetails({{ $item->id }},{{ $shop_details['id'] }})">
                                                                <div class="menu_item_box @if($item['is_new'] == 1 || $item['as_sign'] == 1) new_item_box_icon @endif">
                                                                                {{-- New Product Image --}}
                                                                                    @if ($item['is_new'] == 1)
                                                                                        <img class="is_new tag-img position-absolute"
                                                                                            src="{{ asset('public/client_images/bs-icon/new.png') }}" style="top:0; left:0; width:50px;">
                                                                                    @endif

                                                                                    {{-- Signature Image --}}
                                                                                    @if ($item['as_sign'] == 1)
                                                                                        <img class="is_sign tag-img position-absolute"
                                                                                            src="{{ asset('public/client_images/bs-icon/signature.png') }}" style="top:0; right:40px; width:50px;">
                                                                                    @endif
                                                                    <div class="menu_item_name">
                                                                        {{-- Item Name --}}
                                                                        <h4>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                                        </h4>
                                                                        {{-- Item Description --}}
                                                                        <p>{!! $desc !!}</p>

                                                                        <div class="d-flex align-items-center mb-2">
                                                                                {{-- Calories Section --}}
                                                                                    @if (isset($item[$calories_key]) && !empty($item[$calories_key]))
                                                                                        <p class="m-0 me-3"><strong>Cal:
                                                                                            </strong>{{ isset($item[$calories_key]) && !empty($item[$calories_key]) ? $item[$calories_key] : '' }}
                                                                                        </p>
                                                                                    @endif
                                                                                    {{-- Ingredient Section --}}
                                                                                    @php
                                                                                        $ingrediet_arr = isset($item['ingredients']) && !empty($item['ingredients']) ? unserialize($item['ingredients']) : [];
                                                                                    @endphp

                                                                                    @if (count($ingrediet_arr) > 0)
                                                                                        <div class="item_tag">
                                                                                            @foreach ($ingrediet_arr as $val)
                                                                                                @php
                                                                                                    $ingredient = getIngredientDetail($val);
                                                                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                                    $parent_ing_id = isset($ingredient['parent_id']) ? $ingredient['parent_id'] : null;
                                                                                                @endphp

                                                                                                @if (
                                                                                                    (isset($package_permissions['special_icons']) &&
                                                                                                        !empty($package_permissions['special_icons']) &&
                                                                                                        $package_permissions['special_icons'] == 1) ||
                                                                                                        $parent_ing_id != null)
                                                                                                    @if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon))
                                                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon) }}"
                                                                                                            width="45px" height="45px">
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </div>
                                                                                    @endif
                                                                        </div>

                                                                        {{-- Price --}}
                                                                        <ul class="menu_item_price_ul">
                                                                            @php
                                                                                $price_arr = getItemPrice($item['id']);
                                                                            @endphp
                                                                            @if (count($price_arr) > 0)
                                                                                @foreach ($price_arr as $key => $value)
                                                                                    @php
                                                                                        $price = Currency::currency($currency)->format($value['price']);

                                                                                        $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';

                                                                                    @endphp
                                                                                    <li>
                                                                                        @if ($item_discount > 0)
                                                                                            @php
                                                                                                if ($item_discount_type == 'fixed') {
                                                                                                    $new_amount = number_format($value['price'] - $item_discount, 2);
                                                                                                } else {
                                                                                                    $per_value = ($value['price'] * $item_discount) / 100;
                                                                                                    $new_amount = number_format($value['price'] - $per_value, 2);
                                                                                                }
                                                                                            @endphp
                                                                                            <span>
                                                                                                {{ $price_label }} <span
                                                                                                    class="text-decoration-line-through">{{ $price }}</span>
                                                                                                <span>{{ Currency::currency($currency)->format($new_amount) }}</span>
                                                                                            </span>
                                                                                        @else
                                                                                            <span>
                                                                                                {{ $price_label }}
                                                                                                <span>{{ $price }}</span>
                                                                                            </span>
                                                                                        @endif
                                                                                    </li>
                                                                                @endforeach
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                    <div class="menu_item_img_inner">
                                                                        @if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                            <div class="menu_item_image">
                                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) }}"
                                                                                    alt="" srcset="" class="w-100">
                                                                                </div>
                                                                                <a  class="review_btn" onclick="openRatingModel({{ $item['id'] }})">
                                                                                    <i class="fa-solid fa-star"></i>
                                                                                    <i class="fa-solid fa-star"></i>
                                                                                    <i class="fa-solid fa-star"></i>
                                                                                </a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @else
                                                    <div class="menu_item_list">
                                                        <div class="menu_item_box" style="justify-content: center">
                                                        <div class="menu_item_name">
                                                                    {{-- Item Name --}}
                                                            <h4>{{ isset($item[$name_key]) && !empty($item[$name_key]) ? $item[$name_key] : '' }}
                                                            </h4>
                                                                {{-- Item Description --}}
                                                            <p>{!! $desc !!}</p>
                                                        </div>
                                                        </div>
                                                </div>
                                    @endif
                                @endforeach
                    @else
                        <div class="menu_item_box">
                            <div class="menu_item_name">
                                <h4>Items Not Found !</h4>
                            </div>
                        </div>
    @endif
    @endif
    @endif
    </section>
    @endif
    @endif
    @endforeach
    @endif
    </div>
    </div>
    </div>
    </div>
    </div>

    <div class="side_menu ">
        <div class="side_menu_title">
            <h3>Categories</h3>
            <i class="fa-solid fa-utensils"></i>
        </div>
        <div class="barger_menu_main">
            <div class="barger_menu_inner">
                <div class="barger_menu_icon">
                    <i class="fa-solid fa-bars"></i>
                </div>
                <div class="barger_menu_list">
                    <ul>
                        <li>
                            <div class="barger_menu_box">
                                <button class="btn search_bt openSearchInnerPage" id="openSearchInnerPage">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button class="btn search_bt closeSearchInnerPage d-none" id="closeSearchInnerPage">
                                    <i class="fa-solid fa-times"></i>
                                </button>
                            </div>
                        </li>
                        @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                        @if ($table_enable_status == 1 || $room_enable_status == 1)                            
                            <li>
                                <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                    <i class="fa-solid fa-bell"></i>
                                </a>
                            </li>
                        @endif
                        @endif
                        @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                        <li>
                            <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
                        <div class="mob_side_cat">
                            <div class="mob_side_cat_title">
                                <h3 onclick="openMenu({{ $shop_details['id'] }})">{{ __('MENU') }}<i class="fa-solid fa-angle-down ms-2"></i></h3>
                                <a class="back_to_home" onclick="homePage('{{ $shop_details['shop_slug'] }}')">
                                    <!-- {{ isset($cat_details[$name_key]) ? $cat_details[$name_key] : '' }} -->
                                    <i class="fa-solid fa-arrow-left back_icon"></i>
                                </a>
                            </div>
                        <!-- <div class="side_menu_inr">
                            <ul>
                                @if (count($categories) > 0)
                                    @foreach ($categories as $category)
                                        @if ($category->category_type == 'product_category')
                                            @php
                                                $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                                $name_code = $current_lang_code . '_name';
                                                $nameId = str_replace(' ', '_', $category->en_name);
                                                $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                            @endphp
                                            @if ($active_cat == 1)
                                                @if ($check_cat_type_permission == 1)
                                                    <li>
                                                            <a onclick="scrollToSection('{{ $nameId }}', 150)" class="{{ $nameId }} scrollTab">
                                                            <span></span>
                                                            {{ isset($category->$name_code) ? $category->$name_code : '' }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                            </ul>
                        </div> -->
                            <div class="side_menu_inr">
                                <ul id="par_cats">
                                        @php
                                            $categories_data =  $categories_parent;
                                        @endphp

                                    @if (count($categories_data) > 0)
                                        @foreach ($categories_data as $category)
                                            @if ($category->category_type == 'product_category' || $category->category_type == 'parent_category')
                                                @php
                                                    $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                                    $name_code = $current_lang_code . '_name';
                                                    $nameId = str_replace(' ', '_', $category->en_name);
                                                    $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                                    $child_categories = getChildCategories($category->id); // Assuming a function to get child categories
                                                @endphp
                                                @if ($active_cat == 1 && $check_cat_type_permission == 1)
                                                    <li>
                                                        @if($category->category_type == 'product_category')
                                                            @if($category->parent_id == $cat_parent_id)
                                                                <a onclick="scrollToSection('{{ $nameId }}', 150)" class="{{ $nameId }} scrollTab">
                                                            @else
                                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id]) }}" class="{{ $nameId }} scrollTab">
                                                            @endif
                                                        @else
                                                            <a href="{{ route('restaurant', [$shop_slug, $category->id]) }}" class="{{ $nameId }} scrollTab">
                                                        @endif
                                                            <span></span>
                                                            {{ isset($category->$name_code) ? $category->$name_code : '' }}
                                                        </a>
                                                        @if(count($child_categories) > 0)
                                                            <ul class="side_menu_inr_sub_cat"> <!-- Start Child Categories -->
                                                                @foreach($child_categories as $child_category)
                                                                    @php
                                                                        $active_child_cat = checkCategorySchedule($child_category->id, $child_category->shop_id);
                                                                        $child_name_code = $current_lang_code . '_name';
                                                                        $child_nameId = str_replace(' ', '_', $child_category->en_name);
                                                                        $check_child_cat_type_permission = checkCatTypePermission($child_category->category_type, $shop_details['id']);
                                                                    @endphp
                                                                    @if ($active_child_cat == 1 && $check_child_cat_type_permission == 1)
                                                                        <li>
                                                                             @if($child_category->parent_id == $cat_parent_id)
                                                                                <a onclick="scrollToSection('{{ $child_nameId }}', 150)" class="{{ $child_nameId }} scrollTab">
                                                                             @else
                                                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $child_category->id]) }}" class="{{ $child_nameId }} scrollTab">
                                                                             @endif
                                                                            <!-- <a onclick="scrollToSection('{{ $child_nameId }}', 150)" class="{{ $child_nameId }} scrollTab"> -->
                                                                                <span></span>
                                                                                {{ isset($child_category->$child_name_code) ? $child_category->$child_name_code : '' }}
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul> <!-- End Child Categories -->
                                                        @endif
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                                <ul class="child-cats">
                                    @php
                                        $categories_data =  $categories_childs;
                                    @endphp

                                    @if (count($categories_data) > 0)
                                        @foreach ($categories_data as $category)
                                            @if ($category->category_type == 'product_category' || $category->category_type == 'parent_category')
                                                @php
                                                    $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                                    $name_code = $current_lang_code . '_name';
                                                    $nameId = str_replace(' ', '_', $category->en_name);
                                                    $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                                    $child_categories = getChildCategories($category->id); // Assuming a function to get child categories
                                                @endphp
                                                @if ($active_cat == 1 && $check_cat_type_permission == 1)
                                                    <li>
                                                        @if($category->category_type == 'product_category')
                                                            @if($category->parent_id == $cat_parent_id)
                                                                <a onclick="scrollToSection('{{ $nameId }}', 150)" class="{{ $nameId }} scrollTab">
                                                            @else
                                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id]) }}" class="{{ $nameId }} scrollTab">
                                                            @endif
                                                        @else
                                                            <a href="{{ route('restaurant', [$shop_slug, $category->id]) }}" class="{{ $nameId }} scrollTab">
                                                        @endif
                                                            <span></span>
                                                            {{ isset($category->$name_code) ? $category->$name_code : '' }}
                                                        </a>
                                                        @if(count($child_categories) > 0)
                                                            <ul class="side_menu_inr_sub_cat"> <!-- Start Child Categories -->
                                                                @foreach($child_categories as $child_category)
                                                                    @php
                                                                        $active_child_cat = checkCategorySchedule($child_category->id, $child_category->shop_id);
                                                                        $child_name_code = $current_lang_code . '_name';
                                                                        $child_nameId = str_replace(' ', '_', $child_category->en_name);
                                                                        $check_child_cat_type_permission = checkCatTypePermission($child_category->category_type, $shop_details['id']);
                                                                    @endphp
                                                                    @if ($active_child_cat == 1 && $check_child_cat_type_permission == 1)
                                                                        <li>
                                                                            @if($child_category->parent_id == $cat_parent_id)
                                                                                <a onclick="scrollToSection('{{ $child_nameId }}', 150)" class="{{ $child_nameId }} scrollTab">
                                                                            @else
                                                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $child_category->id]) }}" class="{{ $child_nameId }} scrollTab">
                                                                            @endif
                                                                            <!-- <a onclick="scrollToSection('{{ $child_nameId }}', 150)" class="{{ $child_nameId }} scrollTab"> -->
                                                                                <span></span>
                                                                                {{ isset($child_category->$child_name_code) ? $child_category->$child_name_code : '' }}
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul> <!-- End Child Categories -->
                                                        @endif
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
    </div>
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
    @endif

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



@endsection


{{-- Page JS Function --}}
@section('page-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>

    <script type="text/javascript">
     var layout = "{{ $layout }}";

     var BannerSpeed = {{ $slider_delay_time }};

        $('document').ready(function() {

            var totalTab = $('#is_cat_tab').val();
            if (totalTab > 0) {
                $('#current_tab_id').val('all');
            }
        });



        // Remove Item Details from Model
        $('#itemDetailsModal .btn-close').on('click', function() {
            $('#itemDetailsModal #item_dt_div').html('');
        });

        // Function for get Filterd Categories layout 2 Home Page
        $('#search_btn').on('click', function() {

            var keyword = $('.search_layout').val();
            var catID = $('#current_category_id').val();
            var tabID = $('#current_tab_id').val();
            var tag_id = $('#tag_id').val();
            var parent_id = $('#parent_id').val();
            var shop_id = $('#shop_id').val();

            $.ajax({
                type: "POST",
                url: "{{ route('shop.items.search') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "category_id": catID,
                    "tab_id": tabID,
                    "keyword": keyword,
                    "shop_id": shop_id,
                    "tag_id": tag_id,
                    "parent_id": parent_id,
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.success == 1) {
                        $('.category_inr_list_item').html('');
                        $('.category_inr_list_item').append(response.data);
                    }
                }
            });
        });

        // Function for get Filterd Categories layout 2 Home Page
        $('.src_btn_inner').on('click', function() {

            var keyword = $('.search_layout').val();
            var catID = $('#current_category_id').val();
            var tabID = $('#current_tab_id').val();
            var tag_id = $('#tag_id').val();
            var parent_id = $('#parent_id').val();
            var shop_id = $('#shop_id').val();
            var layout = "{{ $layout }}";


            $.ajax({
                type: "POST",
                url: "{{ route('shop.items.search') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "category_id": catID,
                    "tab_id": tabID,
                    "keyword": keyword,
                    "shop_id": shop_id,
                    "tag_id": tag_id,
                    "parent_id": parent_id,
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.success == 1) {
                        if(layout == 'layout_3'){
                            $('.append_cat_src').html('');
                            $('.append_cat_src').append(response.data);
                        }else{
                            $('.nav-tabs').hide();
                             $('.category_inr_list_item').html('');
                             $('.category_inr_list_item').append(response.data);
                        }

                    }
                }
            });
        });
        // Function for clear search input layout_2 inner page
        $('.clr_btn_inner').on('click', function() {
                $('.search_layout').val('');
                location.reload();
                // $('.src_btn_inner').click();
            });

        // Function for clear search input layout_3 inner page
        $('.clr_btn_inner_layout_three').on('click', function() {
            $('.search_layout').val('');
                location.reload();
        })
        // Function for clear search input
           $('#clear_btn').on('click', function() {
                $('#search_layout').val('');
                $('#search_btn').click();
            });

            if(layout == 'layout_2'){
                $('#closeSearchBox').on('click',function()
                {
                    $("#closeSearchBox").addClass("d-none");
                    $('#openSearchBox').removeClass("d-none");
                    $(".search_input").removeClass("d-block");
                    $('#search').val('');
                    $('#search').trigger('keyup');
                    location.reload();
                });
            }else{
                $('#closeSearchBox').on('click',function()
                {
                    $("#closeSearchBox").addClass("d-none");
                    $('#openSearchBox').removeClass("d-none");
                    $(".search_input").removeClass("d-block");
                    $('#search').val('');
                    $('#search').trigger('keyup');
                    // location.reload();
                });
            }

        // Function for Search Items
        $('#search').on('keyup', function() {
            var catID = $('#current_category_id').val();
            var tabID = $('#current_tab_id').val();
            var tag_id = $('#tag_id').val();
            var parent_id = $('#parent_id').val();
            var shop_id = $('#shop_id').val();
            var keyword = $(this).val();

            $.ajax({
                type: "POST",
                url: "{{ route('shop.items.search') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "category_id": catID,
                    "tab_id": tabID,
                    "keyword": keyword,
                    "shop_id": shop_id,
                    "tag_id": tag_id,
                    "parent_id": parent_id,
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.success == 1) {
                        if (tabID == 'no_tab') {
                            $('.item_inr_info').html('');
                            $('.item_inr_info').append(response.data);
                        } else {
                            if (keyword == '') {
                                $('.item_inr_info #myTab').show();
                                $('.cat_name').show();
                            } else {
                                $('.item_inr_info #myTab').hide();
                                $('.cat_name').hide();
                            }

                            if (tabID == 'all') {
                                $('#' + tabID).html('');
                                $('#' + tabID).append(response.data);
                            } else {
                                // alert('#tag'+tabID);
                                $('#tag' + tabID).html('');
                                $('#tag' + tabID).append(response.data);
                            }
                        }
                    } else {
                        console.log(response.message);
                    }
                }
            });

        });

        function setTabKey(key, tagID) {
            $('#current_tab_id').val(key);
            $('#tag_id').val(tagID);
        }

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

        $(function() {

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

            $("#coverflow").flipster({

                // Container for the flippin' items.
                itemContainer: 'ul',
                // Selector for children of itemContainer to flip
                itemSelector: 'li',
                style: category_effect,
                fadeIn: 400,
                loop: true,
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
                responsive: [{
                    breakpoint: 991,
                    settings: {
                    slidesToShow: 3,
                    }
                },{
                    breakpoint: 767,
                    settings: {
                    slidesToShow: 2,
                    }
                }] 
            });

            slider.on("wheel", function (e) {
                e.preventDefault();

                if (e.originalEvent.deltaY < 0) {
                    $(this).slick("slickPrev");
                } else {
                    $(this).slick("slickNext");
                }
            });

            $('.item_box_main').removeClass('d-none');

        });

        // layout 2 slider
        $(function() {

            // Find the index of the selected category in the array

            $("#coverflow-layout-two").flipster({

                // Container for the flippin' items.
                itemContainer: 'ul',
                // Selector for children of itemContainer to flip
                itemSelector: 'li',
                style: 'coverflow',
                // start:'center',
                // start: selectedIndex,
                // Fading speed
                fadeIn: 400,
                slidesPerView: 3,
                loop: true,
                autoplay: false,
                pauseOnHover: true,
                spacing: -0.6,
                click: true,
                keyboard: true,
                scrollwheel: true,
                touch: true,
                nav: false,
                buttons: false,
                buttonPrev: 'Previous',
                buttonNext: 'next',
                onItemSwitch: $.noop,

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
                touchDrag: true,
                click: true,
                keyboard: true,
                1: true,
                touch: true,
                pullDrag: false,
                autoplay: false,
                navText: [
                    '<span class="fa-stack fa-lg"><i class="fa fa-caret-left fa-stack-2x"></i></span>',
                    '<span class="fa-stack fa-lg"><i class="fa fa-caret-right fa-stack-2x"></i></span>'
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

        $('.tagSelectBox').on('change', function() {
            var selectedTab = $(this).val();

            $('#'+selectedTab).parent().children('.tab-pane').removeClass('active');

            // Show the selected tab content
            $('#'+selectedTab).addClass('active');
        });


        $(document).ready(function() {

            function checkScroll() {

                // Check if the window width is less than or equal to 768px (tablet and mobile views)
                if ($(window).width() <= 1199) {
                    if ($(this).scrollTop() > 160) {

                        $('.side_menu').addClass('openmenu');
                        $('.back_service').addClass('d-none');
                        $('.header_right .barger_menu_main').addClass('d-none');
                        $('.banner .waiter_notification').addClass('d-none');

                    } else {
                        $('.side_menu').removeClass('openmenu');
                        $('.back_service').removeClass('d-none');
                        $('.header_right .barger_menu_main').removeClass('d-none');
                        $('.banner .waiter_notification').removeClass('d-none');

                    }
                } else {
                    // If the window width is greater than 768px, remove the class
                    $('.side_menu').removeClass('openmenu');
                    $('.back_service').removeClass('d-none');
                    $('.header_right .barger_menu_main').removeClass('d-none');
                    $('.banner .waiter_notification').removeClass('d-none');
                }
            }

            // Initial check on page load
            checkScroll();

            // Listen for scroll and resize events
            $(window).on('scroll resize', checkScroll);
        });

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

        @if ($layout == 'layout_2')
            $(window).resize(function() {
                if ($(window).width() < 991) {
                    $('.header').hide();
                    $('.header_preview').show();
                    $('.swiper-container-layout-two').show();
                } else {
                    $('.header').show();
                    $('.header_preview').hide();
                    $('.swiper-container-layout-two').hide();
                }
            });
        @endif

        $( document ).ready(function() {
            var defCat = $("#def_cat").val();
            scrollToSection(defCat,150);
        });

        function scrollToSection(sectionId, offset) {

            $('.scrollTab').removeClass('active');

            $('.'+sectionId).addClass('active');

            var targetSection = document.getElementById(sectionId);
            var topOffset = targetSection?.offsetTop - offset;

            window.scrollTo({ top: topOffset, behavior: 'smooth',});
        }




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
                        console.log(response);
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


        $(document).ready(function(){
            var layout = "{{ $layout }}";
            if(layout == 'layout_2'){
                $('.tags-btn').click(function () {
                    var tabPaneId = $(this).attr('data-bs-target');
                    var tabId = $(this).attr('id');

                    $('.tags-btn').removeClass('active');

                // Show the selected tab content
                $('.'+tabId).addClass('active');
                    var isActive = $(this).hasClass('active');
                    // $('.tags-btn').removeClass('active');
                    if (!isActive) {
                        $('.' + tabId).addClass('active');
                    }
                    $('.tab-pane').removeClass('show active');
                    $(tabPaneId).addClass('show active');
                });
            }
        });

        if(layout == 'layout_3'){

            $(window).scroll(function() {
                    var scroll = $(window).scrollTop(); // Get the vertical scroll position

                    // Define a threshold scroll position where you want to add the class
                    var threshold = 100; // Change this value according to your requirement

                    // Check if the scroll position is beyond the threshold
                    if (scroll >= threshold) {
                        // If scroll position is beyond the threshold, add the class to the element
                        $('.header_inr').addClass('fix-btn');


                    } else {
                        // If scroll position is not beyond the threshold, remove the class from the element
                        $('.header_inr').removeClass('fix-btn');
                    }
            });


        }

        $(document).ready(function () {
            var size = $(window).width(); 
           if(size > 768){
                $('#par_cats').removeClass('d-none');
                $('.child-cats').addClass('d-none');
           }else{
                $('#par_cats').addClass('d-none');
                $('.child-cats').removeClass('d-none');
           }
        });

    </script>

@endsection
