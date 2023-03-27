<?php

    use App\Models\AdminSettings;
    use App\Models\CategoryProductTags;
    use App\Models\ClientSettings;
    use App\Models\Ingredient;
    use App\Models\ItemPrice;
    use App\Models\Languages;
    use App\Models\LanguageSettings;
    use App\Models\ShopBanner;
use App\Models\Subscriptions;
use App\Models\ThemeSettings;
    use App\Models\User;
use App\Models\UserShop;
use App\Models\UsersSubscriptions;
use Illuminate\Support\Facades\Auth;

    // Get Admin's Settings
    function getAdminSettings()
    {
        // Keys
        $keys = ([
            'favourite_client_limit',
            'copyright_text',
            'logo',
            'login_form_background',
            'default_light_theme_image',
            'default_dark_theme_image',
            'theme_main_screen_demo',
            'theme_category_screen_demo',
            'default_special_item_image',
            'contact_us_email',
            'contact_us_subject',
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = AdminSettings::select('value')->where('key',$key)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        return $settings;
    }


    // Get Client's Settings
    function getClientSettings($shopID="")
    {

        if(!empty($shopID))
        {
            $shop_id = $shopID;
        }
        else
        {
            $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        }

        // Keys
        $keys = ([
            'shop_view_header_logo',
            'shop_intro_icon',
            'intro_icon_status',
            'intro_icon_duration',
            'business_name',
            'default_currency',
            'business_telephone',
            'instagram_link',
            'twitter_link',
            'facebook_link',
            'foursquare_link',
            'tripadvisor_link',
            'homepage_intro',
            'map_url',
            'website_url',
            'shop_active_theme',
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = ClientSettings::select('value')->where('shop_id',$shop_id)->where('key',$key)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        return $settings;
    }


    // Get Client's LanguageSettings
    function clientLanguageSettings($shopID)
    {
        // Keys
        $keys = ([
            'primary_language',
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = LanguageSettings::select('value')->where('key',$key)->where('shop_id',$shopID)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        return $settings;
    }


    // Get Package Permissions
    function getPackagePermission($subID)
    {
        $details = Subscriptions::where('id',$subID)->first();
        $permission = (isset($details['permissions']) && !empty($details['permissions'])) ? unserialize($details['permissions']) : '';
        return $permission;
    }


    // Get Subscription ID
    function getClientSubscriptionID($shop_id)
    {
        $user_shop = UserShop::where('shop_id',$shop_id)->first();
        $user_id = (isset($user_shop['user_id'])) ? $user_shop['user_id'] : '';
        $user_subscription = UsersSubscriptions::where('user_id',$user_id)->first();
        $subscription_id = (isset($user_subscription['subscription_id'])) ? $user_subscription['subscription_id'] : '';
        return $subscription_id;
    }


    // Get Theme Settings
    function themeSettings($themeID)
    {
        // Keys
        $keys = ([
            'header_color',
            'sticky_header',
            'language_bar_position',
            'logo_position',
            'search_box_position',
            'banner_position',
            'banner_type',
            'background_color',
            'font_color',
            'label_color',
            'social_media_icon_color',
            'categories_bar_color',
            'menu_bar_font_color',
            'category_title_and_description_color',
            'price_color',
            'item_box_shadow',
            'item_box_shadow_color',
            'item_box_shadow_thickness',
            'item_divider',
            'item_divider_color',
            'item_divider_thickness',
            'item_divider_type',
            'item_divider_position',
            'item_divider_font_color',
            'tag_font_color',
            'tag_label_color',
            'category_bar_type',
            'today_special_icon',
            'theme_preview_image',
            'search_box_icon_color',
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = ThemeSettings::select('value')->where('key',$key)->where('theme_id',$themeID)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        return $settings;
    }


    // Get Language Details
    function getLangDetails($langID)
    {
        $language = Languages::where('id',$langID)->first();
        return $language;
    }


    // Get Language Details by Code
    function getLangDetailsbyCode($langCode)
    {
        $language = Languages::where('code',$langCode)->first();
        return $language;
    }


    // Get Tags Product
    function getTagsProducts($tagID,$catID)
    {
        if(!empty($tagID) && !empty($catID))
        {
            // $items = CategoryProductTags::with(['product'])->where('tag_id',$tagID)->where('category_id',$catID)->get();
            $items = CategoryProductTags::join('items','items.id','category_product_tags.item_id')->where('tag_id',$tagID)->where('category_product_tags.category_id',$catID)->orderBy('items.order_key')->get();
        }
        else
        {
            $items = [];
        }
        return $items;
    }


    // Get Ingredients Details
    function getIngredientDetail($id)
    {
        $ingredient = Ingredient::where('id',$id)->first();
        return $ingredient;
    }


    // Get Banner Settings
    function getBannerSetting($shopID)
    {
        $banner = ShopBanner::where('shop_id',$shopID)->where('key','shop_banner')->first();
        return $banner;
    }


    // Get Favourite Clients List
    function FavClients($limit)
    {
        $clients = User::with(['hasOneShop','hasOneSubscription'])->where('user_type',2)->where('is_fav',1)->limit($limit)->get();
        return $clients;
    }


    // Function for Hex to RGB
    function hexToRgb($hex)
    {
        $hex      = str_replace('#', '', $hex);
        $length   = strlen($hex);
        $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
        $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
        $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));

        return $rgb;
    }


    // Function for Get Item Price
    function getItemPrice($itemID)
    {
        $prices = ItemPrice::where('item_id',$itemID)->get();
        return $prices;
    }

?>
