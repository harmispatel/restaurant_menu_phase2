<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLanguage;
use App\Models\Category;
use App\Models\CategoryProductTags;
use App\Models\CategoryVisit;
use App\Models\Clicks;
use App\Models\Items;
use App\Models\ItemsVisit;
use App\Models\LanguageSettings;
use App\Models\Shop;
use App\Models\UserVisits;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\Language;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;

class ShopController extends Controller
{

    // function for shop Preview
    public function index($slug,Request $request)
    {
        $shop_slug = $slug;

        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        $user_ip = $request->ip();

        $current_date = Carbon::now()->format('Y-m-d');

        // Enter Visitor Count
        $user_visit = UserVisits::where('shop_id',$shop_id)->where('ip_address',$user_ip)->whereDate('created_at','=',$current_date)->first();

        if(!isset($user_visit) || empty($user_visit))
        {
            $new_visit = new UserVisits();
            $new_visit->shop_id = $shop_id;
            $new_visit->ip_address = $user_ip;
            $new_visit->save();
        }

        // Count Clicks
        $clicks = Clicks::where('shop_id',$shop_id)->whereDate('created_at',$current_date)->first();
        $click_id = isset($clicks->id) ? $clicks->id : '';
        if(!empty($click_id))
        {
            $edit_click = Clicks::find($click_id);
            $total_clicks = $edit_click->total_clicks + 1;
            $edit_click->total_clicks = $total_clicks;
            $edit_click->update();
        }
        else
        {
            $new_click = new Clicks();
            $new_click->shop_id = $shop_id;
            $new_click->total_clicks = 1;
            $new_click->save();
        }

        if($data['shop_details'])
        {

            $language_setting = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
            $data['primary_language_details'] = getLangDetails($primary_lang_id);
            $primary_lang_code = isset($data['primary_language_details']->code ) ? $data['primary_language_details']->code  : 'en';

            // If Session not have locale then set primary lang locale
            if(!session()->has('locale'))
            {
                App::setLocale($primary_lang_code);
                session()->put('locale', $primary_lang_code);
                session()->save();
            }

            // Current Languge Code
            $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

            // Get all Categories of Shop
            $data['categories'] = Category::where('shop_id',$shop_id)->orderBy('order_key')->get();

            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

            return view('shop.shop',$data);
        }
        else
        {
            return redirect()->route('login');
        }
    }



    // function for shop's Items Preview
    public function itemPreview($shop_slug,$cat_id)
    {
        $current_date = Carbon::now()->format('Y-m-d');

        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        // Category Details
        $data['cat_details'] = Category::where('shop_id',$shop_id)->where('id',$cat_id)->first();

        // Count Category Visit
        $category_visit = CategoryVisit::where('category_id',$cat_id)->where('shop_id',$shop_id)->first();
        $cat_visit_id = isset($category_visit->id) ? $category_visit->id : '';

        if(!empty($cat_visit_id))
        {
            $cat_visit = CategoryVisit::find($cat_visit_id);
            $total_clicks = $cat_visit->total_clicks + 1;
            $cat_visit->total_clicks = $total_clicks;
            $cat_visit->update();
        }
        else
        {
            $new_cat_visit = new CategoryVisit();
            $new_cat_visit->shop_id = $shop_id;
            $new_cat_visit->category_id = $cat_id;
            $new_cat_visit->total_clicks = 1;
            $new_cat_visit->save();
        }


        // Count Clicks
        $clicks = Clicks::where('shop_id',$shop_id)->whereDate('created_at',$current_date)->first();
        $click_id = isset($clicks->id) ? $clicks->id : '';
        if(!empty($click_id))
        {
            $edit_click = Clicks::find($click_id);
            $total_clicks = $edit_click->total_clicks + 1;
            $edit_click->total_clicks = $total_clicks;
            $edit_click->update();
        }
        else
        {
            $new_click = new Clicks();
            $new_click->shop_id = $shop_id;
            $new_click->total_clicks = 1;
            $new_click->save();
        }

        // CategoryItem Tags
        $data['cat_tags'] = CategoryProductTags::join('tags','tags.id','category_product_tags.tag_id')->orderBy('tags.order')->where('category_id',$cat_id)->get()->unique('tag_id');

        // Get all Categories
        $data['categories'] = Category::orderBy('order_key')->where('shop_id',$shop_id)->get();

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        $data['all_items'] = Items::where('category_id',$cat_id)->orderBy('order_key')->get();

        if($data['cat_details'] && $data['shop_details'])
        {
            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

            return view('shop.item_preview',$data);
        }
        else
        {
            return redirect()->back()->with('error',"Oops, Something Went Wrong !");
        }

    }



    // Change Locale
    public function changeShopLocale(Request $request)
    {
        $lang_code = $request->lang_code;

        // If Session not have locale then set primary lang locale
        if(session()->has('locale'))
        {
            App::setLocale($lang_code);
            session()->put('locale', $lang_code);
            session()->save();
        }
        else
        {
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

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        $name_key = $current_lang_code."_name";
        $description_key = $current_lang_code."_description";
        $price_label_key = $current_lang_code."_label";

        // Shop Details
        $shop_details = Shop::where('id',$shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Shop Settings
        $shop_settings = getClientSettings($shop_id);
        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        try
        {
            $categories = Category::where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->get();
            $html = '';

            if(empty($keyword))
            {
                if(count($categories) > 0)
                {
                    $html .= '<div class="menu_list">';

                    foreach($categories as $category)
                    {
                        $category_name = (isset($category->$name_key)) ? $category->$name_key : '';

                        if(!empty($category->image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category->image))
                        {
                            $image = asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$category->image);
                        }
                        else
                        {
                            $image = asset('public/client_images/not-found/no_image_1.jpg');
                        }

                        $cat_items_url = route('items.preview',[$shop_details['shop_slug'],$category->id]);

                        $html .= '<div class="menu_list_item">';
                            $html .= '<a href="'.$cat_items_url.'">';
                                $html .= '<img src="'.$image.'" class="w-100">';
                                $html .= '<h3 class="item_name">'.$category_name.'</h3>';
                            $html .= '</img>';
                        $html .= '</div>';

                    }

                    $html .= '</div>';
                }
                else
                {
                    $html .= '<h3 class="text-center">Categories not Found.</h3>';
                }
            }
            else
            {
                $items = Items::where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->get();

                if(count($items) > 0)
                {
                    $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';
                            foreach($items as $item)
                            {
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];

                                if($item['type'] == 2)
                                {
                                    $html .= '<div class="col-md-12 mb-3">';
                                        $html .= '<div class="single_item_inr devider">';

                                            if(!empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']))
                                            {
                                                $item_divider_image = asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']);
                                                $html .= '<div class="item_image">';
                                                    $html .= '<img src="'.$item_divider_image.'">';
                                                $html .= '</div>';
                                            }

                                            $html .= '<h3>'.$item_name.'</h3>';

                                            if(count($ingrediet_arr) > 0)
                                            {
                                                $html .= '<div>';
                                                    foreach ($ingrediet_arr as $val)
                                                    {
                                                        $ingredient = getIngredientDetail($val);
                                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';

                                                        if(!empty($ing_icon) && file_exists('public/admin_uploads/ingredients/'.$ing_icon))
                                                        {
                                                            $ing_icon = asset('public/admin_uploads/ingredients/'.$ing_icon);
                                                            $html .= '<img src="'.$ing_icon.'" width="50px">';
                                                        }
                                                    }
                                                $html .= '</div>';
                                            }

                                            $html .= '<p>'.(isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "".'</p>';

                                        $html .= '</div>';
                                    $html .= '</div>';
                                }
                                else
                                {
                                    $html .= '<div class="col-md-6 col-lg-3 mb-3">';
                                        $html .= '<div class="single_item_inr devider-border" onclick="getItemDetails('.$item->id.','.$shop_id.')" style="cursor: pointer">';

                                        if(!empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']))
                                        {
                                            $item_image = asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']);
                                            $html .= '<div class="item_image">';
                                                $html .= '<img src="'.$item_image.'">';
                                            $html .= '</div>';
                                        }

                                        if($item['day_special'] == 1)
                                        {
                                            if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                            {
                                                $today_spec_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                                $html .= '<img width="170" class="mt-3" src="'.$today_spec_icon.'">';
                                            }
                                            else
                                            {
                                                if(!empty($default_special_image))
                                                {
                                                    $html .= '<img width="170" class="mt-3" src="'.$default_special_image.'">';
                                                }
                                                else
                                                {
                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                    $html .= '<img width="170" class="mt-3" src="'.$def_tds_img.'">';
                                                }
                                            }
                                        }

                                        $html .= '<h3>'.$item_name.'</h3>';

                                        if($item['is_new'] == 1)
                                        {
                                            $new_img = asset('public/client_images/bs-icon/new.png');
                                            $html .= '<img class="is_new tag-img" src="'.$new_img.'">';
                                        }

                                        if($item['as_sign'] == 1)
                                        {
                                            $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                            $html .= '<img class="is_sign tag-img" src="'.$as_sign_img.'">';
                                        }

                                        if(count($ingrediet_arr) > 0)
                                        {
                                            $html .= '<div>';
                                                foreach ($ingrediet_arr as $val)
                                                {
                                                    $ingredient = getIngredientDetail($val);
                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';

                                                    if(!empty($ing_icon) && file_exists('public/admin_uploads/ingredients/'.$ing_icon))
                                                    {
                                                        $ing_icon = asset('public/admin_uploads/ingredients/'.$ing_icon);
                                                        $html .= '<img src="'.$ing_icon.'" width="50px">';
                                                    }
                                                }
                                            $html .= '</div>';
                                        }

                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";

                                        if(strlen($desc) > 200)
                                        {
                                            $desc = substr($desc, 0, 200);
                                            $html .= '<p>'.$desc.' ... <br>
                                            <a style="cursor: pointer; color: blue">Read More</a></p>';
                                        }
                                        else
                                        {
                                            $html .= '<p>'.$desc.'</p>';
                                        }

                                        $html .= '<ul class="price_ul">';
                                            $price_arr = getItemPrice($item['id']);
                                            if(count($price_arr) > 0)
                                            {
                                                foreach ($price_arr as $key => $value)
                                                {
                                                    $price = Currency::currency($currency)->format($value['price']);
                                                    $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                                    $html .= '<li><p>'.$price_label.' <span>'.$price.'</span></p></li>';
                                                }
                                            }
                                        $html .= '</ul>';

                                        $html .= '</div>';
                                    $html .= '</div>';
                                }
                            }
                        $html .= '</div>';
                    $html .= '</row>';
                }
                else
                {
                    $html .= '<h3 class="text-center">Items not Found.</h3>';
                }
            }


            return response()->json([
                'success' => 1,
                'message' => "Categories has been retrived Successfully...",
                'data'    => $html,
            ]);

        }
        catch (\Throwable $th)
        {
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

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';
        $name_key = $current_lang_code."_name";
        $description_key = $current_lang_code."_description";
        $price_label_key = $current_lang_code."_label";

        // Shop Details
        $shop_details = Shop::where('id',$shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Shop Settings
        $shop_settings = getClientSettings($shop_id);
        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';

        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        // Today Special Icon
        $today_special_icon = isset($theme_settings['today_special_icon']) ? $theme_settings['today_special_icon'] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        try
        {
            if($tab_id == 'all' || $tab_id == 'no_tab')
            {
                $html = '';

                if($keyword == '')
                {
                    $items = Items::where("$name_key",'LIKE','%'.$keyword.'%')->where('category_id',$category_id)->get();
                }
                else
                {
                    $items = Items::where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->get();
                }

                if(count($items) > 0)
                {
                    $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';

                            foreach($items as $item)
                            {
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];

                                if($item['type'] == 2)
                                {
                                    $html .= '<div class="col-md-12 mb-3">';
                                        $html .= '<div class="single_item_inr devider">';

                                            if(!empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']))
                                            {
                                                $item_divider_image = asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']);
                                                $html .= '<div class="item_image">';
                                                    $html .= '<img src="'.$item_divider_image.'">';
                                                $html .= '</div>';
                                            }

                                            $html .= '<h3>'.$item_name.'</h3>';

                                            if(count($ingrediet_arr) > 0)
                                            {
                                                $html .= '<div>';
                                                    foreach ($ingrediet_arr as $val)
                                                    {
                                                        $ingredient = getIngredientDetail($val);
                                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';

                                                        if(!empty($ing_icon) && file_exists('public/admin_uploads/ingredients/'.$ing_icon))
                                                        {
                                                            $ing_icon = asset('public/admin_uploads/ingredients/'.$ing_icon);
                                                            $html .= '<img src="'.$ing_icon.'" width="50px">';
                                                        }
                                                    }
                                                $html .= '</div>';
                                            }

                                            $html .= '<p>'.(isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "".'</p>';

                                        $html .= '</div>';
                                    $html .= '</div>';
                                }
                                else
                                {
                                    $html .= '<div class="col-md-6 col-lg-3 mb-3">';
                                        $html .= '<div class="single_item_inr devider-border" onclick="getItemDetails('.$item->id.','.$shop_id.')" style="cursor: pointer">';

                                        if(!empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']))
                                        {
                                            $item_image = asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']);
                                            $html .= '<div class="item_image">';
                                                $html .= '<img src="'.$item_image.'">';
                                            $html .= '</div>';
                                        }

                                        if($item['day_special'] == 1)
                                        {
                                            if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                            {
                                                $today_spec_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                                $html .= '<img width="170" class="mt-3" src="'.$today_spec_icon.'">';
                                            }
                                            else
                                            {
                                                if(!empty($default_special_image))
                                                {
                                                    $html .= '<img width="170" class="mt-3" src="'.$default_special_image.'">';
                                                }
                                                else
                                                {
                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                    $html .= '<img width="170" class="mt-3" src="'.$def_tds_img.'">';
                                                }
                                            }
                                        }

                                        $html .= '<h3>'.$item_name.'</h3>';

                                        if($item['is_new'] == 1)
                                        {
                                            $new_img = asset('public/client_images/bs-icon/new.png');
                                            $html .= '<img class="is_new tag-img" src="'.$new_img.'">';
                                        }

                                        if($item['as_sign'] == 1)
                                        {
                                            $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                            $html .= '<img class="is_sign tag-img" src="'.$as_sign_img.'">';
                                        }

                                        if(count($ingrediet_arr) > 0)
                                        {
                                            $html .= '<div>';
                                                foreach ($ingrediet_arr as $val)
                                                {
                                                    $ingredient = getIngredientDetail($val);
                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';

                                                    if(!empty($ing_icon) && file_exists('public/admin_uploads/ingredients/'.$ing_icon))
                                                    {
                                                        $ing_icon = asset('public/admin_uploads/ingredients/'.$ing_icon);
                                                        $html .= '<img src="'.$ing_icon.'" width="50px">';
                                                    }
                                                }
                                            $html .= '</div>';
                                        }

                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";

                                        if(strlen($desc) > 200)
                                        {
                                            $desc = substr($desc, 0, 200);
                                            $html .= '<p>'.$desc.' ... <br>
                                            <a style="cursor: pointer; color: blue">Read More</a></p>';
                                        }
                                        else
                                        {
                                            $html .= '<p>'.$desc.'</p>';
                                        }

                                        $html .= '<ul class="price_ul">';
                                            $price_arr = getItemPrice($item['id']);
                                            if(count($price_arr) > 0)
                                            {
                                                foreach ($price_arr as $key => $value)
                                                {
                                                    $price = Currency::currency($currency)->format($value['price']);
                                                    $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                                    $html .= '<li><p>'.$price_label.' <span>'.$price.'</span></p></li>';
                                                }
                                            }
                                        $html .= '</ul>';

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
                }
                else
                {
                    $html .= '<h3 class="text-center">Items Not Found!</h3>';
                    return response()->json([
                        'success' => 1,
                        'data' => $html,
                    ]);
                }
            }
            else
            {
                $html = '';
                if($keyword == '')
                {
                    $items = CategoryProductTags::join('items','items.id','category_product_tags.item_id')->where("items.$name_key",'LIKE','%'.$keyword.'%')->where('tag_id',$tag_id)->where('category_product_tags.category_id',$category_id)->orderBy('items.order_key')->get();
                }
                else
                {
                    $items = Items::where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->get();
                }

                if(count($items) > 0)
                {
                    $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';

                            foreach($items as $item)
                            {
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];

                                if($item['type'] == 2)
                                {
                                    $html .= '<div class="col-md-12 mb-3">';
                                        $html .= '<div class="single_item_inr devider">';

                                            if(!empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']))
                                            {
                                                $item_divider_image = asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']);
                                                $html .= '<div class="item_image">';
                                                    $html .= '<img src="'.$item_divider_image.'">';
                                                $html .= '</div>';
                                            }

                                            $html .= '<h3>'.$item_name.'</h3>';

                                            if(count($ingrediet_arr) > 0)
                                            {
                                                $html .= '<div>';
                                                    foreach ($ingrediet_arr as $val)
                                                    {
                                                        $ingredient = getIngredientDetail($val);
                                                        $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';

                                                        if(!empty($ing_icon) && file_exists('public/admin_uploads/ingredients/'.$ing_icon))
                                                        {
                                                            $ing_icon = asset('public/admin_uploads/ingredients/'.$ing_icon);
                                                            $html .= '<img src="'.$ing_icon.'" width="50px">';
                                                        }
                                                    }
                                                $html .= '</div>';
                                            }

                                            $html .= '<p>'.(isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "".'</p>';

                                        $html .= '</div>';
                                    $html .= '</div>';
                                }
                                else
                                {
                                    $html .= '<div class="col-md-6 col-lg-3 mb-3">';
                                        $html .= '<div class="single_item_inr devider-border" onclick="getItemDetails('.$item->id.','.$shop_id.')" style="cursor: pointer">';

                                        if(!empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']))
                                        {
                                            $item_image = asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']);
                                            $html .= '<div class="item_image">';
                                                $html .= '<img src="'.$item_image.'">';
                                            $html .= '</div>';
                                        }

                                        if($item['day_special'] == 1)
                                        {
                                            if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                            {
                                                $today_spec_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                                $html .= '<img width="170" class="mt-3" src="'.$today_spec_icon.'">';
                                            }
                                            else
                                            {
                                                if(!empty($default_special_image))
                                                {
                                                    $html .= '<img width="170" class="mt-3" src="'.$default_special_image.'">';
                                                }
                                                else
                                                {
                                                    $def_tds_img = asset('public/client_images/bs-icon/today_special.gif');
                                                    $html .= '<img width="170" class="mt-3" src="'.$def_tds_img.'">';
                                                }
                                            }
                                        }

                                        $html .= '<h3>'.$item_name.'</h3>';

                                        if($item['is_new'] == 1)
                                        {
                                            $new_img = asset('public/client_images/bs-icon/new.png');
                                            $html .= '<img class="is_new tag-img" src="'.$new_img.'">';
                                        }

                                        if($item['as_sign'] == 1)
                                        {
                                            $as_sign_img = asset('public/client_images/bs-icon/signature.png');
                                            $html .= '<img class="is_sign tag-img" src="'.$as_sign_img.'">';
                                        }

                                        if(count($ingrediet_arr) > 0)
                                        {
                                            $html .= '<div>';
                                                foreach ($ingrediet_arr as $val)
                                                {
                                                    $ingredient = getIngredientDetail($val);
                                                    $ing_icon = isset($ingredient['icon']) ? $ingredient['icon'] : '';

                                                    if(!empty($ing_icon) && file_exists('public/admin_uploads/ingredients/'.$ing_icon))
                                                    {
                                                        $ing_icon = asset('public/admin_uploads/ingredients/'.$ing_icon);
                                                        $html .= '<img src="'.$ing_icon.'" width="50px">';
                                                    }
                                                }
                                            $html .= '</div>';
                                        }

                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";

                                        if(strlen($desc) > 200)
                                        {
                                            $html .= '<p>substr('.$desc.', 0, 200) !!}... <br>
                                            <a style="cursor: pointer; color: blue">Read More</a></p>';
                                        }
                                        else
                                        {
                                            $html .= '<p>'.$desc.'</p>';
                                        }

                                        $html .= '<ul class="price_ul">';
                                            $price_arr = getItemPrice($item['id']);
                                            if(count($price_arr) > 0)
                                            {
                                                foreach ($price_arr as $key => $value)
                                                {
                                                    $price = Currency::currency($currency)->format($value['price']);
                                                    $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                                    $html .= '<li><p>'.$price_label.' <span>'.$price.'</span></p></li>';
                                                }
                                            }
                                        $html .= '</ul>';

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
                }
                else
                {
                    $html .= '<h3 class="text-center">Items Not Found!</h3>';
                    return response()->json([
                        'success' => 1,
                        'data' => $html,
                    ]);
                }
            }
        }
        catch (\Throwable $th)
        {
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

        if($shop)
        {
            $shop_logo = isset($shop->logo) ? $shop->logo : '';
            if(!empty($shop_logo))
            {
                $new_path = str_replace(asset('/public/'),public_path(),$shop_logo);
                if(file_exists($new_path))
                {
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
        $today_special_icon = isset($theme_settings['today_special_icon']) ? $theme_settings['today_special_icon'] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();

        // Shop Details
        $shop_details = Shop::where('id',$request->shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Default Today Special Image
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Name Key
        $name_key = $current_lang_code."_name";
        // Description Key
        $description_key = $current_lang_code."_description";
        // Price Label Key
        $price_label_key = $current_lang_code."_label";
        // Item ID
        $item_id = $request->item_id;

        // Count Items Visit
        $item_visit = ItemsVisit::where('item_id',$item_id)->where('shop_id',$request->shop_id)->first();
        $item_visit_id = isset($item_visit->id) ? $item_visit->id : '';

        if(!empty($item_visit_id))
        {
            $edit_item_visit = ItemsVisit::find($item_visit_id);
            $total_clicks = $edit_item_visit->total_clicks + 1;
            $edit_item_visit->total_clicks = $total_clicks;
            $edit_item_visit->update();
        }
        else
        {
            $new_item_visit = new ItemsVisit();
            $new_item_visit->shop_id = $request->shop_id;
            $new_item_visit->item_id = $item_id;
            $new_item_visit->total_clicks = 1;
            $new_item_visit->save();
        }


        // Count Clicks
        $clicks = Clicks::where('shop_id',$request->shop_id)->whereDate('created_at',$current_date)->first();
        $click_id = isset($clicks->id) ? $clicks->id : '';
        if(!empty($click_id))
        {
            $edit_click = Clicks::find($click_id);
            $total_clicks = $edit_click->total_clicks + 1;
            $edit_click->total_clicks = $total_clicks;
            $edit_click->update();
        }
        else
        {
            $new_click = new Clicks();
            $new_click->shop_id = $request->shop_id;
            $new_click->total_clicks = 1;
            $new_click->save();
        }


        try
        {

            $html = '';

            $item = Items::where('id',$item_id)->first();

            if(isset($item))
            {
                $item_image = (isset($item['image']) && !empty($item['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item['image']) : '';
                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : $item['name'];
                $item_desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : $item['description'];
                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                $price_arr = getItemPrice($item['id']);

                if($item['as_sign'] == 1)
                {
                    $sign_image = asset('public/client_images/bs-icon/signature.png');

                    $html .= '<img class="is_sign tag-img position-absolute" src="'.$sign_image.'" style="top:0; left:50%; transform:translate(-50%,0); width:45px;">';
                }

                if($item['is_new'] == 1)
                {
                    $is_new_img = asset('public/client_images/bs-icon/new.png');

                    $html .= '<img class="is_new tag-img position-absolute" src="'.$is_new_img.'" style="top:0; left:0; width:55px;">';
                }

                $html .= '<div class="row ';
                    if($item['as_sign'] == 1 || $item['is_new'] == 1)
                    {
                        $html .= 'mt-3';
                    }
                $html .='">';

                    $html .= '<div class="col-md-12 text-center mb-2 ';
                    if($item['as_sign'] == 1 || $item['is_new'] == 1)
                    {
                        $html .= 'mt-4';
                    }
                    $html .='">';
                        $html .= '<h3>'.$item_name.'</h3>';
                    $html .= '</div>';

                    if(!empty($item_image))
                    {
                        $html .= '<div class="col-md-12 mb-2">';
                            $html .= '<img src="'.$item_image.'" class="w-100" style="max-height:400px">';
                        $html .= '</div>';
                    }

                    if($item['day_special'] == 1)
                    {
                        $html .= '<div class="col-md-12 mb-3 text-center">';
                        if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                        {
                            $tds_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                            $html .= '<img class="mt-3" src="'.$tds_icon.'">';
                        }
                        else
                        {
                            if(!empty($default_special_image))
                            {
                                $html .= '<img class="mt-3" src="'.$default_special_image.'">';
                            }
                            else
                            {
                                $sp_image = asset('public/client_images/bs-icon/today_special.gif');
                                $html .= '<img class="mt-3" src="'.$sp_image.'">';
                            }
                        }
                        $html .= '</div>';
                    }

                    if(count($ingrediet_arr) > 0)
                    {
                        $html .= '<div class="col-md-12 mb-3">';
                            $html .= '<div class="d-flex align-items-center justify-content-center">';
                                foreach ($ingrediet_arr as $val)
                                {
                                    $ingredient = getIngredientDetail($val);

                                    if(isset($ingredient['icon']) && !empty($ingredient['icon']) && file_exists('public/admin_uploads/ingredients/'.$ingredient['icon']))
                                    {
                                        $ing_icon = asset('public/admin_uploads/ingredients/'.$ingredient['icon']);
                                        $html .= '<img src="'.$ing_icon.'" width="50" height="50" style="border: 1px solid black; border-radius:100%; padding:2px;margin:0 2px;">';
                                    }
                                }
                            $html .= '</div>';
                        $html .= '</div>';
                    }

                    if(!empty($item_desc))
                    {
                        $html .= '<div class="col-md-12 text-center mt-2">';
                            $html .= $item_desc;
                        $html .= '</div>';
                    }

                    if(count($price_arr) > 0)
                    {
                        $html .= '<div class="col-md-12 mb-3">';
                            $html .= '<ul class="price_ul">';
                                foreach ($price_arr as $key => $value)
                                {
                                    $price = Currency::currency($currency)->format($value['price']);
                                    $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                    $html .= '<li>';
                                        $html .= '<p>'.$price_label.' <span>'.$price.'</span></p>';
                                    $html .= '</li>';
                                }
                            $html .= '</ul>';
                        $html .= '</div>';
                    }

                $html .= '</div>';
            }

            return response()->json([
                'success' => 1,
                'message' => 'Details has been Fetched SuccessFully...',
                'data'    => $html,
            ]);
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }

    }
}
