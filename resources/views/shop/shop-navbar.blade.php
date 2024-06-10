@php
    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';
    $logo_position = isset($theme_settings['logo_position']) ? $theme_settings['logo_position'] : '';
    $search_box_position = isset($theme_settings['search_box_position']) ? $theme_settings['search_box_position'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';
    $shop_id = isset($shop_details['id']) ? $shop_details['id'] : '';
    $shop_name = isset($shop_details['name']) ? $shop_details['name'] : '';

    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_id);

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);

    // Cart Quantity
    $total_quantity = getCartQuantity();

    // Get Language Settings
    $language_settings = clientLanguageSettings($shop_id);

    $google_translate = isset($language_settings['google_translate']) ? $language_settings['google_translate'] : 0;

    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

    // Get Banner Settings
    $shop_banners = getBanners($shop_details['id']);
    $banner_key = $language_details['code'] . '_image';
    $banner_text_key = $language_details['code'] . '_description';
    $title_key = $current_lang_code . '_title';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);
    $slider_buttons = isset($theme_settings['banner_slide_button']) && !empty($theme_settings['banner_slide_button']) ? $theme_settings['banner_slide_button'] : 0;
    $slider_delay_time = isset($theme_settings['banner_delay_time']) && !empty($theme_settings['banner_delay_time']) ? $theme_settings['banner_delay_time'] : 3000;
    $banner_height = isset($theme_settings['banner_height']) && !empty($theme_settings['banner_height']) ? $theme_settings['banner_height'] : 250;

    // Total Amount
    $total_amount = 0;

    // Cart Item
    $cart = session()->get('cart', []);

    foreach ($cart as $cart_key => $cart_data) {
        foreach ($cart_data as $cart_val) {
            foreach ($cart_val as $cart_item_key => $cart_item) {
                $total_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
            }
        }
    }

    // Current Route Name
    $routeName = Route::currentRouteName();
    $item_page = null;
    if($routeName == 'items.preview'){
        $segments = request()->segments();
        $itemId = $segments[2];
        $item_page = App\Models\Category::where('id',$itemId)->where('category_type','product_category')->first();
    }else{
        $item_page = null;
    }

    $shop_settings = getClientSettings($shop_details['id']);
    $waiter_call_status = isset($shop_settings['waiter_call_status']) ?  $shop_settings['waiter_call_status'] : '0';

    $table_enable_status = (isset($shop_settings['table_enable_status']) && !empty($shop_settings['table_enable_status'])) ? $shop_settings['table_enable_status'] : 0;
    $room_enable_status = (isset($shop_settings['room_enable_status']) && !empty($shop_settings['room_enable_status'])) ? $shop_settings['room_enable_status'] : 0;

    $total_grading = App\Models\ShopRateServies::where('shop_id', $shop_id)->where('status', 1)->count();
@endphp

@if ($layout == 'layout_1')
    <header class="header_preview header-sticky head">
        <nav class="navbar navbar-light bg-light">
            <div class="container">
                @if ($language_bar_position != $logo_position && $language_bar_position != $search_box_position && $logo_position != $search_box_position && $logo_position != $language_bar_position && $search_box_position != $language_bar_position && $search_box_position != $logo_position)

                    {{-- Left Position --}}
                    @if ($language_bar_position == 'left')
                        <div class="lang_select">
                            @if(count($additional_languages) > 0 || $google_translate == 1)
                                <a class="lang_bt"> <x-dynamic-component width="35px" component="flag-language-{{ $language_details['code'] }}" /></a>
                                <div class="lang_inr">
                                    <div class="text-end">
                                        <button class="btn close_bt"><i class="fa-solid fa-chevron-left"></i></button>
                                    </div>
                                    <ul class="lang_ul">
                                        @if (isset($primary_language_details) && !empty($primary_language_details))
                                            <li>
                                                <x-dynamic-component width="35px" component="flag-language-{{ $primary_language_details['code'] }}" />
                                                <a onclick="changeLanguage('{{ $primary_language_details['code'] }}')" style="cursor: pointer;">
                                                    {{ isset($primary_language_details['name']) ? $primary_language_details['name'] : '' }}
                                                </a>
                                            </li>
                                        @endif
                                        @if (count($additional_languages) > 0)
                                            @foreach ($additional_languages as $language)
                                                @php
                                                    $langCode = isset($language->language['code']) ? $language->language['code'] : '';
                                                @endphp
                                                <li>
                                                    <x-dynamic-component width="35px" component="flag-language-{{ $langCode }}" />
                                                    <a onclick="changeLanguage('{{ $langCode }}')" style="cursor: pointer;">{{ isset($language->language['name']) ? $language->language['name'] : '' }}</a>
                                                </li>
                                            @endforeach
                                        @endif
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
                            @endif
                        </div>
                    @elseif ($logo_position == 'left')
                        <a class="navbar-brand m-0" href="{{ route('restaurant', $shop_details['shop_slug']) }}">
                            @if (!empty($shop_logo) && file_exists('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo))
                                <img src="{{ asset('public/client_uploads/shops/' . $shop_slug . '/top_logos/' . $shop_logo) }}" class="top-shop-logo">
                            @else
                                <img src="{{ $default_logo }}" class="top-shop-logo">
                            @endif
                        </a>
                    @elseif ($search_box_position == 'left')
                        <div>
                            @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                                @if ($total_quantity > 0)
                                    @if($routeName != 'shop.cart' && $routeName != 'shop.cart.checkout' && $item_page != null && isset($item_page))
                                        <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span class="qty-number">{{ $total_quantity }}</span>
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
                                @endif
                            @endif
                            @if($item_page !== null || $routeName == 'restaurant' || isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                                <div class="barger_menu_main">
                                    <div class="barger_menu_inner">
                                        <div class="barger_menu_icon">
                                            <i class="fa-solid fa-bars"></i>
                                        </div>
                                        <div class="barger_menu_list">
                                            <ul>
                                                @if($item_page !== null || $routeName == 'restaurant')
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
                                                @endif
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
                            <button class="btn search_bt openSearchBox lay_src_btn" id="openSearchBox">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <button class="btn search_bt lay_src_btn closeSearchBox d-none" id="closeSearchBox">
                                <i class="fa-solid fa-times"></i>
                            </button>
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
                                    @if($routeName != 'shop.cart' && $routeName != 'shop.cart.checkout' && $item_page != null)
                                        <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span class="qty-number">{{ $total_quantity }}</span>
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
                                    <!-- <a href="{{ route('shop.cart', $shop_slug) }}"
                                        class="cart-btn me-1 mt-2 fs-4 text-white text-decoration-none">
                                        <div class="position-relative"><i class="bi bi-cart4"></i> <span
                                                class="qty-number">{{ $total_quantity }}</span></div>
                                    </a> -->
                                @endif
                            @endif
                            @if($item_page !== null || $routeName == 'restaurant' || isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                            <div class="barger_menu_main">
                                <div class="barger_menu_inner">
                                    <div class="barger_menu_icon">
                                        <i class="fa-solid fa-bars"></i>
                                    </div>
                                    <div class="barger_menu_list">
                                        <ul>
                                        @if($item_page !== null || $routeName == 'restaurant')
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
                                            @endif
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
                            <button class="btn search_bt openSearchBox lay_src_btn" id="openSearchBox">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <button class="btn search_bt closeSearchBox lay_src_btn d-none" id="closeSearchBox">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    @endif

                    {{-- Right Position --}}
                    @if ($language_bar_position == 'right')
                        <div class="lang_select">
                            @if(count($additional_languages) > 0 || $google_translate == 1)
                                <a class="lang_bt"> <x-dynamic-component width="35px" component="flag-language-{{ $language_details['code'] }}" /> </a>
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
                            @endif
                        </div>
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
                                    @if($routeName != 'shop.cart' && $routeName != 'shop.cart.checkout' && $item_page != null)
                                        <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                            <i class="fa-solid fa-basket-shopping"></i>
                                            <span class="qty-number">{{ $total_quantity }}</span>
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

                                    <!-- <a href="{{ route('shop.cart', $shop_slug) }}"
                                        class="cart-btn me-1 mt-2 fs-4 text-white text-decoration-none">
                                        <div class="position-relative"><i class="bi bi-cart4"></i> <span
                                                class="qty-number">{{ $total_quantity }}</span></div>
                                    </a> -->
                                @endif
                            @endif
                            @if($item_page !== null || $routeName == 'restaurant' || isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                            <div class="barger_menu_main">
                                <div class="barger_menu_inner">
                                    <div class="barger_menu_icon">
                                        <i class="fa-solid fa-bars"></i>
                                    </div>
                                    <div class="barger_menu_list">
                                        <ul>
                                        @if($item_page !== null || $routeName == 'restaurant')
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
                                        @endif
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
                            <button class="btn search_bt openSearchBox lay_src_btn" id="openSearchBox">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <button class="btn search_bt closeSearchBox lay_src_btn d-none" id="closeSearchBox">
                                <i class="fa-solid fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <div class="search_input">
                        <input type="text" class="form-control w-100" name="search" id="search">
                    </div>
                @else
                    @if(count($additional_languages) > 0 || $google_translate == 1)

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
                                @if($routeName != 'shop.cart' && $routeName != 'shop.cart.checkout' && $item_page != null && isset($item_page))
                                    <div id="cart-box" class="cart-btn  fs-4 text-white text-decoration-none">
                                        <i class="fa-solid fa-basket-shopping"></i>
                                        <span class="qty-number">{{ $total_quantity }}</span>
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
                            @if (isset($package_permissions['ordering']) &&
                                !empty($package_permissions['ordering']) &&
                                $package_permissions['ordering'] == 1)
                                @if ($total_quantity > 0)
                                @if($routeName != 'shop.cart.checkout' && $item_page != null && isset($item_page) && isset($item_page))
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
                                <!-- <a href="{{ route('shop.cart', $shop_slug) }}"
                                    class="cart-btn me-1 mt-2 fs-4 text-white text-decoration-none">
                                    <div class="position-relative"><i class="bi bi-cart4"></i> <span
                                            class="qty-number">{{ $total_quantity }}</span></div>
                                </a> -->
                            @endif
                        @endif
                        @if($item_page !== null || $routeName == 'restaurant' || isset($package_permissions['bell']) && $package_permissions['bell'] == 1 && $waiter_call_status == 1 || isset($package_permissions['grading']) && !empty($package_permissions['grading']) && $package_permissions['grading'] == 1)
                            <div class="barger_menu_main">
                                        <div class="barger_menu_inner">
                                            <div class="barger_menu_icon">
                                                <i class="fa-solid fa-bars"></i>
                                            </div>
                                            <div class="barger_menu_list">
                                                <ul>
                                                @if($item_page !== null || $routeName == 'restaurant')
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
                                                @endif
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

                        <button class="btn search_bt lay_src_btn  openSearchBox" id="openSearchBox">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <button class="btn search_bt lay_src_btn closeSearchBox d-none" id="closeSearchBox">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="search_input">
                        <input type="text" class="form-control w-100" name="search" id="search">
                    </div>
                @endif
            </div>
        </nav>
        @if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
            @if ($total_quantity > 0)
                @if($routeName != 'shop.cart' && $routeName != 'shop.cart.checkout' && $item_page != null && isset($item_page))
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
    </header>
@endif

