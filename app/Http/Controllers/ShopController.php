<?php

namespace App\Http\Controllers;

use App\Models\{Category, CategoryProductTags, CategoryVisit, CheckIn, AdditionalLanguage, Clicks, ItemPrice, ItemReview, Items, ItemsVisit, MailForm, Shop, UserShop, UserVisits, Option, OptionPrice, Order, OrderItems, DefaultShopLayout, TheamSettings, Theme,ShopRateServies,ServiceReview,ShopCoupon,UserCoupon,ShopRoom, ShopTable};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ShopController extends Controller
{

    // function for shop Preview
    public function index($slug, $cat_id = NULL, $cover = NULL)
    {

        $shop_slug = $slug;

        $data['is_cover'] = ($cover == NULL) ? 'false' : 'true';

        $data['shop_details'] = Shop::where('shop_slug', $shop_slug)->first();

        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';


        if (empty($shop_id)) {
            return redirect()->route('home')->with('error', 'This Action is Unauthorized!');
        }

        if ($cat_id != NULL && !is_numeric($cat_id)) {
            return redirect()->route('restaurant', $shop_slug)->with('error', 'This Action is Unauthorized!');
        }
        $user_ip = request()->ip();

        $current_date = Carbon::now()->format('Y-m-d');

        // Enter Visitor Count
        $user_visit = UserVisits::where('shop_id', $shop_id)->where('ip_address', $user_ip)->whereDate('created_at', '=', $current_date)->first();

        if (!isset($user_visit) || empty($user_visit)) {
            $new_visit = new UserVisits();
            $new_visit->shop_id = $shop_id;
            $new_visit->ip_address = $user_ip;
            $new_visit->save();
        }

        // Count Clicks
        $clicks = Clicks::where('shop_id', $shop_id)->whereDate('created_at', $current_date)->first();
        $click_id = isset($clicks->id) ? $clicks->id : '';
        if (!empty($click_id)) {
            $edit_click = Clicks::find($click_id);
            $total_clicks = $edit_click->total_clicks + 1;
            $edit_click->total_clicks = $total_clicks;
            $edit_click->update();
        } else {
            $new_click = new Clicks();
            $new_click->shop_id = $shop_id;
            $new_click->total_clicks = 1;
            $new_click->save();
        }

        if ($data['shop_details']) {

            $language_setting = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
            $data['primary_language_details'] = getLangDetails($primary_lang_id);
            $primary_lang_code = isset($data['primary_language_details']->code) ? $data['primary_language_details']->code  : 'en';

            // If Session not have locale then set primary lang locale
            if (!session()->has('locale')) {
                App::setLocale($primary_lang_code);
                session()->put('locale', $primary_lang_code);
                session()->save();
            }

            // Current Languge Code
            $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

            // Get all Categories of Shop
            $data['categories'] = Category::with(['categoryImages', 'items'])->where('published', 1)->where('shop_id', $shop_id)->where('parent_id', $cat_id)->orderBy('order_key')->get();
            $data['categories_parent'] = Category::with(['categoryImages', 'items'])->where('published', 1)->where('parent_id',null)->where('shop_id', $shop_id)->orderBy('order_key')->get();


            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id', $shop_id)->where('published', 1)->get();

            // Category ID
            $data['current_cat_id'] = $cat_id;


            return view('shop.shop', $data);
        }
    }





    // function for shop's Items Preview
    public function itemPreview($shop_slug, $cat_id)
    {

        $current_date = Carbon::now()->format('Y-m-d');

        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug', $shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        // Shop Settings
        $shop_settings = getClientSettings($shop_id);
        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        $act_layout =  $theme_settings['desk_layout'];

        $is_active_cat = checkCategorySchedule($cat_id, $shop_id);

        if ($is_active_cat == 0) {
            return redirect()->route('restaurant', $shop_slug);
        }

        // Category Details
        $data['cat_details'] = Category::with(['categoryImages'])->where('shop_id', $shop_id)->where('id', $cat_id)->first();
        $cat_parent_id = isset($data['cat_details']->parent_id) ? $data['cat_details']->parent_id : null;
        $data['cat_parent_id'] = isset($data['cat_details']->parent_id) ? $data['cat_details']->parent_id : null;
        $data['cat_en_name'] = isset($data['cat_details']->en_name) ? $data['cat_details']->en_name : null;

        // CategoryItem Tags
        $data['cat_tags'] = CategoryProductTags::join('tags', 'tags.id', 'category_product_tags.tag_id')->orderBy('tags.order')->where('category_id', $cat_id)->where('tags.shop_id', $shop_id)->get()->unique('tag_id');

        // Get all Categories
        $data['categories'] = Category::orderBy('order_key')->where('published', 1)->where('shop_id', $shop_id)->where('parent_id', $cat_parent_id)->where('parent_category', 0)->get();

        // $data['categories_parent'] = Category::with(['categoryImages', 'items'])->where('published', 1)->where('parent_id',null)->where('shop_id', $shop_id)->orderBy('order_key')->get();
        $data['categories_childs'] = Category::with(['categoryImages', 'items'])->where('published', 1)->where('parent_id',$data['cat_details']->parent_id)->where('shop_id', $shop_id)->orderBy('order_key')->get();
        $data['categories_parent'] = Category::with(['categoryImages', 'items'])->where('published', 1)->where('parent_id',null)->where('shop_id', $shop_id)->orderBy('order_key')->get();

        if($act_layout == 'layout_1'){


            $categoriesArray = $data['categories']->toArray();

            // Find the index of the category with cat_id
            $catIndex = array_search($cat_id, array_column($categoriesArray, 'id'));

            // Calculate the middle index
            $middleIndex = ceil(count($categoriesArray) / 2);

            // Move the category with cat_id to the middle
            if ($catIndex !== false) {
                // Remove the category with cat_id from its original position
                $cat = $categoriesArray[$catIndex];
                unset($categoriesArray[$catIndex]);

                // Reinsert the category at the middle index
                array_splice($categoriesArray, $middleIndex, 0, array($cat));
            }

            $data['categories'] = $categoriesArray;

        }

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        $data['all_items'] = Items::where('published', 1)
                            ->whereHas('categories', function ($query) use ($cat_id) {
                                $query->where('id', $cat_id);
                            })
                            ->orderBy('order_key')
                            ->get();

        if ($data['cat_details'] && $data['shop_details']) {
            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id', $shop_id)->where('published', 1)->get();

            // Count Category Visit
            $category_visit = CategoryVisit::where('category_id', $cat_id)->where('shop_id', $shop_id)->first();
            $cat_visit_id = isset($category_visit->id) ? $category_visit->id : '';

            if (!empty($cat_visit_id)) {
                $cat_visit = CategoryVisit::find($cat_visit_id);
                $total_clicks = $cat_visit->total_clicks + 1;
                $cat_visit->total_clicks = $total_clicks;
                $cat_visit->update();
            } else {
                $new_cat_visit = new CategoryVisit();
                $new_cat_visit->shop_id = $shop_id;
                $new_cat_visit->category_id = $cat_id;
                $new_cat_visit->total_clicks = 1;
                $new_cat_visit->save();
            }


            // Count Clicks
            $clicks = Clicks::where('shop_id', $shop_id)->whereDate('created_at', $current_date)->first();
            $click_id = isset($clicks->id) ? $clicks->id : '';
            if (!empty($click_id)) {
                $edit_click = Clicks::find($click_id);
                $total_clicks = $edit_click->total_clicks + 1;
                $edit_click->total_clicks = $total_clicks;
                $edit_click->update();
            } else {
                $new_click = new Clicks();
                $new_click->shop_id = $shop_id;
                $new_click->total_clicks = 1;
                $new_click->save();
            }




            if ($data['cat_details']->category_type == 'page' || $data['cat_details']->category_type == 'gallery' || $data['cat_details']->category_type == 'pdf_page' || $data['cat_details']->category_type == 'check_in') {
                return view('shop.page_preview', $data);
            }

            return view('shop.item_preview', $data);
        } else {
            return redirect()->back()->with('error', "Oops, Something Went Wrong !");
        }
    }



    // Change Locale
    public function changeShopLocale(Request $request)
    {
        $lang_code = $request->lang_code;

        // If Session not have locale then set primary lang locale
        if (session()->has('locale')) {
            App::setLocale($lang_code);
            session()->put('locale', $lang_code);
            session()->save();
        } else {
            App::setLocale($lang_code);
            session()->put('locale', $lang_code);
            session()->save();
        }

        return response()->json([
            'success' => 1,
        ]);
    }


    // Search Categories
    public function searchCategories(Request $request)
    {

        $shop_id = decrypt($request->shopID);

        $keyword = $request->keywords;
        $current_cat_id = $request->current_cat_id;
        $layout_width = $request->layout_width;
        $categories_ids = [];


        $sub_cat = Category::select('id')->where('shop_id', $shop_id)->where('parent_id', $current_cat_id)->get();

        if (count($sub_cat) > 0) {
            foreach ($sub_cat as $val) {
                $categories_ids[] = $val->id;
            }
        }

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        $name_key = $current_lang_code . "_name";
        $description_key = $current_lang_code . "_description";
        $calories_key = $current_lang_code . "_calories";
        $price_label_key = $current_lang_code . "_label";

        // Shop Details
        $shop_details = Shop::where('id', $shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Shop Settings
        $shop_settings = getClientSettings($shop_id);
        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        $active_layout = $request->layout;
        $special_day_effect_box = isset($theme_settings['special_day_effect_box']) && !empty($theme_settings['special_day_effect_box']) ? $theme_settings['special_day_effect_box'] : 'blink';
        $category_view = isset($theme_settings['category_view']) && !empty($theme_settings['category_view']) ? $theme_settings['category_view'] : 'grid';
        $category_image_slider = isset($theme_settings['category_image_sider']) && !empty($theme_settings['category_image_sider']) ? $theme_settings['category_image_sider'] : 'stop';




        // Today Special Icon
        $today_special_icon = moreTranslations($shop_id, 'today_special_icon');
        $today_special_icon = (isset($today_special_icon[$current_lang_code . "_value"]) && !empty($today_special_icon[$current_lang_code . "_value"])) ? $today_special_icon[$current_lang_code . "_value"] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Read More Label
        $read_more_label = moreTranslations($shop_id, 'read_more_link_label');
        $read_more_label = (isset($read_more_label[$current_lang_code . "_value"]) && !empty($read_more_label[$current_lang_code . "_value"])) ? $read_more_label[$current_lang_code . "_value"] : 'Read More';

        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        try {
            $categories = Category::with(['categoryImages'])->where("$name_key", 'LIKE', '%' . $keyword . '%')->where('shop_id', $shop_id)->where('parent_id', $current_cat_id)->where('published', 1)->orderBy('order_key')->get();


            $html = '';
            if (empty($keyword))
             {

                if ($active_layout == 'layout_1') {

                    if(count($categories) > 0){
                        $html .= '<div class="menu_list">';
                        foreach ($categories as $category) {
                            $category_name = (isset($category->$name_key)) ? $category->$name_key : '';
                            $default_image = asset('public/client_images/not-found/no_image_1.jpg');
                            $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                            $thumb_image = isset($category->cover) ? $category->cover : '';
                            $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                            if ($category->category_type == 'product_category') {
                                if (!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image)) {
                                    $image = asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image);
                                } else {
                                    $image = $default_image;
                                }
                            } else {
                                if (!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image)) {
                                    $image = asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image);
                                } else {
                                    $image = $default_image;
                                }
                            }

                            if ($category->category_type == 'link') {
                                $cat_items_url = (isset($category->link_url) && !empty($category->link_url)) ? $category->link_url : '#';
                            } elseif ($category->category_type == 'parent_category') {
                                $cat_items_url = route('restaurant', [$shop_details['shop_slug'], $category->id]);
                            } else {
                                $cat_items_url = route('items.preview', [$shop_details['shop_slug'], $category->id]);
                            }

                            if ($active_cat == 1) {
                                $html .= '<div class="menu_list_item">';
                                $html .= '<a href="' . $cat_items_url . '">';
                                $html .= '<img src="' . $image . '" class="w-100">';
                                $html .= '<h3 class="item_name">' . $category_name . '</h3>';
                                $html .= '</img>';
                                $html .= '</div>';
                            }
                        }

                        $html .= '</div>';
                    }else{
                        $html .= '<h3 class="text-center">Categories not Found.</h3>';
                    }
                }elseif($active_layout == 'layout_3') {

                    if(count($categories) > 0){
                        $html .='<div class="service_main">';
                        if(count($categories) > 0){
                            foreach($categories as $category){
                                $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                $name_code = $current_lang_code . '_name';
                                $nameId = str_replace(' ', '_', $category->en_name);
                                $description_code = $current_lang_code . '_description';
                                $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                $thumb_image = isset($category->cover) ? $category->cover : '';
                                $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                $googleLink = isset($category->link_url) && !empty($category->link_url) ? $category->link_url : "#";
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
                                $html .='<div class="service_box">';
                                    if($active_cat == 1){
                                        if($check_cat_type_permission == 1){
                                            if($category->category_type == 'link'){
                                                $html .= '<a href="'.$googleLink.'" target="_blank">';
                                            }elseif($category->category_type == 'parent_category'){
                                                $html .='<a href="'.route('restaurant', [$shop_details['shop_slug'], $category->id]).'">';
                                            }else{
                                                $html .='<a href="'.route('items.preview', [$shop_details['shop_slug'], $category->id]).'">';
                                            }
                                        }
                                                $html .= '<h2>' . (isset($category->$name_code) ? $category->$name_code : "") . '</h2>';
                                                    $html .='<div class="service_info">';
                                                        $html .='<div class="service_img">';
                                                            $html .='<div id="demo" class="carousel slide" data-bs-ride="carousel">';
                                                                $html .='<div class="carousel-inner">';
                                                                        if($category->category_type == 'product_category'){
                                                                            $html .='<div class="carousel-item active">';
                                                                                if(!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image)){
                                                                                    $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image).'" class="w-100">';
                                                                                }else{
                                                                                    $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                                }
                                                                            $html .='</div>';
                                                                        }else{
                                                                            if(!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image)){
                                                                                $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image).'" class="w-100">';
                                                                            }else{
                                                                                $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                            }
                                                                        }
                                                                        if($category_image_slider == 'slider'){
                                                                            if(count($item_images) > 0){
                                                                                foreach($item_images as $item_image){
                                                                                    $html .='<div class="carousel-item">';
                                                                                        if(!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image)){
                                                                                            $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image).'" alt="New york" class="w-100">';
                                                                                        }
                                                                                    $html .='</div>';
                                                                                }
                                                                            }
                                                                        }
                                                                $html .='</div>';
                                                            $html .='</div>';
                                                        $html .='</div>';
                                                            $html .='<div class="service_info_des">';
                                                                $html .='<p>'.isset($category->$description_code) ? $category->$description_code: "".'</p>';
                                                            $html .='</div>';
                                                    $html .='</div>';
                                            $html .= '</a>';
                                    }
                                $html .='</div>';
                            }
                        }
                    $html .='</div>';
                    } else {
                        $html .= '<h3 class="text-center">Categories not Found.</h3>';
                    }
                }
                elseif ($active_layout == 'layout_2' && $layout_width < 767)
                {
                    if($category_view ==  "grid"){
                        $html .='<div class="category_inr search_grid">';
                            if(count($categories) > 0){
                                foreach($categories as $category){

                                     $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                        $name_code = $current_lang_code . '_name';
                                        $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                        $thumb_image = isset($category->cover) ? $category->cover : '';

                                        $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                                        $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                        $items = $category->items;

                                        $categoryName = isset($category->$name_code) ? $category->$name_code : "";
                                        $googleLink = isset($category->link_url) && !empty($category->link_url) ? $category->link_url : "#";
                                        if (count($items) > 0) {
                                            $img_array = [];

                                            foreach ($items as $key => $value) {
                                                if($value->type == 1){
                                                    $img_array[] = $value->image;
                                                }
                                            }
                                            $item_images = array_filter($img_array);
                                        }else{
                                            $item_images = [];
                                        }
                                        if ($active_cat == 1) {
                                            if ($check_cat_type_permission == 1) {
                                                $html .= '<div class="category_box">';
                                                    if ($category->category_type == 'link') {
                                                        $html .= '<a href="'.$googleLink.'" target="_blank">';
                                                    }elseif($category->category_type == 'parent_category'){
                                                        $html .='<a href="'.route('restaurant', [$shop_details['shop_slug'], $category->id]).'">';
                                                    }else{
                                                        $html .='<a href="'.route('items.preview', [$shop_details['shop_slug'], $category->id]).'">';
                                                    }
                                                    $html .='<div class="category_img">';
                                                        $html .='<div id="demo" class="carousel slide" data-bs-ride="carousel">';
                                                            $html .='<div class="carousel-inner">';
                                                                    if($category->category_type == 'product_category')
                                                                    {
                                                                            $html .='<div class="carousel-item active">';
                                                                                if(!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image)){
                                                                                    $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image).'" class="w-100">';
                                                                                }else{
                                                                                    $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                                }
                                                                            $html .='</div>';
                                                                    }else{
                                                                            if(!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image)){
                                                                                $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image).'" class="w-100">';
                                                                            }else{
                                                                                $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                            }
                                                                    }
                                                                        if($category_image_slider == 'slider'){
                                                                                if(count($item_images) > 0){
                                                                                    foreach($item_images as $item_image){
                                                                                            $html .='<div class="carousel-item">';
                                                                                                if(!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image)){
                                                                                                    $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image).'" alt="New york" class="w-100">';
                                                                                                }
                                                                                            $html .='</div>';
                                                                                    }
                                                                                }
                                                                        }
                                                            $html .='</div>';
                                                        $html .='</div>';
                                                    $html .='</div>';
                                                            $html .='<div class="cate_name">'.$categoryName.'</div>';
                                                    $html .='</a>';
                                                $html .= '</div>';
                                            }
                                        }
                                }
                            } else {
                                $html .='<h3 class="text-center">Categories not Found.</h3>';
                            }
                        $html .='</div>';
                    }else{
                        $html .='<div class="container search_tiles">';
                            $html .='<div class="row justify-content-center">';
                                $html .='<div class="col-md-10 col-xl-12">';
                                    $html .='<div class="service_main">';
                                            if(count($categories) > 0){
                                                foreach($categories as $category){
                                                    $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                                    $name_code = $current_lang_code . '_name';
                                                    $nameId = str_replace(' ', '_', $category->en_name);
                                                    $description_code = $current_lang_code . '_description';
                                                    $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                                    $thumb_image = isset($category->cover) ? $category->cover : '';
                                                    $active_cat = checkCategorySchedule($category->id, $category->shop_id);
                                                    $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                                    $googleLink = isset($category->link_url) && !empty($category->link_url) ? $category->link_url : "#";
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
                                                    $html .='<div class="service_box">';
                                                        if($active_cat == 1){
                                                            if($check_cat_type_permission == 1){
                                                                if($category->category_type == 'link'){
                                                                    $html .= '<a href="'.$googleLink.'" target="_blank">';
                                                                }elseif($category->category_type == 'parent_category'){
                                                                    $html .='<a href="'.route('restaurant', [$shop_details['shop_slug'], $category->id]).'">';
                                                                }else{
                                                                    $html .='<a href="'.route('items.preview', [$shop_details['shop_slug'], $category->id]).'">';
                                                                }
                                                            }
                                                                    $html .= '<h2>' . (isset($category->$name_code) ? $category->$name_code : "") . '</h2>';
                                                                        $html .='<div class="service_info">';
                                                                            $html .='<div class="service_img">';
                                                                                $html .='<div id="demo" class="carousel slide" data-bs-ride="carousel">';
                                                                                    $html .='<div class="carousel-inner">';
                                                                                            if($category->category_type == 'product_category'){
                                                                                                $html .='<div class="carousel-item active">';
                                                                                                    if(!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image)){
                                                                                                        $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image).'" class="w-100">';
                                                                                                    }else{
                                                                                                        $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                                                    }
                                                                                                $html .='</div>';
                                                                                            }else{
                                                                                                if(!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image)){
                                                                                                    $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image).'" class="w-100">';
                                                                                                }else{
                                                                                                    $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                                                }
                                                                                            }
                                                                                            if($category_image_slider == 'slider'){
                                                                                                if(count($item_images) > 0){
                                                                                                    foreach($item_images as $item_image){
                                                                                                        $html .='<div class="carousel-item">';
                                                                                                            if(!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image)){
                                                                                                                $html .='<img src="'.asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image).'" alt="New york" class="w-100">';
                                                                                                            }
                                                                                                        $html .='</div>';
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                    $html .='</div>';
                                                                                $html .='</div>';
                                                                            $html .='</div>';
                                                                                $html .='<div class="service_info_des">';
                                                                                    $html .='<p>'.isset($category->$description_code) ? $category->$description_code: "".'</p>';
                                                                                $html .='</div>';
                                                                        $html .='</div>';
                                                                $html .= '</a>';
                                                        }
                                                    $html .='</div>';
                                                }
                                            }

                                    $html .='</div>';
                                $html .='</div>';
                            $html .='</div>';
                        $html .='</div>';
                    }
                }else{

                     $html .='<div class="category_inr search_grid">';
                            if(count($categories) > 0){
                                foreach($categories as $category){

                                     $default_cat_img = asset('public/client_images/not-found/no_image_1.jpg');
                                        $name_code = $current_lang_code . '_name';
                                        $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                                        $thumb_image = isset($category->cover) ? $category->cover : '';

                                        $active_cat = checkCategorySchedule($category->id, $category->shop_id);

                                        $check_cat_type_permission = checkCatTypePermission($category->category_type, $shop_details['id']);
                                        $items = $category->items;

                                        $categoryName = isset($category->$name_code) ? $category->$name_code : "";
                                        $googleLink = isset($category->link_url) && !empty($category->link_url) ? $category->link_url : "#";
                                        if (count($items) > 0) {
                                            $img_array = [];

                                            foreach ($items as $key => $value) {
                                                if($value->type == 1){
                                                    $img_array[] = $value->image;
                                                }
                                            }
                                            $item_images = array_filter($img_array);
                                        }else{
                                            $item_images = [];
                                        }
                                        if ($active_cat == 1) {
                                            if ($check_cat_type_permission == 1) {
                                                $html .= '<div class="category_box">';
                                                    if ($category->category_type == 'link') {
                                                        $html .= '<a href="'.$googleLink.'" target="_blank">';
                                                    }elseif($category->category_type == 'parent_category'){
                                                        $html .='<a href="'.route('restaurant', [$shop_details['shop_slug'], $category->id]).'">';
                                                    }else{
                                                        $html .='<a href="'.route('items.preview', [$shop_details['shop_slug'], $category->id]).'">';
                                                    }
                                                        $html .='<div class="category_img">';
                                                            $html .='<div id="demo" class="carousel slide" data-bs-ride="carousel">';
                                                                $html .='<div class="carousel-inner">';
                                                                    if($category->category_type == 'product_category'){
                                                                        $html .='<div class="carousel-item active">';
                                                                            if(!empty($cat_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $cat_image)){
                                                                                $html .='<img src="'.asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$cat_image).'" class="w-100">';
                                                                            }else{
                                                                                $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                            }
                                                                        $html .='</div>';
                                                                    }else{
                                                                        if(!empty($thumb_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/categories/' . $thumb_image)){
                                                                            $html .='<img src="'.asset('public/client_uploads/shops/'.$shop_slug.'/categories/'. $thumb_image).'" class="w-100">';
                                                                        }else{
                                                                            $html .='<img src="'.$default_cat_img.'" class="w-100">';
                                                                        }
                                                                    }
                                                                    if($category_image_slider == 'slider'){
                                                                        if(count($item_images) > 0){
                                                                            foreach($item_images as $item_image){
                                                                                $html .='<div class="carousel-item">';
                                                                                    if(!empty($item_image) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_image)){
                                                                                        $html .='<img src="'.asset('public/client_uploads/shops/'.$shop_slug.'/items/'. $item_image).'" class="w-100">';
                                                                                    }
                                                                                $html .='</div>';
                                                                            }
                                                                        }
                                                                    }
                                                                $html .='</div>';
                                                            $html .='</div>';
                                                        $html .='</div>';
                                                            $html .='<div class="cate_name">'.$categoryName.'</div>';
                                                    $html .='</a>';
                                                $html .= '</div>';
                                            }
                                        }
                                }
                            } else {
                                $html .='<h3 class="text-center">Categories not Found.</h3>';
                            }
                        $html .='</div>';
                }
            } else {
                $items = Items::where("$name_key", 'LIKE', '%' . $keyword . '%')
                                ->where('shop_id', $shop_id)
                                ->where('published', 1)
                                ->whereHas('categories', function ($query) use ($categories_ids) {
                                    $query->whereIn('category_id', $categories_ids);
                                })
                                ->get();
                if ($active_layout == "layout_1"){
                    if (count($items) > 0) {
                            $html .= '<div class="item_inr_info_sec">';
                            $html .= '<div class="row">';
                            foreach ($items as $item) {
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                                $active_cat = checkCategorySchedule($item->category_id, $item->shop_id);
                                $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;

                                if ($active_cat == 1) {
                                    if ($item['type'] == 2) {
                                        $html .= '<div class="col-md-12 mb-3">';
                                        $html .= '<div class="single_item_inr devider">';

                                        if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                            $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                            $html .= '<div class="item_image">';
                                            $html .= '<img src="' . $item_divider_image . '">';
                                            $html .= '</div>';
                                        }

                                        if (count($ingrediet_arr) > 0) {
                                            $html .= '<div>';
                                            foreach ($ingrediet_arr as $val) {
                                                $ingredient = getIngredientDetail($val);
                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                    if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                        $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                        $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                    }
                                                }
                                            }
                                            $html .= '</div>';
                                        }

                                        $html .= '<h3>' . $item_name . '</h3>';

                                        if(isset($item[$description_key]) && !empty($item[$description_key]))
                                        {
                                            $html .= '<div class="item-desc">' . $item[$description_key] . '</div>';
                                        }

                                        $html .= '</div>';
                                        $html .= '</div>';
                                    } else {
                                        $html .= '<div class="col-md-6 col-lg-6 col-xl-3 mb-3">';
                                        // $html .= '<div class="item_detail single_item_inr devider-border" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">';
                                        $html .='<div ';
                                        $html .= 'class="item_detail single_item_inr devider-border';
                                        if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink'){
                                            $html .=' special_day_blink';
                                        }elseif ($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate'){
                                            $html .=' special_day_rotate';
                                        }
                                            $html .='">';
                                            $html .='<div class="special"
                                                    <label ></label>
                                                    <label ></label>
                                                    <label ></label>
                                                    <label ></label>
                                                     </div>';

                                        $html .= '<div class="item_image">';
                                            if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                                $item_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                $html .= '<img src="' . $item_image . '" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">';
                                            }                                            
                                        $html .= '</div>';

                                        if (count($ingrediet_arr) > 0) {
                                            $html .= '<div class="mt-3">';
                                            foreach ($ingrediet_arr as $val) {
                                                $ingredient = getIngredientDetail($val);
                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                    if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                        $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                        $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                    }
                                                }
                                            }
                                            $html .= '</div>';
                                        }

                                        $html .= '<div class="item_image">';
                                            $html .= '<a  class="review_btn" onclick="openRatingModel('.$item['id'].')">';
                                                $html .= '<i class="fa-solid fa-star"></i>';
                                                $html .= '<i class="fa-solid fa-star"></i>';
                                                $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '</a>';
                                        $html .= '</div>';

                                        if (!empty($item_calories)) {
                                            $html .= '<p class="m-0 p-0 mt-3"><strong>Cal: ' . $item_calories . '</strong></p>';
                                        }                                        

                                        $html .= '<h3 onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">' . $item_name . '</h3>';                                        

                                        if ($item['is_new'] == 1) {
                                            $new_img = asset('public/client_images/bs-icon/new.png');
                                            $html .= '<img class="is_new tag-img" src="' . $new_img . '">';
                                        }

                                        if ($item['as_sign'] == 1) {
                                            $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                            $html .= '<img class="is_sign tag-img" src="' . $as_sign_img . '">';
                                        }                                        

                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";

                                        if(!empty($desc)){
                                            if (strlen(strip_tags($desc)) > 180) {
                                                $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 150), "\n"));
                                                $html .= '<div class="item-desc"><p>' . $desc . ' ... <br>
                                                            <a class="read-more-desc">' . $read_more_label . '</a></p></div>';
                                            } else {
                                                $html .= '<div class="item-desc"><p>' . strip_tags($desc) . '</p></div>';
                                            }
                                        }

                                        $price_arr = getItemPrice($item['id']);
                                        if (count($price_arr) > 0) {
                                            $html .= '<ul class="price_ul">';
                                                foreach ($price_arr as $key => $value) {
                                                    $price = Currency::currency($currency)->format($value['price']);
                                                    $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                                    $html .= '<li><p>' . $price_label . ' <span>' . $price . '</span></p></li>';
                                                }
                                            $html .= '</ul>';
                                        }

                                        if ($item['day_special'] == 1) {
                                            if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                                $today_spec_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                                $html .= '<img width="170" class="mt-3" src="' . $today_spec_icon . '">';
                                            } else {
                                                if (!empty($default_special_image)) {
                                                    $html .= '<img width="170" class="mt-3" src="' . $default_special_image . '" alt="Special">';
                                                } else {
                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                    $html .= '<img width="170" class="mt-3" src="' . $def_tds_img . '">';
                                                }
                                            }
                                        }

                                        if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1) {
                                            $html .= '<div class="cart-symbol" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer"><i class="bi bi-cart4"></i></div>';
                                        }

                                        $html .= '</div>';
                                        $html .= '</div>';
                                    }
                                }
                            }
                            $html .= '</div>';
                            $html .= '</div>';


                    } else {
                        $html .= '<h3 class="text-center">Items not Found.</h3>';
                    }

                } elseif ($active_layout == "layout_2"){

                    if (count($items) > 0){
                        $html .= '<div class="category_inr_list_item category_item_list">';
                        $html .= '<div class="row">';
                        foreach ($items as $item)
                            {

                                        $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";

                                        $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                        $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                                        $active_cat = checkCategorySchedule($item->category_id, $item->shop_id);
                                        $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;
                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";
                                        $tag_name = getTagName($item['id']);
                                        $tagName = isset($tag_name->hasOneTag->$name_key) ? $tag_name->hasOneTag->$name_key : '';
                                        $price_arr = getItemPrice($item['id']);

                                        $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                        if ($active_cat == 1) {

                                            if ($item['type'] == 2) {

                                                $html .= '<div class="category_title">';
                                                    $html .= '<div class="category_title_img">';
                                                        if(!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                        {
                                                                $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                                $html .='<img src="'.$item_divider_image.'" class="w-100">';
                                                        }else{
                                                            $noImage = asset('public/client_images/not-found/no_image_1.jpg');
                                                            $html .= '<img src="'.$noImage.'" class="w-100">';
                                                        }
                                                        $html .=  '</div>';
                                                        $html .= '<div class="category_title_name">';
                                                            $html .= '<h3>'.$item_name.'</h3>';
                                                        $html .= '</div>';
                                                $html .= '</div>';
                                            }else{

                                                $html .='<div class="col-xl-4 col-lg-6 col-md-6">';
                                                $html .= '<div class="item_detail single_item_inr devider-border';
                                                if (($item['day_special'] == 1 && $special_day_effect_box == 'blink') || ($item['as_sign'] == 1 && $special_day_effect_box == 'blink')) {
                                                    $html .= ' special_day_blink';
                                                } elseif (($item['day_special'] == 1 && $special_day_effect_box == 'rotate') || ($item['as_sign'] == 1 && $special_day_effect_box == 'rotate')) {
                                                    $html .= ' special_day_rotate';
                                                }
                                                if($item['is_new'] == 1 || $item['as_sign'] == 1){
                                                    $html .=' tag_item_detail';
                                                }
                                                $html .= '">';
                                                    $html .='<div class="special">
                                                             <label ></label>
                                                             <label ></label>
                                                             <label ></label>
                                                             <label ></label>
                                                             </div>';
                                                             if ($item['is_new'] == 1) {
                                                                $new_img = asset('public/client_images/bs-icon/new.png');
                                                                $html .= '<img class="is_new tag-img position-absolute" src="' . $new_img . '" style="top:0; left:0; width:70px;">';
                                                            }

                                                            if ($item['as_sign'] == 1) {
                                                                $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                                                $html .= '<img class="is_sign tag-img position-absolute" src="' . $as_sign_img . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                                                            }
                                                             $html .='<div class="category_item_name">';
                                                                $html .='<h3 onclick="getItemDetails('.$item['id'].','.$shop_id.')">'.$item_name.'</h3>';
                                                             $html .='</div>';
                                                             $html .= '<div class="item_detail_inr' . (!empty($item['image']) && file_exists(public_path('client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) ? '' : ' no_img_item_detail') . '">';
                                                            $html .='<div class="item_info">';
                                                                    if (strlen(strip_tags($desc)) > 100) {
                                                                        $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 100), "\n"));
                                                                        $html .='<div class="item-desc"><p>'. $desc . ' ... <br>
                                                                                        <a class="read-more-desc" onclick="getItemDetails('.$item['id'].','.$shop_id.')">' . $read_more_label . '</a></p></div>';
                                                                    }else{
                                                                        $html .='<div class="item_desc"><p>'.strip_tags($desc).'</p></div>';
                                                                    }
                                                                    if(count($ingrediet_arr) > 0) {
                                                                        $html .='<div class="item_tag">';
                                                                            foreach ($ingrediet_arr as $val) {
                                                                                $ingredient = getIngredientDetail($val);
                                                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                                                if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                                                    if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                                                            $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                                                            $html .= '<img src="' . $ing_icon . '" width="45px" height="45px">';
                                                                                    }
                                                                                }
                                                                            }
                                                                        $html .='</div>';
                                                                    }
                                                                    if(isset($item_calories) && !empty($item_calories))
                                                                    {
                                                                            $html .='<p class="m-0 p-2 text-dark"><strong>Cal:</strong>'.$item_calories.'</p>';
                                                                    }
                                                            $html .='</div>';
                                                            $html .='<div class="item_image">';
                                                                if(!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                {
                                                                    $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                                    $html .='<img src="'.$item_divider_image.'" onclick="getItemDetails('.$item['id'].','.$shop_id.')">';
                                                                }
                                                                $html .='<a href="#" class="review_btn" onclick="openRatingModel('.$item['id'].')"><i
                                                                class="fa-solid fa-star"></i> <i
                                                                class="fa-solid fa-star"></i> <i
                                                                class="fa-solid fa-star"></i></a>';

                                                            $html .='</div>';
                                                        $html .='</div>';
                                                        $html .='<div class="special_day_item_gif text-center">';
                                                        if ($item['day_special'] == 1) {
                                                            if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                                                $today_spec_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                                                $html .= '<img width="170"  src="' . $today_spec_icon . '">';
                                                            } else {
                                                                if (!empty($default_special_image)) {
                                                                    $html .= '<img width="170"  src="' . $default_special_image . '" alt="Special">';
                                                                } else {
                                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                                    $html .= '<img width="170"  src="' . $def_tds_img . '">';
                                                                }
                                                            }
                                                        }
                                                        $html .='</div>';
                                                        $html .='<div class="item_footer">';
                                                            if($tagName){
                                                                $html .='<span>'.$tagName.'</span>';
                                                            }
                                                            if (count($price_arr) > 0){
                                                                $price = Currency::currency($currency)->format($price_arr[0]['price']);
                                                                $price_label = isset($price_arr[0][$price_label_key]) ? $price_arr[0][$price_label_key] : '';
                                                                if ($item_discount > 0){
                                                                    if ($item_discount_type == 'fixed') {
                                                                        $new_amount = number_format($price_arr[0]['price'] - $item_discount, 2);
                                                                        $newAmount = Currency::currency($currency)->format($new_amount);
                                                                    } else {
                                                                        $per_value = ($price_arr[0]['price'] * $item_discount) / 100;
                                                                        $new_amount = number_format($price_arr[0]['price'] - $per_value, 2);
                                                                        $newAmount = Currency::currency($currency)->format($new_amount);
                                                                    }
                                                                    $html .='<h4>'.$price_label.' '.$newAmount.'<span>'.$price.'</span></h4>';
                                                                }else{
                                                                    $html .='<h4>'.$price_label.' '.$price.'</h4>';
                                                                }
                                                            }
                                                        if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1) {
                                                            $html .='<button class="item_cart_btn" onclick="getItemDetails('.$item['id'].','.$shop_id.')"><i class="fa-solid fa-cart-plus"></i></button>';
                                                        }
                                                        $html .='</div>';
                                                    $html .='</div>';
                                                $html .='</div>';
                                            }
                                        }
                                }
                            $html .= '</div>';
                        $html .= '</div>';
                    } else {
                        $html .= '<h3 class="text-center">Items not Found.</h3>';
                    }
                } elseif ($active_layout == "layout_3"){
                    $html .= '<div class="menu_info">';
                        if(count($items) > 0 ){
                            foreach ($items as $item) {
                                $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];

                                if($item->type == 1){
                                    $html .= '<div class="menu_item_list">';
                                    $html .= '<a href="#" data-bs-toggle="modal" onclick="getItemDetails(' . $item->id . ', ' . $shop_id . ')">';
                                    $html .= '<div class="menu_item_box';
                                    if ($item['is_new'] == 1 || $item['as_sign'] == 1) {
                                        $html .= ' new_item_box_icon';
                                    }
                                    $html .= '">';

                                    // New Product Image
                                    if($item['is_new'] == 1){
                                        $new_img = asset('public/client_images/bs-icon/new.png');
                                        $html .= '<img class="is_new tag-img position-absolute" src="' . $new_img . '" style="top:0; left:0; width:50px;">';
                                    }
                                    // Sign Image
                                    if ($item['as_sign'] == 1) {
                                        $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                        $html .= '<img class="is_sign tag-img position-absolute" src="' . $as_sign_img . '" style="top:0; right:40px; width:50px;">';
                                    }
                                    $html .= '<div class="menu_item_name">';
                                    // Item Name
                                    $html .= '<h4>'.$item_name.'</h4>';
                                    // Item Desc
                                    $html .= '<p>'.strip_tags($desc).'</p>';
                                    $html .='<div class="d-flex align-items-center mb-2">';
                                            if (!empty($item_calories)) {
                                                $html .= '<p class="m-0 me-3"><strong>Cal: ' . $item_calories . '</strong></p>';
                                            }
                                            if (count($ingrediet_arr) > 0) {
                                                $html .= '<div>';
                                                foreach ($ingrediet_arr as $val) {
                                                    $ingredient = getIngredientDetail($val);
                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                    $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                    if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                        if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                            $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                            $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                        }
                                                    }
                                                }
                                                $html .= '</div>';
                                            }
                                    $html .='</div>';

                                    // price
                                    $html .= '<ul class="menu_item_price_ul">';
                                    $price_arr = getItemPrice($item['id']);
                                    if(count($price_arr) > 0){
                                        foreach($price_arr as $key => $value){
                                            $price = Currency::currency($currency)->format($value['price']);
                                            $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';
                                            $html .= '<li>';
                                            if ($item_discount > 0){
                                                if ($item_discount_type == 'fixed') {
                                                    $new_amount = number_format($value['price'] - $item_discount, 2);
                                                } else {
                                                    $per_value = ($value['price'] * $item_discount) / 100;
                                                    $new_amount = number_format($value['price'] - $per_value, 2);
                                                }
                                                $html .='<span>'.$price_label.'  <span class="text-decoration-line-through">'.$price.'</span><span>'.Currency::currency($currency)->format($new_amount).'</span></span>';
                                            }else{
                                                $html .='<span>'.$price_label.'<span>'.$price.'</span></span>';
                                            }
                                            $html .= '</li>';
                                        }
                                    }
                                    $html .='</ul>';
                                    $html .='</div>';

                                    if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                        $item_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                        $html .= '<div class="menu_item_img_inner">';
                                        $html .= '<div class="menu_item_image';
                                        if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink'){
                                            $html .= ' special_day_blink';
                                        }elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate'){
                                            $html .= ' special_day_rotate';
                                        }
                                        $html .= '">';
                                        $html .='<div class="special">
                                                <label ></label>
                                                <label ></label>
                                                <label ></label>
                                                <label ></label>
                                                </div>';
                                        $html .= '<img src="' . $item_image . '" class="w-100">';
                                        $html .= '</div>';
                                        $html .= '<a  class="review_btn" onclick="openRatingModel('.$item['id'].')">';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                        $html .= '</a>';
                                        $html .='</div>';
                                    }
                                    $html .= '</div>';
                                    $html .= '</a>';
                                    $html .= '</div>';
                                } else {
                                    $html .= '<div class="menu_item_list">';
                                    $html .= '<div class="menu_item_box" style="justify-content: center">';
                                    $html .= '<div class="menu_item_name">';
                                    // Item Name
                                    $html .= '<h4>'.$item_name.'</h4>';
                                    // Item Desc
                                    $html .= '<p>'.strip_tags($desc).'</p>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                }
                            }
                        } else {
                            $html .= '<div class="menu_item_box">';
                            $html .= '<div class="menu_item_name">';
                            $html .= '<h4>Items Not Found !</h4>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';

                 }
            }


            return response()->json([
                'success' => 1,
                'message' => "Categories has been retrived Successfully...",
                'data'    => $html,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }


    // Search Itens
    public function searchItems(Request $request)
    {

        $category_id = $request->category_id;
        $tab_id = $request->tab_id;
        $keyword = $request->keyword;
        $shop_id = $request->shop_id;
        $tag_id = $request->tag_id;
        $parent_id = $request->parent_id;

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';
        $name_key = $current_lang_code . "_name";
        $description_key = $current_lang_code . "_description";
        $calories_key = $current_lang_code . "_calories";
        $price_label_key = $current_lang_code . "_label";

        // Shop Details
        $shop_details = Shop::where('id', $shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Shop Settings
        $shop_settings = getClientSettings($shop_id);

        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        $active_layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';
        $special_day_effect_box = isset($theme_settings['special_day_effect_box']) && !empty($theme_settings['special_day_effect_box']) ? $theme_settings['special_day_effect_box'] : 'blink';

        // Read More Label
        $read_more_label = moreTranslations($shop_id, 'read_more_link_label');
        $read_more_label = (isset($read_more_label[$current_lang_code . "_value"]) && !empty($read_more_label[$current_lang_code . "_value"])) ? $read_more_label[$current_lang_code . "_value"] : 'Read More';

        // Today Special Icon
        $today_special_icon = moreTranslations($shop_id, 'today_special_icon');
        $today_special_icon = (isset($today_special_icon[$current_lang_code . "_value"]) && !empty($today_special_icon[$current_lang_code . "_value"])) ? $today_special_icon[$current_lang_code . "_value"] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        try {
            if ($tab_id == 'all' || $tab_id == 'no_tab') {
                $html = '';

                    if ($keyword == '') {
                        $items = Items::where("$name_key", 'LIKE', '%' . $keyword . '%')
                                    ->where('published', 1)
                                    ->whereHas('categories', function ($query) use ($category_id) {
                                        $query->where('id', $category_id);
                                    })
                                    ->orderBy('order_key', 'ASC')
                                    ->get();

                    } else {
                        $items = Items::whereHas('categories', function ($q) use ($parent_id) {
                            $q->where('parent_id', $parent_id);
                        })
                        ->where("$name_key", 'LIKE', '%' . $keyword . '%')
                        ->where('shop_id', $shop_id)
                        ->where('published', 1)
                        ->orderBy('order_key', 'ASC')
                        ->get();
                    }

                if (count($items) > 0) {
                    if($active_layout == 'layout_1')
                    {
                        $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';

                        foreach ($items as $item) {
                            $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                            $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                            $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                            $active_cat = checkCategorySchedule($item->category_id, $item->shop_id);
                            $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;

                            if ($active_cat == 1) {
                                if ($item['type'] == 2) {

                                    $html .= '<div class="col-md-12 mb-3">';
                                        $html .= '<div class="item_detail single_item_inr devider">';

                                            if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                                $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                $html .= '<div class="item_image">';
                                                $html .= '<img src="' . $item_divider_image . '">';
                                                $html .= '</div>';
                                            }

                                            if (count($ingrediet_arr) > 0) {
                                                $html .= '<div>';
                                                foreach ($ingrediet_arr as $val) {
                                                    $ingredient = getIngredientDetail($val);
                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                    $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                    if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                        if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                            $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                            $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                        }
                                                    }
                                                }
                                                $html .= '</div>';
                                            }

                                            $html .= '<h3>' . $item_name . '</h3>';
                                    
                                            $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";

                                            if(!empty($desc)){
                                                $html .= '<div class="item-desc">' . json_decode($desc, true) . '</div>';
                                            }

                                        $html .= '</div>';
                                    $html .= '</div>';
                                } else {
                                    $html .= '<div class="col-md-6 col-lg-6 col-xl-3 mb-3">';
                                        $html .='<div ';
                                        $html .= 'class="item_detail single_item_inr devider-border';
                                        if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink'){
                                            $html .=' special_day_blink';
                                        }elseif ($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate'){
                                            $html .=' special_day_rotate';
                                        }
                                            $html .='">';
                                            $html .='<div class="special"
                                                    <label ></label>
                                                    <label ></label>
                                                    <label ></label>
                                                    <label ></label>
                                                     </div>';

                                    $html .= '<div class="item_image">';
                                        if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                            $item_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                            $html .= '<img src="' . $item_image . '" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">';
                                        }
                                    $html .= '</div>';

                                    if (count($ingrediet_arr) > 0) {
                                        $html .= '<div class="mt-3">';
                                        foreach ($ingrediet_arr as $val) {
                                            $ingredient = getIngredientDetail($val);
                                            $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                            $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                            if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                    $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                    $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                }
                                            }
                                        }
                                        $html .= '</div>';
                                    }

                                    $html .= '<div class="item_image">';
                                        $html .= '<a  class="review_btn" onclick="openRatingModel('.$item['id'].')">';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                        $html .= '</a>';
                                    $html .= '</div>';

                                    if (!empty($item_calories)) {
                                        $html .= '<p class="m-0 p-0 mt-3"><strong>Cal: </strong>' . $item_calories . '</p>';
                                    }                                    

                                    $html .= '<h3 onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">' . $item_name . '</h3>';                                    

                                    if ($item['is_new'] == 1) {
                                        $new_img = asset('public/client_images/bs-icon/new.png');
                                        $html .= '<img class="is_new tag-img" src="' . $new_img . '">';
                                    }

                                    if ($item['as_sign'] == 1) {
                                        $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                        $html .= '<img class="is_sign tag-img" src="' . $as_sign_img . '">';
                                    }                                    

                                    $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";

                                    if(!empty($desc)){
                                        if (strlen(strip_tags($desc)) > 180) {
                                            $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 150), "\n"));
                                            $html .= '<div class="item-desc"><p>' . $desc . ' ... <br>
                                                        <a class="read-more-desc">' . $read_more_label . '</a></p></div>';
                                        } else {
                                            $html .= '<div class="item-desc"><p?>' . strip_tags($desc) . '</p></div>';
                                        }
                                    }

                                    $price_arr = getItemPrice($item['id']);
                                    if (count($price_arr) > 0) {
                                        $html .= '<ul class="price_ul">';
                                            foreach ($price_arr as $key => $value) {
                                                $price = Currency::currency($currency)->format($value['price']);
                                                $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                                $html .= '<li><p>' . $price_label . ' <span>' . $price . '</span></p></li>';
                                            }
                                        $html .= '</ul>';
                                    }

                                    if ($item['day_special'] == 1) {
                                        if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                            $today_spec_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                            $html .= '<img width="170" class="mt-3" src="' . $today_spec_icon . '">';
                                        } else {
                                            if (!empty($default_special_image)) {
                                                $html .= '<img width="170" class="mt-3" src="' . $default_special_image . '" alt="Special">';
                                            } else {
                                                $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                $html .= '<img width="170" class="mt-3" src="' . $def_tds_img . '">';
                                            }
                                        }
                                    }

                                    if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1) {
                                        $html .= '<div class="cart-symbol" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer"><i class="bi bi-cart4"></i></div>';
                                    }

                                    $html .= '</div>';
                                    $html .= '</div>';
                                }
                            }
                        }

                        $html .= '</div>';
                        $html .= '</div>';
                        return response()->json([
                            'success' => 1,
                            'data'    => $html,
                        ]);
                    } elseif($active_layout == 'layout_2')
                    {
                        if($keyword == ''){
                            if($items[0]['type'] == 2){
                                $items = $items->slice(1);
                            }
                        }
                        $html .= '<div class="category_inr_list_item">';
                        $html .= '<div class="row">';

                        foreach ($items as $item){


                                        $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";

                                        $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                        $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                                        $active_cat = checkCategorySchedule($item->category_id, $item->shop_id);
                                        $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;
                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";
                                        $tag_name = getTagName($item['id']);
                                        $tagName = isset($tag_name->hasOneTag->$name_key) ? $tag_name->hasOneTag->$name_key : '';
                                        $price_arr = getItemPrice($item['id']);

                                        $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                        if ($active_cat == 1) {
                                            if ($item['type'] == 2) {
                                                $html .='<div class="category_title">';
                                                    $html .='<div class="category_title_img">';
                                                    if(!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                    {
                                                        $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                        $html .='<img src="'.$item_divider_image.'" class="w-100">';

                                                    }else{
                                                        $no_img = asset('public/client_images/not-found/no_image_1.jpg');
                                                        $html .= '<img src="'.$no_img.'" class="w-100">';
                                                    }
                                                    $html .='</div>';
                                                    $html .='<div class="category_title_name">';
                                                    $html .='<h3>'.$item_name.'</h3>';
                                                    $html .='</div>';
                                                $html .='</div>';
                                            }else{
                                                $html .='<div class="col-xl-4 col-lg-6 col-md-6">';
                                                $html .= '<div class="item_detail single_item_inr devider-border';
                                                if (($item['day_special'] == 1 && $special_day_effect_box == 'blink') || ($item['as_sign'] == 1 && $special_day_effect_box == 'blink')) {
                                                    $html .= ' special_day_blink';
                                                } elseif (($item['day_special'] == 1 && $special_day_effect_box == 'rotate') || ($item['as_sign'] == 1 && $special_day_effect_box == 'rotate')) {
                                                    $html .= ' special_day_rotate';
                                                }
                                                if($item['is_new'] == 1 || $item['as_sign'] == 1){
                                                    $html .=' tag_item_detail';
                                                }
                                                $html .= '">';
                                                    $html .='<div class="special">
                                                            <label ></label>
                                                            <label ></label>
                                                            <label ></label>
                                                            <label ></label>
                                                            </div>';
                                                            if ($item['is_new'] == 1) {
                                                                $new_img = asset('public/client_images/bs-icon/new.png');
                                                                $html .= '<img class="is_new tag-img position-absolute" src="' . $new_img . '" style="top:0; left:0; width:70px;">';
                                                            }

                                                            if ($item['as_sign'] == 1) {
                                                                $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                                                $html .= '<img class="is_sign tag-img position-absolute" src="' . $as_sign_img . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                                                            }
                                                            $html .='<div class="category_item_name">';
                                                                $html .='<h3 onclick="getItemDetails('.$item['id'].','.$shop_id.')">'.$item_name.'</h3>';
                                                            $html .='</div>';
                                                            $html .= '<div class="item_detail_inr' . (!empty($item['image']) && file_exists(public_path('client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) ? '' : ' no_img_item_detail') . '">';
                                                            $html .='<div class="item_info">';
                                                                // $html .='<h3 onclick="getItemDetails('.$item['id'].','.$shop_id.')">'.$item_name.'</h3>';
                                                                    if (strlen(strip_tags($desc)) > 100) {
                                                                        $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 100), "\n"));
                                                                        $html .='<div class="item-desc"><p>'. $desc . ' ... <br>
                                                                                        <a class="read-more-desc" onclick="getItemDetails('.$item['id'].','.$shop_id.')">' . $read_more_label . '</a></p></div>';
                                                                    }else{
                                                                        $html .='<div class="item_desc"><p>'.strip_tags($desc).'</p></div>';
                                                                    }

                                                                    if(count($ingrediet_arr) > 0) {
                                                                        $html .='<div class="item_tag">';
                                                                            foreach ($ingrediet_arr as $val) {
                                                                                $ingredient = getIngredientDetail($val);
                                                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                                                if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                                                    if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                                                            $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                                                            $html .= '<img src="' . $ing_icon . '" width="45px" height="45px">';
                                                                                    }
                                                                                }
                                                                            }
                                                                        $html .='</div>';
                                                                    }
                                                                    if(isset($item_calories) && !empty($item_calories))
                                                                    {
                                                                        $html .='<p class="m-0 p-2"><strong>Cal:</strong>'.$item_calories.'</p>';
                                                                    }
                                                            $html .='</div>';
                                                            $html .='<div class="item_image">';
                                                                if(!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                {
                                                                    $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                                    $html .='<img src="'.$item_divider_image.'" onclick="getItemDetails('.$item['id'].','.$shop_id.')">';
                                                                }
                                                                $html .='<a href="#" class="review_btn" onclick="openRatingModel('.$item['id'].')"><i
                                                                class="fa-solid fa-star"></i> <i
                                                                class="fa-solid fa-star"></i> <i
                                                                class="fa-solid fa-star"></i></a>';

                                                            $html .='</div>';
                                                        $html .='</div>';
                                                        $html .='<div class="special_day_item_gif text-center">';
                                                        if ($item['day_special'] == 1) {
                                                            if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                                                $today_spec_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                                                $html .= '<img width="170"  src="' . $today_spec_icon . '">';
                                                            } else {
                                                                if (!empty($default_special_image)) {
                                                                    $html .= '<img width="170"  src="' . $default_special_image . '" alt="Special">';
                                                                } else {
                                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                                    $html .= '<img width="170"  src="' . $def_tds_img . '">';
                                                                }
                                                            }
                                                        }
                                                        $html .='</div>';
                                                        $html .='<div class="item_footer">';
                                                            if($tagName){
                                                                $html .='<span>'.$tagName.'</span>';
                                                            }
                                                            if (count($price_arr) > 0){
                                                                $price = Currency::currency($currency)->format($price_arr[0]['price']);
                                                                $price_label = isset($price_arr[0][$price_label_key]) ? $price_arr[0][$price_label_key] : '';
                                                                if ($item_discount > 0){
                                                                    if ($item_discount_type == 'fixed') {
                                                                        $new_amount = number_format($price_arr[0]['price'] - $item_discount, 2);
                                                                        $newAmount = Currency::currency($currency)->format($new_amount);
                                                                    } else {
                                                                        $per_value = ($price_arr[0]['price'] * $item_discount) / 100;
                                                                        $new_amount = number_format($price_arr[0]['price'] - $per_value, 2);
                                                                        $newAmount = Currency::currency($currency)->format($new_amount);
                                                                    }
                                                                    $html .='<h4>'.$price_label.' '.$newAmount.'<span>'.$price.'</span></h4>';
                                                                }else{
                                                                    $html .='<h4>'.$price_label.' '.$price.'</h4>';
                                                                }
                                                            }
                                                        if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1) {
                                                            $html .='<button class="item_cart_btn" onclick="getItemDetails('.$item['id'].','.$shop_id.')"><i class="fa-solid fa-cart-plus"></i></button>';
                                                            }
                                                        $html .='</div>';
                                                    $html .='</div>';
                                                $html .='</div>';
                                            }
                                        }
                                    }
                            $html .= '</div>';
                        $html .= '</div>';
                        return response()->json([
                            'success' => 1,
                            'data'    => $html,
                        ]);
                    } else{

                        $html .= '<div class="menu_info">';

                            foreach ($items as $item) {
                                $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                                $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];

                                if($item->type == 1){
                                    $html .= '<div class="menu_item_list">';
                                    $html .= '<a href="#" data-bs-toggle="modal" onclick="getItemDetails(' . $item->id . ', ' . $shop_id . ')">';
                                    $html .= '<div class="menu_item_box';
                                    if ($item['is_new'] == 1 || $item['as_sign'] == 1) {
                                        $html .= ' new_item_box_icon';
                                    }
                                    $html .= '">';

                                    // New Product Image
                                    if($item['is_new'] == 1){
                                        $new_img = asset('public/client_images/bs-icon/new.png');
                                        $html .= '<img class="is_new tag-img position-absolute" src="' . $new_img . '" style="top:0; left:0; width:50px;">';
                                    }
                                    // Sign Image
                                    if ($item['as_sign'] == 1) {
                                        $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                        $html .= '<img class="is_sign tag-img position-absolute" src="' . $as_sign_img . '" style="top:0; right:40px; width:50px;">';
                                    }

                                    $html .= '<div class="menu_item_name">';
                                    // Item Name
                                    $html .= '<h4>'.$item_name.'</h4>';
                                    // Item Desc
                                    $html .= '<p>'.strip_tags($desc).'</p>';

                                    $html .='<div class="d-flex align-items-center mb-2">';
                                            if (!empty($item_calories)) {
                                                $html .= '<p class="m-0 me-3"><strong>Cal: ' . $item_calories . '</strong></p>';
                                            }
                                            if (count($ingrediet_arr) > 0) {
                                                $html .= '<div>';
                                                foreach ($ingrediet_arr as $val) {
                                                    $ingredient = getIngredientDetail($val);
                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                    $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                    if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                        if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                            $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                            $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                        }
                                                    }
                                                }
                                                $html .= '</div>';
                                            }
                                    $html .='</div>';
                                    // price
                                    $html .= '<ul class="menu_item_price_ul">';
                                    $price_arr = getItemPrice($item['id']);
                                    if(count($price_arr) > 0){
                                        foreach($price_arr as $key => $value){
                                            $price = Currency::currency($currency)->format($value['price']);
                                            $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';
                                            $html .= '<li>';
                                            if ($item_discount > 0){
                                                if ($item_discount_type == 'fixed') {
                                                    $new_amount = number_format($value['price'] - $item_discount, 2);
                                                } else {
                                                    $per_value = ($value['price'] * $item_discount) / 100;
                                                    $new_amount = number_format($value['price'] - $per_value, 2);
                                                }
                                                $html .='<span>'.$price_label.'  <span class="text-decoration-line-through">'.$price.'</span><span>'.Currency::currency($currency)->format($new_amount).'</span></span>';
                                            }else{
                                                $html .='<span>'.$price_label.'<span>'.$price.'</span></span>';
                                            }
                                            $html .= '</li>';
                                        }
                                    }
                                    $html .='</ul>';
                                    $html .='</div>';

                                    if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                        $item_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                        $html .= '<div class="menu_item_img_inner">';
                                        $html .= '<div class="menu_item_image';
                                        if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink'){
                                            $html .= ' special_day_blink';
                                        }elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate'){
                                            $html .= ' special_day_rotate';
                                        }
                                        $html .= '">';
                                        $html .='<div class="special">
                                                <label ></label>
                                                <label ></label>
                                                <label ></label>
                                                <label ></label>
                                                </div>';
                                        $html .= '<img src="' . $item_image . '" class="w-100">';
                                        $html .= '</div>';
                                            $html .= '<a  class="review_btn" onclick="openRatingModel('.$item['id'].')">';
                                                $html .= '<i class="fa-solid fa-star"></i>';
                                                $html .= '<i class="fa-solid fa-star"></i>';
                                                $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '</a>';
                                        $html .='</div>';
                                    }

                                    $html .= '</div>';
                                    $html .= '</a>';
                                    $html .= '</div>';
                                } else {
                                    $html .= '<div class="menu_item_list">';
                                    $html .= '<div class="menu_item_box" style="justify-content: center">';
                                    $html .= '<div class="menu_item_name">';
                                    // Item Name
                                    $html .= '<h4>'.$item_name.'</h4>';
                                    // Item Desc
                                    $html .= '<p>'.strip_tags($desc).'</p>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                    $html .= '</div>';
                                }
                            }

                        $html .= '</div>';
                    }
                    return response()->json([
                        'success' => 1,
                        'data' => $html,
                    ]);

                } else {
                    $html .= '<h3 class="text-center">Items Not Found!</h3>';
                    return response()->json([
                        'success' => 1,
                        'data' => $html,
                    ]);
                }
            } else {

                $html = '';
                if ($keyword == '') {
                    $items = CategoryProductTags::join('items', 'items.id', 'category_product_tags.item_id')->where("items.$name_key", 'LIKE', '%' . $keyword . '%')->where('tag_id', $tag_id)->where('category_product_tags.category_id', $category_id)->where('items.published', 1)->orderBy('items.order_key')->get();
                } else {
                    $items = Items::where("$name_key", 'LIKE', '%' . $keyword . '%')->where('shop_id', $shop_id)->where('published', 1)->get();
                }

                if (count($items) > 0) {
                    if($active_layout == 'layout_1'){
                     $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';

                        foreach ($items as $item) {
                            $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                            $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                            $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                            $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;

                            if ($item['type'] == 2) {
                                $html .= '<div class="col-md-12 mb-3">';
                                    $html .= '<div class="single_item_inr devider">';

                                        if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                            $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                            $html .= '<div class="item_image">';
                                            $html .= '<img src="' . $item_divider_image . '">';
                                            $html .= '</div>';
                                        }

                                        if (count($ingrediet_arr) > 0) {
                                            $html .= '<div>';
                                            foreach ($ingrediet_arr as $val) {
                                                $ingredient = getIngredientDetail($val);
                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                    if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                        $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                        $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                                    }
                                                }
                                            }
                                            $html .= '</div>';
                                        }

                                        $html .= '<h3>' . $item_name . '</h3>';


                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";

                                        if(!empty($desc)){
                                            $html .= '<div class="item-desc">' . json_decode($desc, true) . '</div>';
                                        }

                                    $html .= '</div>';
                                $html .= '</div>';
                            } else {
                                $html .= '<div class="col-md-6 col-lg-6 col-xl-3 mb-3">';
                                $html .= '<div class="item_detail single_item_inr devider-border">';

                                $html .= '<div class="item_image">';
                                    if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                        $item_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                        $html .= '<img src="' . $item_image . '" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">';
                                    }
                                $html .= '</div>';

                                if (count($ingrediet_arr) > 0) {
                                    $html .= '<div class="mt-3">';
                                    foreach ($ingrediet_arr as $val) {
                                        $ingredient = getIngredientDetail($val);
                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                        $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                        if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                            if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                            }
                                        }
                                    }
                                    $html .= '</div>';
                                }

                                $html .= '<div class="item_image">';
                                    $html .= '<a  class="review_btn" onclick="openRatingModel('.$item['id'].')">';
                                        $html .= '<i class="fa-solid fa-star"></i>';
                                        $html .= '<i class="fa-solid fa-star"></i>';
                                        $html .= '<i class="fa-solid fa-star"></i>';
                                    $html .= '</a>';
                                $html .= '</div>';

                                if (!empty($item_calories)) {
                                    $html .= '<p class="m-0 p-0 mt-3"><strong>Cal: </strong>' . $item_calories . '</p>';
                                }                                

                                $html .= '<h3 onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer">' . $item_name . '</h3>';                               

                                if ($item['is_new'] == 1) {
                                    $new_img = asset('public/client_images/bs-icon/new.png');
                                    $html .= '<img class="is_new tag-img" src="' . $new_img . '">';
                                }

                                if ($item['as_sign'] == 1) {
                                    $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                    $html .= '<img class="is_sign tag-img" src="' . $as_sign_img . '">';
                                }                                

                                $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";

                                if(!empty($desc)){
                                    if (strlen(strip_tags($desc)) > 180) {
                                        $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 150), "\n"));
                                        $html .= '<div class="item-desc"><p>' . $desc . ' ... <br>
                                                        <a class="read-more-desc">' . $read_more_label . '</a></p></div>';
                                    } else {
                                        $html .= '<div class="item-desc"><p>' . strip_tags($desc) . '</p></div>';
                                    }
                                }

                                $price_arr = getItemPrice($item['id']);
                                if (count($price_arr) > 0) {
                                    $html .= '<ul class="price_ul">';
                                        foreach ($price_arr as $key => $value) {
                                            $price = Currency::currency($currency)->format($value['price']);
                                            $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                            $html .= '<li><p>' . $price_label . ' <span>' . $price . '</span></p></li>';
                                        }
                                    $html .= '</ul>';
                                }

                                if ($item['day_special'] == 1) {
                                    if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                        $today_spec_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                        $html .= '<img width="170" class="mt-3" src="' . $today_spec_icon . '">';
                                    } else {
                                        if (!empty($default_special_image)) {
                                            $html .= '<img width="170" class="mt-3" src="' . $default_special_image . '" alt="Special">';
                                        } else {
                                            $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                            $html .= '<img width="170" class="mt-3" src="' . $def_tds_img . '">';
                                        }
                                    }
                                }

                                if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1) {
                                    $html .= '<div class="cart-symbol" onclick="getItemDetails(' . $item->id . ',' . $shop_id . ')" style="cursor: pointer"><i class="bi bi-cart4"></i></div>';
                                }

                                $html .= '</div>';
                                $html .= '</div>';
                            }
                        }

                        $html .= '</div>';
                        $html .= '</div>';

                        return response()->json([
                            'success' => 1,
                            'data'    => $html,
                        ]);
                    }elseif($active_layout == 'layout_2'){

                        if($keyword == ''){
                            if($items[0]['type'] == 2){
                                $items = $items->slice(1);
                            }
                        }
                        $html .=  '<div class="category_inr_list_item">';
                        $html .= '<div class="row">';

                        foreach ($items as $item){


                                        $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";

                                        $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                        $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                                        $active_cat = checkCategorySchedule($item->category_id, $item->shop_id);
                                        $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;
                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";
                                        $tag_name = getTagName($item['id']);
                                        $tagName = isset($tag_name->hasOneTag->$name_key) ? $tag_name->hasOneTag->$name_key : '';
                                        $price_arr = getItemPrice($item['id']);

                                        $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                                                $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                                        if ($active_cat == 1) {
                                            if ($item['type'] == 2) {
                                                $html .='<div class="category_title">';
                                                    $html .='<div class="category_title_img">';
                                                    if(!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                    {
                                                        $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                        $html .='<img src="'.$item_divider_image.'" class="w-100">';

                                                    }else{
                                                        $no_img = asset('public/client_images/not-found/no_image_1.jpg');
                                                        $html .= '<img src="'.$no_img.'" class="w-100">';
                                                    }
                                                    $html .='</div>';
                                                    $html .='<div class="category_title_name">';
                                                    $html .='<h3>'.$item_name.'</h3>';
                                                    $html .='</div>';
                                                $html .='</div>';
                                            }else{
                                                $html .='<div class="col-xl-4 col-lg-6 col-md-6">';
                                                    $html .='<div class="item_detail single_item_inr devider-border';
                                                        if($item['day_special'] == 1 && $special_day_effect_box == 'blink' || $item['as_sign'] == 1 && $special_day_effect_box == 'blink'){
                                                            $html .= ' special_day_blink';
                                                        }elseif($item['day_special'] == 1 && $special_day_effect_box == 'rotate' || $item['as_sign'] == 1 && $special_day_effect_box == 'rotate'){
                                                            $html .= ' special_day_rotate';
                                                        }
                                                        if($item['is_new'] == 1 || $item['as_sign'] == 1){
                                                            $html .=' tag_item_detail';
                                                        }
                                                    $html .='">';
                                                        $html .='<div class="special">
                                                            <label ></label>
                                                            <label ></label>
                                                            <label ></label>
                                                            <label ></label>
                                                            </div>';
                                                            if ($item['is_new'] == 1) {
                                                                $new_img = asset('public/client_images/bs-icon/new.png');
                                                                $html .= '<img class="is_new tag-img position-absolute" src="' . $new_img . '" style="top:0; left:0; width:70px;">';
                                                            }

                                                            if ($item['as_sign'] == 1) {
                                                                $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                                                $html .= '<img class="is_sign tag-img position-absolute" src="' . $as_sign_img . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                                                            }
                                                            $html .='<div class="category_item_name">';
                                                                $html .='<h3 onclick="getItemDetails('.$item['id'].','.$shop_id.')">'.$item_name.'</h3>';
                                                            $html .='</div>';
                                                        $html .='<div class="item_detail_inr">';
                                                            $html .='<div class="item_info">';
                                                                // $html .='<h3 onclick="getItemDetails('.$item['id'].','.$shop_id.')">'.$item_name.'</h3>';
                                                                    if (strlen(strip_tags($desc)) > 100) {
                                                                        $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc), 100), "\n"));
                                                                        $html .='<div class="item-desc"><p>'. $desc . ' ... <br>
                                                                                        <a class="read-more-desc" onclick="getItemDetails('.$item['id'].','.$shop_id.')">' . $read_more_label . '</a></p></div>';
                                                                    }else{
                                                                        $html .='<div class="item_desc"><p>'.strip_tags($desc).'</p></div>';
                                                                    }

                                                                    if(count($ingrediet_arr) > 0) {
                                                                        $html .='<div class="item_tag">';
                                                                            foreach ($ingrediet_arr as $val) {
                                                                                $ingredient = getIngredientDetail($val);
                                                                                $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                                                                $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                                                if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                                                                    if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                                                            $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                                                            $html .= '<img src="' . $ing_icon . '" width="45px" height="45px">';
                                                                                    }
                                                                                }
                                                                            }
                                                                        $html .='</div>';
                                                                    }
                                                                    if(isset($item_calories) && !empty($item_calories))
                                                                    {
                                                                        $html .='<p class="m-0 p-2"><strong>Cal:</strong>'.$item_calories.'</p>';
                                                                    }
                                                            $html .='</div>';
                                                            $html .='<div class="item_image">';
                                                                if(!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']))
                                                                {
                                                                    $item_divider_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                                                    $html .='<img src="'.$item_divider_image.'" onclick="getItemDetails('.$item['id'].','.$shop_id.')">';
                                                                }
                                                                $html .='<a href="#" class="review_btn" onclick="openRatingModel('.$item['id'].')"><i
                                                                class="fa-solid fa-star"></i> <i
                                                                class="fa-solid fa-star"></i> <i
                                                                class="fa-solid fa-star"></i></a>';

                                                            $html .='</div>';
                                                        $html .='</div>';
                                                        $html .='<div class="special_day_item_gif text-center">';
                                                        if ($item['day_special'] == 1) {
                                                            if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                                                $today_spec_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                                                $html .= '<img width="170"  src="' . $today_spec_icon . '">';
                                                            } else {
                                                                if (!empty($default_special_image)) {
                                                                    $html .= '<img width="170"  src="' . $default_special_image . '" alt="Special">';
                                                                } else {
                                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                                    $html .= '<img width="170"  src="' . $def_tds_img . '">';
                                                                }
                                                            }
                                                        }
                                                        $html .='</div>';
                                                        $html .='<div class="item_footer">';
                                                            $html .='<span>'.$tagName.'</span>';
                                                            if (count($price_arr) > 0){
                                                                $price = Currency::currency($currency)->format($price_arr[0]['price']);
                                                                $price_label = isset($price_arr[0][$price_label_key]) ? $price_arr[0][$price_label_key] : '';
                                                                if ($item_discount > 0){
                                                                    if ($item_discount_type == 'fixed') {
                                                                        $new_amount = number_format($price_arr[0]['price'] - $item_discount, 2);
                                                                        $newAmount = Currency::currency($currency)->format($new_amount);
                                                                    } else {
                                                                        $per_value = ($price_arr[0]['price'] * $item_discount) / 100;
                                                                        $new_amount = number_format($price_arr[0]['price'] - $per_value, 2);
                                                                        $newAmount = Currency::currency($currency)->format($new_amount);
                                                                    }
                                                                    $html .='<h4>'.$price_label.' '.$newAmount.'<span>'.$price.'</span></h4>';
                                                                }else{
                                                                    $html .='<h4>'.$price_label.' '.$price.'</h4>';
                                                                }
                                                            }
                                                        if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && count($price_arr) > 0 && $item_delivery == 1) {
                                                            $html .='<button class="item_cart_btn" onclick="getItemDetails('.$item['id'].','.$shop_id.')"><i class="fa-solid fa-cart-plus"></i></button>';
                                                            }
                                                        $html .='</div>';
                                                    $html .='</div>';
                                                $html .='</div>';
                                            }
                                        }
                                    }
                            $html .= '</div>';
                            $html .= '</div>';

                            return response()->json([
                                'success' => 1,
                                'data'    => $html,
                            ]);

                    }elseif($active_layout == 'layout_3'){
                        $html .= '<div class="menu_info">';

                        foreach ($items as $item) {
                            $item_discount = isset($item['discount']) ? $item['discount'] : 0;
                            $item_discount_type = isset($item['discount_type']) ? $item['discount_type'] : 'percentage';
                            $item_delivery = isset($item['delivery']) && $item['delivery'] == 1 ? $item['delivery'] : 0;
                            $desc = isset($item[$description_key]) && !empty($item[$description_key]) ? $item[$description_key] : '';
                            $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                            $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];

                            if($item->type == 1){
                                $html .= '<div class="menu_item_list">';
                                $html .= '<a href="#" data-bs-toggle="modal" onclick="getItemDetails(' . $item->id . ', ' . $shop_id . ')">';
                                $html .= '<div class="menu_item_box';
                                if ($item['is_new'] == 1 || $item['as_sign'] == 1) {
                                    $html .= ' new_item_box_icon';
                                }
                                $html .= '">';

                                // New Product Image
                                if($item['is_new'] == 1){
                                    $new_img = asset('public/client_images/bs-icon/new.png');
                                    $html .= '<img class="is_new tag-img position-absolute" src="' . $new_img . '" style="top:0; left:0; width:50px;">';
                                }
                                // Sign Image
                                if ($item['as_sign'] == 1) {
                                    $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                    $html .= '<img class="is_sign tag-img position-absolute" src="' . $as_sign_img . '" style="top:0; right:40px; width:50px;">';
                                }

                                $html .= '<div class="menu_item_name">';
                                // Item Name
                                $html .= '<h4>'.$item_name.'</h4>';
                                // Item Desc
                                $html .= '<p>'.strip_tags($desc).'</p>';

                                $html .='<div class="d-flex align-items-center mb-2">';
                                if (!empty($item_calories)) {
                                    $html .= '<p class="m-0 me-3"><strong>Cal: ' . $item_calories . '</strong></p>';
                                }
                                if (count($ingrediet_arr) > 0) {
                                    $html .= '<div>';
                                    foreach ($ingrediet_arr as $val) {
                                        $ingredient = getIngredientDetail($val);
                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';
                                        $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                        if ((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL) {
                                            if (!empty($ing_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon)) {
                                                $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ing_icon);
                                                $html .= '<img src="' . $ing_icon . '" width="60px" height="60px">';
                                            }
                                        }
                                    }
                                    $html .= '</div>';
                                }
                        $html .='</div>';
                                // price
                                $html .= '<ul class="menu_item_price_ul">';
                                $price_arr = getItemPrice($item['id']);
                                if(count($price_arr) > 0){
                                    foreach($price_arr as $key => $value){
                                        $price = Currency::currency($currency)->format($value['price']);
                                        $price_label = isset($value[$price_label_key]) ? $value[$price_label_key] : '';
                                        $html .= '<li>';
                                        if ($item_discount > 0){
                                            if ($item_discount_type == 'fixed') {
                                                $new_amount = number_format($value['price'] - $item_discount, 2);
                                            } else {
                                                $per_value = ($value['price'] * $item_discount) / 100;
                                                $new_amount = number_format($value['price'] - $per_value, 2);
                                            }
                                            $html .='<span>'.$price_label.'  <span class="text-decoration-line-through">'.$price.'</span><span>'.Currency::currency($currency)->format($new_amount).'</span></span>';
                                        }else{
                                            $html .='<span>'.$price_label.'<span>'.$price.'</span></span>';
                                        }
                                        $html .= '</li>';
                                    }
                                }
                                $html .='</ul>';
                                $html .='</div>';

                                if (!empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) {
                                    $item_image = asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']);
                                    $html .= '<div class="menu_item_img_inner">';
                                        $html .= '<div class="menu_item_image">';
                                        $html .= '<img src="' . $item_image . '" class="w-100">';
                                        $html .= '</div>';
                                        $html .= '<a  class="review_btn" onclick="openRatingModel('.$item['id'].')">';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                            $html .= '<i class="fa-solid fa-star"></i>';
                                        $html .= '</a>';
                                    $html .= '</div>';
                                }

                                $html .= '</div>';
                                $html .= '</a>';
                                $html .= '</div>';
                            } else {
                                $html .= '<div class="menu_item_list">';
                                $html .= '<div class="menu_item_box" style="justify-content: center">';
                                $html .= '<div class="menu_item_name">';
                                // Item Name
                                $html .= '<h4>'.$item_name.'</h4>';
                                // Item Desc
                                $html .= '<p>'.strip_tags($desc).'</p>';
                                $html .= '</div>';
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                        }

                    $html .= '</div>';
                    }
                    return response()->json([
                        'success' => 1,
                        'data' => $html,
                    ]);
                } else {
                    $html .= '<h3 class="text-center">Items Not Found!</h3>';
                    return response()->json([
                        'success' => 1,
                        'data' => $html,
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                "message" => 'Internal Server Errors',
            ]);
        }
    }


    // Delete Shop Logo
    public function deleteShopLogo()
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $shop = Shop::find($shop_id);

        if ($shop) {
            $shop_logo = isset($shop->logo) ? $shop->logo : '';
            if (!empty($shop_logo)) {
                $new_path = str_replace(asset('/public/'), public_path(), $shop_logo);
                if (file_exists($new_path)) {
                    unlink($new_path);
                }
            }

            $shop->logo = "";
        }

        $shop->update();

        return redirect()->back()->with('success', "Shop Logo has been Removed SuccessFully..");
    }


    // Function for Get Item Details
    public function getDetails(Request $request)
    {

        $current_date = Carbon::now();

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        // Shop Settings
        $shop_settings = getClientSettings($request->shop_id);

        // Shop Default Currency
        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        // Shop Theme ID
        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        // Today Special Icon
        $today_special_icon = moreTranslations($request->shop_id, 'today_special_icon');
        $today_special_icon = (isset($today_special_icon[$current_lang_code . "_value"]) && !empty($today_special_icon[$current_lang_code . "_value"])) ? $today_special_icon[$current_lang_code . "_value"] : '';

        // Layout
        $active_layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($request->shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        // Shop Details
        $shop_details = Shop::where('id', $request->shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Default Today Special Image
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Column Keys
        $name_key = $current_lang_code . "_name";
        $title_key = $current_lang_code . "_title";
        $description_key = $current_lang_code . "_description";
        $calories_key = $current_lang_code . "_calories";
        $price_label_key = $current_lang_code . "_label";

        // Item ID
        $item_id = $request->item_id;

        // Count Items Visit
        $item_visit = ItemsVisit::where('item_id', $item_id)->where('shop_id', $request->shop_id)->first();
        $item_visit_id = isset($item_visit->id) ? $item_visit->id : '';

        if (!empty($item_visit_id)) {
            $edit_item_visit = ItemsVisit::find($item_visit_id);
            $total_clicks = $edit_item_visit->total_clicks + 1;
            $edit_item_visit->total_clicks = $total_clicks;
            $edit_item_visit->update();
        } else {
            $new_item_visit = new ItemsVisit();
            $new_item_visit->shop_id = $request->shop_id;
            $new_item_visit->item_id = $item_id;
            $new_item_visit->total_clicks = 1;
            $new_item_visit->save();
        }


        // Count Clicks
        $clicks = Clicks::where('shop_id', $request->shop_id)->whereDate('created_at', $current_date)->first();
        $click_id = isset($clicks->id) ? $clicks->id : '';
        if (!empty($click_id)) {
            $edit_click = Clicks::find($click_id);
            $total_clicks = $edit_click->total_clicks + 1;
            $edit_click->total_clicks = $total_clicks;
            $edit_click->update();
        } else {
            $new_click = new Clicks();
            $new_click->shop_id = $request->shop_id;
            $new_click->total_clicks = 1;
            $new_click->save();
        }


        try {

            $html = '';

            $item = Items::where('id', $item_id)->first();

            if (isset($item)) {
                $item_image = (isset($item['image']) && !empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) ? asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) : '';
                $item_image_detail = (isset($item['image_detail']) && !empty($item['image_detail']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image_detail'])) ? asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image_detail']) : '';
                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : $item['name'];
                $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : $item['calories'];
                $item_desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : $item['description'];
                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                $price_arr = getItemPrice($item['id']);
                $item_discount = (isset($item['discount'])) ? $item['discount'] : 0;
                $item_discount_type = (isset($item['discount_type'])) ? $item['discount_type'] : 'percentage';
                $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;

                $html .= '<input type="hidden" name="item_id" id="item_id" value="' . $item['id'] . '">';
                $html .= '<input type="hidden" name="shop_id" id="shop_id" value="' . $request->shop_id . '">';
                
                    $html .= '<div class="modal-body">';
                    if(!empty($item_image_detail)){
                        $html .= '<div class="item_image">';
                        $html .= '<img src="' . $item_image_detail . '" alt="" class="w-100 h-100">';
                        if ($item['as_sign'] == 1) {
                            $sign_image = asset('public/client_images/bs-icon/signature.png');
                            $html .= '<img class="is_sign tag-img position-absolute" src="' . $sign_image . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                        }
                        if ($item['is_new'] == 1) {
                            $is_new_img = asset('public/client_images/bs-icon/new.png');
                            $html .= '<img class="is_new tag-img position-absolute" src="' . $is_new_img . '" style="top:0; left:0; width:70px;">';
                        }
                        if(count($ingrediet_arr) > 0){
                            $html .= '<div class="modal_img_ingredient">';
                                if (count($ingrediet_arr) > 0) {
                                        foreach ($ingrediet_arr as $val) {
                                            $ingredient = getIngredientDetail($val);
                                            if (isset($ingredient['icon']) && !empty($ingredient['icon']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon'])) {
                                                $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon']);
                                                $html .= '<img src="' . $ing_icon . '" width="55px" height="55px">';
                                            }
                                        }
                                }
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                    }else if(!empty($item_image))
                    {
                        $html .= '<div class="item_image">';
                        $html .= '<img src="' . $item_image . '" alt="" class="w-100 h-100">';
                        if ($item['as_sign'] == 1) {
                            $sign_image = asset('public/client_images/bs-icon/signature.png');
                            $html .= '<img class="is_sign tag-img position-absolute" src="' . $sign_image . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                        }
                        if ($item['is_new'] == 1) {
                            $is_new_img = asset('public/client_images/bs-icon/new.png');
                            $html .= '<img class="is_new tag-img position-absolute" src="' . $is_new_img . '" style="top:0; left:0; width:70px;">';
                        }
                        if(count($ingrediet_arr) > 0){
                            $html .= '<div class="modal_img_ingredient">';
                                if (count($ingrediet_arr) > 0) {
                                        foreach ($ingrediet_arr as $val) {
                                            $ingredient = getIngredientDetail($val);
                                            if (isset($ingredient['icon']) && !empty($ingredient['icon']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon'])) {
                                                $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon']);
                                                $html .= '<img src="' . $ing_icon . '" width="55px" height="55px">';
                                            }
                                        }
                                }
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                    }else{
                        if($item['as_sign'] == 1 || $item['is_new'] == 1){
                            $html .='<div class="without_img_modal" style="height: 70px;">';
                            if ($item['as_sign'] == 1) {
                                $sign_image = asset('public/client_images/bs-icon/signature.png');
                                $html .= '<img class="is_sign tag-img position-absolute" src="' . $sign_image . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                            }
                            if ($item['is_new'] == 1) {
                                $is_new_img = asset('public/client_images/bs-icon/new.png');
                                $html .= '<img class="is_new tag-img position-absolute" src="' . $is_new_img . '" style="top:0; left:0; width:70px;">';
                            }
                            $html .='</div>';
                        }
                    }

                    $html .= '<div class="item_info">';
                    $html .= '<h3>' . $item_name . '</h3>';
                    if (!empty($item_desc)) {
                        $html .= '<p>' . $item_desc . '</p>';
                    }
                    if(count($ingrediet_arr) > 0 || $item['day_special'] == 1 || !empty($item_calories))
                    {
                        $html .='<div class="item_detail_inner_info">';
                        if(empty($item_image))
                            if (count($ingrediet_arr) > 0) {
                                $html .='<div class="item_detail_tag">';
                                foreach ($ingrediet_arr as $val) {
                                    $ingredient = getIngredientDetail($val);
                                    if (isset($ingredient['icon']) && !empty($ingredient['icon']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon'])) {
                                        $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon']);
                                        $html .= '<img src="' . $ing_icon . '" width="55px" height="55px">';
                                    }
                                }
                                $html .='</div>';
                            }

                            if ($item['day_special'] == 1) {
                                $html .='<div class="item_detail_special">';
                                if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                    $tds_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                    $html .= '<img  src="' . $tds_icon . '">';
                                }else{
                                    if (!empty($default_special_image)) {
                                        $html .= '<img  src="' . $default_special_image . '" alt="Special">';
                                    } else {
                                        $sp_image = asset('public/client_images/bs-icon/today_special.gif');
                                        $html .= '<img src="' . $sp_image . '">';
                                    }
                                }
                                $html .='</div>';
                            }
                            if (!empty($item_calories)) {
                                $html .='<div class="item_detail_cal">';
                                    $html .= '<p><strong>Cal: </strong>' . $item_calories.'</p>';
                                $html .='</div>';
                            }
                        $html .='</div>';

                    }
                    $html .= '</div>';
                    // if (!empty($item_desc)) {
                    // $html .= '<div class="item_info_dec">';
                    //     $html .= '<p>' . $item_desc . '</p>';
                    //     $html .= '</div>';
                    // }
                    if (count($price_arr) > 0) {
                        $html .= '<input type="hidden" name="def_currency" id="def_currency" value="' . $currency . '">';
                        $t_price = (isset($price_arr[0]->price)) ? Currency::currency($currency)->format($price_arr[0]->price) : Currency::currency($currency)->format(0.00);
                        // $html .= '<div class="item_price">';
                        // $html .= '<h4 class="total_price">' . $t_price . '</h4>';
                        // if ($item_discount > 0) {
                        //     if ($item_discount_type == 'fixed') {
                        //         $hidden_price = number_format($price_arr[0]->price - $item_discount, 2);
                        //     } else {
                        //         $dis_per = $price_arr[0]->price * $item_discount / 100;
                        //         $hidden_price = number_format($price_arr[0]->price - $dis_per, 2);
                        //     }
                        //     $html .= "<input type='hidden' name='total_amount' id='total_amount' value='" . $hidden_price . "'>";
                        // } else {
                        //     $html .= "<input type='hidden' name='total_amount' id='total_amount' value='" . $price_arr[0]->price . "'>";
                        // }
                        // $html . '</div>';
                        $html .= '</div>';

                        // Options
                        $option_ids = (isset($item['options']) && !empty($item['options'])) ? unserialize($item['options']) : [];

                        if(count($option_ids) > 0){
                            $opt_display = '';
                        }else{
                            $opt_display = 'none';
                        }

                        if (count($price_arr) > 0) {
                            if (count($price_arr) > 1) {
                                $display = '';
                                $dinamic_col = 6;
                            } else {
                                if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1){
                                    $display = 'none';
                                }else{
                                    $display = '';
                                }
                                $dinamic_col = 12;
                            }

                            $outer_display = ($opt_display == 'none' && $display == 'none') ? 'none' : '';

                            $html .= '<div class="igradient_box" style="display: '.$outer_display.'">';
                            $html .= '<div class="igradient_category_box" style="display:' . $display . '">';
                            $html .= '<div class="row">';
                                            foreach ($price_arr as $key => $value) {
                                                if ($item_discount > 0) {
                                                    if ($item_discount_type == 'fixed') {
                                                        $price = number_format($value['price'] - $item_discount, 2);
                                                    } else {
                                                        $price_per = $value['price'] *  $item_discount / 100;
                                                        $price = number_format($value['price'] - $price_per, 2);
                                                    }
                                                } else {
                                                    $price = $value['price'];
                                                }
                                $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1) {
                                    $inputVisible = 1;
                                    $lableVisible = '';
                                }else{
                                    $inputVisible = 0;
                                    $lableVisible = 'radio-item-disabled';
                                }

                                $html .= '<div class="col-md-'.$dinamic_col.'">';
                                    $html .= '<div class="radio-item '.$lableVisible.'">';

                                        if ($inputVisible == 1) {
                                            $html .= '<input type="radio" name="base_price" onchange="updatePrice()" value="' . $price . '" id="base_price_' . $key . '"';
                                            if ($key == 0) {
                                                $html .= 'checked';
                                            }
                                            $html .= ' option-id="' . $value['id'] . '">';
                                        }

                                        $html .= '<label for="base_price_' . $key . '">';
                                            $html .= '<span>' . $price_label . '</span>';
                                            $html .= '<span>' . Currency::currency($currency)->format($price) . '</span>';
                                        $html .= '</label>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            }
                            $html .= '</div>';
                            $html .= '</div>';

                        }

                            if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1) {

                                $html .= "<input type='hidden' name='option_ids' id='option_ids' value='" . json_encode($option_ids, TRUE) . "'>";

                                    if (count($option_ids) > 0) {
                                        foreach ($option_ids as $outer_key => $opt_id) {
                                            $html .= '<div class="igradient_category_box">';
                                            $html .= '<div class="row" id="option_' . $outer_key . '">';
                                            $opt_dt = Option::with(['optionPrices'])->where('id', $opt_id)->first();
                                            $enable_price = (isset($opt_dt['enabled_price'])) ? $opt_dt['enabled_price'] : '';
                                            $option_prices = (isset($opt_dt['optionPrices'])) ? $opt_dt['optionPrices'] : [];

                                            if (count($option_prices) > 0) {
                                                $html .= '<h3>' . $opt_dt[$title_key] . '</h3>';
                                                $radio_key = 0;
                                                foreach ($option_prices as $key => $option_price) {
                                                    $opt_price = Currency::currency($currency)->format($option_price['price']);
                                                    $opt_price_label = (isset($option_price[$name_key])) ? $option_price[$name_key] : "";

                                                    if (isset($opt_dt['multiple_select']) && $opt_dt['multiple_select'] == 1) {
                                                        $is_checked = (isset($opt_dt['pre_select']) && $opt_dt['pre_select'] == 1) ? 'checked' : '';
                                                        $html .= '<div class="col-md-6">';
                                                        $html .= '<div class="radio-item check">';
                                                        $html .= '<input type="checkbox" value="' . $option_price['price'] . '" name="option_price_checkbox_' . $outer_key . '" onchange="updatePrice()" id="option_price_checkbox_' . $outer_key . '_' . $key . '" class="me-2" opt_price_id="' . $option_price['id'] . '" ' . $is_checked . '>';
                                                        $html .= '<label class="form-label" for="option_price_checkbox_' . $outer_key . '_' . $key . '">';
                                                        $html .= '<span>' . $opt_price_label . '</span>';
                                                        if ($enable_price == 1) {
                                                            $html .= '<span>' . $opt_price . '</span>';
                                                        }
                                                        $html .= '</label>';
                                                        $html .= '</div>';
                                                        $html .= '</div>';
                                                    } else {
                                                        $radio_key++;
                                                        if ($radio_key == 1) {
                                                            $auto_check_radio = 'checked';
                                                        } else {
                                                            $auto_check_radio = "";
                                                        }
                                                        $html .= '<div class="col-md-6">';
                                                        $html .= '<div class="radio-item">';
                                                        $html .= '<input type="radio" value="' . $option_price['price'] . '" name="option_price_radio_' . $outer_key . '" onchange="updatePrice()" id="option_price_radio_' . $outer_key . '_' . $key . '" class="me-2" opt_price_id="' . $option_price['id'] . '" ' . $auto_check_radio . '>';
                                                        $html .= '<label class="form-label" for="option_price_radio_' . $outer_key . '_' . $key . '">';
                                                        $html .= '<span>' . $opt_price_label . '</span>';
                                                        if ($enable_price == 1) {
                                                            $html .= '<span>' . $opt_price . '</span>';
                                                        }
                                                        $html .= '</label>';
                                                        $html .= '</div>';
                                                        $html .= '</div>';
                                                    }
                                                }
                                            }
                                            $html .= '</div>';
                                            $html .= '</div>';
                                        }
                                    }
                            }
                        $html .= '</div>';
                        $html .= '</div>';
                        // $html .= '</div>';

                        if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1) {
                            $html .= '<div class="modal-footer">';
                            $html .= '<div class="add_to_cart_box w-100">';
                            $html .= '<div class="row align-items-center">';
                            $html .= '<div class="col-4">';
                            $html .= '<div class="quantity_btn_group">';
                            $html .= '<button type="button" class="btn  btn-number" disabled="disabled" data-type="minus" onclick="QuntityIncDec(this)" data-field="quant[1]">';
                            $html .= '<span class="fa fa-minus"></span>';
                            $html .= '</button>';
                            $html .= '<input type="text" name="quant[1]" id="quantity" onchange="QuntityIncDecOnChange(this)" class="form-control input-number" value="1" min="1" max="1000" width="60">';
                            $html .= '<button type="button" onclick="QuntityIncDec(this)" class="btn  btn-number" data-type="plus" data-field="quant[1]" >';
                            $html .= '<span class="fa fa-plus"></span>';
                            $html .= '</button>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="col-4">';
                            // $html .= '<button class="btn add_to_cart_btn_modal btn-success w-100">Add to Cart</button>';
                            $html .= '<a class="btn add_to_cart_btn_modal btn-success w-100" onclick="addToCart(' . $item['id'] . ')"><i class="bi bi-cart4"></i> ' . __('Add It') . '</a>';
                            $html .= '</div>';
                            $html .='<div class="col-4">';
                            if (count($price_arr) > 0) {
                                $html .= '<div class="item_price text-center">';
                                $html .= '<input type="hidden" name="def_currency" id="def_currency" value="' . $currency . '">';
                            $t_price = (isset($price_arr[0]->price)) ? Currency::currency($currency)->format($price_arr[0]->price) : Currency::currency($currency)->format(0.00);
                            $html .= '<h4 id="total_price">' . $t_price . '</h4>';
                            if ($item_discount > 0) {
                                    if ($item_discount_type == 'fixed') {
                                        $hidden_price = number_format($price_arr[0]->price - $item_discount, 2);
                                    } else {
                                        $dis_per = $price_arr[0]->price * $item_discount / 100;
                                        $hidden_price = number_format($price_arr[0]->price - $dis_per, 2);
                                    }
                                    $html .= "<input type='hidden' name='total_amount' id='total_amount' value='" . $hidden_price . "'>";
                                } else {
                                    $html .= "<input type='hidden' name='total_amount' id='total_amount' value='" . $price_arr[0]->price . "'>";
                                }
                            $html .= '</div>';
                            }
                            $html .='</div>';
                            $html .= '</div>';
                            $html .= '</div>';

                        }
                    }
                // }
            }

            return response()->json([
                'success' => 1,
                'message' => 'Details has been Fetched SuccessFully...',
                'data'    => $html,
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }


    // Function for Check In
    public function checkIn(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'passport' => 'required',
            'date_of_birth' => 'required',
            'nationality' => 'required',
            'arrival_date' => 'required',
            'departure_date' => 'required',
            'room_number' => 'required',
            'residence_address' => 'required',
        ]);

        $shop_id = $request->store_id;

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        $shop_details = Shop::where('id', $shop_id)->first();
        $shop_name = (isset($shop_details['name'])) ? $shop_details['name'] : '';
        $shop_url = (isset($shop_details['shop_slug'])) ? $shop_details['shop_slug'] : '';
        $shop_url = asset($shop_url);
        $shop_name = '<a href="' . $shop_url . '">' . $shop_name . '</a>';
        $shop_logo = (isset($shop_details['logo'])) ? $shop_details['logo'] : '';
        $shop_logo = '<img src="' . $shop_logo . '" width="200">';

        $shop_user = UserShop::with(['user'])->where('shop_id', $shop_id)->first();
        $contact_emails = (isset($shop_user->user['contact_emails']) && !empty($shop_user->user['contact_emails'])) ? unserialize($shop_user->user['contact_emails']) : [];
        $client_email = (isset($shop_user->user['email']) && !empty($shop_user->user['email'])) ? $shop_user->user['email'] : '';


        $shop_settings = getClientSettings($shop_id);

        // Name Key
        $form_key = $current_lang_code . "_form";

        // CheckIN Mail Template
        $mail_forms = MailForm::where('shop_id', $shop_id)->where('mail_form_key', 'check_in_mail_form')->first();
        $check_in_mail_form = (isset($mail_forms[$form_key])) ? $mail_forms[$form_key] : $mail_forms['en_form'];

        $age = Carbon::parse($request->date_of_birth)->age;

        $data['firstname'] = $request->firstname;
        $data['lastname'] = $request->lastname;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $data['passport'] = $request->passport;
        $data['nationality'] = $request->nationality;
        $data['arrival_date'] = $request->arrival_date;
        $data['departure_date'] = $request->departure_date;
        $data['room_number'] = $request->room_number;
        $data['residence_address'] = $request->residence_address;
        $data['message'] = $request->message;
        $data['dob'] = $request->date_of_birth;
        $data['age'] = $age;

        $from_mail = $data['email'];
        $data['subject'] = "New Check In";
        $data['description'] = $data['firstname'] . ' ' . $data['lastname'] . ' has been check in at : ' . date('d-m-Y h:i:s', strtotime($data['arrival_date']));

        // $sendData = [
        //     'message' => $data['description'],
        //     'subject' => $data['subject'],
        //     'firstname' => $data['firstname'],
        //     'lastname' => $data['lastname'],
        //     'email' => $data['email'],
        //     'phone' => $data['phone'],
        //     'age' => $data['age'],
        //     'room_number' => $data['room_number'],
        //     'from_mail' => $from_mail,
        // ];

        try {
            if (count($contact_emails) > 0 && !empty($check_in_mail_form)) {
                foreach ($contact_emails as $mail) {
                    $to = $mail;
                    $subject = $data['subject'];

                    $message = $check_in_mail_form;
                    $message = str_replace('{shop_logo}', $shop_logo, $message);
                    $message = str_replace('{shop_name}', $shop_name, $message);
                    $message = str_replace('{firstname}', $data['firstname'], $message);
                    $message = str_replace('{lastname}', $data['lastname'], $message);
                    $message = str_replace('{phone}', $data['phone'], $message);
                    $message = str_replace('{passport_no}', $data['passport'], $message);
                    $message = str_replace('{room_no}', $data['room_number'], $message);
                    $message = str_replace('{nationality}', $data['nationality'], $message);
                    $message = str_replace('{age}', $data['age'], $message);
                    $message = str_replace('{address}', $data['residence_address'], $message);
                    $message = str_replace('{arrival_date}', date('d-m-Y h:i:s', strtotime($data['arrival_date'])), $message);
                    $message = str_replace('{departure_date}', date('d-m-Y h:i:s', strtotime($data['departure_date'])), $message);
                    $message = str_replace('{message}', $data['message'], $message);

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <' . $from_mail . '>' . "\r\n";

                    mail($to, $subject, $message, $headers);

                    // Mail::to($mail)->send(new CheckInMail($sendData));
                    // mail($mail,$data['subject'],$data['description']);
                }
            } elseif (!empty($check_in_mail_form)) {
                $to = $client_email;
                $subject = $data['subject'];

                $message = $check_in_mail_form;
                $message = str_replace('{shop_name}', $shop_name, $message);
                $message = str_replace('{first_name}', $data['firstname'], $message);
                $message = str_replace('{last_name}', $data['lastname'], $message);
                $message = str_replace('{phone}', $data['phone'], $message);
                $message = str_replace('{passport_no}', $data['passport'], $message);
                $message = str_replace('{room_no}', $data['room_number'], $message);
                $message = str_replace('{nationality}', $data['nationality'], $message);
                $message = str_replace('{age}', $data['age'], $message);
                $message = str_replace('{address}', $data['residence_address'], $message);
                $message = str_replace('{arrival_date}', $data['arrival_date'], $message);
                $message = str_replace('{departure_date}', $data['departure_date'], $message);
                $message = str_replace('{message}', $data['message'], $message);

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <' . $from_mail . '>' . "\r\n";

                mail($to, $subject, $message, $headers);
                // Mail::to($client_email)->send(new CheckInMail($sendData));
                // mail($mail,$data['subject'],$data['description']);
            }

            // Insert Check In Info
            $new_check_in = new CheckIn();
            $new_check_in->shop_id = $shop_id;
            $new_check_in->firstname = $data['firstname'];
            $new_check_in->lastname = $data['lastname'];
            $new_check_in->email = $data['email'];
            $new_check_in->phone = $data['phone'];
            $new_check_in->passport_no = $data['passport'];
            $new_check_in->nationality = $data['nationality'];
            $new_check_in->arrival_date = $data['arrival_date'];
            $new_check_in->departure_date = $data['departure_date'];
            $new_check_in->room_no = $data['room_number'];
            $new_check_in->address = $data['residence_address'];
            $new_check_in->message = $data['message'];
            $new_check_in->dob = $data['dob'];
            $new_check_in->age = $data['age'];
            $new_check_in->save();

            return redirect()->back()->with('success', 'Check In SuccessFully....');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Internal Server Error!');
        }
    }


    // Function for add to Cart
    public function addToCart(Request $request)
    {

        $cart_data = $request->cart_data;
        $item_id = $cart_data['item_id'];
        $quantity = $cart_data['quantity'];
        $total_amount = $cart_data['total_amount'];
        $total_amount_text = $cart_data['total_amount_text'];
        $option_id = $cart_data['option_id'];
        $shop_id = $cart_data['shop_id'];
        $currency = $cart_data['currency'];
        $categories_data =  (isset($cart_data['categories_data']) && !empty($cart_data['categories_data'])) ? json_decode($cart_data['categories_data'], true) : [];

        try {
            $cart = session()->get('cart', []);

            if (isset($cart[$item_id][$option_id])) {
                $serialized_new_options = (isset($categories_data) && count($categories_data) > 0) ? serialize($categories_data) : '';

                $items = $cart[$item_id][$option_id];

                if (count($items) > 0) {
                    $update = 0;
                    foreach ($items as $key => $item) {
                        $serialized_old_options = (isset($item['categories_data']) && !empty($item['categories_data'])) ? serialize($item['categories_data']) : '';

                        if ($serialized_old_options == $serialized_new_options) {
                            $new_amount = number_format($total_amount / $quantity, 2);
                            $quantity = $quantity + $cart[$item_id][$option_id][$key]['quantity'];
                            $total_amount = $new_amount * $quantity;
                            $total_amount_text = Currency::currency($currency)->format($total_amount);

                            $cart[$item_id][$option_id][$key] = [
                                'item_id' => $item_id,
                                'shop_id' => $shop_id,
                                'option_id' => $option_id,
                                'quantity' => $quantity,
                                'total_amount' => $total_amount,
                                'total_amount_text' => $total_amount_text,
                                'currency' => $currency,
                                'categories_data' => $categories_data,
                            ];

                            $update = 1;
                            break;
                        }
                    }

                    if ($update == 0) {
                        $cart[$item_id][$option_id][] = [
                            'item_id' => $item_id,
                            'shop_id' => $shop_id,
                            'option_id' => $option_id,
                            'quantity' => $quantity,
                            'total_amount' => $total_amount,
                            'total_amount_text' => $total_amount_text,
                            'currency' => $currency,
                            'categories_data' => $categories_data,
                        ];
                    }
                } else {
                    $cart[$item_id][$option_id][] = [
                        'item_id' => $item_id,
                        'shop_id' => $shop_id,
                        'option_id' => $option_id,
                        'quantity' => $quantity,
                        'total_amount' => $total_amount,
                        'total_amount_text' => $total_amount_text,
                        'currency' => $currency,
                        'categories_data' => $categories_data,
                    ];
                }
            } else {
                $cart[$item_id][$option_id][] = [
                    'item_id' => $item_id,
                    'shop_id' => $shop_id,
                    'option_id' => $option_id,
                    'quantity' => $quantity,
                    'total_amount' => $total_amount,
                    'total_amount_text' => $total_amount_text,
                    'currency' => $currency,
                    'categories_data' => $categories_data,
                ];
            }

            session()->put('cart', $cart);
            session()->save();

            return response()->json([
                'success' => 1,
                'message' => 'Items has been Added to Cart',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function directAddToCart(Request $request)
    {
        $id = $request->id;
        $shop_id = $request->shop_id;
        // Shop Settings
        $shop_settings = getClientSettings($request->shop_id);

        // Shop Default Currency
        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';
        $item = Items::where('id', $id)->first();
        $item_id = $item->id;
        $quantity = 1;


        $price_arr = getItemPrice($item->id);
        $item_discount = (isset($item->discount)) ? $item->discount : 0;
        $item_discount_type = (isset($item->discount_type)) ? $item->discount_type : 'percentage';

        if(count($price_arr) > 0){
            if($item_discount > 0){
                if ($item_discount_type == 'fixed') {
                    $hidden_price = number_format($price_arr[0]->price - $item_discount, 2);
                } else {
                    $dis_per = $price_arr[0]->price * $item_discount / 100;
                    $hidden_price = number_format($price_arr[0]->price - $dis_per, 2);
                }
                $total_amount = $hidden_price;
            }else{
                 $total_amount = $price_arr[0]->price;
            }
            $total_amount_text = Currency::currency($currency)->format($total_amount);
        }
        $option_id = isset($price_arr[0]->id) ?  $price_arr[0]->id : '';

        $categories_data = [];

        try {
            $cart = session()->get('cart', []);

            if (!isset($cart[$item_id][$option_id])) {
                $cart[$item_id][$option_id][] = [
                    'item_id' => strval($item_id),
                    'shop_id' => strval($shop_id),
                    'option_id' => strval($option_id),
                    'quantity' => strval($quantity),
                    'total_amount' => strval($total_amount),
                    'total_amount_text' => strval($total_amount_text),
                    'currency' => strval($currency),
                    'categories_data'   => $categories_data,
                ];
            }
            session()->put('cart', $cart);
            session()->save();

            return response()->json([
                'success' => 1,
                'message' => 'Items has been Added to Cart',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }


    // Function for UpdateCart
    public function updateCart(Request $request)
    {
        $item_id = $request->item_id;
        $price_id = $request->price_id;
        $item_key = $request->item_key;
        $quantity = $request->quantity;
        // $old_quantity = $request->old_quantity;
        $currency = $request->currency;

        try {
            if (!is_numeric($quantity)) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please Enter a Valid Number',
                ]);
            } else {
                if ($quantity > 0) {
                    if ($quantity > 1000) {
                        return response()->json([
                            'success' => 0,
                            'message' => 'Maximum Quantity Limit is 1000!',
                        ]);
                    } else {
                        $cart = session()->get('cart', []);
                        if (isset($cart[$item_id][$price_id][$item_key])) {
                            $old_quantity = $cart[$item_id][$price_id][$item_key]['quantity'];
                            $amount = $cart[$item_id][$price_id][$item_key]['total_amount'] / $old_quantity;
                            $total_amount = $amount * $quantity;

                            $total_amount_text = Currency::currency($currency)->format($total_amount);
                            $cart[$item_id][$price_id][$item_key]['quantity'] = $quantity;
                            $cart[$item_id][$price_id][$item_key]['total_amount'] = $total_amount;
                            $cart[$item_id][$price_id][$item_key]['total_amount_text'] = $total_amount_text;

                            session()->put('cart', $cart);
                            session()->save();
                            $final_amount = 0;
                            foreach ($cart as $cart_key => $cart_data) {
                                foreach ($cart_data as $cart_val) {
                                    foreach ($cart_val as $cart_item_key => $cart_item) {
                                        $final_amount += isset($cart_item['total_amount']) ? $cart_item['total_amount'] : 0;
                                    }
                                }
                            }
                            $final_amount_text = Currency::currency($currency)->format($final_amount);
                        }

                        $total_quantity = getCartQuantity();

                        return response()->json([
                            'success' => 1,
                            'message' => 'Cart has been Updated SuccessFully...',
                            'final_amount_text' => $final_amount_text,
                            'total_amount_text' => $total_amount_text,
                            'quantity' =>$quantity,
                            'item_id' =>$request->item_id,
                            'total_quantity' => $total_quantity,
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Minumum 1 Quanity is Required!',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }


    // Function for Remove Cart Item
    public function removeCartItem(Request $request)
    {
        $item_id = $request->item_id;
        $price_id = $request->price_id;
        $item_key = $request->item_key;

        try {
            $cart = session()->get('cart', []);

            if (isset($cart[$item_id][$price_id][$item_key])) {
                unset($cart[$item_id][$price_id][$item_key]);
                session()->put('cart', $cart);
                session()->save();
            }

            return response()->json([
                'success' => 1,
                'message' => 'Item has been Removed SuccessFully...',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }


    public function editCart(Request $request)
    {

        $shop_id = $request->shop_id;

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        // Shop Settings
        $shop_settings = getClientSettings($shop_id);

        // Shop Default Currency
        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        // Shop Theme ID
        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        // Today Special Icon
        $today_special_icon = moreTranslations($shop_id, 'today_special_icon');
        $today_special_icon = (isset($today_special_icon[$current_lang_code . "_value"]) && !empty($today_special_icon[$current_lang_code . "_value"])) ? $today_special_icon[$current_lang_code . "_value"] : '';

        // Layout
        $active_layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        // Shop Details


        $shop_details = Shop::where('id', $shop_id)->first();
        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Default Today Special Image
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Column Keys
        $name_key = $current_lang_code . "_name";
        $title_key = $current_lang_code . "_title";
        $description_key = $current_lang_code . "_description";
        $calories_key = $current_lang_code . "_calories";
        $price_label_key = $current_lang_code . "_label";


        $cart = session()->get('cart', []);

        $item_id = $request->item_id;
        $price_id = $request->price_id;
        $item_key = $request->item_key;


        try {
            $html = '';

            $item = Items::where('id', $item_id)->first();

            if (isset($item)) {
                $item_image = (isset($item['image']) && !empty($item['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image'])) ? asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image']) : '';
                $item_image_detail = (isset($item['image_detail']) && !empty($item['image_detail']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image_detail'])) ? asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item['image_detail']) : '';
                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : $item['name'];
                $item_calories = (isset($item[$calories_key]) && !empty($item[$calories_key])) ? $item[$calories_key] : $item['calories'];
                $item_desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : $item['description'];
                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                $price_arr = getItemPrice($item['id']);
                $item_discount = (isset($item['discount'])) ? $item['discount'] : 0;
                $item_discount_type = (isset($item['discount_type'])) ? $item['discount_type'] : 'percentage';
                $item_delivery = (isset($item['delivery']) && $item['delivery'] == 1) ? $item['delivery'] : 0;

                $html .= '<input type="hidden" name="item_id" id="item_id" value="' . $item['id'] . '">';
                $html .= '<input type="hidden" name="shop_id" id="shop_id" value="' . $shop_id . '">';
                    $html .= '<div class="modal-body">';
                    if(!empty($item_image_detail)){
                        $html .= '<div class="item_image">';
                        $html .= '<img src="' . $item_image_detail . '" alt="" class="w-100 h-100">';
                        if ($item['as_sign'] == 1) {
                            $sign_image = asset('public/client_images/bs-icon/signature.png');
                            $html .= '<img class="is_sign tag-img position-absolute" src="' . $sign_image . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                        }
                        if ($item['is_new'] == 1) {
                            $is_new_img = asset('public/client_images/bs-icon/new.png');
                            $html .= '<img class="is_new tag-img position-absolute" src="' . $is_new_img . '" style="top:0; left:0; width:70px;">';
                        }
                        $html .= '</div>';
                    }else if(!empty($item_image))
                    {
                        $html .= '<div class="item_image">';
                        $html .= '<img src="' . $item_image . '" alt="" class="w-100 h-100">';
                        if ($item['as_sign'] == 1) {
                            $sign_image = asset('public/client_images/bs-icon/signature.png');
                            $html .= '<img class="is_sign tag-img position-absolute" src="' . $sign_image . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                        }
                        if ($item['is_new'] == 1) {
                            $is_new_img = asset('public/client_images/bs-icon/new.png');
                            $html .= '<img class="is_new tag-img position-absolute" src="' . $is_new_img . '" style="top:0; left:0; width:70px;">';
                        }
                        $html .= '</div>';
                    }else{
                        if($item['as_sign'] == 1 || $item['is_new'] == 1){
                            $html .='<div class="without_img_modal" style="height: 70px;">';
                            if ($item['as_sign'] == 1) {
                                $sign_image = asset('public/client_images/bs-icon/signature.png');
                                $html .= '<img class="is_sign tag-img position-absolute" src="' . $sign_image . '" style="top:0; left:50%; transform:translate(-50%,0); width:50px;">';
                            }
                            if ($item['is_new'] == 1) {
                                $is_new_img = asset('public/client_images/bs-icon/new.png');
                                $html .= '<img class="is_new tag-img position-absolute" src="' . $is_new_img . '" style="top:0; left:0; width:70px;">';
                            }
                            $html .='</div>';
                        }
                    }

                    $html .= '<div class="item_info">';
                    $html .= '<h3>' . $item_name . '</h3>';
                    if (!empty($item_desc)) {
                        $html .= '<p>' . $item_desc . '</p>';
                    }
                    if(count($ingrediet_arr) > 0 || $item['day_special'] == 1 || !empty($item_calories))
                    {
                        $html .='<div class="item_detail_inner_info">';
                            if (count($ingrediet_arr) > 0) {
                                $html .='<div class="item_detail_tag">';
                                foreach ($ingrediet_arr as $val) {
                                    $ingredient = getIngredientDetail($val);
                                    if (isset($ingredient['icon']) && !empty($ingredient['icon']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon'])) {
                                        $ing_icon = asset('public/client_uploads/shops/' . $shop_slug . '/ingredients/' . $ingredient['icon']);
                                        $html .= '<img src="' . $ing_icon . '" width="55px" height="55px">';
                                    }
                                }
                                $html .='</div>';
                            }
                            if ($item['day_special'] == 1) {
                                $html .='<div class="item_detail_special">';
                                if (!empty($today_special_icon) && file_exists('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon)) {
                                    $tds_icon = asset('public/client_uploads/shops/' . $shop_slug . '/today_special_icon/' . $today_special_icon);
                                    $html .= '<img  src="' . $tds_icon . '">';
                                }else{
                                    if (!empty($default_special_image)) {
                                        $html .= '<img  src="' . $default_special_image . '" alt="Special">';
                                    } else {
                                        $sp_image = asset('public/client_images/bs-icon/today_special.gif');
                                        $html .= '<img src="' . $sp_image . '">';
                                    }
                                }
                                $html .='</div>';
                            }
                            if (!empty($item_calories)) {
                                $html .='<div class="item_detail_cal">';
                                $html .= '<p><strong>Cal: </strong>' . $item_calories.'</p>';
                                $html .='</div>';
                            }
                        $html .='</div>';

                    }
                    $html .= '</div>';

                    // Options
                    $option_ids = (isset($item['options']) && !empty($item['options'])) ? unserialize($item['options']) : [];

                    if(count($option_ids) > 0){
                        $opt_display = '';
                    }else{
                        $opt_display = 'none';
                    }

                    if (count($price_arr) > 0) {
                        $html .= '<input type="hidden" name="def_currency" id="def_currency" value="' . $currency . '">';
                        $t_price = (isset($price_arr[0]->price)) ? Currency::currency($currency)->format($price_arr[0]->price) : Currency::currency($currency)->format(0.00);
                        $html .= '</div>';
                        if (count($price_arr) > 0) {
                            if (count($price_arr) > 1) {
                                $display = '';
                                $dinamic_col = 6;
                            } else {
                                // $display = 'none';
                                $display = '';
                                $dinamic_col = 12;
                            }

                            $outer_display = ($opt_display == 'none' && $display == 'none') ? 'none' : '';

                            if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1) {
                                $inputVisible = 1;
                                $lableVisible = '';
                            }else{
                                $inputVisible = 0;
                                $lableVisible = 'radio-item-disabled';
                            }


                            $html .= '<div class="igradient_box" style="display: '.$outer_display.'">';
                            $html .= '<div class="igradient_category_box" style="display:' . $display . '">';
                            $html .= '<div class="row">';
                                            foreach ($price_arr as $key => $value) {
                                                if ($item_discount > 0) {
                                                    if ($item_discount_type == 'fixed') {
                                                        $price = number_format($value['price'] - $item_discount, 2);
                                                    } else {
                                                        $price_per = $value['price'] *  $item_discount / 100;
                                                        $price = number_format($value['price'] - $price_per, 2);
                                                    }
                                                } else {
                                                    $price = $value['price'];
                                                }
                                $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                $html .= '<div class="col-md-'.$dinamic_col.'">';
                                    $html .= '<div class="radio-item '.$lableVisible.'">';

                                        if ($inputVisible == 1) {
                                            $html .= '<input type="radio" name="base_price" onchange="updatePrice()" value="' . $price . '" id="base_price_' . $key . '"';
                                            if ($key == 0) {
                                                $html .= 'checked';
                                            }
                                            if ($value['id'] == $cart[$item_id][$price_id][$item_key]['option_id']) {
                                                $html .= ' checked'; // Check if the option-id matches the one in the cart
                                            }
                                            $html .= ' option-id="' . $value['id'] . '">';
                                        }

                                        $html .= '<label for="base_price_' . $key . '">';
                                            $html .= '<span>' . $price_label . '</span>';
                                            $html .= '<span>' . Currency::currency($currency)->format($price) . '</span>';
                                        $html .= '</label>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            }
                            $html .= '</div>';
                            $html .= '</div>';

                        }

                            if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1) {

                                $html .= "<input type='hidden' name='option_ids' id='option_ids' value='" . json_encode($option_ids, TRUE) . "'>";

                                    if (count($option_ids) > 0) {
                                        foreach ($option_ids as $outer_key => $opt_id) {
                                            $html .= '<div class="igradient_category_box">';
                                            $html .= '<div class="row" id="option_' . $outer_key . '">';
                                            $opt_dt = Option::with(['optionPrices'])->where('id', $opt_id)->first();
                                            $enable_price = (isset($opt_dt['enabled_price'])) ? $opt_dt['enabled_price'] : '';
                                            $option_prices = (isset($opt_dt['optionPrices'])) ? $opt_dt['optionPrices'] : [];

                                            if (count($option_prices) > 0) {
                                                $html .= '<h3>' . $opt_dt[$title_key] . '</h3>';
                                                $radio_key = 0;
                                                foreach ($option_prices as $key => $option_price) {
                                                    $opt_price = Currency::currency($currency)->format($option_price['price']);
                                                    $opt_price_label = (isset($option_price[$name_key])) ? $option_price[$name_key] : "";

                                                    // Check if the option should be checked based on data in $cart
                                                    $is_checked = '';
                                                    if (isset($cart[$item_id][$price_id][$item_key]['categories_data'][$opt_id])) {
                                                        $selected_options = $cart[$item_id][$price_id][$item_key]['categories_data'][$opt_id];
                                                        if (is_array($selected_options)) {
                                                            if (in_array($option_price['id'], $selected_options)) {
                                                                $is_checked = 'checked';
                                                            }
                                                        } else {
                                                            if ($selected_options == $option_price['id']) {
                                                                $is_checked = 'checked';
                                                            }
                                                        }
                                                    }

                                                    if (isset($opt_dt['multiple_select']) && $opt_dt['multiple_select'] == 1) {
                                                        $html .= '<div class="col-6">';
                                                        $html .= '<div class="radio-item check">';
                                                        $html .= '<input type="checkbox" value="' . $option_price['price'] . '" name="option_price_checkbox_' . $outer_key . '" onchange="updatePrice()" id="option_price_checkbox_' . $outer_key . '_' . $key . '" class="me-2" opt_price_id="' . $option_price['id'] . '" ' . $is_checked . '>';
                                                        $html .= '<label class="form-label" for="option_price_checkbox_' . $outer_key . '_' . $key . '">';
                                                        $html .= '<span>' . $opt_price_label . '</span>';
                                                        if ($enable_price == 1) {
                                                            $html .= '<span>' . $opt_price . '</span>';
                                                        }
                                                        $html .= '</label>';
                                                        $html .= '</div>';
                                                        $html .= '</div>';
                                                    } else {
                                                        $radio_key++;
                                                        $auto_check_radio = ($radio_key == 1) ? 'checked' : '';
                                                        $html .= '<div class="col-md-6">';
                                                        $html .= '<div class="radio-item">';
                                                        $html .= '<input type="radio" value="' . $option_price['price'] . '" name="option_price_radio_' . $outer_key . '" onchange="updatePrice()" id="option_price_radio_' . $outer_key . '_' . $key . '" class="me-2" opt_price_id="' . $option_price['id'] . '" ' . $auto_check_radio . ' ' . $is_checked . '>';
                                                        $html .= '<label class="form-label" for="option_price_radio_' . $outer_key . '_' . $key . '">';
                                                        $html .= '<span>' . $opt_price_label . '</span>';
                                                        if ($enable_price == 1) {
                                                            $html .= '<span>' . $opt_price . '</span>';
                                                        }
                                                        $html .= '</label>';
                                                        $html .= '</div>';
                                                        $html .= '</div>';
                                                    }
                                                }
                                            }
                                            $html .= '</div>';
                                            $html .= '</div>';
                                        }

                                    }
                            }
                        $html .= '</div>';
                        $html .= '</div>';


                        if (isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1 && $item_delivery == 1) {
                            $html .= '<div class="modal-footer">';
                            $html .= '<div class="add_to_cart_box w-100">';
                            $html .= '<div class="row align-items-center">';
                            $html .= '<div class="col-4">';
                            $html .= '<div class="quantity_btn_group">';
                            $quantity = $cart[$item_id][$price_id][$item_key]['quantity'];
                            $html .= '<button type="button" class="btn btn-number" ' . ($quantity > 1 ? '' : 'disabled="disabled"') . ' data-type="minus" onclick="QuntityIncDec(this)" data-field="quant[1]">';
                            $html .= '<span class="fa fa-minus"></span>';
                            $html .= '</button>';
                            $html .= '<input type="text" name="quant[1]" id="quantity" onchange="QuntityIncDecOnChange(this)" class="form-control input-number" value="' . $quantity . '" min="1" max="1000" width="60">';
                            $html .= '<button type="button" onclick="QuntityIncDec(this)" class="btn  btn-number" data-type="plus" data-field="quant[1]" >';
                            $html .= '<span class="fa fa-plus"></span>';
                            $html .= '</button>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="col-4">';
                            // $html .= '<button class="btn add_to_cart_btn_modal btn-success w-100">Add to Cart</button>';
                            $html .= '<a class="btn add_to_cart_btn_modal btn-success w-100" onclick="updateToCart(' . $item_id . ', ' . $price_id . ', \'' . $item_key . '\')"><i class="bi bi-cart4"></i> ' . __('Update') . '</a>';


                            $html .= '</div>';
                            $html .='<div class="col-4">';
                            if (count($price_arr) > 0) {
                                $html .= '<div class="item_price text-center">';
                                $html .= '<input type="hidden" name="def_currency" id="def_currency" value="' . $currency . '">';
                            $t_price = (isset($price_arr[0]->price)) ? Currency::currency($currency)->format($price_arr[0]->price) : Currency::currency($currency)->format(0.00);
                            $html .= '<h4 id="total_price">' . $t_price . '</h4>';
                            if ($item_discount > 0) {
                                    if ($item_discount_type == 'fixed') {
                                        $hidden_price = number_format($price_arr[0]->price - $item_discount, 2);
                                    } else {
                                        $dis_per = $price_arr[0]->price * $item_discount / 100;
                                        $hidden_price = number_format($price_arr[0]->price - $dis_per, 2);
                                    }
                                    $html .= "<input type='hidden' name='total_amount' id='total_amount' value='" . $hidden_price . "'>";
                                } else {
                                    $html .= "<input type='hidden' name='total_amount' id='total_amount' value='" . $price_arr[0]->price . "'>";
                                }
                            $html .= '</div>';
                            }
                            $html .='</div>';
                            $html .= '</div>';
                            $html .= '</div>';

                        }
                    }

            }
            return response()->json([
                'success' => 1,
                'message' => 'Details has been Fetched SuccessFully...',
                'data'    => $html,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function updateItemCart(Request $request)
    {
        $itemID = $request->itemID;
        $price_id = $request->price_id;
        $item_key = $request->item_key;
        $cart_data = $request->cart_data;
        $item_id = $cart_data['item_id'];
        $quantity = $cart_data['quantity'];
        $total_amount = $cart_data['total_amount'];
        $total_amount_text = $cart_data['total_amount_text'];
        $option_id = $cart_data['option_id'];
        $shop_id = $cart_data['shop_id'];
        $currency = $cart_data['currency'];
        $categories_data =  (isset($cart_data['categories_data']) && !empty($cart_data['categories_data'])) ? json_decode($cart_data['categories_data'], true) : [];

        try {
            $cart = session()->get('cart', []);
            if (isset($cart[$itemID][$price_id][$item_key])) {
                unset($cart[$itemID][$price_id][$item_key]);
                session()->put('cart', $cart);
                session()->save();
            }

            if (isset($cart[$item_id][$option_id])) {
                $serialized_new_options = (isset($categories_data) && count($categories_data) > 0) ? serialize($categories_data) : '';

                $items = $cart[$item_id][$option_id];

                if (count($items) > 0) {
                    $update = 0;
                    foreach ($items as $key => $item) {
                        $serialized_old_options = (isset($item['categories_data']) && !empty($item['categories_data'])) ? serialize($item['categories_data']) : '';

                        if ($serialized_old_options == $serialized_new_options) {
                            $new_amount = number_format($total_amount / $quantity, 2);
                            $quantity = $quantity + $cart[$item_id][$option_id][$key]['quantity'];
                            $total_amount = $new_amount * $quantity;
                            $total_amount_text = Currency::currency($currency)->format($total_amount);

                            $cart[$item_id][$option_id][$key] = [
                                'item_id' => $item_id,
                                'shop_id' => $shop_id,
                                'option_id' => $option_id,
                                'quantity' => $quantity,
                                'total_amount' => $total_amount,
                                'total_amount_text' => $total_amount_text,
                                'currency' => $currency,
                                'categories_data' => $categories_data,
                            ];

                            $update = 1;
                            break;
                        }
                    }

                    if ($update == 0) {
                        $cart[$item_id][$option_id][] = [
                            'item_id' => $item_id,
                            'shop_id' => $shop_id,
                            'option_id' => $option_id,
                            'quantity' => $quantity,
                            'total_amount' => $total_amount,
                            'total_amount_text' => $total_amount_text,
                            'currency' => $currency,
                            'categories_data' => $categories_data,
                        ];
                    }
                } else {
                    $cart[$item_id][$option_id][] = [
                        'item_id' => $item_id,
                        'shop_id' => $shop_id,
                        'option_id' => $option_id,
                        'quantity' => $quantity,
                        'total_amount' => $total_amount,
                        'total_amount_text' => $total_amount_text,
                        'currency' => $currency,
                        'categories_data' => $categories_data,
                    ];
                }
            } else {
                $cart[$item_id][$option_id][] = [
                    'item_id' => $item_id,
                    'shop_id' => $shop_id,
                    'option_id' => $option_id,
                    'quantity' => $quantity,
                    'total_amount' => $total_amount,
                    'total_amount_text' => $total_amount_text,
                    'currency' => $currency,
                    'categories_data' => $categories_data,
                ];
            }

            session()->put('cart', $cart);
            session()->save();

            return response()->json([
                'success' => 1,
                'message' => 'Item has been Updated SuccessFully...',
            ]);


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }



    // Function for Display Cart Details
    public function viewCart($shop_slug)
    {
        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug', $shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        // Order Settings
        $order_settings = getOrderSettings($shop_id);


        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);


        if (!isset($package_permissions['ordering']) || empty($package_permissions['ordering']) || $package_permissions['ordering'] != 1) {
            session()->remove('cart');
            session()->save();
            return redirect()->route('restaurant', $shop_slug);
        }

        $discount_per = (isset($order_settings['discount_percentage']) && ($order_settings['discount_percentage'] > 0)) ? $order_settings['discount_percentage'] : 0;
        $discount_type = (isset($order_settings['discount_type'])) ? $order_settings['discount_type'] : 'percentage';
        session()->put('discount_per', $discount_per);
        session()->put('discount_type', $discount_type);
        session()->save();

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Get all Additional Language of Shop
        $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id', $shop_id)->where('published', 1)->get();

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        $data['cart'] = session()->get('cart', []);
        if (count($data['cart']) > 0) {
            return view('shop.view_cart', $data);
        } else {
            return redirect()->route('restaurant', $shop_slug);
        }
    }


    // Set Checkout Type
    public function setCheckoutType(Request $request)
    {


        $request->validate([
            'check_type' => 'required|not_in:0',
        ]);
        $checkout_type = $request->check_type;
        if($checkout_type == 'room_delivery'){
            $request->validate([
                'room' => 'required',
            ]);
        }
        if($checkout_type == 'table_service'){
            $request->validate([
                'table_no' => 'required',
            ]);
        }
        $tableId = isset($request->table_no) ? $request->table_no : '';
        if($tableId){
            $table = ShopTable::where('id',$tableId)->first();
        }
        try {
            session()->put('checkout_type', $checkout_type);
            if($tableId){
                session()->put('table',$table->table_name);
            }
            session()->put('instructions',$request->instructions);

            session()->save();
            return response()->json([
                'success' => 1,
                "message" => "Redirecting to Checkout SuccessFully...",
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json([
                'success' => 0,
                "message" => "Internal server error!",
            ]);
        }
    }


    // Function for Redirect Checkout Page
    public function cartCheckout($shop_slug)
    {
        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug', $shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);


        if (!isset($package_permissions['ordering']) || empty($package_permissions['ordering']) || $package_permissions['ordering'] != 1) {
            session()->remove('cart');
            session()->save();
            return redirect()->route('restaurant', $shop_slug);
        }

        $order_settings = getOrderSettings($shop_id);
        $total_cart_amount = getCartTotal();

        $data['cart'] = session()->get('cart', []);

        $data['checkout_type'] = session()->get('checkout_type', '');



        $delivery_schedule = checkDeliverySchedule($shop_id);

        if ($delivery_schedule == 0) {
            $message = __("Sorry you can't order!") . " " . __("The store is closed during these hours.");
            return redirect()->route('restaurant', $shop_slug)->with('error', $message);
        }

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Get all Additional Language of Shop
        $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id', $shop_id)->where('published', 1)->get();

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        if ($data['checkout_type'] == '') {
            return redirect()->route('restaurant', $shop_slug)->with('error', 'UnAuthorized Action!');
        }

        if (count($data['cart']) > 0) {

            return view('shop.view_checkout', $data);
        } else {
            return redirect()->route('restaurant', $shop_slug);
        }
    }


    // Function for Processing Checkout
    public function checkoutProcessing($shop_slug, Request $request)
    {

        // Checkout Type & Payment Method
        $checkout_type = $request->checkout_type;
        $payment_method = $request->payment_method;
        $discount_per = session()->get('discount_per');
        $discount_type = session()->get('discount_type');
        $coupon_per = session()->get('coupon_value');
        $coupon_type = session()->get('coupon_type');
        $coupon_code = session()->get('coupon_code');
        $coupon_id = session()->get('coupon_id');
        $instructions = session()->get('instructions');


        $user_lat = $request->latitude;
        $user_lng = $request->longitude;

        if ($checkout_type == 'takeaway') {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'phone' => 'required|max:10|min:10',
            ]);
        }  elseif ($checkout_type == 'room_delivery') {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
            ]);
        } elseif ($checkout_type == 'delivery') {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'phone' => 'required|max:10|min:10',
                'address' => 'required',
                'street_number' => 'required',
            ]);
        }

        $tip = (isset($request->tip) && !empty($request->tip)) ? $request->tip : 0;

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug', $shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';
        $shop_name = isset($data['shop_details']->name) ? $data['shop_details']->name : '';
        $shop_url = (isset($data['shop_details']->shop_slug)) ? $data['shop_details']->shop_slug : '';
        $shop_url = asset($shop_url);
        $shop_name = '<a href="' . $shop_url . '">' . $shop_name . '</a>';
        $shop_logo = (isset($data['shop_details']->logo)) ? $data['shop_details']->logo : '';
        $shop_logo = '<img src="' . $shop_logo . '" width="200">';
        $shop_settings = getClientSettings($shop_id);



        // Get Shop Max ID
        $order_max = Order::where('shop_id', $shop_id)->max('order_id');
        $order_max = (isset($order_max) && !empty($order_max)) ? $order_max + 1 : 1;

        // Shop Currency
        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        $delivery_schedule = checkDeliverySchedule($shop_id);

        if ($delivery_schedule == 0) {
            return redirect()->route('restaurant', $shop_slug)->with('error', 'We are sorry the venue is no longer accepting orders.');
        }

        // Distance Alert Message
        $distance_alert_message = moreTranslations($shop_id, 'distance_alert_message');
        $distance_alert_message = (isset($distance_alert_message[$current_lang_code . "_value"]) && !empty($distance_alert_message[$current_lang_code . "_value"])) ? $distance_alert_message[$current_lang_code . "_value"] : 'Left for the minimum order';

        // Order Settings
        $order_settings = getOrderSettings($shop_id);
        $shop_latitude = (isset($order_settings['shop_latitude'])) ? $order_settings['shop_latitude'] : "";
        $shop_longitude = (isset($order_settings['shop_longitude'])) ? $order_settings['shop_longitude'] : "";
        $min_amount_for_delivery = (isset($order_settings['min_amount_for_delivery']) && !empty($order_settings['min_amount_for_delivery'])) ? unserialize($order_settings['min_amount_for_delivery']) : [];
        $total_cart_amount = getCartTotal();
        $distance = getDistance($shop_latitude, $shop_longitude, $user_lat, $user_lng);

        if ($checkout_type == 'delivery') {
            // Check Delivery Availability
            $delivey_avaialbility = checkDeliveryAvilability($shop_id, $user_lat, $user_lng);

            if ($delivey_avaialbility == 0) {
                $validator = Validator::make([], []);
                $validator->getMessageBag()->add('address', 'Sorry your address is out of our delivery range.');
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Check Min Amount
            if (count($min_amount_for_delivery) > 0) {
                foreach ($min_amount_for_delivery as $min_amt_key => $min_amount) {
                    $from = $min_amount['from'];
                    $to = $min_amount['to'];
                    $amount = $min_amount['amount'];

                    if ($distance >= $from && $distance <= $to) {
                        if ($total_cart_amount < $amount) {
                            $remain_amount = Currency::currency($currency)->format($amount - $total_cart_amount);
                            $message = "$remain_amount " . $distance_alert_message;

                            return redirect()->back()->with('error', $message);
                        }
                    }
                }
            }
        }

        if (isset($order_settings['auto_order_approval']) && $order_settings['auto_order_approval'] == 1) {
            $order_status = 'accepted';
            $is_new = 0;
        } else {
            $order_status = 'pending';
            $is_new = 1;
        }

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Get all Additional Language of Shop
        $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id', $shop_id)->where('published', 1)->get();

        // Keys
        $name_key = $current_lang_code . "_name";
        $label_key = $current_lang_code . "_label";

        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect()->route('restaurant', $shop_slug);
        }

        // Ip Address
        $user_ip = $request->ip();

        $final_amount = 0;
        $total_qty = 0;

        // Form Key
        $form_key = $current_lang_code . "_form";

        // Order Mail Template
        $mail_forms = MailForm::where('shop_id', $shop_id)->where('mail_form_key', 'orders_mail_form_client')->first();
        $orders_mail_form_client = (isset($mail_forms[$form_key])) ? $mail_forms[$form_key] : $mail_forms['en_form'];

        $shop_user = UserShop::with(['user'])->where('shop_id', $shop_id)->first();
        $contact_emails = (isset($shop_user->user['contact_emails']) && !empty($shop_user->user['contact_emails'])) ? unserialize($shop_user->user['contact_emails']) : [];

        if ($payment_method == 'cash' || $payment_method == 'cash_pos') {
            if ($checkout_type == 'takeaway') {
                // New Order
                $order = new Order();
                $order->order_id = $order_max;
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->email = $request->email;
                $order->phone = $request->phone;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->instructions = $instructions;
                $order->is_new = $is_new;
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            } elseif ($checkout_type == 'table_service') {
                // New Order
                $order = new Order();
                $order->order_id = $order_max;
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->table = $request->table;
                $order->instructions = $instructions;
                $order->is_new = $is_new;
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            } elseif ($checkout_type == 'room_delivery') {
                // New Order
                $order = new Order();
                $order->order_id = $order_max;
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->instructions = $instructions;
                $order->is_new = $is_new;
                $order->room = $request->room;
                $order->floor = $request->floor;
                $order->delivery_time = (isset($request->delivery_time)) ? $request->delivery_time : '';
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            } elseif ($checkout_type == 'delivery') {
                $latitude = isset($request->latitude) ? $request->latitude : '';
                $longitude = isset($request->longitude) ? $request->longitude : '';
                $address = isset($request->address) ? $request->address : '';
                $floor = isset($request->floor) ? $request->floor : '';
                $door_bell = isset($request->door_bell) ? $request->door_bell : '';
                $street_number = isset($request->street_number) ? $request->street_number : '';

                // New Order
                $order = new Order();
                $order->order_id = $order_max;
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->email = $request->email;
                $order->phone = $request->phone;
                $order->address = $address;
                $order->latitude = $latitude;
                $order->longitude = $longitude;
                $order->floor = $floor;
                $order->door_bell = $door_bell;
                $order->street_number = $street_number;
                $order->instructions = $instructions;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->is_new = $is_new;
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }

            $from_email = (isset($request->email)) ? $request->email : '';

            // Insert Order Items
            if ($order->id) {
                foreach ($cart as $cart_data) {
                    if (count($cart_data) > 0) {
                        foreach ($cart_data as $cart_val) {
                            if (count($cart_val) > 0) {
                                foreach ($cart_val as $cart_item) {
                                    $otpions_arr = [];
                                    // Item Details
                                    $item_details = Items::where('id', $cart_item['item_id'])->first();
                                    $item_discount = (isset($item_details['discount'])) ? $item_details['discount'] : 0;
                                    $item_discount_type = (isset($item_details['discount_type'])) ? $item_details['discount_type'] : 'percentage';
                                    $item_name = (isset($item_details[$name_key])) ? $item_details[$name_key] : '';

                                    //Price Details
                                    $price_detail = ItemPrice::where('id', $cart_item['option_id'])->first();
                                    $price_label = (isset($price_detail[$label_key])) ? $price_detail[$label_key] : '';
                                    $item_price = (isset($price_detail['price'])) ? $price_detail['price'] : 0;

                                    if ($item_discount > 0) {
                                        if ($item_discount_type == 'fixed') {
                                            $item_price = number_format($item_price - $item_discount, 2);
                                        } else {
                                            $dis_per = $item_price * $item_discount / 100;
                                            $item_price = number_format($item_price - $dis_per, 2);
                                        }
                                    }

                                    if (!empty($price_label)) {
                                        $otpions_arr[] = $price_label;
                                    }

                                    $total_amount = $cart_item['total_amount'];
                                    $total_amount_text = $cart_item['total_amount_text'];
                                    $categories_data = (isset($cart_item['categories_data']) && !empty($cart_item['categories_data'])) ? $cart_item['categories_data'] : [];

                                    $final_amount += $total_amount;
                                    $total_qty += $cart_item['quantity'];

                                    if (count($categories_data) > 0) {
                                        foreach ($categories_data as $option_id) {
                                            $my_opt = $option_id;

                                            if (is_array($my_opt)) {
                                                if (count($my_opt) > 0) {
                                                    foreach ($my_opt as $optid) {
                                                        $opt_price_dt = OptionPrice::where('id', $optid)->first();
                                                        $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                        $otpions_arr[] = $opt_price_name;
                                                    }
                                                }
                                            } else {
                                                $opt_price_dt = OptionPrice::where('id', $my_opt)->first();
                                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                                $otpions_arr[] = $opt_price_name;
                                            }
                                        }
                                    }

                                    // Order Items
                                    $order_items = new OrderItems();
                                    $order_items->shop_id = $shop_id;
                                    $order_items->order_id = $order->id;
                                    $order_items->item_id = $cart_item['item_id'];
                                    $order_items->item_name = $item_name;
                                    $order_items->item_price = $item_price;
                                    $order_items->item_price_label = $price_label;
                                    $order_items->item_qty = $cart_item['quantity'];
                                    $order_items->sub_total = $total_amount;
                                    $order_items->sub_total_text = $total_amount_text;
                                    $order_items->options = serialize($otpions_arr);
                                    $order_items->save();
                                }
                            }
                        }
                    }
                }

                $update_order = Order::find($order->id);
                $update_order->tip = 0;

                if ($discount_per > 0) {
                    if ($discount_type == 'fixed') {
                        $discount_amount = $discount_per;
                    } else {
                        $discount_amount = ($final_amount * $discount_per) / 100;
                    }
                    $update_order->discount_per = $discount_per;
                    $update_order->discount_type = $discount_type;
                    $update_order->discount_value = $final_amount - $discount_amount;
                } else {
                    $update_order->discount_value = $final_amount;
                }
                if($coupon_per > 0){
                    if($coupon_type == 'fixed') {
                        $coupon_amount = $coupon_per;
                    } else {
                        $coupon_amount = ($final_amount * $coupon_per) / 100;
                    }
                    $update_order->coupon_per = $coupon_per;
                    $update_order->coupon_type = $coupon_type;
                    $update_order->coupon_value = $final_amount - $coupon_amount;
                }else{
                    $update_order->coupon_value = $final_amount;
                }
                $update_order->order_total = $final_amount;
                $update_order->order_total_text = Currency::currency($currency)->format($final_amount);
                $update_order->total_qty = $total_qty;
                $update_order->update();

                if($coupon_per > 0){
                    $userCoupon = new UserCoupon;
                    $userCoupon->shop_id = $shop_id;
                    $userCoupon->ip_address = $request->ip();
                    $userCoupon->coupon_code = $coupon_code;
                    $userCoupon->coupon_id = $coupon_id;
                    $userCoupon->save();

                    $usercount =  ShopCoupon::find($coupon_id);
                    $total_user = $usercount->total_users;
                    $usercount->total_users = $total_user - 1;
                    $usercount->save();

                }


                // Mail Sent Functionality
                if ($checkout_type == 'takeaway' || $checkout_type == 'delivery') {
                    $order_details = Order::with(['order_items'])->where('id', $order->id)->first();
                    $order_items = (isset($order_details->order_items) && count($order_details->order_items) > 0) ? $order_details->order_items : [];

                    // Sent Mail to Shop Owner
                    if (count($contact_emails) > 0 && !empty($orders_mail_form_client)) {
                        foreach ($contact_emails as $mail) {
                            $to = $mail;
                            $subject = "New Order";
                            $fname = (isset($request->firstname)) ? $request->firstname : '';
                            $lname = (isset($request->lastname)) ? $request->lastname : '';

                            $message = $orders_mail_form_client;
                            $message = str_replace('{shop_logo}', $shop_logo, $message);
                            $message = str_replace('{shop_name}', $shop_name, $message);
                            $message = str_replace('{firstname}', $fname, $message);
                            $message = str_replace('{lastname}', $lname, $message);
                            $message = str_replace('{order_id}', $order->order_id, $message);
                            $message = str_replace('{order_type}', $checkout_type, $message);
                            $message = str_replace('{payment_method}', $payment_method, $message);

                            // Order Items
                            $order_html  = "";
                            $order_html .= '<div>';
                            $order_html .= '<table style="width:100%; border:1px solid gray;border-collapse: collapse;">';
                            $order_html .= '<thead style="background:lightgray; color:white">';
                            $order_html .= '<tr style="text-transform: uppercase!important;    font-weight: 700!important;">';
                            $order_html .= '<th style="text-align: left!important;width: 60%;padding:10px">Item</th>';
                            $order_html .= '<th style="text-align: center!important;padding:10px">Qty.</th>';
                            $order_html .= '<th style="text-align: right!important;padding:10px">Item Total</th>';
                            $order_html .= '</tr>';
                            $order_html .= '</thead>';
                            $order_html .= '<tbody style="font-weight: 600!important;">';

                            if (count($order_items) > 0) {
                                foreach ($order_items as $order_item) {
                                    $item_dt = itemDetails($order_item['item_id']);
                                    $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_dt['image'])) ? asset('public/client_uploads/shops/' . $shop_slug . '/items/' . $item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                                    $options_array = (isset($order_item['options']) && !empty($order_item['options'])) ? unserialize($order_item['options']) : '';
                                    if (count($options_array) > 0) {
                                        $options_array = implode(', ', $options_array);
                                    }

                                    $order_html .= '<tr>';

                                    $order_html .= '<td style="text-align: left!important;padding:10px; border-bottom:1px solid gray;">';
                                    $order_html .= '<div style="align-items: center!important;display: flex!important;">';
                                    $order_html .= '<a style="display: inline-block;
                                                            flex-shrink: 0;position: relative;border-radius: 0.75rem;">';
                                    $order_html .= '<span style="width: 50px;
                                                                height: 50px;display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                font-weight: 500;background-repeat: no-repeat;
                                                                background-position: center center;
                                                                background-size: cover;
                                                                border-radius: 0.75rem; background-image:url(' . $item_image . ')"></span>';
                                    $order_html .= '</a>';
                                    $order_html .= '<div style="display: block;    margin-left: 3rem!important;">';
                                    $order_html .= '<a style="font-weight: 700!important;color: #7e8299;
                                                                ">' . $order_item->item_name . '</a>';

                                    if (!empty($options_array)) {
                                        $order_html .= '<div style="color: #a19e9e;display: block;">' . $options_array . '</div>';
                                    } else {
                                        $order_html .= '<div style="color: #a19e9e;display: block;"></div>';
                                    }

                                    $order_html .= '</div>';
                                    $order_html .= '</div>';
                                    $order_html .= '</td>';

                                    $order_html .= '<td style="text-align: center!important;padding:10px; border-bottom:1px solid gray;">';
                                    $order_html .= $order_item['item_qty'];
                                    $order_html .= '</td>';

                                    $order_html .= '<td style="text-align: right!important;padding:10px; border-bottom:1px solid gray;">';
                                    $order_html .= $order_item['sub_total_text'];
                                    $order_html .= '</td>';

                                    $order_html .= '</tr>';
                                }
                            }

                            $order_html .= '</tbody>';
                            $order_html .= '</table>';
                            $order_html .= '</div>';
                            $message = str_replace('{items}', $order_html, $message);

                            // Order Total
                            $order_tot_amount = $order_details->order_total;
                            $order_total_html = "";
                            $order_total_html .= '<div>';
                            $order_total_html .= '<table style="width:50%; border:1px solid gray;border-collapse: collapse;">';
                            $order_total_html .= '<tbody style="font-weight: 700!important;">';
                            $order_total_html .= '<tr>';
                            $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Sub Total : </td>';
                            $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">' . Currency::currency($currency)->format($order_tot_amount) . '</td>';
                            $order_total_html .= '</tr>';

                            if ($order_details->discount_per > 0) {
                                $order_total_html .= '<tr>';
                                $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Discount : </td>';
                                if ($discount_type == 'fixed') {
                                    $discount_amount = $order_details->discount_per;
                                    $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">- ' . Currency::currency($currency)->format($order_details->discount_per) . '</td>';
                                } else {
                                    $discount_amount = ($order_tot_amount * $order_details->discount_per) / 100;
                                    $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">- ' . $order_details->discount_per . '%</td>';
                                }
                                $order_tot_amount = $order_tot_amount - $discount_amount;
                                $order_total_html .= '</tr>';
                            }

                            if (($order_details->payment_method == 'paypal' || $order_details->payment_method == 'every_pay') && $order_details->tip > 0) {
                                $order_tot_amount = $order_tot_amount + $order_details->tip;
                                $order_total_html .= '<tr>';
                                $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Tip : </td>';
                                $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">+ ' . Currency::currency($currency)->format($order_details->tip) . '</td>';
                                $order_total_html .= '</tr>';
                            }

                            $order_total_html .= '<tr>';
                            $order_total_html .= '<td style="padding:10px;">Total : </td>';
                            $order_total_html .= '<td style="padding:10px;">';
                            $order_total_html .= Currency::currency($currency)->format($order_tot_amount);
                            $order_total_html .= '</td>';
                            $order_total_html .= '</tr>';

                            $order_total_html .= '</tbody>';
                            $order_total_html .= '</table>';
                            $order_total_html .= '</div>';

                            $message = str_replace('{total}', $order_total_html, $message);

                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                            // More headers
                            $headers .= 'From: <' . $from_email . '>' . "\r\n";

                            mail($to, $subject, $message, $headers);
                        }
                    }
                }
            }

            session()->forget('cart');
            session()->forget('checkout_type');
            session()->forget('discount_per');
            session()->forget('discount_type');
            session()->forget('coupon_value');
            session()->forget('coupon_type');
            session()->forget('cust_lat');
            session()->forget('cust_long');
            session()->forget('cust_address');
            session()->forget('cust_street');
            session()->forget('room_no');
            session()->forget('floor');
            session()->forget('table');
            session()->forget('instructions');
            session()->save();

            return redirect()->route('shop.checkout.success', [$shop_slug, encrypt($order->id)]);
        } elseif ($payment_method == 'paypal') {
            session()->put('order_details', $request->all());
            session()->save();
            return redirect()->route('paypal.payment', $shop_slug);
        } elseif ($payment_method == 'every_pay') {
            session()->put('order_details', $request->all());
            session()->save();
            return redirect()->route('everypay.checkout.view', $shop_slug);
        }
    }


    // Function for redirect Checkout Success
    public function checkoutSuccess($shop_slug, $orderID)
    {
        try {
            $order_id = decrypt($orderID);

            $data['order_details'] = Order::where('id', $order_id)->first();

            if (empty($data['order_details'])) {
                return redirect()->route('restaurant', $shop_slug);
            }

            // Shop Details
            $data['shop_details'] = Shop::where('shop_slug', $shop_slug)->first();

            // Shop ID
            $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

            // Primary Language Details
            $language_setting = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
            $data['primary_language_details'] = getLangDetails($primary_lang_id);

            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id', $shop_id)->where('published', 1)->get();

            // Current Languge Code
            $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

            return view('shop.checkout_success', $data);
        } catch (\Throwable $th) {
            return redirect()->route('restaurant', $shop_slug)->with('error', 'Internal Server Error!');
        }
    }


    // Function for Check Order Status
    public function checkOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $order = Order::where('id', $order_id)->first();
        $order_status = (isset($order['order_status'])) ? $order['order_status'] : '';
        return response()->json([
            'success' => 1,
            'status' => $order_status,
        ]);
    }

    // Function for Item Review
    public function itemReview(Request $request)
    {
        try {
            $item = Items::where('id', $request->item_id)->first();

            $html = '';
            if ($item) {

                if ($item['review'] == 1) {
                    {
                        $html .='<div class="modal-body">';
                        $html .='<div class="item_info">';
                        $html .= '<h4>Item Reviews</h4>';
                        $html .= '</div>';
                        $html .='<div class="item_review_modal">';


                        $html .= '<form class="m-0 p-0" method="POST" id="reviewForm" enctype="multipart/form-data">';
                        $html .= csrf_field();
                        $html .= '<input type="hidden" name="item_id" id="item_id" value="' . $item['id'] . '">';
                        $html .= '<div class="form--group">';
                        $html .= '<div class="rate">';
                        $html .= '<input type="radio" id="star5" class="rate" name="rating" value="5"/>';
                        $html .= '<label for="star5" title="text">5 stars</label>';
                        $html .= '<input type="radio" id="star4" class="rate" name="rating" value="4"/>';
                        $html .= '<label for="star4" title="text">4 stars</label>';
                        $html .= '<input type="radio" id="star3" class="rate" name="rating" value="3" checked />';
                        $html .= '<label for="star3" title="text">3 stars</label>';
                        $html .= '<input type="radio" id="star2" class="rate" name="rating" value="2">';
                        $html .= '<label for="star2" title="text">2 stars</label>';
                        $html .= '<input type="radio" id="star1" class="rate" name="rating" value="1"/>';
                        $html .= '<label for="star1" title="text">1 star</label>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="form--group">';
                        $html .= '<input type="text" name="email_id" id="email_id" class="form-control" placeholder="Enter Your Email">';
                        $html .= '</div>';
                        $html .= '<div class="form--group">';
                        $html .= '<textarea class="form-control" name="item_review" id="item_review" rows="4" placeholder="Comment"></textarea>';
                        $html .= '</div>';
                        $html .= '<div class="form--group mb-2 mt-3 text-center">';
                        $html .= '<a class="btn  review_submit" onclick="submitItemReview()" id="btn-review"><i class="bi bi-send"></i> Submit</a>';
                        $html .= '<button class="btn  review_submit" type="button" disabled style="display:none;" id="load-btn-review">
                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                            Please Wait...
                                                        </button>';
                        $html .= '</div>';
                        $html .= '</form>';
                        $html .= '</div>';
                        $html .= '</div>';


                    }
                }else{
                    return response()->json([
                        'success' => 0,
                        'message' => 'Not permission review...',
                    ]);
                }
                return response()->json([
                    'success' => 1,
                    'message' => 'Details has been Fetched SuccessFully...',
                    'data'    => $html,
                ]);
            }


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }


    // Function for Send Item Review
    public function sendItemReview(Request $request)
    {

        $rules = [
            'item_review' => 'required',
        ];

        if (!empty($request->email_id)) {
            $rules += [
                'email_id' => 'email',
            ];
        }

        $request->validate($rules);

        try {

            $item_id = (isset($request->item_id)) ? $request->item_id : '';
            $comment = (isset($request->item_review)) ? $request->item_review : '';
            $rating = (isset($request->rating)) ? $request->rating : '';
            $email = (isset($request->email_id)) ? $request->email_id : '';

            // Item Details
            $item = Items::where('id', $item_id)->first();
            $category_ids = $item->categories->pluck('id')->toArray();

            $shop_id = (isset($item['shop_id'])) ? $item['shop_id'] : '';
            $user_ip = $request->ip();

            if ($item->id) {
                $item_review = new ItemReview();
                $item_review->shop_id = $shop_id;
                $item_review->category_id = json_encode($category_ids);
                $item_review->item_id = $item_id;
                $item_review->rating = $rating;
                $item_review->rating = $rating;
                $item_review->ip_address = $user_ip;
                $item_review->comment = $comment;
                $item_review->email = $email;
                $item_review->save();

                return response()->json([
                    'success' => 1,
                    'message' => 'Your Review has been Submitted SuccessFully...',
                ]);
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Internal Server Error!',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    // Function for Shop Service Review
    public function serviceReview(Request $request)
    {
        try {
            $shopsServies = ShopRateServies::where('shop_id', $request->shop_id)->where('status',1)->get();
            $uuid = time() . rand(1000, 9999);
            $html = '';
            if((count($shopsServies)) > 0){
                $html .='<div class="modal-body">';
                $html .='<div class="item_info">';
                $html .= '<h4>'.__('Tell us your opinion').'</h4>';
                $html .='</div>';
                $html .='<div class="item_review_modal">';
                $html .= '<form action="javascript:void(0)" class="m-0 p-0" method="POST" id="reviewForm" enctype="multipart/form-data">';
                $html .= csrf_field();
                $html .= '<input type="hidden" name="shop_id" id="shop_id" value="' . $request->shop_id . '">';
                foreach ($shopsServies as $value) {
                    // Current Languge Code
                    $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

                    $name_key = $current_lang_code . "_name";
                    $ratingName = $value->name;
                    $html .= '<div class="row align-items-center">';
                    $html .= '<div class="col-md-6 col-4">';
                    $html .='<div class="rating_service">';
                    $html .= '<h3>'.$value->$name_key.'</h3>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="col-md-6 col-8">';
                    $html .= '<div class="rate">';
                        $html .= '<input type="radio" id="'.$ratingName.'_star5" class="rate" name="ratings['.$value->id.']" value="5"/>';
                        $html .= '<label for="'.$ratingName.'_star5" title="text">5 stars</label>';
                        $html .= '<input type="radio" id="'.$ratingName.'_star4" class="rate" name="ratings['.$value->id.']" value="4"/>';
                        $html .= '<label for="'.$ratingName.'_star4" title="text">4 stars</label>';
                        $html .= '<input type="radio" id="'.$ratingName.'_star3" class="rate" name="ratings['.$value->id.']" value="3" />';
                        $html .= '<label for="'.$ratingName.'_star3" title="text">3 stars</label>';
                        $html .= '<input type="radio" id="'.$ratingName.'_star2" class="rate" name="ratings['.$value->id.']" value="2" />';
                        $html .= '<label for="'.$ratingName.'_star2" title="text">2 stars</label>';
                        $html .= '<input type="radio" id="'.$ratingName.'_star1" class="rate" name="ratings['.$value->id.']" value="1" checked />';
                        $html .= '<label for="'.$ratingName.'_star1" title="text">1 star</label>';
                        $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }
                $html .= '<input type="hidden" id="uuid" name="uuid" value="'.$uuid.'">';
                $html .= '<div class="form--group">';
                $html .= '<input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name">';
                $html .= '</div>';
                $html .= '<div class="form--group">';
                $html .= '<input type="text" name="email" id="email" class="form-control" placeholder="Enter Your Email">';
                $html .= '</div>';
                $html .= '<div class="form--group">';
                $html .= '<textarea class="form-control" name="service_review" id="service_review" rows="4" placeholder="Comment"></textarea>';
                $html .= '</div>';
                $html .= '<div class="form--group mb-2 mt-3 text-center">';
                $html .= '<a class="btn  review_submit" onclick="submitServiceReview()" id="btn-review"><i class="bi bi-send"></i> Submit</a>';
                $html .= '<button class="btn  review_submit" type="button" disabled style="display:none;" id="load-btn-review">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Please Wait...
                                                </button>';
                $html .= '</div>';
                $html .='</form>';
                $html .='</div>';
                $html .='</div>';

                return response()->json([
                    'success' => 1,
                    'message' => 'Details has been Fetched SuccessFully...',
                    'data'    => $html,
                ]);
            }

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    // Function for send Service Review
    public function sendServiceReview(Request $request)
    {


        $client_settings = getClientSettings($request->shop_id);
        $gradingEmailRequired = isset($client_settings['grading-email-required']) ? $client_settings['grading-email-required'] : '';

        if($gradingEmailRequired == 1){
            $request->validate([
                'service_review' => 'required',
                'ratings.*' => 'required|integer',
                'email' => 'required|email',
                'name'=>'required',
            ]);
        }else{
            $request->validate([
                'service_review' => 'required',
                'ratings.*' => 'required|integer',
                'email' => 'nullable|email',
                'name'=>'required',
            ]);
        }



        try {

            $user_ip = $request->ip();
            $shop_id = $request->shop_id;
            $ratings = $request->ratings;
            $service_review = $request->service_review;
            $email = $request->email;
            $uuid = $request->uuid;
            $name = $request->name;

            foreach ($ratings as $key => $value) {
                $review = new ServiceReview;
                $review->shop_id = $shop_id;
                $review->uuid = $uuid;
                $review->name = $name;
                $review->servies_id = $key;
                $review->rating = $value;
                $review->ip_address = $user_ip;
                $review->comment = $service_review;
                $review->email = $email;
                $review->save();
            }
            return response()->json([
                'success' => 1,
                'message' => 'Your Review has been Submitted SuccessFully...',
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }


    }

    public function validateCoupon(Request $request)
    {
        $couponCode = $request->input('code');
        $shopId = $request->input('shop_id');
        $cartAmount = $request->input('cart_amount');
        $user_ip = $request->ip();

        $userCouponUsing=  UserCoupon::where('ip_address',$user_ip)->where('shop_id',$shopId)->where('coupon_code',$couponCode)->first();


        // Perform validation logic here
        $coupon = ShopCoupon::where('code', $couponCode)
                    ->where('shop_id', $shopId)
                    ->where('status', 1)
                    ->where('total_users', '>', 0) // Assuming 'total_users' should be greater than 0
                    ->whereDate('end_date', '>=', now()) // Check if the expiry date is greater than or equal to the current date
                    ->first();
        if(empty($userCouponUsing)  && !is_null($coupon))
        {
            if($coupon){
                if ($coupon->min_cart_amount <= $cartAmount) {
                    // Check if the coupon exists and its properties are set
                    if ($coupon && $coupon->value > 0) {
                        $coupon_value = $coupon->value;
                        $coupon_type = $coupon->type ?? 'percentage';
                        $coupon_code = $coupon->code ?? '';
                        $coupon_id = $coupon->id ?? '';
                        session()->put('coupon_value', $coupon_value);
                        session()->put('coupon_type', $coupon_type);
                        session()->put('coupon_code',$coupon_code);
                        session()->put('coupon_id', $coupon_id);
                        session()->save();
                        return response()->json(['success' => 1, 'message' => 'Coupon Applied Successfully']);
                    } else {
                        return response()->json(['success' => 0, 'message' => 'Coupon is not valid']);
                    }
                } else {
                    return response()->json(['success' => 0, 'message' => 'Cart amount does not meet the minimum requirement']);
                }
            }else{
                return response()->json(['success' => 0, 'message' => 'Coupon is not valid']);
            }
        }else{
            return response()->json(['success' => 0, 'message' => 'Coupon is not valid']);
        }
    }

    public function removeCoupon()
    {
        try {

            session()->forget('coupon_value');
            session()->forget('coupon_type');
            session()->forget('coupon_code');
            session()->forget('coupon_id');
            session()->save();

            return response()->json([ 'success' => 1,'message' => 'Coupon Remove Successfully...']);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function getRoom(Request $request)
    {

        try {
                $room_no = $request->room;
                $shop_id = $request->shop_id;
                $data = ShopRoom::where('shop_id',$shop_id)->where('room_no',$room_no)->first();
                 session()->put('room_no', $room_no);
                 session()->put('floor',$data->floor);
                 session()->save();

            return response()->json([
                'success' => 1,
                'message' => 'Rooms has been Fetched SuccessFully...',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function mobileItemCategory(Request $request)
    {

        try {
            $shop_id = $request->shop_id;
            $categories = Category::with(['categoryImages', 'items'])->where('published', 1)->where('parent_id',null)->where('shop_id', $shop_id)->orderBy('order_key')->get();
            $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';
                 // Shop Details
                $shop_details = Shop::where('id', $shop_id)->first();

                $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';
            $html = '';

            if(count($categories) > 0)
            {
                $html .='<div class="modal-body">';
                    $html .='<div class="mob_cat_title">';
                    $html .= '<h3>'.__('Categories').'</h3>';
                    $html .='</div>';
                    $html .= '<div class="mob_cat_list">';
                    $html .='<ul>';
                        foreach ($categories as $key => $parent_category){
                            $active_parent_cat = checkCategorySchedule($parent_category->id, $parent_category->shop_id);
                            $parent_name_code = $current_lang_code . '_name';
                            $parent_nameId = str_replace(' ', '_', $parent_category->en_name);
                            $check_parent_cat_type_permission = checkCatTypePermission($parent_category->category_type, $shop_id);
                            $child_categories = getChildCategories($parent_category->id); // Assuming a function to get child categories
                            if($active_parent_cat == 1 && $check_parent_cat_type_permission == 1)
                            {
                                $html .= '<li>';
                                if ($parent_category->category_type == 'link') {
                                    $html .= '<a href="' . (isset($parent_category->link_url) && !empty($parent_category->link_url) ? $parent_category->link_url : '#') . '" target="_blank">';
                                } elseif ($parent_category->category_type == 'parent_category') {
                                    $html .= '<a href="' . route('restaurant', [$shop_slug, $parent_category->id]) . '">';
                                } else {
                                    $html .= '<a href="' . route('items.preview', [$shop_details['shop_slug'], $parent_category->id]) . '#' . $parent_nameId . '">';


                                }
                                $html .= isset($parent_category->$parent_name_code) ? $parent_category->$parent_name_code : '';
                                $html .= '</a>';
                                if(count($child_categories) > 0){
                                    $html .= '<ul class="sub_category">';
                                        foreach($child_categories as $child_category){
                                            $active_child_cat = checkCategorySchedule($child_category->id, $child_category->shop_id);
                                            $child_name_code = $current_lang_code . '_name';
                                            $child_nameId = str_replace(' ', '_', $child_category->en_name);
                                            $check_child_cat_type_permission = checkCatTypePermission($child_category->category_type, $shop_details['id']);

                                            if ($active_child_cat == 1 && $check_child_cat_type_permission == 1){
                                                $html .= '<li>';
                                                if ($child_category->category_type == 'link') {
                                                    $html .= '<a href="' . $child_category->link_url . '" target="_blank" class="cat-btn">';
                                                } elseif ($child_category->category_type == 'parent_category') {
                                                    $html .= '<a href="' . route('items.preview', [$shop_details['shop_slug'], $child_category->id]) . '" class="cat-btn';
                                                    // $html .= $child_category->id == $current_cat_id ? ' active' : '';
                                                    $html .= '">';
                                                } else {
                                                    $html .= '<a href="' . route('items.preview', [$shop_details['shop_slug'], $child_category->id]) . '">';
                                                }

                                                $html .= '- ' . (isset($child_category->$child_name_code) ? $child_category->$child_name_code : '') . '</a>';
                                                $html .= '</li>';
                                            }
                                        }
                                    $html .='</ul>';
                                }
                                $html .='</li>';
                            }
                        }
                        $html .='</ul>';
                    $html .='</div>';
                $html .='</div>';
            }

            return response()->json([
                'success' => 1,
                'message' => 'Details has been Fetched SuccessFully...',
                'data'    => $html,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    public function isCover(Request $request){
        try {

            session()->put('is_cover',1);
            session()->save();

            return response()->json([
                'success' => 1,
                'message' => 'session store',
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }
}
