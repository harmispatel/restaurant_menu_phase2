@php
    $shop_settings = getClientSettings($shop_details['id']);
    $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';


    $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

    // Theme
    $theme = \App\Models\Theme::where('id',$shop_theme_id)->first();
    $theme_name = isset($theme['name']) ? $theme['name'] : '';

    // Theme Settings
    $theme_settings = themeSettings($shop_theme_id);

    // Language Bar Position
    $language_bar_position = isset($theme_settings['language_bar_position']) ? $theme_settings['language_bar_position'] : '';

    // $banner_setting = getBannerSetting($shop_details['id']);
    // $banner_key = $language_details['code']."_image";
    // $banner_text_key = $language_details['code']."_title";
    // $banner_image = isset($banner_setting[$banner_key]) ? $banner_setting[$banner_key] : "";
    // $banner_text = isset($banner_setting[$banner_text_key]) ? $banner_setting[$banner_text_key] : "";

@endphp

<!-- bootstrap css -->
<link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/bootstrap.min.css') }}">

<!-- custom css -->
<link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/custom.css') }}">

<!-- font awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"/>

{{-- Bootstarp Icons --}}
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

{{-- Toastr CSS --}}
<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/toastr.min.css') }}">

{{-- Swiper --}}
<link rel="stylesheet" href="{{ asset('public/client/assets/css/swiper-bundle.min.css') }}">

{{-- Masonary --}}
<link rel="stylesheet" href="{{ asset('public/client/assets/css/lightbox.css') }}">

{{-- Dynamic CSS --}}
<style>

    #itemDetailsModal .btn:disabled
    {
        cursor: not-allowed;
        pointer-events: auto;
    }

    @if(!empty($shop_theme_id))

        /* Header Color  */
        @if(isset($theme_settings['header_color']) && !empty($theme_settings['header_color']))
            .header_preview .navbar{
                background-color : {{ $theme_settings['header_color'] }}!important;
            }
        @endif

        /* Background Color */
        @if(isset($theme_settings['background_color']) && !empty($theme_settings['background_color']) && $theme_name != 'Default Dark Theme')
            body{
                background-color : {{ $theme_settings['background_color'] }}!important;
            }
        @endif

        /* Font Color */
        @if(isset($theme_settings['font_color']) && !empty($theme_settings['font_color']))
            .menu_list .menu_list_item .item_name{
                color : {{ $theme_settings['font_color'] }}!important;
            }
        @endif

        /* Label Color */
        @if(isset($theme_settings['label_color']) && !empty($theme_settings['label_color']))
            .menu_list .menu_list_item .item_name{
                background-color : {{ $theme_settings['label_color'] }}!important;
            }
        @endif

        /* Social Media Icons Color */
        @if(isset($theme_settings['social_media_icon_color']) && !empty($theme_settings['social_media_icon_color']))
            .footer_media ul li a, .footer_media h3{
                color : {{ $theme_settings['social_media_icon_color'] }}!important;
            }
            .footer-inr p{
                color : {{ $theme_settings['social_media_icon_color'] }}!important;
            }
        @endif

        /* Categories Bar Color */
        @if(isset($theme_settings['categories_bar_color']) && !empty($theme_settings['categories_bar_color']) && $theme_name != 'Default Dark Theme' && $theme_name != 'Default Light Theme')
            .item_box_main .nav::-webkit-scrollbar-thumb{
                background-color : {{ $theme_settings['categories_bar_color'] }}!important;
            }
        @endif

        /* Menu Bar Font Color */
        @if(isset($theme_settings['menu_bar_font_color']) && !empty($theme_settings['menu_bar_font_color']))
            .item_box_main .nav-tabs .cat-btn{
                color : {{ $theme_settings['menu_bar_font_color'] }}!important;
            }
        @endif

        /* Categories Title & Description Color */
        @if(isset($theme_settings['category_title_and_description_color']) && !empty($theme_settings['category_title_and_description_color']))
            .item_list_div .cat_name{
                color : {{ $theme_settings['category_title_and_description_color'] }}!important;
            }
        @endif

        /* Price Color */
        @if(isset($theme_settings['price_color']) && !empty($theme_settings['price_color']))
            .price_ul li span{
                color : {{ $theme_settings['price_color'] }}!important;
            }
        @endif

        /* Tags Font Color */
        @if(isset($theme_settings['tag_font_color']) && !empty($theme_settings['tag_font_color']))
            .nav-item .tags-btn{
                color : {{ $theme_settings['tag_font_color'] }}!important;
            }
        @endif

        /* Tags Label Color */
        @if(isset($theme_settings['tag_label_color']) && !empty($theme_settings['tag_label_color']))
            .nav-item .tags-btn{
                background-color : {{ $theme_settings['tag_label_color'] }}!important;
            }
        @endif

        /* Item Divider Font Color */
        @if(isset($theme_settings['item_divider_font_color']) && !empty($theme_settings['item_divider_font_color']))
            .devider h3, .devider p{
                color : {{ $theme_settings['item_divider_font_color'] }}!important;
            }
        @endif

        /* Bottom Border Shadow */
        @if (isset($theme_settings['item_box_shadow']) && !empty($theme_settings['item_box_shadow']) && $theme_settings['item_box_shadow'] == 1)
            .devider-border{
                border-bottom : {{ $theme_settings['item_box_shadow_thickness'] }} solid {{ $theme_settings['item_box_shadow_color'] }} !important;
            }
        @endif

        /* Sticky Header */
        @if (isset($theme_settings['sticky_header']) && !empty($theme_settings['sticky_header']) && $theme_settings['sticky_header'] == 1)
            .header-sticky{
                position: fixed;
                z-index: 999;
                left: 0 !important;
                right: 0 !important;
                top: 0 !important;
                margin:0 !important;
                transition: all 0.3s cubic-bezier(.4,0,.2,1);
            }

            .shop-main{
                margin-top: 70px;
            }
        @endif

        /* Language Bar Position */
        @if ($language_bar_position == 'right')
            .lang_select .sidebar{
                right : 0 !important;
                display:block;
                transition:all 0.5s ease-in-out;
            }
            .lang_select .lang_inr{
                right : -100%;
            }
        @elseif($language_bar_position == 'left')
            .lang_select .sidebar{
                left : 0 !important;
                display:block;
                transition:all 0.5s ease-in-out;
            }
            .lang_select .lang_inr{
                left : -100%;
            }
        @endif

        /* Category Bar Type */
        @if (isset($theme_settings['category_bar_type']) && !empty($theme_settings['category_bar_type']))
            .item_box_main .nav .nav-link .img_box img{
                border-radius: {{ $theme_settings['category_bar_type'] }} !important;
            }
        @endif

        /* Search Box Icon Color */
        @if (isset($theme_settings['search_box_icon_color']) && !empty($theme_settings['search_box_icon_color']))
            #openSearchBox i, #closeSearchBox i, .cart-btn{
                color : {{ $theme_settings['search_box_icon_color'] }} !important;
            }
        @endif

        /* Read More Link Color */
        @if (isset($theme_settings['read_more_link_color']) && !empty($theme_settings['read_more_link_color']))
            .read-more-desc{
                color : {{ $theme_settings['read_more_link_color'] }} !important;
                cursor : pointer;
            }
        @else
            .read-more-desc{
                color : blue!important;
                cursor : pointer;
            }
        @endif

        /* Item Devider */
        @if (isset($theme_settings['item_divider']) && !empty($theme_settings['item_divider']) && $theme_settings['item_divider'] == 1)
            @if (isset($theme_settings['item_divider_position']) && !empty($theme_settings['item_divider_position']) && $theme_settings['item_divider_position'] == 'top')
                .item_inr_info_sec .devider{
                    border-top : {{ $theme_settings['item_divider_thickness'] }}px {{ $theme_settings['item_divider_type'] }} {{ $theme_settings['item_divider_color'] }} !important;
                    margin-top: 30px;
                }
            @elseif (isset($theme_settings['item_divider_position']) && !empty($theme_settings['item_divider_position']) && $theme_settings['item_divider_position'] == 'bottom')
                .item_inr_info_sec .devider{
                    border-bottom : {{ $theme_settings['item_divider_thickness'] }}px {{ $theme_settings['item_divider_type'] }} {{ $theme_settings['item_divider_color'] }} !important;
                }
            @endif
        @endif

    @endif

</style>
