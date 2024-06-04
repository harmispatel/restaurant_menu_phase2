@php
$admin_settings = getAdminSettings();
$main_screen = isset($admin_settings['theme_main_screen_demo']) ? $admin_settings['theme_main_screen_demo'] : '';
$category_screen = isset($admin_settings['theme_category_screen_demo']) ? $admin_settings['theme_category_screen_demo'] : '';
$second_main_screen = isset($admin_settings['theme_main_screen_layout_two_demo']) ? $admin_settings['theme_main_screen_layout_two_demo'] : '';
$second_category_screen = isset($admin_settings['theme_category_screen_layout_two_demo']) ? $admin_settings['theme_category_screen_layout_two_demo'] : '';
$three_main_screen = isset($admin_settings['theme_main_screen_layout_three_demo']) ? $admin_settings['theme_main_screen_layout_three_demo'] : '';
$three_category_screen = isset($admin_settings['theme_category_screen_layout_three_demo']) ? $admin_settings['theme_category_screen_layout_three_demo'] : '';
$cart_modal_screen_layout = isset($admin_settings['cart_modal_screen_layout']) ? $admin_settings['cart_modal_screen_layout'] : '';



// Subscrption ID
    $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);


@endphp

@extends('client.layouts.client-layout')

@section('title', __('New Theme'))

@section('content')

<section class="theme_section">
    <div class="main_section_inr">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('design.theme') }}">{{ __('Themes')}}</a></li>
                <li class="breadcrumb-item active">{{ __('New Theme')}}</li>
            </ol>
        </nav>
        <div class="row">
            <form action="{{ route('design.theme-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12">
                    <div class="theme_change_sec">
                        <div class="theme_name">
                            <h2 class="form-group">
                                <input type="text" name="theme_name" id="theme_name" class="form-control border-0 {{ $errors->has('theme_name') ? 'is-invalid' : '' }}" placeholder="Enter Theme Name" value="">
                                @if ($errors->has('theme_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('theme_name') }}
                                </div>
                                @endif
                            </h2>
                        </div>
                        <div class="theme_change_sec_inr">
                            <div class="accordion" id="accordionExample">

                                {{-- Main Page Section --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">{{ __('Main Screen') }}</button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="main_theme_color">
                                                        <div class="row align-items-center mb-4">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Appearance') }}</span>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <select name="desk_layout" id="desk_layout" class="form-select" onchange="changeLayout();">
                                                                    <option value="layout_1">Layout 1</option>
                                                                    @if(isset($package_permissions['layout_2']) && !empty($package_permissions['layout_2']) && $package_permissions['layout_2'] == 1)
                                                                    <option value="layout_2">Layout 2</option>
                                                                    @endif
                                                                    @if(isset($package_permissions['layout_3']) && !empty($package_permissions['layout_3']) && $package_permissions['layout_3'] == 1)
                                                                    <option value="layout_3">Layout 3</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row align-item-center mb-4" id="category-view">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Category View') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <select name="category_view" id="category_view" class="form-select" onchange="categoryLayout();">
                                                                    <option value="grid">Grid</option>
                                                                    <option value="tiles">Tiles</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="slider-effect">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Silder Effect') }}</span>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <select name="slider_effect" id="slider_effect" class="form-select">
                                                                    <option value="fade">fade</option>
                                                                    <option value="cube">cube</option>
                                                                    <option value="flip">flip</option>
                                                                    <option value="coverflow">coverflow</option>
                                                                    <option value="cards">cards</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="header-color">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Header Color') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="from-group d-flex align-items-center">
                                                                    <input type="color" id="header_color" name="header_color" class="form-control me-2 p-0" value="" onchange="changeVal('header_color','header_color_input')">
                                                                    <input type="text" id="header_color_input" class="form-control" onkeyup="changeColor('header_color_input','header_color')" value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="sticky-header">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Sticky Header') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label class="switch">
                                                                    <input type="checkbox" name="sticky_header" id="sticky_header" value="1">
                                                                    <span class="slider round">
                                                                        <i class="fa-solid fa-circle-check check_icon"></i>
                                                                        <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="language-box-position">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Language Box Position') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <select name="language_bar_position" id="language_bar_position" class="form-select">
                                                                    <option value="right">
                                                                        Right</option>
                                                                    <option value="left">
                                                                        Left</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="logo-position">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Logo Position') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <select name="logo_position" id="logo_position" class="form-select">
                                                                    <option value="right">
                                                                        Right</option>
                                                                    <option value="left">
                                                                        Left</option>
                                                                    <option value="center">
                                                                        Center</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="search-box-position">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Burger Menu Position') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <select name="search_box_position" id="search_box_position" class="form-select">
                                                                    <option value="right">
                                                                        Right</option>
                                                                    <option value="left">
                                                                        Left</option>
                                                                    <option value="center">
                                                                        Center</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="search-box-icon-color">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Submenu Icon Color') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="from-group d-flex align-items-center">
                                                                    <input type="color" id="search_box_icon_color" name="search_box_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('search_box_icon_color','search_box_icon_color_input')">
                                                                    <input id="search_box_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('search_box_icon_color_input','search_box_icon_color')">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="icon-bg-color">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Submenu BG Color') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="from-group d-flex align-items-center">
                                                                    <input type="color" id="icon_bg_color" name="icon_bg_color" class="form-control me-2 p-0" value="" onchange="changeVal('icon_bg_color','icon_bg_color_input')">
                                                                    <input id="icon_bg_color_input" type="text" class="form-control" value="" onkeyup="changeColor('icon_bg_color_input','icon_bg_color')">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center mb-4" id="banner-position">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Banner Position') }}</span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <select name="banner_position" id="banner_position" class="form-select">
                                                                    <option value="top">
                                                                        Top</option>
                                                                    <option value="bottom">
                                                                        Bottom</option>
                                                                    <option value="hide">
                                                                        Hidden</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        {{-- <div class="row align-items-center mb-4">
                                                            <div class="col-md-7">
                                                                <span>{{ __('Banner Type')}}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select name="banner_type" id="banner_type" class="form-select">
                                                            <option value="text">Text</option>
                                                            <option value="image">Image</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                <div class="row align-items-center mb-4" id="banner-slide-button">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Banner Buttons') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="switch">
                                                            <input type="checkbox" name="banner_slide_button" id="banner_slide_button" value="1">
                                                            <span class="slider round">
                                                                <i class="fa-solid fa-circle-check check_icon"></i>
                                                                <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="banner-delay-time">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Banner Speed') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="number" name="banner_delay_time" id="banner_delay_time" class="form-control" value="">
                                                            <code>Enter Time in Miliseconds</code>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category_image_slider">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Categories Effect') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <select name="category_image_sider" id="category_image_siders" class="form-control" onchange="changeSlider();">
                                                                <option value="stop">
                                                                    Stop</option>
                                                                <option value="slider">
                                                                    Slider</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row align-items-center mb-4" id="category-image-slider-delay-time">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Categories Effect Speed') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="number" name="category_image_slider_delay_time" id="category_image_slider_delay_time" class="form-control" value="">
                                                            <code>Enter Time in Miliseconds</code>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="banner-height">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Banner Height') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="number" name="banner_height" id="banner_height" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="background-color">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Background Color') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="background_color" name="background_color" class="form-control me-2 p-0" value="" onchange="changeVal('background_color','background_color_input')">
                                                            <input id="background_color_input" type="text" class="form-control" value="" onkeyup="changeColor('background_color_input','background_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Background Image') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="file" name="bg_image"
                                                                id="bg_image"
                                                                class="form-control {{ $errors->has('bg_image') ? 'is-invalid' : '' }}">
                                                        </div>
                                                    </div>
                                            </div>
                                                <div class="row align-items-center mb-4" id="font-color">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Font Color') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="font_color" name="font_color" class="form-control me-2 p-0" value="" onchange="changeVal('font_color','font_color_input')">
                                                            <input id="font_color_input" type="text" class="form-control" value="" onkeyup="changeColor('font_color_input','font_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="label-color">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Label Color') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="label_color" name="label_color" class="form-control me-2 p-0" value="" onchange="changeVal('label_color','label_color_input')">
                                                            <input id="label_color_input" type="text" class="form-control" value="" onkeyup="changeColor('label_color_input','label_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="label-font-size">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Label font size') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="number" min="1" id="label_font_size" name="label_font_size" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="label-color-transparency">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Label Color Transparency') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="range" id="label_color_transparency" min="0" max="1" step="0.1" value="1" name="label_color_transparency">
                                                            <output class="ms-1" for="label_color_transparency" id="slider-value">1</output>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="social-media-icon-color">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Social Media Icons Color') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="social_media_icon_color" name="social_media_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('social_media_icon_color','social_media_icon_color_input')">
                                                            <input id="social_media_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('social_media_icon_color_input','social_media_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-side">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Category Side') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <select name="category_side" id="category_side" class="form-control">
                                                                <option value="left">
                                                                    Left</option>
                                                                <option value="right">
                                                                    Right</option>
                                                                <option value="alternatively_even">
                                                                    Alternatively Even</option>
                                                                <option value="alternatively_odd">
                                                                    Alternatively Odd</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="bar-icon-color">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Burger Menu Icon') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="bar_icon_color"
                                                                name="bar_icon_color"
                                                                class="form-control me-2 p-0"
                                                                value=""
                                                                onchange="changeVal('bar_icon_color','bar_icon_color_input')">
                                                            <input id="bar_icon_color_input"
                                                                type="text" class="form-control"
                                                                value=""
                                                                onkeyup="changeColor('bar_icon_color_input','bar_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4"
                                                                id="bar-icon-bg-color">
                                                                <div class="col-md-7">
                                                                    <span>{{ __('Burger Menu BG') }}</span>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="from-group d-flex align-items-center">
                                                                        <input type="color" id="bar_icon_bg_color"
                                                                            name="bar_icon_bg_color"
                                                                            class="form-control me-2 p-0"
                                                                            value=""
                                                                            onchange="changeVal('bar_icon_bg_color','bar_icon_bg_color_input')">
                                                                        <input id="bar_icon_bg_color_input"
                                                                            type="text" class="form-control"
                                                                            value=""
                                                                            onkeyup="changeColor('bar_icon_bg_color_input','bar_icon_bg_color')">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center mb-4"
                                                                id="cover-link-icon-color">
                                                                <div class="col-md-7">
                                                                    <span>{{ __('Cover link Icon Color') }}</span>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="from-group d-flex align-items-center">
                                                                        <input type="color" id="cover_link_icon_color"
                                                                            name="cover_link_icon_color"
                                                                            class="form-control me-2 p-0"
                                                                            value=""
                                                                            onchange="changeVal('cover_link_icon_color','cover_link_icon_color_input')">
                                                                        <input id="cover_link_icon_color_input"
                                                                            type="text" class="form-control"
                                                                            value=""
                                                                            onkeyup="changeColor('cover_link_icon_color_input','cover_link_icon_color')">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center mb-4"
                                                                id="cover-link-bg-color">
                                                                <div class="col-md-7">
                                                                    <span>{{ __('Cover Link BG Color') }}</span>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <div class="from-group d-flex align-items-center">
                                                                        <input type="color" id="cover_link_bg_color"
                                                                            name="cover_link_bg_color"
                                                                            class="form-control me-2 p-0"
                                                                            value=""
                                                                            onchange="changeVal('cover_link_bg_color','cover_link_bg_color_input')">
                                                                        <input id="cover_link_bg_color_input"
                                                                            type="text" class="form-control"
                                                                            value=""
                                                                            onkeyup="changeColor('cover_link_bg_color_input','cover_link_bg_color')">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                <div class="row align-items-center mb-4" id="cart-animation-color">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Cart Animation Color') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="cart_animation_color" name="cart_animation_color" class="form-control me-2 p-0" value="" onchange="changeVal('cart_animation_color','cart_animation_color_input')">
                                                            <input id="cart_animation_color_input" type="text" class="form-control" value="" onkeyup="changeColor('cart_animation_color_input','cart_animation_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="header-image">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Header Image') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <input type="file" name="header_image" id="header_image" class="form-control {{ $errors->has('header_image') ? 'is-invalid' : '' }}">
                                                            @if ($errors->has('header_image'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('header_image') }}
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="preview_img" id="main_screen">
                                                @if (!empty($main_screen) && file_exists($main_screen))
                                                <img src="{{ $main_screen }}" class="w-100">
                                                {{-- <img src="{{ asset('public/client_images/not-found/theme_main_screen.png') }}" class="w-100"> --}}
                                                @endif
                                            </div>
                                            <div class="preview_img" id="second_main_screen">
                                                @if (!empty($second_main_screen) && file_exists($second_main_screen))
                                                <img src="{{ $second_main_screen }}" class="w-100">
                                                {{-- <img src="{{ asset('public/client_images/not-found/theme_main_screen.png') }}" class="w-100"> --}}
                                                @endif
                                            </div>
                                            <div class="preview_img" id="three_main_screen">
                                                @if (!empty($three_main_screen) && file_exists($three_main_screen))
                                                <img src="{{ $three_main_screen }}" class="w-100">
                                                {{-- <img src="{{ asset('public/client_images/not-found/theme_main_screen.png') }}" class="w-100"> --}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Item Page Section --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    {{ __('Within Item Screen') }}
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="main_theme_color">
                                                <div class="row align-item-center mb-4" id="category-slider-effect">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Categories Search Style') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <select name="category_slider_effect" id="category_slider_effect" class="form-select">
                                                                <option value="default">
                                                                    Default</option>
                                                                <option value="coverflow">
                                                                    Coverflow</option>
                                                                <option value="wheel">
                                                                    Wheel</option>
                                                                <option value="carousel">
                                                                    Carousel</option>
                                                                <option value="flat">
                                                                    Flat</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="categories-bar-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Search Arrows') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="categories_bar_color" name="categories_bar_color" class="form-control me-2 p-0" value="" onchange="changeVal('categories_bar_color','categories_bar_color_input')">
                                                            <input id="categories_bar_color_input" type="text" class="form-control" value="" onkeyup="changeColor('categories_bar_color_input','categories_bar_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-bar-type">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Images') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="category_bar_type" id="category_bar_type" class="form-select">
                                                            <option value="8px">
                                                                Square</option>
                                                            <option value="50%">
                                                                Circle</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="menu-bar-font-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Bar Fonts Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="menu_bar_font_color" name="menu_bar_font_color" class="form-control me-2 p-0" value="" onchange="changeVal('menu_bar_font_color','menu_bar_font_color_input')">
                                                            <input id="menu_bar_font_color_input" type="text" class="form-control" value="" onkeyup="changeColor('menu_bar_font_color_input','menu_bar_font_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-title-and-description-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Title & Description Fonts Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_title_and_description_color" name="category_title_and_description_color" class="form-control me-2 p-0" value="" onchange="changeVal('category_title_and_description_color','category_title_and_description_color_input')">
                                                            <input id="category_title_and_description_color_input" type="text" class="form-control" value="" onkeyup="changeColor('category_title_and_description_color_input','category_title_and_description_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="price-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Price Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="price_color" name="price_color" class="form-control me-2 p-0" value="" onchange="changeVal('price_color','price_color_input')">
                                                            <input id="price_color_input" type="text" class="form-control" value="" onkeyup="changeColor('price_color_input','price_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="cart-icon-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Cart Icon Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="cart_icon_color" name="cart_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('cart_icon_color','cart_icon_color_input')">
                                                            <input id="cart_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('cart_icon_color_input','cart_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-box-background-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Box Background Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="item_box_background_color" name="item_box_background_color" class="form-control me-2 p-0" value="" onchange="changeVal('item_box_background_color','item_box_background_color_input')">
                                                            <input id="item_box_background_color_input" type="text" class="form-control" value="" onkeyup="changeColor('item_box_background_color_input','item_box_background_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-title-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Title Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="item_title_color" name="item_title_color" class="form-control me-2 p-0" value="" onchange="changeVal('item_title_color','item_title_color_input')">
                                                            <input id="item_title_color_input" type="text" class="form-control" value="" onkeyup="changeColor('item_title_color_input','item_title_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-description-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Description Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="item_description_color" name="item_description_color" class="form-control me-2 p-0" value="" onchange="changeVal('item_description_color','item_description_color_input')">
                                                            <input id="item_description_color_input" type="text" class="form-control" value="" onkeyup="changeColor('item_description_color_input','item_description_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-box-shadow">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Box Shadow') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="switch">
                                                            <input type="checkbox" name="item_box_shadow" id="item_box_shadow"  value="1">
                                                            <span class="slider round">
                                                                <i class="fa-solid fa-circle-check check_icon"></i>
                                                                <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-box-shadow-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Box Shadow Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="item_box_shadow_color" name="item_box_shadow_color" class="form-control me-2 p-0" value="" onchange="changeVal('item_box_shadow_color','item_box_shadow_color_input')">
                                                            <input id="item_box_shadow_color_input" type="text" class="form-control" value="" onkeyup="changeColor('item_box_shadow_color_input','item_box_shadow_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-box-shadow-thickness">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Box Shadow Thickness') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="item_box_shadow_thickness" id="item_box_shadow_thickness" class="form-select">
                                                            <option value="1px">
                                                                Light</option>
                                                            <option value="3px">
                                                                Medium</option>
                                                            <option value="5px">
                                                                Bold</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-divider">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Divider') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="switch">
                                                            <input type="checkbox" name="item_divider" id="item_divider" value="1" >
                                                            <span class="slider round">
                                                                <i class="fa-solid fa-circle-check check_icon"></i>
                                                                <i class="fa-sharp fa-solid fa-circle-xmark uncheck_icon"></i>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-divider-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Divider Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="item_divider_color" name="item_divider_color" class="form-control me-2 p-0" value="" onchange="changeVal('item_divider_color','item_divider_color_input')">
                                                            <input id="item_divider_color_input" type="text" class="form-control" value="" onkeyup="changeColor('item_divider_color_input','item_divider_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-divider-thickness">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Divider Thickness') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <input type="number" name="item_divider_thickness" id="item_divider_thickness" class="form-control" value="">
                                                            <code>Enter Thickness in Pexels</code>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-divider-type">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Divider Type') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="item_divider_type" id="item_divider_type" class="form-select">
                                                            <option value="solid">
                                                                Solid</option>
                                                            <option value="dotted">
                                                                Dotted</option>
                                                            <option value="dashed">
                                                                Dashed</option>
                                                            <option value="double">
                                                                Double</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-divider-position">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Divider Position') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="item_divider_position" id="item_divider_position" class="form-select">
                                                            <option value="top">
                                                                Top</option>
                                                            <option value="bottom">
                                                                Bottom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-divider-font-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Item Divider Font Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="item_divider_font_color" name="item_divider_font_color" class="form-control me-2 p-0" value="" onchange="changeVal('item_divider_font_color','item_divider_font_color_input')">
                                                            <input id="item_divider_font_color_input" type="text" class="form-control" value="" onkeyup="changeColor('item_divider_font_color_input','item_divider_font_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="tag-font-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Active Tag Text') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="tag_font_color" name="tag_font_color" class="form-control me-2 p-0" value="" onchange="changeVal('tag_font_color','tag_font_color_input')">
                                                            <input id="tag_font_color_input" type="text" class="form-control" value="" onkeyup="changeColor('tag_font_color_input','tag_font_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="tag-label-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Tag & Underline Text') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="tag_label_color" name="tag_label_color" class="form-control me-2 p-0" value="" onchange="changeVal('tag_label_color','tag_label_color_input')">
                                                            <input id="tag_label_color_input" type="text" class="form-control" value="" onkeyup="changeColor('tag_label_color_input','tag_label_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="read-more-link-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Read More Link Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="read_more_link_color" name="read_more_link_color" class="form-control me-2 p-0" value="" onchange="changeVal('read_more_link_color','read_more_link_color_input')">
                                                            <input id="read_more_link_color_input" type="text" class="form-control" value="" onkeyup="changeColor('read_more_link_color_input','read_more_link_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="special-discount-backgound-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Special Discount Backgound color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="special_discount_backgound_color" name="special_discount_backgound_color" class="form-control me-2 p-0" value="" onchange="changeVal('special_discount_backgound_color','special_discount_backgound_color_input')">
                                                            <input id="special_discount_backgound_color_input" type="text" class="form-control" value="" onkeyup="changeColor('special_discount_backgound_color_input','special_discount_backgound_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="special-discount-text-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Special Discount text color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="special_discount_text_color" name="special_discount_text_color" class="form-control me-2 p-0" value="" onchange="changeVal('special_discount_text_color','special_discount_text_color_input')">
                                                            <input id="special_discount_text_color_input" type="text" class="form-control" value="" onkeyup="changeColor('special_discount_text_color_input','special_discount_text_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="special-day-effect-box">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Special Day Effect Box') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="special_day_effect_box" id="special_day_effect_box" class="form-select">
                                                            <option value="blink">
                                                                Blink</option>
                                                            <option value="rotate">
                                                                Rotate</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="special-day-effect-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Special Day Effect Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="special_day_effect_color" name="special_day_effect_color" class="form-control me-2 p-0" value="" onchange="changeVal('special_day_effect_color','special_day_effect_color_input')">
                                                            <input id="special_day_effect_color_input" type="text" class="form-control" value="" onkeyup="changeColor('special_day_effect_color_input','special_day_effect_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-box-shadow">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Box Shadow') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_box_shadow" name="category_box_shadow" class="form-control me-2 p-0" value="" onchange="changeVal('category_box_shadow','category_box_shadow_input')">
                                                            <input id="category_box_shadow_input" type="text" class="form-control" value="" onkeyup="changeColor('category_box_shadow_input','category_box_shadow')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-box-title-icon-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Box title & icon color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_box_title_icon_color" name="category_box_title_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('category_box_title_icon_color','category_box_title_icon_color_input')">
                                                            <input id="category_box_title_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('category_box_title_icon_color_input','category_box_title_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-box-text-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Box Text Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_box_text_color" name="category_box_text_color" class="form-control me-2 p-0" value="" onchange="changeVal('category_box_text_color','category_box_text_color_input')">
                                                            <input id="category_box_text_color_input" type="text" class="form-control" value="" onkeyup="changeColor('category_box_text_color_input','category_box_text_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-box-title-background">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Box Title Background') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_box_title_background" name="category_box_title_background" class="form-control me-2 p-0" value="" onchange="changeVal('category_box_title_background','category_box_title_background_input')">
                                                            <input id="category_box_title_background_input" type="text" class="form-control" value="" onkeyup="changeColor('category_box_title_background_input','category_box_title_background')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-box-background">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Box background') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_box_background" name="category_box_background" class="form-control me-2 p-0" value="" onchange="changeVal('category_box_background','category_box_background_input')">
                                                            <input id="category_box_background_input" type="text" class="form-control" value="" onkeyup="changeColor('category_box_background_input','category_box_background')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="category-box-act-txt-bg">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Category Box Active Text Background') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="category_box_act_txt_bg" name="category_box_act_txt_bg" class="form-control me-2 p-0" value="" onchange="changeVal('category_box_act_txt_bg','category_box_act_txt_bg_input')">
                                                            <input id="category_box_act_txt_bg_input" type="text" class="form-control" value="" onkeyup="changeColor('category_box_act_txt_bg_input','category_box_act_txt_bg')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="back-arrow-bg-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Back Arrow Backgorund Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="back_arrow_bg_color" name="back_arrow_bg_color" class="form-control me-2 p-0" value="" onchange="changeVal('back_arrow_bg_color','back_arrow_bg_color_input')">
                                                            <input id="back_arrow_bg_color_input" type="text" class="form-control" value="" onkeyup="changeColor('back_arrow_bg_color_input','back_arrow_bg_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="back-arrow-icon-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Back Arrow Backgorund Icon Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="back_arrow_icon_color" name="back_arrow_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('back_arrow_icon_color','back_arrow_icon_color_input')">
                                                            <input id="back_arrow_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('back_arrow_icon_color_input','back_arrow_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="item-rating-star-icon-colors">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Rating Stars Icon Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="rating_star_icon_color" name="rating_star_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('rating_star_icon_color','rating_star_icon_color_input')">
                                                            <input id="rating_star_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('rating_star_icon_color_input','rating_star_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="theme-preview-image">
                                                    <div class="col-md-4">
                                                        <span>{{ __('Theme Preview Image') }}</span>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group align-items-center">
                                                            <input type="file" name="theme_preview_image" id="theme_preview_image" class="form-control {{ $errors->has('theme_preview_image') ? 'is-invalid' : '' }}">
                                                            @if ($errors->has('theme_preview_image'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('theme_preview_image') }}
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="header-effect-bg-color">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Header Effect Background Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color"
                                                                id="header_effect_bg_color"
                                                                name="header_effect_bg_color"
                                                                class="form-control me-2 p-0"
                                                                value=""
                                                                onchange="changeVal('header_effect_bg_color','header_effect_bg_color_input')">
                                                            <input id="header_effect_bg_color_input"
                                                                type="text" class="form-control"
                                                                value=""
                                                                onkeyup="changeColor('header_effect_bg_color_input','header_effect_bg_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4" id="header-bg-color-opc">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Header Background Color Opacity') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="range" id="header_bg_color_opc" min="0" max="1" step="0.1" value="1" name="header_bg_color_opc">
                                                            <output class="ms-1" for="header_bg_color_opc" id="header-opacity">1</output>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="preview_img" id="category-screen">
                                                @if (!empty($category_screen) && file_exists($category_screen))
                                                <img src="{{ $category_screen }}" class="w-100">
                                                @endif
                                            </div>
                                            <div class="preview_img" id="second-category-screen">
                                                @if (!empty($second_category_screen) && file_exists($second_category_screen))
                                                <img src="{{ $second_category_screen }}" class="w-100">
                                                @endif
                                            </div>
                                            <div class="preview_img" id="three-category-screen">
                                                @if (!empty($three_category_screen) && file_exists($three_category_screen))
                                                <img src="{{ $three_category_screen }}" class="w-100">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Item Cart Modal --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    {{ __('Item Cart Modal') }}
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="main_theme_color">
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Title Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_item_title_color" name="modal_item_title_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_item_title_color','modal_item_title_color_input')">
                                                            <input id="modal_item_title_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_item_title_color_input','modal_item_title_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-7">
                                                        <span>{{ __('Title Font Size') }}</span>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="number" min="1" id="modal_item_title_font_size" name="modal_item_title_font_size" class="form-control" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Description Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_item_des_color" name="modal_item_des_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_item_des_color','modal_item_des_color_input')">
                                                            <input id="modal_item_des_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_item_des_color_input','modal_item_des_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Price Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_item_price_color" name="modal_item_price_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_item_price_color','modal_item_price_color_input')">
                                                            <input id="modal_item_price_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_item_price_color_input','modal_item_price_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Close Button Icon') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_close_icon_color" name="modal_close_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_close_icon_color','modal_close_icon_color_input')">
                                                            <input id="modal_close_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_close_icon_color_input','modal_close_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Close Button BG') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_close_bg_color" name="modal_close_bg_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_close_bg_color','modal_close_bg_color_input')">
                                                            <input id="modal_close_bg_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_close_bg_color_input','modal_close_bg_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Cart Button color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_add_btn_color" name="modal_add_btn_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_add_btn_color','modal_add_btn_color_input')">
                                                            <input id="modal_add_btn_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_add_btn_color_input','modal_add_btn_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Cart Button text color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_add_btn_text_color" name="modal_add_btn_text_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_add_btn_text_color','modal_add_btn_text_color_input')">
                                                            <input id="modal_add_btn_text_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_add_btn_text_color_input','modal_add_btn_text_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Quantity Icon Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_quantity_icon_color" name="modal_quantity_icon_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_quantity_icon_color','modal_quantity_icon_color_input')">
                                                            <input id="modal_quantity_icon_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_quantity_icon_color_input','modal_quantity_icon_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Quantity BG Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_quantity_bg_color" name="modal_quantity_bg_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_quantity_bg_color','modal_quantity_bg_color_input')">
                                                            <input id="modal_quantity_bg_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_quantity_bg_color_input','modal_quantity_bg_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Price label color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_price_label_color" name="modal_price_label_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_price_label_color','modal_price_label_color_input')">
                                                            <input id="modal_price_label_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_price_label_color_input','modal_price_label_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Ingredients Text Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_igradient_type_color" name="modal_igradient_type_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_igradient_type_color','modal_igradient_type_color_input')">
                                                            <input id="modal_igradient_type_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_igradient_type_color_input','modal_igradient_type_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Background Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="modal_body_bg_color" name="modal_body_bg_color" class="form-control me-2 p-0" value="" onchange="changeVal('modal_body_bg_color','modal_body_bg_color_input')">
                                                            <input id="modal_body_bg_color_input" type="text" class="form-control" value="" onkeyup="changeColor('modal_body_bg_color_input','modal_body_bg_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-4">
                                                    <div class="col-md-8">
                                                        <span>{{ __('Rating Service Name Color') }}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="from-group d-flex align-items-center">
                                                            <input type="color" id="rating_service_name_color" name="rating_service_name_color" class="form-control me-2 p-0" value="" onchange="changeVal('rating_service_name_color','rating_service_name_color_input')">
                                                            <input id="rating_service_name_color_input" type="text" class="form-control" value="" onkeyup="changeColor('rating_service_name_color_input','rating_service_name_color')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="preview_img">
                                                @if (!empty($cart_modal_screen_layout) && file_exists($cart_modal_screen_layout))
                                                <img src="{{ $cart_modal_screen_layout }}" class="w-100">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <button class="btn btn-success">{{ __('S A V E')}}</button>
    </div>
    </form>
    </div>
    </div>
</section>

@endsection

{{-- Custom JS --}}
@section('page-js')

<script type="text/javascript">
    $(document).ready(function() {
        changeLayout();
        changeSlider();
        categoryLayout();
    });

    function changeVal(colorPickerID, textID) {
        var value = $('#' + colorPickerID).val();
        $('#' + textID).val(value);
    }

    function changeColor(textID, colorPickerID) {
        var value = $('#' + textID).val();
        $('#' + colorPickerID).val(value);
    }

    // Function for Label Color Opacity
    $('#label_color_transparency').on("change mousemove", function() {
        $('#slider-value').html($(this).val());
    });

    // Function for Label Color Opacity
    $('#header_bg_color_opc').on("change mousemove", function() {
        $('#header-opacity').html($(this).val());
    });



          // Fuction layout Change
        function changeLayout() {
            var layout = $('#desk_layout').val();

            if (layout == 'layout_2') {
                $('#banner-position,#main_screen,#three_main_screen,#category-slider-effect,#categories-bar-color,#category-screen,#three-category-screen,#category-box-shadow,#category-box-background,#category-box-title-icon-color,#category-box-text-color,#category-box-title-background,#back-arrow-bg-color,#back-arrow-icon-color,#category-box-act-txt-bg').hide();
                $('#banner-height,#icon-bg-color,#special-discount-text-color,#cart-animation-color,#category-view,#banner-slide-button,#slider-effect,#search-box-icon-color,#category_image_slider,#category-image-slider-delay-time,#second_main_screen,#category-side,#header-image,#category-view,#second-category-screen,#special-discount-backgound-color,#label-font-size,#header-bg-color-opc,##banner-delay-time,#sticky-header,#header-effect-bg-color,#logo-position,#search-box-position,#bar-icon-color,#bar-icon-bg-color,#cover-link-icon-color,#cover-link-bg-color').show();
            } else if (layout == 'layout_1') {
                $('#second_main_screen,#three_main_screen,#header-image,#category-view,#second-category-screen,#three-category-screen#special-discount-backgound-color,#special-discount-text-color,#cart-animation-color,#category-view,#cart-animation-color,#three-category-screen,#category-box-shadow,#category-box-background,#category-box-title-icon-color,#category-box-text-color,#category-box-title-background,#category-side,#back-arrow-bg-color,#back-arrow-icon-color,#category-box-act-txt-bg,#header-bg-color-opc,#header-effect-bg-color,#category_image_slider,#category-image-slider-delay-time').hide();
                $('#sticky-header,#logo-position,#search-box-position,#banner-position,#banner-delay-time,#banner-height,#slider-effect,#search-box-icon-color,#main_screen,#category-slider-effect,#categories-bar-color,#category-bar-type,#menu-bar-font-color,#item-box-background-color,#price-color,#item-title-color,#item-description-color,#item-box-shadow,#item-box-shadow-color,#item-box-shadow-thickness,#item-divider,#item-divider-color,#item-divider-thickness,#item-divider-type,#item-divider-position,#item-divider-font-color,#read-more-link-color,#cart-icon-color,#category-screen,#label-font-size,#icon-bg-color,#bar-icon-color,#bar-icon-bg-color,#cover-link-icon-color,#cover-link-bg-color').show();
            } else if (layout == 'layout_3') {
                $('#language-box-position,#category-view,#logo-position,#search-box-position,#banner-position,#font-color,#label-color,#label-color-transparency,#main_screen,#second_main_screen,#header-image,#special-discount-text-color,#cart-animation-color,#category-slider-effect,#categories-bar-color,#category-bar-type,#menu-bar-font-color,#item-box-background-color,#price-color,#item-title-color,#item-description-color,#item-box-shadow,#item-box-shadow-color,#item-box-shadow-thickness,#item-box-shadow-thickness,#item-divider,#item-divider-color,#item-divider-thickness,#item-divider-type,#item-divider-position,#item-divider-font-color,#read-more-link-color,#cart-icon-color,#category-screen,#second-category-screen,#special-discount-backgound-color,#category-view,#cart-animation-color,#label-font-size,#header-bg-color-opc,#header-effect-bg-color').hide();
                $('#sticky-header,#banner-slide-button,#banner-delay-time,#banner-height,#slider-effect,#category-side,#category_image_slider,#category-image-slider-delay-time,#three-category-screen,#three_main_screen,#category-box-shadow,#category-box-background,#category-box-title-icon-color,#category-box-text-color,#category-box-title-background,#back-arrow-bg-color,#back-arrow-icon-color,#category-box-act-txt-bg,#icon-bg-color,#search-box-icon-color,#bar-icon-color,#bar-icon-bg-color,#cover-link-icon-color,#cover-link-bg-color').show();
            }
        }

    function changeSlider() {
        var sliderType = $("#category_image_siders").val();
        if (sliderType == 'stop') {
            $('#category_image_slider_delay_time').hide();
        } else {
            $('#category_image_slider_delay_time').show();
        }
    }

    // Function Category view
    function categoryLayout() {
        var categoryView = $("#category_view").val();
        var layout = $('#desk_layout').val();
        if (layout == 'layout_2') {
            if (categoryView == "grid" || categoryView == "") {
                $('#category-side').hide();
            } else {
                $('#category-side').show();
            }
        }
    }
</script>

@endsection
