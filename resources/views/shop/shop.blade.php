@php
    // Shop Settings
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Intro Second
    $intro_second = (isset($shop_settings['intro_icon_duration']) && !empty($shop_settings['intro_icon_duration'])) ? $shop_settings['intro_icon_duration'] : '';

    // Shop Name
    $shop_name = (isset($shop_details['name']) && !empty($shop_details['name'])) ? $shop_details['name'] : "";

    $shop_desc = (isset($shop_details['description']) && !empty($shop_details['description'])) ? strip_tags($shop_details['description']) : "";

    // Default Logo
    $default_logo = asset('public/client_images/not-found/your_logo_1.png');

    // Shop Logo
    $shop_logo = (isset($shop_settings['shop_view_header_logo']) && !empty($shop_settings['shop_view_header_logo'])) ? $shop_settings['shop_view_header_logo'] : "";

    // Language Details
    $language_details = getLangDetailsbyCode($current_lang_code);

    // Get Banner Settings
    $banner_setting = getBannerSetting($shop_details['id']);
    $banner_key = $language_details['code']."_image";
    $banner_text_key = $language_details['code']."_title";
    $banner_image = isset($banner_setting[$banner_key]) ? $banner_setting[$banner_key] : "";
    $banner_text = isset($banner_setting[$banner_text_key]) ? $banner_setting[$banner_text_key] : "";

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);

    // Get Subscription ID
    $subscription_id = getClientSubscriptionID($shop_details['id']);

    // Get Package Permissions
    $package_permissions = getPackagePermission($subscription_id);
    $shop_desc= html_entity_decode($shop_desc);
    $shop_title = "$shop_name | $shop_desc";

@endphp

@extends('shop.shop-layout')

@section('title', $shop_title)

@section('content')

    <input type="hidden" name="shop_id" id="shop_id" value="{{ encrypt($shop_details['id']); }}">

    @if(isset($package_permissions['banner']) && !empty($package_permissions['banner']) && $package_permissions['banner'] == 1)
        @if(isset($theme_settings['banner_position']) && !empty($theme_settings['banner_position']) && $theme_settings['banner_position'] == 'top')
            @if($theme_settings['banner_type'] == 'image')
                @if(!empty($banner_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$banner_image))
                    <div class="banner-image banner-img">
                        {{-- <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/banners/'.$banner_image) }}" class="w-100"> --}}
                    </div>
                @endif
            @else
                @if(!empty($banner_text))
                    <div class="banner-image">
                        <div class="container">{!! $banner_text !!}</div>
                    </div>
                @endif
            @endif
        @endif
    @endif

    <section class="sec_main">
        <div class="container" id="CategorySection">

            @if(count($categories) > 0)
                <div class="menu_list">
                    @foreach ($categories as $category)

                        @php
                            $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                            $name_code = $current_lang_code."_name";
                        @endphp

                        <div class="menu_list_item">
                            <a href="{{ route('items.preview',[$shop_details['shop_slug'],$category->id]) }}">
                                @if(!empty($category->image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category->image))
                                    <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category->image) }}" class="w-100">
                                @else
                                    <img src="{{ $default_cat_img }}" class="w-100">
                                @endif
                                <h3 class="item_name">{{ isset($category->$name_code) ? $category->$name_code : '' }}</h3>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <h3 class="text-center empty-category">Categories not Found.</h3>
            @endif
        </div>
    </section>

    @if(isset($package_permissions['banner']) && !empty($package_permissions['banner']) && $package_permissions['banner'] == 1)
        @if(isset($theme_settings['banner_position']) && !empty($theme_settings['banner_position']) && $theme_settings['banner_position'] == 'bottom')
            @if($theme_settings['banner_type'] == 'image')
                @if(!empty($banner_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$banner_image))
                    <div class="banner-image banner-img">
                        {{-- <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/banners/'.$banner_image) }}" class="w-100"> --}}
                    </div>
                @endif
            @else
                @if(!empty($banner_text))
                    <div class="banner-image">
                        <div class="container">{!! $banner_text !!}</div>
                    </div>
                @endif
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
                        @if(isset($shop_settings['business_telephone']) && !empty($shop_settings['business_telephone']))
                            <li>
                                <a href="tel:{{ $shop_settings['business_telephone'] }}"><i class="fa-solid fa-phone"></i></a>
                            </li>
                        @endif

                        {{-- Instagram Link --}}
                        @if(isset($shop_settings['instagram_link']) && !empty($shop_settings['instagram_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['instagram_link'] }}"><i class="fa-brands fa-square-instagram"></i></a>
                            </li>
                        @endif

                        {{-- Twitter Link --}}
                        @if(isset($shop_settings['twitter_link']) && !empty($shop_settings['twitter_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['twitter_link'] }}"><i class="fa-brands fa-square-twitter"></i></a>
                            </li>
                        @endif

                        {{-- Facebook Link --}}
                        @if(isset($shop_settings['facebook_link']) && !empty($shop_settings['facebook_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['facebook_link'] }}"><i class="fa-brands fa-square-facebook"></i></a>
                            </li>
                        @endif

                        {{-- FourSquare Link --}}
                        @if(isset($shop_settings['foursquare_link']) && !empty($shop_settings['foursquare_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['foursquare_link'] }}"><i class="fa-brands fa-foursquare"></i></a>
                            </li>
                        @endif

                        {{-- TripAdvisor Link --}}
                        @if(isset($shop_settings['tripadvisor_link']) && !empty($shop_settings['tripadvisor_link']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['tripadvisor_link'] }}"><img src="{{ asset('public/client_images/bs-icon/tripadvisor_green.png') }}" style="width: 37px; height: 35px;"></a>
                            </li>
                        @endif

                        {{-- Website Link --}}
                        @if(isset($shop_settings['website_url']) && !empty($shop_settings['website_url']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['website_url'] }}"><i class="fa-solid fa-globe"></i></a>
                            </li>
                        @endif

                        {{-- Gmap Link --}}
                        @if(isset($shop_settings['map_url']) && !empty($shop_settings['map_url']))
                            <li>
                                <a target="_blank" href="{{ $shop_settings['map_url'] }}"><i class="fa-solid fa-location-dot"></i></a>
                            </li>
                        @endif

                    </ul>
                </div>

                @if(isset($shop_settings['homepage_intro']) && !empty($shop_settings['homepage_intro']))
                    <p>{!! $shop_settings['homepage_intro'] !!}</p>
                @else
                    @php
                        $current_year = \Carbon\Carbon::now()->format('Y');
                        $settings = getAdminSettings();
                        $copyright_text = (isset($settings['copyright_text']) && !empty($settings['copyright_text'])) ? $settings['copyright_text'] : '';
                        $copyright_text = str_replace('[year]',$current_year,$copyright_text);
                    @endphp
                    <p>{!! $copyright_text !!}</p>
                @endif

            </div>
        </div>
    </footer>

    <div class="loader">
        <img src="{{ asset('public/admin_images/logos/qr_gif.gif') }}" width="200px">
        {{-- <div class="restaurant-loader">
            <div class="restaurant-loader-inner"></div>
        </div> --}}
    </div>

    <input type="hidden" name="intro_second" id="intro_second" value="{{ $intro_second }}">

    @if(isset($shop_settings['intro_icon_status']) && !empty($shop_settings['intro_icon_status']) && $shop_settings['intro_icon_status'] == 1)
        @if(isset($shop_settings['shop_intro_icon']) && !empty($shop_settings['shop_intro_icon']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_settings['shop_intro_icon']))
            @php
                $intro_file_ext = pathinfo($shop_settings['shop_intro_icon'], PATHINFO_EXTENSION);
            @endphp

            <div class="cover-img">
                @if($intro_file_ext == 'mp4' || $intro_file_ext == 'mov')
                    <video src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_settings['shop_intro_icon']) }}" width="100%" autoplay muted loop>
                </video>
                @else
                    <img src="{{ asset('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_settings['shop_intro_icon']) }}" width="100%">
                @endif
            </div>
        @endif
    @endif



@endsection

{{-- Page JS Function --}}
@section('page-js')

    <script type="text/javascript">

        // Document Ready Function
        $(document).ready(function()
        {
            // TimeOut for Intro
            setTimeout(() => {
                $('.loader').hide();
            }, 1500);

            // Timeout for Cover
            var introSec = $('#intro_second').val() * 1000;
            setTimeout(() => {
                $('.cover-img').hide();
            }, introSec);
        });


        // Function for Get Filterd Categories
        $('#search').on('keyup',function()
        {
            var keywords = $(this).val();
            var shopID = $('#shop_id').val();

            $.ajax({
                type: "POST",
                url: '{{ route("shop.categories.search") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'keywords':keywords,
                    'shopID':shopID,
                },
                dataType: 'JSON',
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        $('#CategorySection').html('');
                        $('#CategorySection').append(response.data);
                    }
                    else
                    {
                        console.log(response.message);
                    }
                }
            });

        });


        // Error Messages
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}')
        @endif

    </script>

@endsection
