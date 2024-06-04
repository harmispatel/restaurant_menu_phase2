@php
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    // Theme
    $theme = \App\Models\Theme::where('id',$shop_theme_id)->first();
    $theme_name = isset($theme['name']) ? $theme['name'] : '';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);

    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

    if ($layout == "layout_1") {
        $class = 'layout_1';
    }elseif($layout == "layout_2")
    {
        $class = 'layout_2';
    }else {
        $class = 'layout_3';
    }

    // Order Settings
    $order_settings = getOrderSettings($shop_details['id']);

    $delivery_schedule = checkDeliverySchedule($shop_details['id']);

    $service_closed_message = moreTranslations($shop_details['id'], 'service_closed_message');
    $service_closed_message = isset($service_closed_message[$current_lang_code . '_value']) && !empty($service_closed_message[$current_lang_code . '_value']) ? $service_closed_message[$current_lang_code . '_value'] : "Sorry you can't order! The store is closed during these hours.";

    $min_amount_for_delivery = isset($order_settings['min_amount_for_delivery']) && !empty($order_settings['min_amount_for_delivery']) ? unserialize($order_settings['min_amount_for_delivery']) : [];


    // Current Route Name
    $routeName = Route::currentRouteName();

@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="{{ asset('public/admin_images/favicons/home.png') }}" rel="icon">
    @include('shop.shop-css')
</head>
<body class="{{ (!empty($theme_name) && $theme_name == 'Default Dark Theme') ? 'dark' : '' }} body {{ $class }}">

    {{-- @if ($layout == "layout_1") --}}
    {{-- Item Details Modal --}}
    {{-- <div class="modal fade" id="itemDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="item_dt_div">
                </div>
            </div>
        </div>
    </div>
        @elseif ($layout == "layout_2") --}}
        {{-- Item Details Modal --}}
            {{-- <div class="modal fade csm-modal" id="itemDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="modal-body" id="item_dt_div">
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Review Modal -->
            <div class="modal fade csm-review-modal" id="itemReviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aira-labelledby="itemReviewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div id="review_dt_div"></div>
                    </div>
                </div>
            </div>
        {{-- @else --}}

        <!-- Item Modal -->
        <div class="modal fade csm-modal item_model_preview" id="itemDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div id="item_dt_div">
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Modal -->
        <div class="modal fade csm-modal menu_model_preview" id="menuModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div id="menu_dt_div">
                    </div>
                </div>
            </div>
        </div>

        <!-- Call Waiter -->
        <div class="modal fade csm-modal call_waiter" id="callWaiterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div id="waiter_dt_div">
                    </div>
                </div>
            </div>
        </div>

        {{-- Show Store Close Message --}}
        <div class="modal fade csm-modal store_close" id="storeCloseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="storeCloseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="modal-body">
                        <div class="text-danger store_close_title">
                            {!! $service_closed_message !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Min Spent Notes --}}
        <div class="modal fade csm-modal spent_notes" id="minSpentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="minSpentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="btn-close menu_modal_close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <div class="modal-body">
                        <div class="spent_notes_title">
                            <h3 class="text-center">{{ __('Notice') }}</h3>
                        </div>
                        <div class="spent_notes_info">
                            @if (count($min_amount_for_delivery) > 0)
                                @foreach ($min_amount_for_delivery as $min_key => $min_amount)
                                    @php
                                        $minAmount = isset($min_amount['amount']) && !empty($min_amount['amount']) ? $min_amount['amount'] : 0;
                                        // Distance Message
                                        $distance_message = moreTranslations($shop_details['id'], 'distance_message');
                                        $distance_message = isset($distance_message[$current_lang_code . '_value']) && !empty($distance_message[$current_lang_code . '_value']) ? $distance_message[$current_lang_code . '_value'] : 'Distance from our store up ({from}) to ({to}) Km The lowest order price is ({amount}).';
                                        $distance_message = str_replace('{from}', $min_amount['from'], $distance_message);
                                        $distance_message = str_replace('{to}', $min_amount['to'], $distance_message);
                                        $distance_message = str_replace('{amount}', Currency::currency($currency)->format($minAmount), $distance_message);
                                    @endphp
                                    @if ($minAmount > 0)
                                        <p>{{ $distance_message }}</p>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- @endif --}}

    {{-- Navbar --}}
    @if($layout == 'layout_1')
        @include('shop.shop-navbar')
    @else
        @yield('header')
    @endif

    {{-- Main Content --}}
    <main id="main" class="main shop-main">
        <div class="page">
            @yield('content')
        </div>
    </main>

    {{-- JS --}}
    @include('shop.shop-js')

    {{-- Custom JS --}}
    @yield('page-js')

</body>
</html>
