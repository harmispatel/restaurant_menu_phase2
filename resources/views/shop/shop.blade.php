@php
    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);

    $shop_intro_icon_is_cube = (isset($shop_settings['shop_intro_icon_is_cube']) && $shop_settings['shop_intro_icon_is_cube'] == 1) ? $shop_settings['shop_intro_icon_is_cube'] : 0;
    $intro_icon_status      = (isset($shop_settings['intro_icon_status']) &&  $shop_settings['intro_icon_status'] == 1) ? $shop_settings['intro_icon_status'] : 0;

    $shop_intro_icon = isset($shop_settings['shop_intro_icon']) ? $shop_settings['shop_intro_icon'] : '';
    $shop_intro_icon_1 = isset($shop_settings['shop_intro_icon_1']) ? $shop_settings['shop_intro_icon_1'] : '';
    $shop_intro_icon_2 = isset($shop_settings['shop_intro_icon_2']) ? $shop_settings['shop_intro_icon_2'] : '';
    $shop_intro_icon_3 = isset($shop_settings['shop_intro_icon_3']) ? $shop_settings['shop_intro_icon_3'] : '';
    $shop_intro_icon_4 = isset($shop_settings['shop_intro_icon_4']) ? $shop_settings['shop_intro_icon_4'] : '';
    $shop_intro_icon_link_1 = isset($shop_settings['shop_intro_icon_link_1']) ? $shop_settings['shop_intro_icon_link_1'] : '';
    $shop_intro_icon_link_2 = isset($shop_settings['shop_intro_icon_link_2']) ? $shop_settings['shop_intro_icon_link_2'] : '';
    $shop_intro_icon_link_3 = isset($shop_settings['shop_intro_icon_link_3']) ? $shop_settings['shop_intro_icon_link_3'] : '';
    $shop_intro_icon_link_4 = isset($shop_settings['shop_intro_icon_link_4']) ? $shop_settings['shop_intro_icon_link_4'] : '';

    $is_loader = isset($shop_settings['is_loader']) ? $shop_settings['is_loader'] : '';
    $shop_loader = isset($shop_settings['shop_loader']) ? $shop_settings['shop_loader'] : '';
    $currency = isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency']) ? $shop_settings['default_currency'] : 'EUR';

    $waiter_call_status = isset($shop_settings['waiter_call_status']) ?  $shop_settings['waiter_call_status'] : '0';

    $is_sub_title = isset($shop_settings['is_sub_title']) ? $shop_settings['is_sub_title'] : '0';

    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Intro Second
    $intro_second = isset($shop_settings['intro_icon_duration']) && !empty($shop_settings['intro_icon_duration']) ? $shop_settings['intro_icon_duration'] : '';
    $shop_start_time = isset($shop_settings['shop_start_time']) && !empty($shop_settings['shop_start_time']) ? $shop_settings['shop_start_time'] : '';
    $shop_end_time = isset($shop_settings['shop_end_time']) && !empty($shop_settings['shop_end_time']) ? $shop_settings['shop_end_time'] : '';
    $happy_start_time = isset($shop_settings['happy_start_time']) && !empty($shop_settings['happy_start_time']) ? $shop_settings['happy_start_time'] : '';
    $happy_end_time = isset($shop_settings['happy_end_time']) && !empty($shop_settings['happy_end_time']) ? $shop_settings['happy_end_time'] : '';

    // Shop Name
    $shop_name = isset($shop_details['name']) && !empty($shop_details['name']) ? $shop_details['name'] : '';

    // Shop Title
    $shop_subtitle = isset($shop_settings['business_subtitle']) && !empty($shop_settings['business_subtitle']) ? $shop_settings['business_subtitle'] : '';
    $shop_desc = isset($shop_details['description']) && !empty($shop_details['description']) ? strip_tags($shop_details['description']) : '';

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');    

    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Get Banner Settings
    $shop_banners = getBanners($shop_details['id']);
    $shop_banner_count = count($shop_banners) == 1 ? 'false' : 'true';
    $banner_key = $language_details['code'] . '_image';
    $banner_text_key = $language_details['code'] . '_description';
    $price_label_key = $current_lang_code . '_label';
    $title_key = $current_lang_code."_title";

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);

    $category_image_slider_delay_time = (isset($theme_settings['category_image_slider_delay_time']) && !empty($theme_settings['category_image_slider_delay_time'])) ? $theme_settings['category_image_slider_delay_time'] : 100;
    $category_iamge_slider = isset($theme_settings['category_image_sider']) && !empty($theme_settings['category_image_sider']) ? $theme_settings['category_image_sider'] : 'stop';
    $slider_buttons = isset($theme_settings['banner_slide_button']) && !empty($theme_settings['banner_slide_button']) ? $theme_settings['banner_slide_button'] : 0;
    $slider_delay_time = isset($theme_settings['banner_delay_time']) && !empty($theme_settings['banner_delay_time']) ? $theme_settings['banner_delay_time'] : 3000;
    $banner_height = isset($theme_settings['banner_height']) && !empty($theme_settings['banner_height']) ? $theme_settings['banner_height'] : 250;
    $category_view = isset($theme_settings['category_view']) && !empty($theme_settings['category_view']) ? $theme_settings['category_view'] : 'grid';
    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';
    $effect = isset($theme_settings['slider_effect']) ? $theme_settings['slider_effect'] : 'fabe';
    $stiky_header =  isset($theme_settings['sticky_header']) && !empty($theme_settings['sticky_header']) ? $theme_settings['sticky_header']  : '';

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

    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_details['id']);

    // Home Page Intro
    $homepage_intro = moreTranslations($shop_details['id'], 'homepage_intro');
    $homepage_intro = isset($homepage_intro[$current_lang_code . '_value']) && !empty($homepage_intro[$current_lang_code . '_value']) ? $homepage_intro[$current_lang_code . '_value'] : '';

    // Home Page Intro
    $seo_message = moreTranslations($shop_details['id'], 'seo_message');
    $seo_message = isset($seo_message[$current_lang_code . '_value']) && !empty($seo_message[$current_lang_code . '_value']) ? $seo_message[$current_lang_code . '_value'] : '';

    // Header Text One
    $header_text_one = moreTranslations($shop_details['id'], 'header_text_1');
    $header_text_one = isset($header_text_one[$current_lang_code. '_value']) && !empty($header_text_one[$current_lang_code . '_value']) ? $header_text_one[$current_lang_code . '_value'] : '';

    // Header Text Two
    $header_text_two = moreTranslations($shop_details['id'], 'header_text_2');
    $header_text_two = isset($header_text_two[$current_lang_code. '_value']) && !empty($header_text_two[$current_lang_code . '_value']) ? $header_text_two[$current_lang_code . '_value'] : '';

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    // Name Key
    $name_key = $current_lang_code . '_name';

    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';
    $logo_position = isset($theme_settings['logo_position']) ? $theme_settings['logo_position'] : '';
    $search_box_position = isset($theme_settings['search_box_position']) ? $theme_settings['search_box_position'] : '';

    // header image

    $header_img = (isset($theme_settings['header_image']) && !empty($theme_settings['header_image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image']) : asset('public/client/assets/images2/allo_spritz.jpg');

    // $shop_desc= html_entity_decode($shop_desc);
    $shop_title = "$shop_name | $seo_message";

    $shop_id = isset($shop_details['id']) ? $shop_details['id'] : '';

    // Get Language Settings
    $language_settings = clientLanguageSettings($shop_id);

    $google_translate = isset($language_settings['google_translate']) ? $language_settings['google_translate'] : 0;

    $encrptyShopId = encrypt($shop_details['id']);

    // Cart Quantity
    $total_quantity = getCartQuantity();

    // Cart Item
    $cart = session()->get('cart', []);

    // Total Amount
    $total_amount = 0;
    // Shop Currency
    $currency = isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency']) ? $shop_settings['default_currency'] : 'EUR';

    // Total Amount
    foreach ($cart as $cart_key => $cart_data) {
        foreach ($cart_data as $cart_val) {
            foreach ($cart_val as $cart_item_key => $cart_item) {
                $total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
            }
        }
    }

    $adminSetting = getAdminSettings();

    $parent = request()->segment(2);

    $catgory_detail = getCategoryDetail($current_cat_id);

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
                                            @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
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
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
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
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
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
                                                @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
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
                </div>
            </nav>
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

        </header>

        {{-- desktop view --}}
        <header class="header side_header @if($parent != null) sidebar_header @endif">
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
                    @if($is_sub_title == 1)
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
                    </div>
                </div>


                @if($parent != null)
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
                @endif

                <div class="header_bottom">

                    <div class="cart_notification">
                    @if(isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1)
                        @if ($table_enable_status == 1 || $room_enable_status == 1)                            
                            <a  class="waiter_notification" onclick="openWaiter({{ $shop_details['id'] }})">
                                <i class="fa-solid fa-bell"></i>
                            </a>
                        @endif
                    @endif
                        
                    @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
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
                    @endif

                    </div>
                </div>
            </div>
        </header>
    @elseif($layout == 'layout_3')
        <div class="layout3_header @if($stiky_header) header-sticky @endif">
            <header class="header">
                <div class="header_inr">
                    <div class="row justify-content-between">
                        <div class="col-md-6 col-lg-4">
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
                                @if($parent != null)
                                    <div class="back_service">
                                        <a onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                                                class="fa-solid fa-arrow-left"></i></a>
                                    </div>
                                @endif
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
                                                        <button class="btn search_bt" id="openSearch">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <button class="btn search_bt  d-none" id="closeSearch">
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
                                            @if(isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1 && $total_grading > 0)
                                                <li>
                                                    <a class="star_icon" onclick="openServiceRatingmodel({{ $shop_details['id'] }})"><i class="fa-solid fa-star" ></i></a>
                                                </li>
                                            @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                    <!-- <a href="#" class="btn search_bt" id="openSearch"><i
                                            class="fa-solid fa-search"></i></a>
                                    <a href="#" class="btn search_bt d-none" id="closeSearch"><i
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
                                                                <input type="checkbox" value="1"
                                                                    name="auto_translate" id="auto_translate"
                                                                    value="1">
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
                </div>
            </header>
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
                    @if($parent != null)
                    <div class="back_service">
                                <a onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i
                                        class="fa-solid fa-arrow-left"></i></a>
                        </div>
                    @endif
                    </section>
                @endif
            @endif
        </div>
    @endif
@endsection

@section('content')
    @if ($layout == 'layout_2')

        <input type="hidden" name="shop_id" id="shop_id" value="{{ encrypt($shop_details['id']) }}">
        <input type="hidden" name="current_cat_id" id="current_cat_id" value="{{ $current_cat_id }}">
        @if(session()->has('is_cover'))
        @php
            session()->forget('is_cover');
            session()->save();
        @endphp
    @else
        @if($parent == null)
            @if ($intro_icon_status == 1)
                @if (isset($shop_settings['shop_intro_icon']) &&
                        !empty($shop_settings['shop_intro_icon']) &&
                        file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']))
                    @php
                        $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
                    @endphp
                        <div class="cover-img">
                            <a class="close-cover btn-danger p-2 rounded-circle d-flex align-items-center justify-content-center position-fixed"
                                    style="width: 50px; height: 50px; top:10px; right:10px;text-decoration:none; cursor: pointer; z-index:9999"
                                    onclick="$('.cover-img').hide();"><i class="fa-solid fa-times"></i></a>
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-5">
                                        <div class="cover_single_img text-center">
                                                            @if ($intro_file_ext == 'mp4' || $intro_file_ext == 'mov')
                                                                <video
                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                    width="100%" autoplay muted loop>
                                                                </video>
                                                            @else
                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                    width="100%">
                                                            @endif
                                        </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                @endif
            @elseif($shop_intro_icon_is_cube == 1)
                <div class="cover-img">
                            <a class="close-cover btn-danger p-2 rounded-circle d-flex align-items-center justify-content-center position-fixed"
                                    style="width: 50px; height: 50px; top:10px; right:10px;text-decoration:none; cursor: pointer; z-index:9999"
                                    onclick="$('.cover-img').hide();"><i class="fa-solid fa-times"></i></a>
                                    @php
                                        $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
                                    @endphp
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <div class="cube_cover_slider">
                                                <div class="swiper-container h-100 position-relative">
                                                <div class="swiper-wrapper">
                                                            @if (isset($shop_intro_icon_1) &&
                                                                !empty($shop_intro_icon_1) &&
                                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1))
                                                                @php
                                                                    $intro_file_ext_1 = pathinfo($shop_intro_icon_1, PATHINFO_EXTENSION);
                                                                @endphp
                                                                @if($intro_file_ext_1 == 'mp4' || $intro_file_ext_1 == 'mov')
                                                                <div class="swiper-slide">

                                                                    <video
                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1) }}"
                                                                        width="100%" class="h-100" autoplay muted loop>
                                                                    </video>
                                                                    @if($shop_intro_icon_link_1 != '' || !empty($shop_intro_icon_link_1))
                                                                        <a href="{{ $shop_intro_icon_link_2 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                                @else
                                                                    <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1) }}')">
                                                                    @if($shop_intro_icon_link_1 != '' || !empty($shop_intro_icon_link_1))
                                                                        <a href="{{ $shop_intro_icon_link_1 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                    @endif
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            @if (isset($shop_intro_icon_2) &&
                                                                !empty($shop_intro_icon_2) &&
                                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2))
                                                                @php
                                                                    $intro_file_ext_2 = pathinfo($shop_intro_icon_2, PATHINFO_EXTENSION);
                                                                @endphp
                                                                    @if($intro_file_ext_2 == 'mp4' || $intro_file_ext_2 == 'mov')
                                                                    <div class="swiper-slide">

                                                                        <video
                                                                            src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2) }}"
                                                                            width="100%" class="h-100" autoplay muted loop>
                                                                        </video>
                                                                        @if($shop_intro_icon_link_2 != '' || !empty($shop_intro_icon_link_2))
                                                                        <a href="{{ $shop_intro_icon_link_2 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                        @endif
                                                                    </div>
                                                                    @else
                                                                    <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2) }}')">
                                                                        @if($shop_intro_icon_link_2 != '' || !empty($shop_intro_icon_link_2))
                                                                            <a href="{{ $shop_intro_icon_link_2 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                    @endif
                                                            @endif
                                                            @if (isset($shop_intro_icon_3) &&
                                                                !empty($shop_intro_icon_3) &&
                                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3))
                                                                @php
                                                                    $intro_file_ext_3 = pathinfo($shop_intro_icon_3, PATHINFO_EXTENSION);
                                                                @endphp
                                                                    @if($intro_file_ext_3 == 'mp4' || $intro_file_ext_3 == 'mov')
                                                                    <div class="swiper-slide">

                                                                        <video
                                                                            src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3) }}"
                                                                            width="100%" class="h-100" autoplay muted loop>
                                                                        </video>
                                                                        <a href="{{ $shop_intro_icon_link_3 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                    </div>
                                                                    @else
                                                                    <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3) }}')">
                                                                    @if($shop_intro_icon_link_3 != '' || !empty($shop_intro_icon_link_3))
                                                                        <a href="{{ $shop_intro_icon_link_3 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                        @endif
                                                                    </div>
                                                                    @endif
                                                            @endif
                                                            @if (isset($shop_intro_icon_4) &&
                                                                !empty($shop_intro_icon_4) &&
                                                                file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4))
                                                                @php
                                                                    $intro_file_ext_4 = pathinfo($shop_intro_icon_4, PATHINFO_EXTENSION);
                                                                @endphp
                                                                    @if($intro_file_ext_4 == 'mp4' || $intro_file_ext_4 == 'mov')
                                                                    <div class="swiper-slide">

                                                                            <video
                                                                                src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4) }}"
                                                                                width="100%" class="h-100" autoplay muted loop>
                                                                            </video>
                                                                            @if($shop_intro_icon_link_4 != '' || !empty($shop_intro_icon_link_4))
                                                                            <a href="{{ $shop_intro_icon_link_4 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                            @endif

                                                                    </div>
                                                                    @else
                                                                    <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4) }}')">
                                                                    @if($shop_intro_icon_link_4 != '' || !empty($shop_intro_icon_link_4))
                                                                        <a href="{{ $shop_intro_icon_link_4 }}" target="_blank" class="cover_link" ><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                    @endif
                                                                    </div>
                                                                    @endif
                                                            @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
            @endif
        @endif
    @endif
        <div class="content-warpper">
            @if (isset($package_permissions['banner']) &&
            !empty($package_permissions['banner']) &&
            $package_permissions['banner'] == 1)
                @if (isset($theme_settings['banner_position']) &&
                        !empty($theme_settings['banner_position']) &&
                        $theme_settings['banner_position'] == 'top')
                    @if (count($shop_banners) > 0)

                        <section class="home_main_slider" style="height: {{ $banner_height }}px;">
                            <div class="h-100">
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
                            </div>
                        </section>
                    @endif
                @endif
            @endif
            <div class="d-none row d-flex justify-content-center mb-3" id="search_input">
                <div class="col-md-6">
                        <div class="text-center search_input position-relative">
                                        <input type="text" class="form-control" name="search" id="search_layout"
                                            placeholder="Search Items">
                                        <button class="btn btn-secondary search_btn_header" id="search_btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        <button class="btn btn-danger clear_btn_header" id="clear_btn"><i class="fa-solid fa-x"></i></button>
                                    </div>

                </div>
            </div>
            @if($parent != null)
            <div class="category_back_btn mb-3">
                <a class="back_btn" onclick="homePage('{{ $shop_details['shop_slug'] }}')">
                <i class="fa-solid fa-circle-chevron-left me-2"></i>
                <span>{{ isset($catgory_detail['name']) ? $catgory_detail['name'] : '' }}</span>
                @php
                    $cat_parent_image = isset($catgory_detail->categoryImages[0]['image']) ? $catgory_detail->categoryImages[0]['image'] : '';
                @endphp
                @if (!empty($cat_parent_image) && file_exists('public/client_uploads/shops/' . $shop_details['shop_slug'] . '/categories/' . $cat_parent_image))
                    <img src="{{ asset('public/client_uploads/shops/' . $shop_details['shop_slug'] . '/categories/' . $cat_parent_image) }}" width="30" class="rounded-circle ms-2">
                @endif
                </a>
            </div>
            @endif
            <section class="category_section category_view_grid" id="CategorySection">
                <div class="category_inr">

                    @if (count($categories) > 0)
                        @foreach ($categories as $category)
                            @php
                                $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                $name_code = $current_lang_code . '_name';
                                $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                $thumb_image = isset($category->cover) ? $category->cover : '';

                                $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                                $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                $items = $category->items;
                                if (count($items) > 0) {
                                    $img_array = [];

                                    foreach ($items as $key => $value) {
                                        if ($value->type == 1) {
                                            $img_array[] = $value->image;
                                        }
                                    }
                                    $item_images = array_filter($img_array);
                                }else{
                                    $item_images = [];
                                }

                            @endphp

                            @if ($active_cat == 1)
                                @if ($check_cat_type_permission == 1)
                                    <div class="category_box">
                                        @if ($category->category_type == 'link')
                                            <a href="{{ isset($category->link_url) && !empty($category->link_url) ? $category->link_url : '#' }}"
                                                target="_blank">
                                            @elseif($category->category_type == 'parent_category')
                                                <a href="{{ route('restaurant', [$shop_slug, $category->id]) }}">
                                                @else
                                                    <a
                                                        href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id]) }}">
                                        @endif
                                        {{-- Image Section --}}
                                        <div class="category_img">
                                            <div id="demo" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    @if ($category->category_type == 'product_category')
                                                        <div class="carousel-item active">
                                                            @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                                    class="w-100">
                                                            @else
                                                                <img src="{{ $default_cat_img }}" class="w-100">
                                                            @endif
                                                        </div>
                                                    @else
                                                        @if (!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image))
                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image) }}"
                                                                class="w-100">
                                                        @else
                                                            <img src="{{ $default_cat_img }}" class="w-100">
                                                        @endif
                                                    @endif
                                                    @if ($category_iamge_slider == 'slider')
                                                        @if (count($item_images) > 0)
                                                            @foreach ($item_images as $item_image)
                                                                <div class="carousel-item">
                                                                    @if (!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image))
                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image) }}"alt="New york"
                                                                            class="w-100">
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cate_name">
                                            {{ isset($category->$name_code) ? $category->$name_code : '' }}
                                        </div>
                                        </a>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </section>

            <section class="sec_main service_sec category_view_tiles" id="CategorySectionTwo">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-xl-12">
                            <div class="service_main">
                                @if (count($categories) > 0)

                                    @foreach ($categories as $category)
                                        @php
                                            $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                            $name_code = $current_lang_code . '_name';
                                            $nameId = str_replace(' ', '_', $category->en_name);
                                            $description_code = $current_lang_code . '_description';
                                            $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                            $thumb_image = isset($category->cover) ? $category->cover : '';

                                            $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                                            $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                            $items = $category->items;

                                            if (count($items) > 0) {
                                                $img_array = [];

                                                foreach ($items as $key => $value) {
                                                    if ($value->type == 1) {
                                                        $img_array[] = $value->image;
                                                    }
                                                }
                                                $item_images = array_filter($img_array);
                                            }else{
                                                $item_images = [];
                                            }

                                        @endphp

                                        <div class="service_box">
                                            @if ($active_cat == 1)
                                                @if ($check_cat_type_permission == 1)
                                                    @if ($category->category_type == 'link')
                                                        <a href="{{ isset($category->link_url) && !empty($category->link_url) ? $category->link_url : '#' }}"
                                                            target="_blank">
                                                        @elseif($category->category_type == 'parent_category')
                                                            <a href="{{ route('restaurant', [$shop_slug, $category->id]) }}">
                                                            @elseif($category->category_type == 'pdf_page')
                                                                <a href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id . '#' . $nameId]) }}"
                                                                    target="_blank">
                                                                @else
                                                                    <a
                                                                        href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id . '#' . $nameId]) }}">
                                                    @endif
                                                    <h2>{{ isset($category->$name_code) ? $category->$name_code : '' }}</h2>

                                                    <div class="service_info">
                                                        <div class="service_img">
                                                            <div id="demo" class="carousel slide" data-bs-ride="carousel">
                                                                <div class="carousel-inner">
                                                                    @if ($category->category_type == 'product_category')
                                                                        <div class="carousel-item active">
                                                                            @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                                                    class="w-100">
                                                                            @else
                                                                                <img src="{{ $default_cat_img }}"
                                                                                    class="w-100">
                                                                            @endif
                                                                        </div>
                                                                    @else
                                                                        @if (!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image))
                                                                            <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image) }}"
                                                                                class="w-100">
                                                                        @else
                                                                            <img src="{{ $default_cat_img }}" class="w-100">
                                                                        @endif
                                                                    @endif
                                                                    @if ($category_iamge_slider == 'slider')
                                                                        @if (count($item_images) > 0)
                                                                            @foreach ($item_images as $item_image)
                                                                                <div class="carousel-item">
                                                                                    @if (!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image))
                                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image) }}"alt="New york"
                                                                                            class="w-100">
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="service_info_des">
                                                            <p>{!! isset($category->$description_code) ? $category->$description_code : '' !!}</p>
                                                        </div>
                                                    </div>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </section>
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
                                    <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i
                                            class="fa-solid fa-mask"></i></a>
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
        @if($is_loader == 1 && isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
            <div class="loader">
                @if(!empty($shop_loader) && file_exists('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader))
                    <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader) }}" width="200px"/>
                @else
                    <div class="loader">
                        <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                    </div>
                @endif
            </div>
        @else
            @if (!empty($adminSetting['loader']))
                @if (isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
                    @if ($is_loader == 1)
                        <div class="loader">
                            <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                        </div>
                    @endif
                @else
                    <div class="loader">
                        <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                    </div>
                @endif
            @endif
        @endif
        <input type="hidden" name="intro_second" id="intro_second" value="{{ $intro_second }}">

    @elseif($layout == 'layout_1')

        <input type="hidden" name="shop_id" id="shop_id" value="{{ encrypt($shop_details['id']) }}">
        <input type="hidden" name="current_cat_id" id="current_cat_id" value="{{ $current_cat_id }}">

        @if (isset($package_permissions['banner']) &&
                !empty($package_permissions['banner']) &&
                $package_permissions['banner'] == 1)
            @if (isset($theme_settings['banner_position']) &&
                    !empty($theme_settings['banner_position']) &&
                    $theme_settings['banner_position'] == 'top')
                @if (count($shop_banners) > 0)
                    <section class="home_main_slider" style="height: {{ $banner_height }}px;">
                        <div class="container h-100">
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
                                            <!-- <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div> -->
                                                    <div class="swiper-button-next swiper-btn">
                                                        <i class="fa-solid fa-angle-right"></i>
                                                    </div>
                                                    <div class="swiper-button-prev swiper-btn">
                                                        <i class="fa-solid fa-angle-left"></i>
                                                    </div>
                                            @endif
                                    </div>
                                @endif
                        </div>
                    </section>
                @endif
            @endif
        @endif

        <section class="sec_main">
            <div class="container">
                @if($parent != null)
                <div class="sub_cat_title">
                    <!-- <a href="{{ route('restaurant', $shop_details['shop_slug']) }}"><i class="fa-solid fa-circle-chevron-left me-2"></i><span>{{ isset($catgory_detail->$name_key) ? $catgory_detail->$name_key : '' }}</span></a> -->
                    <a  onclick="homePage('{{ $shop_details['shop_slug'] }}')"><i class="fa-solid fa-circle-chevron-left me-2"></i><span>{{ isset($catgory_detail->$name_key) ? $catgory_detail->$name_key : '' }}</span></a>
                </div>
                @endif
                <div id="CategorySection" class="sub_cat">
                    @if (count($categories) > 0)
                        <div class="menu_list">
                            @foreach ($categories as $category)
                                @php
                                    $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                    $name_code = $current_lang_code . '_name';
                                    $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                    $thumb_image = isset($category->cover) ? $category->cover : '';

                                    $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                                    $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                @endphp
                                @if ($active_cat == 1)
                                    @if ($check_cat_type_permission == 1)
                                        <div class="menu_list_item">
                                            @if ($category->category_type == 'link')
                                                <a href="{{ isset($category->link_url) && !empty($category->link_url) ? $category->link_url : '#' }}"
                                                    target="_blank">
                                                @elseif($category->category_type == 'parent_category')
                                                    <a href="{{ route('restaurant', [$shop_slug, $category->id]) }}">
                                                    @else
                                                        <a
                                                            href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id]) }}">
                                            @endif

                                            {{-- Image Section --}}
                                            @if ($category->category_type == 'product_category')
                                                @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                        class="w-100">
                                                @else
                                                    <img src="{{ $default_cat_img }}" class="w-100">
                                                @endif
                                            @else
                                                @if (!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image))
                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image) }}"
                                                        class="w-100">
                                                @else
                                                    <img src="{{ $default_cat_img }}" class="w-100">
                                                @endif
                                            @endif

                                            {{-- Name Section --}}
                                            <h3 class="item_name">{{ isset($category->$name_code) ? $category->$name_code : '' }}
                                            </h3>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    @else
                        <h3 class="text-center empty-category">Categories not Found.</h3>
                    @endif
                </div>
            </div>
        </section>



        @if (isset($package_permissions['banner']) &&
                !empty($package_permissions['banner']) &&
                $package_permissions['banner'] == 1)
            @if (isset($theme_settings['banner_position']) &&
                    !empty($theme_settings['banner_position']) &&
                    $theme_settings['banner_position'] == 'bottom')
                @if (count($shop_banners) > 0)
                    <section class="home_main_slider" style="height: {{ $banner_height }}px;">
                        <div class="container h-100">
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
                        </div>
                </section>
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
                                <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i
                                        class="fa-solid fa-mask"></i></a>
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
    @if($is_loader == 1 && isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
        <div class="loader">
                @if(!empty($shop_loader) && file_exists('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader))
                    <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader) }}" width="200px"/>
                @else
                    <div class="loader">
                        <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                    </div>
                @endif
        </div>
    @else
        @if (!empty($adminSetting['loader']))
            @if (isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
                @if ($is_loader == 1)
                    <div class="loader">
                        <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                    </div>
                @endif
            @else
                <div class="loader">
                    <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                </div>
            @endif
        @endif
    @endif

    @if($parent != null)
            <!-- <a class="back_bt" href="{{ route('restaurant', $shop_details['shop_slug']) }}"><i
                class="fa-solid fa-chevron-left"></i></a> -->


            @if($is_loader == 1 && isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
                <div class="loader">
                        @if(!empty($shop_loader) && file_exists('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader))
                            <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader) }}" width="200px"/>
                        @else
                            <div class="loader">
                                <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                            </div>
                        @endif
                </div>
            @else
                @if (!empty($adminSetting['loader']))
                    @if (isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
                        @if ($is_loader == 1)
                            <div class="loader">
                                <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                            </div>
                        @endif
                    @else
                        <div class="loader">
                            <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                        </div>
                    @endif
                @endif
            @endif

        @endif

    <input type="hidden" name="intro_second" id="intro_second" value="{{ $intro_second }}">

    @if(session()->has('is_cover'))
        @php
            session()->forget('is_cover');
            session()->save();
        @endphp
    @else
        @if($parent == null)
            @if ($intro_icon_status == 1)
                    @if (isset($shop_settings['shop_intro_icon']) &&
                            !empty($shop_settings['shop_intro_icon']) &&
                            file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']))
                        @php
                            $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
                        @endphp
                            <div class="cover-img">
                                <a class="close-cover btn-danger p-2 rounded-circle d-flex align-items-center justify-content-center position-fixed"
                                        style="width: 50px; height: 50px; top:10px; right:10px;text-decoration:none; cursor: pointer; z-index:9999"
                                        onclick="$('.cover-img').hide();"><i class="fa-solid fa-times"></i></a>
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-md-5">
                                            <div class="cover_single_img text-center">
                                                                @if ($intro_file_ext == 'mp4' || $intro_file_ext == 'mov')
                                                                    <video
                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                        width="100%" autoplay muted loop>
                                                                    </video>
                                                                @else
                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                        width="100%">
                                                                @endif
                                            </div>
                                                <!-- <div class="cube_cover_slider">
                                                    <div class="swiper-container h-100 position-relative">
                                                    <div class="swiper-wrapper">
                                                            @if ($intro_file_ext == 'mp4' || $intro_file_ext == 'mov')
                                                                <div class="swiper-slide">
                                                                <video
                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                        width="100%" class="h-100" autoplay muted loop>
                                                                    </video>
                                                                </div>
                                                            @else

                                                                <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}')">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                            </div>
                    @endif
            @elseif($shop_intro_icon_is_cube == 1)
                    <div class="cover-img">
                                <a class="close-cover btn-danger p-2 rounded-circle d-flex align-items-center justify-content-center position-fixed"
                                        style="width: 50px; height: 50px; top:10px; right:10px;text-decoration:none; cursor: pointer; z-index:9999"
                                        onclick="$('.cover-img').hide();"><i class="fa-solid fa-times"></i></a>
                                        @php
                                            $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
                                        @endphp
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-md-5">
                                                <div class="cube_cover_slider">
                                                    <div class="swiper-container h-100 position-relative">
                                                    <div class="swiper-wrapper">
                                                                @if (isset($shop_intro_icon_1) &&
                                                                    !empty($shop_intro_icon_1) &&
                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1))
                                                                    @php
                                                                        $intro_file_ext_1 = pathinfo($shop_intro_icon_1, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                    @if($intro_file_ext_1 == 'mp4' || $intro_file_ext_1 == 'mov')
                                                                    <div class="swiper-slide">

                                                                        <video
                                                                            src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1) }}"
                                                                            width="100%" class="h-100" autoplay muted loop>
                                                                        </video>
                                                                        @if($shop_intro_icon_link_1 != '' || !empty($shop_intro_icon_link_1))
                                                                            <a href="{{ $shop_intro_icon_link_1 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                    @else
                                                                    <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1) }}')">
                                                                        @if($shop_intro_icon_link_1 != '' || !empty($shop_intro_icon_link_1))
                                                                        <a href="{{ $shop_intro_icon_link_1 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                        </a>
                                                                        @endif
                                                                    </div>
                                                                    @endif
                                                                @endif
                                                                @if (isset($shop_intro_icon_2) &&
                                                                    !empty($shop_intro_icon_2) &&
                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2))
                                                                    @php
                                                                        $intro_file_ext_2 = pathinfo($shop_intro_icon_2, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                        @if($intro_file_ext_2 == 'mp4' || $intro_file_ext_2 == 'mov')
                                                                        <div class="swiper-slide">

                                                                            <video
                                                                                src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2) }}"
                                                                                width="100%" class="h-100" autoplay muted loop>
                                                                            </video>
                                                                        @if($shop_intro_icon_link_2 != '' || !empty($shop_intro_icon_link_2))
                                                                            <a  href="{{ $shop_intro_icon_link_2}}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                        @endif
                                                                        </div>
                                                                        @else
                                                                        <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2) }}')">
                                                                        @if($shop_intro_icon_link_2 != '' || !empty($shop_intro_icon_link_2))
                                                                            <a  href="{{ $shop_intro_icon_link_2}}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                            @endif
                                                                        </div>
                                                                        @endif
                                                                @endif
                                                                @if (isset($shop_intro_icon_3) &&
                                                                    !empty($shop_intro_icon_3) &&
                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3))
                                                                    @php
                                                                        $intro_file_ext_3 = pathinfo($shop_intro_icon_3, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                        @if($intro_file_ext_3 == 'mp4' || $intro_file_ext_3 == 'mov')
                                                                        <div class="swiper-slide">

                                                                            <video
                                                                                src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3) }}"
                                                                                width="100%" class="h-100" autoplay muted loop>
                                                                            </video>
                                                                            @if($shop_intro_icon_link_3 != '' || !empty($shop_intro_icon_link_3))
                                                                                <a  href="{{ $shop_intro_icon_link_3}}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                        @else
                                                                        <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3) }}')">
                                                                        @if($shop_intro_icon_link_3 != '' || !empty($shop_intro_icon_link_3))
                                                                            <a   href="{{ $shop_intro_icon_link_3}}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                            @endif
                                                                        </div>
                                                                        @endif
                                                                @endif
                                                                @if (isset($shop_intro_icon_4) &&
                                                                    !empty($shop_intro_icon_4) &&
                                                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4))
                                                                    @php
                                                                        $intro_file_ext_4 = pathinfo($shop_intro_icon_4, PATHINFO_EXTENSION);
                                                                    @endphp
                                                                        @if($intro_file_ext_4 == 'mp4' || $intro_file_ext_4 == 'mov')
                                                                        <div class="swiper-slide">
                                                                                <video
                                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4) }}"
                                                                                    width="100%" class="h-100" autoplay muted loop>
                                                                                </video>
                                                                            @if($shop_intro_icon_link_4 != '' || !empty($shop_intro_icon_link_4))
                                                                                <a href="{{ $shop_intro_icon_link_4}}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                </a>
                                                                                @endif
                                                                        </div>
                                                                        @else
                                                                        <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4) }}')">
                                                                        @if($shop_intro_icon_link_4 != '' || !empty($shop_intro_icon_link_4))
                                                                            <a href="{{ $shop_intro_icon_link_4}}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                            </a>
                                                                        @endif
                                                                        </div>

                                                                        @endif
                                                                @endif

                                                        </div>
                                                        <!-- <div class="swiper-button-next swiper-btn"><i class="fa-solid fa-angle-right"></i></div>
                                                        <div class="swiper-button-prev swiper-btn"><i class="fa-solid fa-angle-left"></i></div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    </div>
            @endif
        @endif
    @endif
    @else
        <section class="sec_main service_sec">
            <div class="container">
                <div class="d-none row d-flex justify-content-center mb-4" id="search_input">
                        <div class="col-md-6">
                                <div class="text-center search_input position-relative">
                                                <input type="text" class="form-control" name="search" id="search_layout"
                                                    placeholder="Search Items">
                                                <button class="btn btn-secondary search_btn_header" id="search_btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                <button class="btn btn-danger clear_btn_header" id="clear_btn_layout_three"><i class="fa-solid fa-x"></i></button>
                                </div>

                        </div>
                    </div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-xl-12">
                    <div class="service_main">
                        @if (count($categories) > 0)

                            @foreach ($categories as $category)
                                @php
                                    $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                    $name_code = $current_lang_code . '_name';
                                    $nameId = str_replace(' ', '_', $category->en_name);
                                    $description_code = $current_lang_code . '_description';
                                    $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                    $thumb_image = isset($category->cover) ? $category->cover : '';

                                    $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                                    $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                    $items = $category->items;

                                    if (count($items) > 0) {
                                        $img_array = [];

                                        foreach ($items as $key => $value) {
                                            if ($value->type == 1) {
                                                $img_array[] = $value->image;
                                            }
                                        }
                                        $item_images = array_filter($img_array);
                                    }else{
                                        $item_images = [];
                                    }

                                @endphp

                                <div class="service_box">
                                    @if ($active_cat == 1)
                                        @if ($check_cat_type_permission == 1)
                                            @if ($category->category_type == 'link')
                                                <a href="{{ isset($category->link_url) && !empty($category->link_url) ? $category->link_url : '#' }}"
                                                    target="_blank">
                                                @elseif($category->category_type == 'parent_category')
                                                    <a href="{{ route('restaurant', [$shop_slug, $category->id]) }}">
                                                    @elseif($category->category_type == 'pdf_page')
                                                        <a href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id . '#' . $nameId]) }}"
                                                            target="_blank">
                                                        @else
                                                            <a
                                                                href="{{ route('items.preview', [$shop_details['shop_slug'], $category->id . '#' . $nameId]) }}">
                                            @endif
                                            <h2>{{ isset($category->$name_code) ? $category->$name_code : '' }}</h2>
                                            <div class="service_info">
                                                <div class="service_img">
                                                    <div id="demo" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-inner">
                                                            @if ($category->category_type == 'product_category')
                                                                <div class="carousel-item active">
                                                                    @if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image))
                                                                        <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image) }}"
                                                                            class="w-100">
                                                                    @else
                                                                        <img src="{{ $default_cat_img }}"
                                                                            class="w-100">
                                                                    @endif
                                                                </div>
                                                            @else
                                                                @if (!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image))
                                                                    <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image) }}"
                                                                        class="w-100">
                                                                @else
                                                                    <img src="{{ $default_cat_img }}" class="w-100">
                                                                @endif
                                                            @endif
                                                            @if ($category_iamge_slider == 'slider')
                                                                @if (count($item_images) > 0)
                                                                    @foreach ($item_images as $item_image)
                                                                        <div class="carousel-item">
                                                                            @if (!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image))
                                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image) }}"alt="New york"
                                                                                    class="w-100">
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="service_info_des">
                                                    <p>{!! isset($category->$description_code) ? $category->$description_code : '' !!}</p>
                                                </div>
                                            </div>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
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
                                    <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><i
                                            class="fa-solid fa-mask"></i></a>
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
        @if($is_loader == 1 && isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
            <div class="loader">
                    @if(!empty($shop_loader) && file_exists('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader))
                        <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/loader/'.$shop_loader) }}" width="200px"/>
                    @else
                        <div class="loader">
                            <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                        </div>
                    @endif
            </div>
        @else
            @if (!empty($adminSetting['loader']))
                @if (isset($package_permissions['loader']) && !empty($package_permissions['loader']) && $package_permissions['loader'] == 1)
                    @if ($is_loader == 1)
                        <div class="loader">
                            <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                        </div>
                    @endif
                @else
                    <div class="loader">
                        <img src="{{ $adminSetting['loader'] }}" alt="" width="200px">
                    </div>
                @endif
            @endif
        @endif
        <input type="hidden" name="intro_second" id="intro_second" value="{{ $intro_second }}">
        @if(session()->has('is_cover'))
            @php
                session()->forget('is_cover');
                session()->save();
            @endphp
        @else
            @if($parent == null)
                    @if ($intro_icon_status == 1)
                            @if (isset($shop_settings['shop_intro_icon']) &&
                                    !empty($shop_settings['shop_intro_icon']) &&
                                    file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']))
                                @php
                                    $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
                                @endphp
                                    <div class="cover-img">
                                        <a class="close-cover btn-danger p-2 rounded-circle d-flex align-items-center justify-content-center position-fixed"
                                                style="width: 50px; height: 50px; top:10px; right:10px;text-decoration:none; cursor: pointer; z-index:9999"
                                                onclick="$('.cover-img').hide();"><i class="fa-solid fa-times"></i></a>
                                            <div class="container">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-5">
                                                        <div class="cover_single_img text-center">
                                                            @if ($intro_file_ext == 'mp4' || $intro_file_ext == 'mov')
                                                                <video
                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                    width="100%" autoplay muted loop>
                                                                </video>
                                                            @else
                                                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                    width="100%">
                                                            @endif
                                                        </div>
                                                        <!-- <div class="cube_cover_slider">
                                                            <div class="swiper-container h-100 position-relative">
                                                            <div class="swiper-wrapper">
                                                                    @if ($intro_file_ext == 'mp4' || $intro_file_ext == 'mov')
                                                                        <div class="swiper-slide">
                                                                        <video
                                                                                src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}"
                                                                                width="100%" class="h-100" autoplay muted loop>
                                                                            </video>
                                                                        </div>
                                                                    @else

                                                                        <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_settings['shop_intro_icon']) }}')">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                            @endif
                    @elseif($shop_intro_icon_is_cube == 1)
                            <div class="cover-img">
                                        <a class="close-cover btn-danger p-2 rounded-circle d-flex align-items-center justify-content-center position-fixed"
                                                style="width: 50px; height: 50px; top:10px; right:10px;text-decoration:none; cursor: pointer; z-index:9999"
                                                onclick="$('.cover-img').hide();"><i class="fa-solid fa-times"></i></a>
                                                @php
                                                    $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
                                                @endphp
                                            <div class="container">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-5">
                                                        <div class="cube_cover_slider">
                                                            <div class="swiper-container h-100 position-relative">
                                                            <div class="swiper-wrapper">
                                                                        @if (isset($shop_intro_icon_1) &&
                                                                            !empty($shop_intro_icon_1) &&
                                                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1))
                                                                            @php
                                                                                $intro_file_ext_1 = pathinfo($shop_intro_icon_1, PATHINFO_EXTENSION);
                                                                            @endphp
                                                                            @if($intro_file_ext_1 == 'mp4' || $intro_file_ext_1 == 'mov')
                                                                            <div class="swiper-slide">

                                                                                <video
                                                                                    src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1) }}"
                                                                                    width="100%" class="h-100" autoplay muted loop>
                                                                                </video>
                                                                                 @if($shop_intro_icon_link_1 != '' || !empty($shop_intro_icon_link_1))
                                                                                    <a href="{{ $shop_intro_icon_link_1 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                        </a>
                                                                                @endif
                                                                            </div>
                                                                            @else
                                                                            <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_1) }}')">
                                                                            @if($shop_intro_icon_link_1 != '' || !empty($shop_intro_icon_link_1))
                                                                                <a href="{{ $shop_intro_icon_link_1 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                </a>
                                                                                @endif
                                                                            </div>

                                                                            @endif
                                                                        @endif
                                                                        @if (isset($shop_intro_icon_2) &&
                                                                            !empty($shop_intro_icon_2) &&
                                                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2))
                                                                            @php
                                                                                $intro_file_ext_2 = pathinfo($shop_intro_icon_2, PATHINFO_EXTENSION);
                                                                            @endphp
                                                                                @if($intro_file_ext_2 == 'mp4' || $intro_file_ext_2 == 'mov')
                                                                                <div class="swiper-slide">
                                                                                    <video
                                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2) }}"
                                                                                        width="100%" class="h-100" autoplay muted loop>
                                                                                    </video>
                                                                                 @if($shop_intro_icon_link_2 != '' || !empty($shop_intro_icon_link_2))
                                                                                     <a href="{{ $shop_intro_icon_link_2 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                    </a>
                                                                                    @endif
                                                                                </div>
                                                                                @else
                                                                                <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_2) }}')">
                                                                                @if($shop_intro_icon_link_2 != '' || !empty($shop_intro_icon_link_2))
                                                                                    <a href="{{ $shop_intro_icon_link_2 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                    </a>
                                                                                    @endif
                                                                                </div>
                                                                                @endif
                                                                        @endif
                                                                        @if (isset($shop_intro_icon_3) &&
                                                                            !empty($shop_intro_icon_3) &&
                                                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3))
                                                                            @php
                                                                                $intro_file_ext_3 = pathinfo($shop_intro_icon_3, PATHINFO_EXTENSION);
                                                                            @endphp
                                                                                @if($intro_file_ext_3 == 'mp4' || $intro_file_ext_3 == 'mov')
                                                                                <div class="swiper-slide">

                                                                                    <video
                                                                                        src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3) }}"
                                                                                        width="100%" class="h-100" autoplay muted loop>
                                                                                    </video>
                                                                                 @if($shop_intro_icon_link_3 != '' || !empty($shop_intro_icon_link_3))
                                                                                    <a href="{{ $shop_intro_icon_link_3 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                    </a>
                                                                                    @endif
                                                                                </div>
                                                                                @else
                                                                                <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_3) }}')">
                                                                                @if($shop_intro_icon_link_3 != '' || !empty($shop_intro_icon_link_3))
                                                                                    <a href="{{ $shop_intro_icon_link_3 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                    </a>
                                                                                @endif
                                                                                </div>
                                                                                @endif
                                                                        @endif
                                                                        @if (isset($shop_intro_icon_4) &&
                                                                            !empty($shop_intro_icon_4) &&
                                                                            file_exists('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4))
                                                                            @php
                                                                                $intro_file_ext_4 = pathinfo($shop_intro_icon_4, PATHINFO_EXTENSION);
                                                                            @endphp
                                                                                @if($intro_file_ext_4 == 'mp4' || $intro_file_ext_4 == 'mov')
                                                                                <div class="swiper-slide">

                                                                                        <video
                                                                                            src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4) }}"
                                                                                            width="100%" class="h-100" autoplay muted loop>
                                                                                        </video>
                                                                                @if($shop_intro_icon_link_4 != '' || !empty($shop_intro_icon_link_4))
                                                                                    <a href="{{ $shop_intro_icon_link_4 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                    </a>
                                                                                    @endif
                                                                                </div>
                                                                                @else
                                                                                <div class="swiper-slide" style="background-image: url('{{ asset('public/client_uploads/shops/' . $shop_slug . '/intro_icons/' . $shop_intro_icon_4) }}')">
                                                                                @if($shop_intro_icon_link_4 != '' || !empty($shop_intro_icon_link_4))
                                                                                    <a href="{{ $shop_intro_icon_link_4 }}" target="_blank" class="cover_link"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                                                    </a>
                                                                                    @endif
                                                                                </div>

                                                                                @endif
                                                                        @endif

                                                                </div>
                                                                <!-- <div class="swiper-button-next swiper-btn"><i class="fa-solid fa-angle-right"></i></div>
                                                                <div class="swiper-button-prev swiper-btn"><i class="fa-solid fa-angle-left"></i></div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                            </div>
                    @endif
            @endif
        @endif

    </section>

    @endif
@endsection

{{-- Page JS Function --}}
@section('page-js')
<script type="text/javascript" src="//translate.google.com/translate_a/element.js"></script>
    <script type="text/javascript">
        var BannerSpeed = {{ $slider_delay_time }};
        var slider_effect = "{{ $effect }}";

        var categoryDelayTime = {{ $category_image_slider_delay_time }};

        var layout = "{{ $layout }}";


        $(document).ready(function() {
            $('.service_info .carousel').carousel({
                interval: categoryDelayTime
            });
            $('.category_img .carousel').carousel({
                interval: categoryDelayTime
            });
        });

        // Document Ready Function
        $(document).ready(function() {
            // TimeOut for Intro

            setTimeout(() => {
                $('.loader').hide();
            }, 1500);

             var cubecheck= {{ $shop_intro_icon_is_cube }};

             if(cubecheck==0){
                 // Timeout for Cover
                 var introSec = $('#intro_second').val() * 1000;
                 setTimeout(() => {
                     $('.cover-img').hide();
                 }, introSec);
             }

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


             new Swiper(".cube_cover_slider .swiper-container", {
	            slidesPerView: 1,
	            effect: 'cube',
                navigation: {
                    nextEl: ".cube_cover_slider .swiper-button-next",
                    prevEl: ".cube_cover_slider .swiper-button-prev"
                    },
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false
                },
	            speed: 2000
            });

        });

        // Function for clear search input layout 3
        $('#clear_btn_layout_three').on('click', function() {
            $('#search_layout').val('');
            var keywords = ''; // Get the value of the input field
            var shopID = @json($encrptyShopId);
            var currCatId = $('#current_cat_id').val();
            var layout = "{{ $layout }}";
            var categoryDelayTime = {{ $category_image_slider_delay_time }};


            $.ajax({
                type: "POST",
                url: '{{ route('shop.categories.search') }}',
                data: {
                        "_token": "{{ csrf_token() }}",
                        "keywords": keywords,
                        "shopID": shopID,
                        "current_cat_id": currCatId,
                        "layout": layout,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == 1) {
                            {
                                if(keywords == ''){
                                    $('#search_input').addClass('d-none');
                                    $('#closeSearch').addClass('d-none');
                                    $('#openSearch').removeClass('d-none');
                                }
                                $('.service_main').html('');
                                $('.service_main').append(response.data);
                                $('.service_info .carousel').carousel({
                                interval: categoryDelayTime
                            });
                            }
                        }
                    }

            });
        });

        // Function for clear search input
        $('#clear_btn').on('click', function() {
            $('#search_layout').val('');
            var keywords = ''; // Get the value of the input field
            var shopID = @json($encrptyShopId);
            var currCatId = $('#current_cat_id').val();

                $.ajax({
                    type: "POST",
                    url: '{{ route('shop.categories.search') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "keywords": keywords,
                        "shopID": shopID,
                        "current_cat_id": currCatId,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.success == 1) {
                            {
                                if(keywords == ''){
                                    $('#search_input').addClass('d-none');
                                    $('#closeSearch').addClass('d-none');
                                    $('#openSearch').removeClass('d-none');
                                }
                                $('#CategorySection').html('');
                                $('#CategorySection').append(response.data);
                                var categoryDelayTime = {{ $category_image_slider_delay_time }};
                                 $('.category_img .carousel').carousel({
                                    interval: categoryDelayTime
                                });
                            }
                        }
                    }
                });
        });


        // Function for get Filterd Categories layout 2
        $('#search_btn').on('click', function() {

            var keywords = $('#search_layout').val();
            var shopID = @json($encrptyShopId);
            var currCatId = $('#current_cat_id').val();
            var layout = "{{ $layout }}";

            $.ajax({
                type: "POST",
                url: '{{ route('shop.categories.search') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "keywords": keywords,
                    "shopID": shopID,
                    "current_cat_id": currCatId,
                    'layout_width' : $(window).width(),
                    'layout' : layout,
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.success == 1) {
                        if(layout == 'layout_3'){
                            $('.service_main').html('');
                            $('.service_main').append(response.data);
                            $('.service_info .carousel').carousel({
                                interval: categoryDelayTime
                            });
                        }else{
                            $('#CategorySection').html('');
                            $('#CategorySection').append(response.data);
                            $('.category_img .carousel').carousel({
                               interval: categoryDelayTime
                            });
                        }
                    }
                }
            });
        });


        // Function for Get Filterd Categories
        $('#search').on('keyup', function() {
            var keywords = $(this).val();
            var shopID = $('#shop_id').val();
            var currCatID = $('#current_cat_id').val();
            var category_view = '{{ $category_view }}';
            var layout = "{{ $layout }}";
            var categoryDelayTime = {{ $category_image_slider_delay_time }};



            $.ajax({
                type: "POST",
                url: '{{ route('shop.categories.search') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'keywords': keywords,
                    'shopID': shopID,
                    'current_cat_id': currCatID,
                    'layout_width' : $(window).width(),
                    'layout' : layout,
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.success == 1) {
                        if(layout == 'layout_2'){
                            if ($(window).width() < 767) {
                                console.log(category_view);
                                if(category_view == 'grid'){
                                    $('.search_grid').show();
                                    $('.search_tiles').hide();
                                    $('#CategorySection').html('');
                                    $('#CategorySection').append(response.data);
                                    $('.category_img .carousel').carousel({
                                         interval: categoryDelayTime
                                    });
                                }else{
                                    $('.search_grid').hide();
                                    $('.search_tiles').show();
                                    $('#CategorySectionTwo').html('');
                                    $('#CategorySectionTwo').append(response.data);
                                    $('.service_info .carousel').carousel({
                                        interval: categoryDelayTime
                                    });
                                }
                            }else{
                                $('#CategorySection').html('');
                                $('#CategorySection').append(response.data);
                                $('.category_img .carousel').carousel({
                                    interval: categoryDelayTime
                                });
                            }
                        }else{
                            $('#CategorySection').html('');
                            $('#CategorySection').append(response.data);
                        }

                    } else {
                        console.log(response.message);
                    }
                }
            });

        });


        // Error Messages
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif


        @if ($layout == 'layout_2')
            $(window).resize(function() {
                if ($(window).width() < 991) {
                    $('.header').hide();
                    $('.header_preview').show();
                } else {
                    $('.header').show();
                    $('.header_preview').hide();
                }
            });
        @endif


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





    </script>

@endsection
