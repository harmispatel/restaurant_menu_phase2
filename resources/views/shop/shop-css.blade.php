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

    // Catgeory Image Position
    $category_side = isset($theme_settings['category_side']) ? $theme_settings['category_side'] : 'left';

    $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

    $category_view = isset($theme_settings['category_view']) ? $theme_settings['category_view'] : 'grid';

    $header_img = (isset($theme_settings['header_image']) && !empty($theme_settings['header_image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/header_image/'.$theme_settings['header_image']) : asset('public/client/assets/images2/allo_spritz.jpg');

    $bg_image = (isset($theme_settings['bg_image']) && !empty($theme_settings['bg_image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/background_image/'.$theme_settings['bg_image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/background_image/'.$theme_settings['bg_image']) : '';

    $cart_animation = isset($theme_settings['cart_animation_color']) ? $theme_settings['cart_animation_color'] :'';

    $header_trans = (isset($theme_settings['header_bg_color_opc']) && !empty($theme_settings['header_bg_color_opc'])) ? $theme_settings['header_bg_color_opc'] : 1;




    // $banner_setting = getBannerSetting($shop_details['id']);
    // $banner_key = $language_details['code']."_image";
    // $banner_text_key = $language_details['code']."_title";
    // $banner_image = isset($banner_setting[$banner_key]) ? $banner_setting[$banner_key] : "";
    // $banner_text = isset($banner_setting[$banner_text_key]) ? $banner_setting[$banner_text_key] : "";

@endphp

<!-- bootstrap css -->
<link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/bootstrap.min.css') }}">

<!-- owl Carousel Slider -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">

<!-- custom css -->
@if ($layout == "layout_2")
<link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/custom_layout2.css') }}">
@elseif ($layout == "layout_3")
<link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/custom_layout3.css') }}">
@else
<link rel="stylesheet" type="text/css" href="{{ asset('public/client/assets/css/custom.css') }}">
@endif



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

{{-- <link href="/path/to/dist/jquery.flipster.min.css" rel="stylesheet" /> --}}
<link rel="stylesheet" href="{{ asset('public/client/assets/css/flipster.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.flipster/1.1.6/jquery.flipster.css">



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

{{-- <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;400;500;600;700&display=swap" rel="stylesheet"> --}}

<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.0/slick/slick-theme.css">





{{-- Dynamic CSS --}}
<style>

    #toast-container{
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        right: unset;
    }

    #itemDetailsModal .btn:disabled
    {
        cursor: not-allowed;
        pointer-events: auto;
    }


    @if(!empty($shop_theme_id))
        
        /* Header Color  */
        @if(isset($theme_settings['header_color']) && !empty($theme_settings['header_color']))
            .header_preview .navbar, .header{
                background-color : {{ $theme_settings['header_color'] }}!important;
            }
        @endif

        @if($header_img)
            .layout_2 .side_header {
                background : url('{{ $header_img }}');
            }
        @endif

        @if(!empty($bg_image))
            body {
                background : url('{{ $bg_image }}') !important;
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
            .category_box .cate_name{
                color: {{ $theme_settings['font_color'] }}!important;
            }
        @endif

        /* Label Color */
        @if(isset($theme_settings['label_color']) && !empty($theme_settings['label_color']))
            @php
                $rgb_label_color = hexToRgb($theme_settings['label_color']);
                $label_color_tran = (isset($theme_settings['label_color_transparency']) && !empty($theme_settings['label_color_transparency'])) ? $theme_settings['label_color_transparency'] : 1;
            @endphp
            .menu_list .menu_list_item .item_name{
                /* background-color : {{ $theme_settings['label_color'] }}!important; */
                background-color : rgba({{ $rgb_label_color['r'] }},{{ $rgb_label_color['g'] }},{{ $rgb_label_color['b'] }},{{ $label_color_tran }})!important;
            }
            .category_box .cate_name{
                /* background-color : {{ $theme_settings['label_color'] }}!important; */
                background-color : rgba({{ $rgb_label_color['r'] }},{{ $rgb_label_color['g'] }},{{ $rgb_label_color['b'] }},{{ $label_color_tran }})!important;
            }
        @endif

        /* Label font size */
        @if(isset($theme_settings['label_font_size']) && !empty($theme_settings['label_font_size']))
        .menu_list .menu_list_item .item_name, .category_box .cate_name{
            font-size : {{ $theme_settings['label_font_size'] }}px !important;
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

            .category_slider .slick-arrow{
                color : {{ $theme_settings['categories_bar_color'] }}!important;
            }

            .flipster__button{
                color : {{ $theme_settings['categories_bar_color'] }}!important;

            }
        @endif

        /* Menu Bar Font Color */
        @if(isset($theme_settings['menu_bar_font_color']) && !empty($theme_settings['menu_bar_font_color']))
            .item_box_main .nav-tabs .cat-btn{
                color : {{ $theme_settings['menu_bar_font_color'] }}!important;
            }

            .slick-list .slick-slide .cat-btn{
                color : {{ $theme_settings['menu_bar_font_color'] }}!important;
            }

            .item_box_main #coverflow ul li a span{
                color : {{ $theme_settings['menu_bar_font_color'] }}!important;
            }

        @endif

        /* Categories Title & Description Color */
        @if(isset($theme_settings['category_title_and_description_color']) && !empty($theme_settings['category_title_and_description_color']))
            .item_list_div .cat_name, .menu_info_inr .menu_title h3, .category_item_list .category_title .category_title_name h3, .item_list_div .cat_name a{
                color : {{ $theme_settings['category_title_and_description_color'] }}!important;
            }
        @endif

        /* Price Color */
        @if(isset($theme_settings['price_color']) && !empty($theme_settings['price_color']))
            .price_ul li p{
                color : {{ $theme_settings['price_color'] }}!important;
            }
            .item_footer span, .item_footer h4{
                color : {{ $theme_settings['price_color'] }}!important;
            }

        @endif

        /* Cart Icon Color */
        @if(isset($theme_settings['cart_icon_color']) && !empty($theme_settings['cart_icon_color']))
        .cart-symbol i, .item_cart_btn i{
                color : {{ $theme_settings['cart_icon_color'] }}!important;
            }
        @endif


        /* Item Box Background Color */
        @if(isset($theme_settings['item_box_background_color']) && !empty($theme_settings['item_box_background_color']))
            .single_item_inr.devider-border{
                background-color : {{ $theme_settings['item_box_background_color'] }}!important;
            }
        @endif


        /* Item Title Color */
        @if(isset($theme_settings['item_title_color']) && !empty($theme_settings['item_title_color']))
            .single_item_inr h3, .category_item_name h3{
                color : {{ $theme_settings['item_title_color'] }}!important;
            }
        @endif


        /* Item Description Color */
        @if(isset($theme_settings['item_description_color']) && !empty($theme_settings['item_description_color']))
            .single_item_inr .item-desc p{
                color : {{ $theme_settings['item_description_color'] }}!important;
            }
        @endif


        /* Tags Font Color */
        @if(isset($theme_settings['tag_font_color']) && !empty($theme_settings['tag_font_color']))
            .nav-item .tags-btn.active{
                color : {{ $theme_settings['tag_font_color'] }}!important;
            }
        @endif

        /* Tags Label Color */
        @if(isset($theme_settings['tag_label_color']) && !empty($theme_settings['tag_label_color']))
            .nav-item .tags-btn.active{
                background-color : {{ $theme_settings['tag_label_color'] }}!important;
                border-color: {{ $theme_settings['tag_label_color'] }}!important;
            }
        @endif

        /* Tags Label Color */
        @if(isset($theme_settings['tag_label_color']) && !empty($theme_settings['tag_label_color']))
            .nav-item .tags-btn{
                color : {{ $theme_settings['tag_label_color'] }}!important;
                border-color: {{ $theme_settings['tag_label_color'] }}!important;
            }
        @endif

        /* Special discount Color */
        @if(isset($theme_settings['special_discount_backgound_color']) && !empty($theme_settings['special_discount_backgound_color']))
            .discount_btn{
                background-color:{{$theme_settings['special_discount_backgound_color']}}!important;
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

            .shop-main .page{
                margin-top: 70px;
                padding-top: 0;
            }

            .home_main_slider{
                padding-top:15px;
            }

            @media(max-width:991px){
                .layout_2 .shop-main .page{
                    margin-top: 70px !important;
                }

               .layout_2 .shop_cart .page{
                        margin: 0 !important;
                }
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
            .item_box_main .nav .nav-link .img_box img, .category_title .category_title_img img{
                border-radius: {{ $theme_settings['category_bar_type'] }} !important;
            }
            .slick-slide img{
                border-radius: {{ $theme_settings['category_bar_type'] }} !important;
            }

        @endif

        /* Search Box Icon Color */
        @if (isset($theme_settings['search_box_icon_color']) && !empty($theme_settings['search_box_icon_color']))
            #openSearchBox i, #closeSearchBox i, .cart-btn, .search_bt, .waiter_notification, .star_icon, .cart_box, .mobile-header .search_bt{
                color : {{ $theme_settings['search_box_icon_color'] }} !important;
            }
        @endif

        /* Icon Backgound Color */
        @if(isset($theme_settings['icon_bg_color']) && !empty($theme_settings['icon_bg_color']))
        .header_inr_menu_ul li.navlink a, .waiter_notification, .star_icon, .cart_box .cart_box_inr, .search_bt{
            background-color: {{  $theme_settings['icon_bg_color']  }} !important;
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

        /* Category Image Side */
        @if($category_side == 'right')

            .service_box .service_info .service_img {
                order: 2;
                margin-right: 0;
                margin-left: 20px;
            }
            @media(max-width:767px){
                .service_box .service_info .service_img {
                    margin-left: 10px;
                    margin-right: 0;
                }
            }
            @elseif($category_side == 'alternatively_even')

            .service_box:nth-child(even) .service_info .service_img {
                order: 2;
                margin-right: 0;
                margin-left:20px;
            }

            @media(max-width:767px){
                .service_box:nth-child(even) .service_info .service_img {
                    margin-left: 10px;
                    margin-right: 0;

                }
            }
            @elseif($category_side == 'alternatively_odd')
            .service_box:nth-child(odd) .service_info .service_img {
                order: 2;
                margin-right: 0;
                margin-left:20px;
            }
            @media(max-width:767px){
                .service_box:nth-child(odd) .service_info .service_img {
                    margin-left: 10px;
                    margin-right: 0;
                }
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

    /* special discount text color */

    @if(isset($theme_settings['special_discount_text_color']) && !empty($theme_settings['special_discount_text_color']))
            .category_header .discount_btn {
                color : {{ $theme_settings['special_discount_text_color'] }} ;
            }
    @endif

    /* category view */
    @media(max-width:767px) {
           @if($category_view == 'grid')
            .category_view_tiles {
                display: none;
            }
            .category_view_grid {
                display: block;
            }
            @else
                .category_view_tiles {
                    display: block;
                }

                .category_view_grid {
                    display: none;
                }
                @endif
        }


        /* Cart Animation color */

        @if(isset($theme_settings['cart_animation_color']) && !empty($theme_settings['cart_animation_color']))
            .cart_count:after {
                border-left-color:{{ $theme_settings['cart_animation_color'] }} !important;
            }
        @endif


        /* Model Item title */
        @if(isset($theme_settings['modal_item_title_color']) && !empty($theme_settings['modal_item_title_color']))
            .item_model_preview .item_info h3, .csm-review-modal .item_info h4, .call_waiter .call_waiter_title h3, .spent_notes .spent_notes_title h3{
                color : {{ $theme_settings['modal_item_title_color'] }};
            }
        @endif

        /* Model Desc Color */
        @if (isset($theme_settings['modal_item_des_color']) && !empty($theme_settings['modal_item_des_color']))
        .item_model_preview .item_info_dec p, .call_waiter .call_waiter_title p{
                color : {{ $theme_settings['modal_item_des_color'] }};
        }
        @endif

        /* Model Close Icon Color */
        @if (isset($theme_settings['modal_close_icon_color']) && !empty($theme_settings['modal_close_icon_color']))
            .item_model_preview .menu_modal_close i, .csm-review-modal .menu_modal_close i, .call_waiter .menu_modal_close i, .checkout_close_btn i{
                color : {{ $theme_settings['modal_close_icon_color'] }};
        }
        @endif

        /* Model Close Background Color */
        @if (isset($theme_settings['modal_close_bg_color']) && !empty($theme_settings['modal_close_bg_color']))
            .item_model_preview .menu_modal_close,.csm-review-modal .menu_modal_close, .call_waiter .menu_modal_close, .checkout_close_btn {
                background : {{ $theme_settings['modal_close_bg_color'] }} !important;
        }
        @endif

        /* Model Item Price Color */
        @if (isset($theme_settings['modal_item_price_color']) && !empty($theme_settings['modal_item_price_color']))
            .item_model_preview .item_price h4 {
                color : {{ $theme_settings['modal_item_price_color'] }};
        }
        @endif
        /* Model Price Label Color */
        @if (isset($theme_settings['modal_item_price_color']) && !empty($theme_settings['modal_item_price_color']))
        .item_model_preview .radio-item label{
                color : {{ $theme_settings['modal_item_price_color'] }};
        }
        @endif

        /* Model Price Label Color */
        @if (isset($theme_settings['modal_item_price_color']) && !empty($theme_settings['modal_item_price_color']))
        .item_model_preview .radio-item label{
                color : {{ $theme_settings['modal_item_price_color'] }};
        }
        @endif

        /* Model Add To Cart Backgorund color */
        @if(isset($theme_settings['modal_add_btn_color']) && !empty($theme_settings['modal_add_btn_color']))
        .item_model_preview .add_to_cart_btn_modal,  .csm-review-modal .review_submit{
            background : {{ $theme_settings['modal_add_btn_color'] }};
        }
        @endif

        /* Model Add To Cart Backgorund text */
        @if(isset($theme_settings['modal_add_btn_text_color']) && !empty($theme_settings['modal_add_btn_text_color']))
        .item_model_preview .add_to_cart_btn_modal, .csm-review-modal .review_submit {
            color : {{ $theme_settings['modal_add_btn_text_color'] }} !important;
        }
        @endif

        /* Model Quantity Icon Color */
        @if(isset($theme_settings['modal_quantity_icon_color']) && !empty($theme_settings['modal_quantity_icon_color']))
        .item_model_preview .quantity_btn_group .btn-number, .cart_item_qty .btn-number {
            color : {{ $theme_settings['modal_quantity_icon_color'] }} !important;
        }
        @endif

        /* Model Quantity Icon Background Color */
        @if(isset($theme_settings['modal_quantity_bg_color']) && !empty($theme_settings['modal_quantity_bg_color']))
        .item_model_preview .quantity_btn_group .btn-number, .cart_item_qty .btn-number {
            background : {{ $theme_settings['modal_quantity_bg_color'] }} !important;
        }
        @endif

        /* Model Igradient Text Color */
        @if(isset($theme_settings['modal_igradient_type_color']) && !empty($theme_settings['modal_igradient_type_color']))
        .igradient_box .igradient_category_box h3 {
            color : {{ $theme_settings['modal_igradient_type_color'] }} !important;
        }
        @endif

        /* Model Background Color */
        @if(isset($theme_settings['modal_body_bg_color']) && !empty($theme_settings['modal_body_bg_color']))
        .item_model_preview .igradient_box, .call_waiter .call_waiter_info{
            background : {{ $theme_settings['modal_body_bg_color'] }} !important;
        }
        @endif

        /* Special Day Effect Color */

        @if(isset($theme_settings['special_day_effect_color']) && !empty($theme_settings['special_day_effect_color']))
        .special_day_blink::before{
            border-color: {{ $theme_settings['special_day_effect_color'] }} !important;
        }
        .special_day_rotate .special label:nth-child(1),.special_day_rotate .special label:nth-child(2),.special_day_rotate .special label:nth-child(3),.special_day_rotate .special label:nth-child(4){
            background: {{ $theme_settings['special_day_effect_color'] }} !important;
        }
        @endif

        /* Category Box Title Backgroud */

        @if(isset($theme_settings['category_box_title_background']) && !empty($theme_settings['category_box_title_background']))
        .side_menu_title{
            background: {{ $theme_settings['category_box_title_background'] }} !important;
        }
        @endif

        /* Category Box Title & icon color */

        @if(isset($theme_settings['category_box_title_icon_color']) && !empty($theme_settings['category_box_title_icon_color']))
        .side_menu_title{
            color: {{ $theme_settings['category_box_title_icon_color'] }} !important;
        }
        @endif

        /* Category Box Text color */

        @if(isset($theme_settings['category_box_text_color']) && !empty($theme_settings['category_box_text_color']))
        .side_menu_inr ul li a{
            color: {{ $theme_settings['category_box_text_color'] }} !important;
        }
        @endif

        /* Category Box Background */

        @if(isset($theme_settings['category_box_background']) && !empty($theme_settings['category_box_background']))
        .side_menu_inr{
            background: {{ $theme_settings['category_box_background'] }} !important;
        }
        @endif

        /* Category Box shadow */

        @if(isset($theme_settings['category_box_shadow']) && !empty($theme_settings['category_box_shadow']))
        @php
                $rgb_category_box_shadow = hexToRgb($theme_settings['category_box_shadow']);
            @endphp
        .side_menu{
            box-shadow: 3px 3px 4px rgba({{ $rgb_category_box_shadow['r'] }},{{ $rgb_category_box_shadow['g'] }},{{ $rgb_category_box_shadow['b'] }} ,.16) , 3px 3px 4px rgba({{ $rgb_category_box_shadow['r'] }},{{ $rgb_category_box_shadow['g'] }},{{ $rgb_category_box_shadow['b'] }},.23);
        }
        @endif


        /* Category Title font size */
        @if(isset($theme_settings['modal_item_title_font_size']) && !empty($theme_settings['modal_item_title_font_size']))
            .item_model_preview .item_info h3{
                font-size : {{ $theme_settings['modal_item_title_font_size'] }}px !important;
            }
        @endif


        @if(isset($theme_settings['sticky_header']) && $theme_settings['sticky_header'] == 1)
            .layout_3 .shop-main .page{
                margin-top : {{ $theme_settings['banner_height'] }}px ;
            }
            @media(max-width:767px){
                .layout_3 .shop-main .page{
                margin-top : calc({{ $theme_settings['banner_height'] }}px + 65px) ;
            }
            }
        @endif


        /* Back Button Backgorund Color layout 3 */

        @if(isset($theme_settings['back_arrow_bg_color']) && !empty($theme_settings['back_arrow_bg_color']))
        .back_service{
            background: {{ $theme_settings['back_arrow_bg_color'] }} !important;
        }
        @endif

        /* Back Button Icon Color layout 3 */

        @if(isset($theme_settings['back_arrow_icon_color']) && !empty($theme_settings['back_arrow_icon_color']))
        .back_service a{
            color: {{ $theme_settings['back_arrow_icon_color'] }} !important;
        }
        @endif

        /* Mobile View Active Color layout 3 */

        @if(isset($theme_settings['category_box_act_txt_bg']) && !empty($theme_settings['category_box_act_txt_bg']))
            @media (max-width: 1199px){
                .side_menu_inr ul li a.active {
                background: {{ $theme_settings['category_box_act_txt_bg'] }} !important;
                }
            }

        @endif

        /* Header Color Opacity layout 2 */

        @if($header_trans || isset($theme_settings['header_effect_bg_color']) && !empty($theme_settings['header_effect_bg_color']))
        .header.side_header.sidebar_header:after{
            opacity: {{ $header_trans }} !important;
            background: {{ $theme_settings['header_effect_bg_color'] }} !important;
        }
        @endif


        /* Rating name color */
        @if(isset($theme_settings['rating_service_name_color']) && !empty($theme_settings['rating_service_name_color']))
        .rating_service h3{
            color: {{ $theme_settings['rating_service_name_color'] }} !important;
        }
        @endif

        /* Bar Icon color */
        @if(isset($theme_settings['bar_icon_color']) && !empty($theme_settings['bar_icon_color']))
            .barger_menu_icon {
                color: {{ $theme_settings['bar_icon_color'] }} !important;
            }
        @endif

        /* Bar Icon Backgorund color */
        @if(isset($theme_settings['bar_icon_bg_color']) && !empty($theme_settings['bar_icon_bg_color']))
            .barger_menu_icon {
                background-color: {{ $theme_settings['bar_icon_bg_color'] }} !important;
            }
        @endif

        /* Cover Link Icon Color */
        @if(isset($theme_settings['cover_link_icon_color']) && !empty($theme_settings['cover_link_icon_color']))
            .cover_link {
                color: {{ $theme_settings['cover_link_icon_color'] }} !important;
            }
        @endif

        /* Cover Link Background Color */
        @if(isset($theme_settings['cover_link_bg_color']) && !empty($theme_settings['cover_link_bg_color']))
            .cover_link {
                background-color: {{ $theme_settings['cover_link_bg_color'] }} !important;
            }
        @endif

        /* Item Rating Star Icon Color */
        @if(isset($theme_settings['rating_star_icon_color']) && !empty($theme_settings['rating_star_icon_color']))
            .item_detail .review_btn {
                color: {{ $theme_settings['rating_star_icon_color'] }} !important;
            }
        @endif        

</style>
