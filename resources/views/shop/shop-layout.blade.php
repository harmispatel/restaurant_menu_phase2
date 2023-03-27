@php
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    // Theme
    $theme = \App\Models\Theme::where('id',$shop_theme_id)->first();
    $theme_name = isset($theme['name']) ? $theme['name'] : '';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);

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
<body class="{{ (!empty($theme_name) && $theme_name == 'Default Dark Theme') ? 'dark' : '' }} test">

    {{-- Item Details Modal --}}
    <div class="modal fade" id="itemDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="itemDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 500px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="item_dt_div">
                </div>
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    @include('shop.shop-navbar')

    {{-- Main Content --}}
    <main id="main" class="main shop-main py-3">
        @yield('content')
    </main>

    {{-- JS --}}
    @include('shop.shop-js')

    {{-- Custom JS --}}
    @yield('page-js')

</body>
</html>
