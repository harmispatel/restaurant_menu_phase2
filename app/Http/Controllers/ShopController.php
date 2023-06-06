<?php

namespace App\Http\Controllers;

use App\Models\{Category,CategoryProductTags,CategoryVisit,CheckIn,AdditionalLanguage,Clicks, ItemPrice, ItemReview, Items,ItemsVisit,Shop,UserShop,UserVisits,Option, OptionPrice, Order, OrderItems};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;
use Mail;
use App\Mail\CheckInMail;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{

    // function for shop Preview
    public function index($slug,$cat_id=NULL,Request $request)
    {
        $shop_slug = $slug;

        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        if(empty($shop_id))
        {
            return redirect()->route('home')->with('error','This Action is Unauthorized!');
        }

        if($cat_id != NULL && !is_numeric($cat_id))
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','This Action is Unauthorized!');
        }

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
            $data['categories'] = Category::with(['categoryImages'])->where('published',1)->where('shop_id',$shop_id)->where('parent_id',$cat_id)->orderBy('order_key')->get();

            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

            // Category ID
            $data['current_cat_id'] = $cat_id;

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

        $is_active_cat = checkCategorySchedule($cat_id,$shop_id);

        if($is_active_cat == 0)
        {
            return redirect()->route('restaurant',$shop_slug);
        }

        // Category Details
        $data['cat_details'] = Category::with(['categoryImages'])->where('shop_id',$shop_id)->where('id',$cat_id)->first();
        $cat_parent_id = isset($data['cat_details']->parent_id) ? $data['cat_details']->parent_id : null;

        // CategoryItem Tags
        $data['cat_tags'] = CategoryProductTags::join('tags','tags.id','category_product_tags.tag_id')->orderBy('tags.order')->where('category_id',$cat_id)->where('tags.shop_id',$shop_id)->get()->unique('tag_id');

        // Get all Categories
        $data['categories'] = Category::orderBy('order_key')->where('published',1)->where('shop_id',$shop_id)->where('parent_id',$cat_parent_id)->where('parent_category',0)->get();

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        $data['all_items'] = Items::where('category_id',$cat_id)->orderBy('order_key')->where('published',1)->get();

        if($data['cat_details'] && $data['shop_details'])
        {
            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

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

            $data['parent_id'] = $cat_parent_id;

            if($data['cat_details']->category_type == 'page' || $data['cat_details']->category_type == 'gallery' || $data['cat_details']->category_type == 'pdf_page' || $data['cat_details']->category_type == 'check_in')
            {
                return view('shop.page_preview',$data);
            }

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
        $current_cat_id = $request->current_cat_id;
        $categories_ids = [];

        $sub_cat = Category::select('id')->where('shop_id',$shop_id)->where('parent_id',$current_cat_id)->get();

        if(count($sub_cat) > 0)
        {
            foreach($sub_cat as $val)
            {
                $categories_ids[] = $val->id;
            }
        }

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

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        // Theme Settings
        $theme_settings = themeSettings($shop_theme_id);

        // Read More Label
        $read_more_label = (isset($theme_settings['read_more_link_label']) && !empty($theme_settings['read_more_link_label'])) ? $theme_settings['read_more_link_label'] : 'Read More';

        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        try
        {
            $categories = Category::with(['categoryImages'])->where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->where('parent_id',$current_cat_id)->where('published',1)->orderBy('order_key')->get();

            $html = '';

            if(empty($keyword))
            {
                if(count($categories) > 0)
                {
                    $html .= '<div class="menu_list">';

                    foreach($categories as $category)
                    {
                        $category_name = (isset($category->$name_key)) ? $category->$name_key : '';
                        $default_image = asset('public/client_images/not-found/no_image_1.jpg');
                        $cat_image = isset($category->categoryImages[0]['image']) ? $category->categoryImages[0]['image'] : '';
                        $thumb_image = isset($category->cover) ? $category->cover : '';
                        $active_cat = checkCategorySchedule($category->id,$category->shop_id);

                        if($category->category_type == 'product_category')
                        {
                            if(!empty($cat_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$cat_image))
                            {
                                $image = asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$cat_image);
                            }
                            else
                            {
                                $image = $default_image;
                            }
                        }
                        else
                        {
                            if(!empty($thumb_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/categories/'.$thumb_image))
                            {
                                $image = asset('public/client_uploads/shops/'.$shop_slug.'/categories/'.$thumb_image);
                            }
                            else
                            {
                                $image = $default_image;
                            }
                        }

                        if($category->category_type == 'link')
                        {
                            $cat_items_url = (isset($category->link_url) && !empty($category->link_url)) ? $category->link_url : '#';
                        }
                        elseif($category->category_type == 'parent_category')
                        {
                            $cat_items_url = route('restaurant',[$shop_details['shop_slug'],$category->id]);
                        }
                        else
                        {
                            $cat_items_url = route('items.preview',[$shop_details['shop_slug'],$category->id]);
                        }

                        if($active_cat == 1)
                        {
                            $html .= '<div class="menu_list_item">';
                                $html .= '<a href="'.$cat_items_url.'">';
                                    $html .= '<img src="'.$image.'" class="w-100">';
                                    $html .= '<h3 class="item_name">'.$category_name.'</h3>';
                                $html .= '</img>';
                            $html .= '</div>';
                        }

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
                $items = Items::where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->whereIn('category_id',$categories_ids)->where('published',1)->get();

                if(count($items) > 0)
                {
                    $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';
                            foreach($items as $item)
                            {
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                                $active_cat = checkCategorySchedule($item->category_id,$item->shop_id);

                                if($active_cat == 1)
                                {
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
                                                            $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                            if((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL)
                                                            {
                                                                if(!empty($ing_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon))
                                                                {
                                                                    $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon);
                                                                    $html .= '<img src="'.$ing_icon.'" width="60px" height="60px">';
                                                                }
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
                                                        $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                        if((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL)
                                                        {
                                                            if(!empty($ing_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon))
                                                            {
                                                                $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon);
                                                                $html .= '<img src="'.$ing_icon.'" width="60px" height="60px">';
                                                            }
                                                        }
                                                    }
                                                $html .= '</div>';
                                            }

                                            $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? $item[$description_key] : "";

                                            if(strlen(strip_tags($desc)) > 180)
                                            {
                                                $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc),150), "\n"));
                                                $html .= '<p>'.$desc.' ... <br>
                                                <a class="read-more-desc">'.$read_more_label.'</a></p>';
                                            }
                                            else
                                            {
                                                $html .= '<p>'.strip_tags($desc).'</p>';
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
        $parent_id = $request->parent_id;

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

        // Read More Label
        $read_more_label = (isset($theme_settings['read_more_link_label']) && !empty($theme_settings['read_more_link_label'])) ? $theme_settings['read_more_link_label'] : 'Read More';

        // Today Special Icon
        $today_special_icon = isset($theme_settings['today_special_icon']) ? $theme_settings['today_special_icon'] : '';

        // Admin Settings
        $admin_settings = getAdminSettings();
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        try
        {
            if($tab_id == 'all' || $tab_id == 'no_tab')
            {
                $html = '';

                if($keyword == '')
                {
                    $items = Items::where("$name_key",'LIKE','%'.$keyword.'%')->where('category_id',$category_id)->where('published',1)->get();
                }
                else
                {
                    $items = Items::whereHas('category', function($q) use ($parent_id)
                    {
                        $q->where('parent_id',$parent_id);
                    })->where("$name_key",'LIKE','%'.$keyword.'%')->where('shop_id',$shop_id)->where('published',1)->get();
                }

                if(count($items) > 0)
                {
                    $html .= '<div class="item_inr_info_sec">';
                        $html .= '<div class="row">';

                            foreach($items as $item)
                            {
                                $item_name = (isset($item[$name_key]) && !empty($item[$name_key])) ? $item[$name_key] : "";
                                $ingrediet_arr = (isset($item['ingredients']) && !empty($item['ingredients'])) ? unserialize($item['ingredients']) : [];
                                $active_cat = checkCategorySchedule($item->category_id,$item->shop_id);

                                if($active_cat == 1)
                                {
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
                                                            $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                            if((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL)
                                                            {
                                                                if(!empty($ing_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon))
                                                                {
                                                                    $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon);
                                                                    $html .= '<img src="'.$ing_icon.'" width="60px" height="60px">';
                                                                }
                                                            }
                                                        }
                                                    $html .= '</div>';
                                                }
                                                $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";
                                                $html .= '<p>'.json_decode($desc,true).'</p>';

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
                                                        $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                        if((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL)
                                                        {
                                                            if(!empty($ing_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon))
                                                            {
                                                                $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon);
                                                                $html .= '<img src="'.$ing_icon.'" width="60px" height="60px">';
                                                            }
                                                        }
                                                    }
                                                $html .= '</div>';
                                            }

                                            $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";

                                            if(strlen(strip_tags($desc)) > 180)
                                            {
                                                $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc),150), "\n"));
                                                $html .= '<p>'.$desc.' ... <br>
                                                <a class="read-more-desc">'.$read_more_label.'</a></p>';
                                            }
                                            else
                                            {
                                                $html .= '<p>'.strip_tags($desc).'</p>';
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
                                                        $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                        if((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL)
                                                        {
                                                            if(!empty($ing_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon))
                                                            {
                                                                $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon);
                                                                $html .= '<img src="'.$ing_icon.'" width="60px" height="60px">';
                                                            }
                                                        }
                                                    }
                                                $html .= '</div>';
                                            }

                                            $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";
                                            $html .= '<p>'.json_decode($desc,true).'</p>';

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
                                                    $parent_ing_id = (isset($ingredient['parent_id'])) ? $ingredient['parent_id'] : NULL;

                                                    if((isset($package_permissions['special_icons']) && !empty($package_permissions['special_icons']) && $package_permissions['special_icons'] == 1) || $parent_ing_id != NULL)
                                                    {
                                                        if(!empty($ing_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon))
                                                        {
                                                            $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ing_icon);
                                                            $html .= '<img src="'.$ing_icon.'" width="60px" height="60px">';
                                                        }
                                                    }
                                                }
                                            $html .= '</div>';
                                        }

                                        $desc = (isset($item[$description_key]) && !empty($item[$description_key])) ? html_entity_decode($item[$description_key]) : "";

                                        if(strlen(strip_tags($desc)) > 180)
                                        {
                                            $desc = substr(strip_tags($desc), 0, strpos(wordwrap(strip_tags($desc),150), "\n"));
                                            $html .= '<p>'.$desc.' ... <br>
                                                <a class="read-more-desc">'.$read_more_label.'</a></p>';
                                        }
                                        else
                                        {
                                            $html .= '<p>'.strip_tags($desc).'</p>';
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

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($request->shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        // Shop Details
        $shop_details = Shop::where('id',$request->shop_id)->first();

        $shop_slug = isset($shop_details['shop_slug']) ? $shop_details['shop_slug'] : '';

        // Default Today Special Image
        $default_special_image = (isset($admin_settings['default_special_item_image'])) ? $admin_settings['default_special_item_image'] : '';

        // Name Key
        $name_key = $current_lang_code."_name";
        // Title Key
        $title_key = $current_lang_code."_title";
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

                $html .= '<input type="hidden" name="item_id" id="item_id" value="'.$item['id'].'">';
                $html .= '<input type="hidden" name="shop_id" id="shop_id" value="'.$request->shop_id.'">';

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
                        $html .= '<div class="col-md-12 mb-2 text-center">';
                            // $html .= '<img src="'.$item_image.'" class="w-100 item-dt-img" style="max-height:400px">';
                            $html .= '<img src="'.$item_image.'" class="item-dt-img">';
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

                                    if(isset($ingredient['icon']) && !empty($ingredient['icon']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ingredient['icon']))
                                    {
                                        $ing_icon = asset('public/client_uploads/shops/'.$shop_slug.'/ingredients/'.$ingredient['icon']);
                                        $html .= '<img src="'.$ing_icon.'" width="80px" height="80px" style="border: 1px solid black; border-radius:100%; padding:2px;margin:0 2px;">';
                                    }
                                }
                            $html .= '</div>';
                        $html .= '</div>';
                    }

                    if(!empty($item_desc))
                    {
                        $html .= '<div class="col-md-12 text-center mt-2 mb-2">';
                            $html .= $item_desc;
                        $html .= '</div>';
                    }

                    if(count($price_arr) > 0)
                    {
                        $html .= '<input type="hidden" name="def_currency" id="def_currency" value="'.$currency.'">';

                        $html .= '<div class="col-md-12 text-center py-2" style="border-top: 2px solid #ccc; border-bottom: 2px solid #ccc">';
                            $t_price = (isset($price_arr[0]->price)) ? Currency::currency($currency)->format($price_arr[0]->price) : Currency::currency($currency)->format(0.00);
                            $html .= '<div><b id="total_price">'.$t_price.'</b></div>';
                            $html .= "<input type='hidden' name='total_amount' id='total_amount' value='".$price_arr[0]->price."'>";
                        $html .= '</div>';

                        if(count($price_arr) > 0)
                        {
                            $html .= '<div class="col-md-12 mt-3 cart-price">';
                                $html .= '<div class="row p-3">';
                                foreach ($price_arr as $key => $value)
                                {
                                    $price = Currency::currency($currency)->format($value['price']);
                                    $price_label = (isset($value[$price_label_key])) ? $value[$price_label_key] : "";

                                    $html .= '<div class="col-6">';
                                        $html .= '<input type="radio" name="base_price" onchange="updatePrice()" value="'.$value['price'].'" id="base_price_'.$key.'" class="me-2" ';
                                            if($key == 0)
                                            {
                                                $html .= 'checked';
                                            }
                                        $html .=' option-id="'.$value['id'].'">';
                                        $html .= '<label class="form-label" for="base_price_'.$key.'">'.$price_label.'</label>';
                                    $html .= '</div>';
                                    $html .= '<div class="col-6 text-end">';
                                        $html .= '<label class="form-label">'.$price.'</label>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                            $html .= '</div>';
                        }

                        // Options
                        $option_ids = (isset($item['options']) && !empty($item['options'])) ? unserialize($item['options']) : [];

                        $html .= "<input type='hidden' name='option_ids' id='option_ids' value='".json_encode($option_ids,TRUE)."'>";

                        if(count($option_ids) > 0)
                        {
                            $html .= '<div class="col-md-12 mb-3 cart-price">';
                                foreach($option_ids as $outer_key => $opt_id)
                                {
                                    $html .= '<div class="row p-3" id="option_'.$outer_key.'">';
                                        $opt_dt = Option::with(['optionPrices'])->where('id',$opt_id)->first();
                                        $option_prices = (isset($opt_dt['optionPrices'])) ? $opt_dt['optionPrices'] : [];

                                        if(count($option_prices) > 0)
                                        {
                                            $html .= '<div class="col-md-12">';
                                                $html .= '<b>'.$opt_dt[$title_key].'</b>';
                                            $html .= '</div>';

                                            foreach($option_prices as $key => $option_price)
                                            {
                                                $opt_price = Currency::currency($currency)->format($option_price['price']);
                                                $opt_price_label = (isset($option_price[$name_key])) ? $option_price[$name_key] : "";
                                                if(isset($opt_dt['multiple_select']) && $opt_dt['multiple_select'] == 1)
                                                {
                                                    $html .= '<div class="col-6">';
                                                        $html .= '<input type="checkbox" value="'.$option_price['price'].'" name="option_price_checkbox_'.$outer_key.'" onchange="updatePrice()" id="option_price_checkbox_'.$outer_key.'_'.$key.'" class="me-2" opt_price_id="'.$option_price['id'].'">';
                                                        $html .= '<label class="form-label" for="option_price_checkbox_'.$outer_key.'_'.$key.'">'.$opt_price_label.'</label>';
                                                    $html .= '</div>';
                                                    $html .= '<div class="col-6 text-end">';
                                                        $html .= '<label class="form-label">'.$opt_price.'</label>';
                                                    $html .= '</div>';
                                                }
                                                else
                                                {
                                                    $html .= '<div class="col-6">';
                                                        $html .= '<input type="radio" value="'.$option_price['price'].'" name="option_price_radio_'.$outer_key.'" onchange="updatePrice()" id="option_price_radio_'.$outer_key.'_'.$key.'" class="me-2" opt_price_id="'.$option_price['id'].'">';
                                                        $html .= '<label class="form-label" for="option_price_radio_'.$outer_key.'_'.$key.'">'.$opt_price_label.'</label>';
                                                    $html .= '</div>';
                                                    $html .= '<div class="col-6 text-end">';
                                                        $html .= '<label class="form-label">'.$opt_price.'</label>';
                                                    $html .= '</div>';
                                                }
                                            }
                                        }
                                    $html .= '</div>';
                                }
                            $html .= '</div>';
                        }

                        if(isset($package_permissions['ordering']) && !empty($package_permissions['ordering']) && $package_permissions['ordering'] == 1)
                        {
                            $html .= '<div class="row">';
                                $html .= '<div class="col-md-4">';
                                    $html .= '<div class="input-group" style="width:130px">';
                                        $html .= '<span class="input-group-btn">';
                                            $html .= '<button type="button" class="btn btn-danger btn-number" disabled="disabled" data-type="minus" onclick="QuntityIncDec(this)" data-field="quant[1]" style="border-radius:5px 0 0 5px">';
                                                $html .= '<span class="fa fa-minus"></span>';
                                            $html .= '</button>';
                                        $html .= '</span>';
                                        $html .= '<input type="text" name="quant[1]" id="quantity" onchange="QuntityIncDecOnChange(this)" class="form-control input-number" value="1" min="1" max="1000">';
                                        $html .= '<span class="input-group-btn">';
                                            $html .= '<button type="button" onclick="QuntityIncDec(this)" class="btn btn-success btn-number" data-type="plus" data-field="quant[1]" style="border-radius:0 5px 5px 0">';
                                                $html .= '<span class="fa fa-plus"></span>';
                                            $html .= '</button>';
                                        $html .= '</span>';
                                    $html .= '</div>';
                                $html .= '</div>';
                            $html .= '</div>';

                            $html .= '<div class="row">';
                                $html .= '<div class="col-md-12 text-center mt-3">';
                                    $html .= '<a class="btn btn-primary" onclick="addToCart('.$item['id'].')"><i class="bi bi-cart4"></i> '.__('Add to Cart').'</a>';
                                $html .= '</div>';
                            $html .= '</div>';
                        }

                    }

                    // Review Section
                    if($item['review'] == 1)
                    {
                        $html .= '<div class="col-md-12 mt-3">';
                            $html .= '<div class="row">';
                                $html .= '<form method="POST" id="reviewForm" enctype="multipart/form-data">';
                                    $html .= csrf_field();
                                    $html .= '<input type="hidden" name="item_id" id="item_id" value="'.$item['id'].'">';
                                    $html .= '<div class="col-md-12">';
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
                                    $html .= '<div class="col-md-12 mt-2">';
                                        $html .= '<input type="text" name="email_id" id="email_id" class="form-control" placeholder="Enter Your Email">';
                                    $html .= '</div>';
                                    $html .= '<div class="col-md-12 mt-2">';
                                        $html .= '<textarea class="form-control" name="item_review" id="item_review" rows="4" placeholder="Comment"></textarea>';
                                    $html .= '</div>';
                                    $html .= '<div class="col-md-12 mb-2 mt-2 text-center">';
                                        $html .= '<a class="btn btn-success" onclick="submitItemReview()" id="btn-review"><i class="bi bi-send"></i> Submit</a>';
                                        $html .= '<button class="btn btn-success" type="button" disabled style="display:none;" id="load-btn-review">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Please Wait...
                                    </button>';
                                    $html .= '</div>';
                                $html .= '</form>';
                            $html .= '</div>';
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

        $shop_details = Shop::where('id',$shop_id)->first();
        $shop_name = (isset($shop_details['name'])) ? $shop_details['name'] : '';

        $shop_user = UserShop::with(['user'])->where('shop_id',$shop_id)->first();
        $contact_emails = (isset($shop_user->user['contact_emails']) && !empty($shop_user->user['contact_emails'])) ? unserialize($shop_user->user['contact_emails']) : '';
        $client_email = (isset($shop_user->user['email']) && !empty($shop_user->user['email'])) ? $shop_user->user['email'] : '';


        $shop_settings = getClientSettings($shop_id);

        // CheckIN Mail Template
        $check_in_mail_template = (isset($shop_settings['check_in_mail_template'])) ? $shop_settings['check_in_mail_template'] : '';

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
        $data['description'] = $data['firstname'].' '.$data['lastname'].' has been check in at : '.date('d-m-Y h:i:s',strtotime($data['arrival_date']));

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

        try
        {
            if(count($contact_emails) > 0)
            {
                foreach($contact_emails as $mail)
                {
                    $to = $mail;
                    $subject = $data['subject'];

                    $message = $check_in_mail_template;
                    $message = str_replace('{shop_name}',$shop_name,$message);
                    $message = str_replace('{first_name}',$data['firstname'],$message);
                    $message = str_replace('{last_name}',$data['lastname'],$message);
                    $message = str_replace('{phone}',$data['phone'],$message);
                    $message = str_replace('{passport_no}',$data['passport'],$message);
                    $message = str_replace('{room_no}',$data['room_number'],$message);
                    $message = str_replace('{nationality}',$data['nationality'],$message);
                    $message = str_replace('{age}',$data['age'],$message);
                    $message = str_replace('{address}',$data['residence_address'],$message);
                    $message = str_replace('{arrival_date}',$data['arrival_date'],$message);
                    $message = str_replace('{departure_date}',$data['departure_date'],$message);
                    $message = str_replace('{message}',$data['description'],$message);

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <'.$from_mail.'>' . "\r\n";

                    mail($to,$subject,$message,$headers);

                    // Mail::to($mail)->send(new CheckInMail($sendData));
                    // mail($mail,$data['subject'],$data['description']);
                }
            }
            else
            {
                    $to = $client_email;
                    $subject = $data['subject'];

                    $message = $check_in_mail_template;
                    $message = str_replace('{shop_name}',$shop_name,$message);
                    $message = str_replace('{first_name}',$data['firstname'],$message);
                    $message = str_replace('{last_name}',$data['lastname'],$message);
                    $message = str_replace('{phone}',$data['phone'],$message);
                    $message = str_replace('{passport_no}',$data['passport'],$message);
                    $message = str_replace('{room_no}',$data['room_number'],$message);
                    $message = str_replace('{nationality}',$data['nationality'],$message);
                    $message = str_replace('{age}',$data['age'],$message);
                    $message = str_replace('{address}',$data['residence_address'],$message);
                    $message = str_replace('{arrival_date}',$data['arrival_date'],$message);
                    $message = str_replace('{departure_date}',$data['departure_date'],$message);
                    $message = str_replace('{message}',$data['description'],$message);

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <'.$from_mail.'>' . "\r\n";

                    mail($to,$subject,$message,$headers);
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

            return redirect()->back()->with('success','Check In SuccessFully....');

        }
        catch (\Throwable $th)
        {
            return redirect()->back()->with('error','Internal Server Error!');
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
        $categories_data =  (isset($cart_data['categories_data']) && !empty($cart_data['categories_data'])) ? json_decode($cart_data['categories_data'],true) : [];

        try
        {
            $cart = session()->get('cart', []);

            if(isset($cart[$item_id]))
            {
                $new_amount = $total_amount / $quantity;
                $quantity = $quantity + $cart[$item_id]['quantity'];
                $total_amount = $total_amount + $new_amount;
                $total_amount_text = Currency::currency($currency)->format($total_amount);

                $cart[$item_id] = [
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
            else
            {
                $cart[$item_id] = [
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

        }
        catch (\Throwable $th)
        {
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
        $quantity = $request->quantity;
        // $old_quantity = $request->old_quantity;
        $currency = $request->currency;

        try
        {
            if(!is_numeric($quantity))
            {
                return response()->json([
                    'success' => 0,
                    'message' => 'Please Enter a Valid Number',
                ]);
            }
            else
            {
                if($quantity > 0)
                {
                    if($quantity > 1000)
                    {
                        return response()->json([
                            'success' => 0,
                            'message' => 'Maximum Quantity Limit is 1000!',
                        ]);
                    }
                    else
                    {
                        $cart = session()->get('cart', []);

                        if(isset($cart[$item_id]))
                        {
                            $old_quantity = $cart[$item_id]['quantity'];
                            $amount = $cart[$item_id]['total_amount'] / $old_quantity;
                            $total_amount = $amount * $quantity;
                            $total_amount_text = Currency::currency($currency)->format($total_amount);

                            $cart[$item_id]['quantity'] = $quantity;
                            $cart[$item_id]['total_amount'] = $total_amount;
                            $cart[$item_id]['total_amount_text'] = $total_amount_text;

                            session()->put('cart', $cart);
                            session()->save();
                        }

                        return response()->json([
                            'success' => 1,
                            'message' => 'Cart has been Updated SuccessFully...',
                        ]);
                    }
                }
                else
                {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Minumum 1 Quanity is Required!',
                    ]);
                }
            }
        }
        catch (\Throwable $th)
        {
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

        try
        {
            $cart = session()->get('cart', []);

            if(isset($cart[$item_id]))
            {
                unset($cart[$item_id]);
                session()->put('cart', $cart);
                session()->save();
            }

            return response()->json([
                'success' => 1,
                'message' => 'Item has been Removed SuccessFully...',
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


    // Function for Display Cart Details
    public function viewCart($shop_slug)
    {
        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        // Order Settings
        $order_settings = getOrderSettings($shop_id);

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);


        if(!isset($package_permissions['ordering']) || empty($package_permissions['ordering']) || $package_permissions['ordering'] != 1)
        {
            session()->remove('cart');
            session()->save();
            return redirect()->route('restaurant',$shop_slug);
        }

        $discount_per = (isset($order_settings['discount_percentage']) && ($order_settings['discount_percentage'] > 0)) ? $order_settings['discount_percentage'] : 0;
        session()->put('discount_per',$discount_per);
        session()->save();

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Get all Additional Language of Shop
        $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        $data['cart'] = session()->get('cart', []);

        if(count($data['cart']) > 0)
        {
            return view('shop.view_cart',$data);
        }
        else
        {
            return redirect()->route('restaurant',$shop_slug);
        }
    }


    // Set Checkout Type
    public function setCheckoutType(Request $request)
    {
        $checkout_type = $request->check_type;

        try
        {
            session()->put('checkout_type',$checkout_type);
            session()->save();

            return response()->json([
                'success' => 1,
                "message" => "Redirecting to Checkout SuccessFully...",
            ]);
        }
        catch (\Throwable $th)
        {
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
        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

        // Get Subscription ID
        $subscription_id = getClientSubscriptionID($shop_id);

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);


        if(!isset($package_permissions['ordering']) || empty($package_permissions['ordering']) || $package_permissions['ordering'] != 1)
        {
            session()->remove('cart');
            session()->save();
            return redirect()->route('restaurant',$shop_slug);
        }

        $order_settings = getOrderSettings($shop_id);
        $min_amount_for_delivery = (isset($order_settings['min_amount_for_delivery'])) ? $order_settings['min_amount_for_delivery'] : '';
        $total_cart_amount = getCartTotal();

        $data['cart'] = session()->get('cart', []);

        $data['checkout_type'] = session()->get('checkout_type', '');

        if($data['checkout_type'] == 'delivery')
        {
            if(!empty($min_amount_for_delivery) && ($total_cart_amount < $min_amount_for_delivery))
            {
                return redirect()->back();
            }
        }

        $delivery_schedule = checkDeliverySchedule($shop_id);

        if($delivery_schedule == 0)
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','We are sorry the venue is no longer accepting orders.');
        }

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Get all Additional Language of Shop
        $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

        // Current Languge Code
        $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

        if($data['checkout_type'] == '')
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','UnAuthorized Action!');
        }

        if(count($data['cart']) > 0)
        {
            return view('shop.view_checkout',$data);
        }
        else
        {
            return redirect()->route('restaurant',$shop_slug);
        }
    }


    // Function for Processing Checkout
    public function checkoutProcessing($shop_slug, Request $request)
    {
        // Checkout Type & Payment Method
        $checkout_type = $request->checkout_type;
        $payment_method = $request->payment_method;
        $discount_per = session()->get('discount_per');

        if($checkout_type == 'takeaway')
        {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'phone' => 'required|max:10|min:10',
            ]);
        }
        elseif($checkout_type == 'table_service')
        {
            $request->validate([
                'table' => 'required',
            ]);
        }
        elseif($checkout_type == 'room_delivery')
        {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'room' => 'required',
            ]);
        }
        elseif($checkout_type == 'delivery')
        {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                'phone' => 'required|max:10|min:10',
                'address' => 'required',
            ]);
        }

        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';
        $shop_name = isset($data['shop_details']->name) ? $data['shop_details']->name : '';

        $delivery_schedule = checkDeliverySchedule($shop_id);

        if($delivery_schedule == 0)
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','We are sorry the venue is no longer accepting orders.');
        }

        // Order Settings
        $order_settings = getOrderSettings($shop_id);
        $min_amount_for_delivery = (isset($order_settings['min_amount_for_delivery'])) ? $order_settings['min_amount_for_delivery'] : '';
        $total_cart_amount = getCartTotal();

        if(isset($order_settings['auto_order_approval']) && $order_settings['auto_order_approval'] == 1)
        {
            $order_status = 'accepted';
            $is_new = 0;
        }
        else
        {
            $order_status = 'pending';
            $is_new = 1;
        }

        $shop_settings = getClientSettings($shop_id);

        // Shop Currency
        $currency = (isset($shop_settings['default_currency']) && !empty($shop_settings['default_currency'])) ? $shop_settings['default_currency'] : 'EUR';

        // Primary Language Details
        $language_setting = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
        $data['primary_language_details'] = getLangDetails($primary_lang_id);

        // Get all Additional Language of Shop
        $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

        // Current Languge Code
        $current_lang_code = (session()->has('locale')) ? session()->get('locale') : 'en';

        // Keys
        $name_key = $current_lang_code."_name";
        $label_key = $current_lang_code."_label";

        $cart = session()->get('cart', []);

        if(count($cart) == 0)
        {
            return redirect()->route('restaurant',$shop_slug);
        }

        // Ip Address
        $user_ip = $request->ip();

        $final_amount = 0;
        $total_qty = 0;

        // Order Mail Template
        $order_mail_template = (isset($shop_settings['order_mail_template'])) ? $shop_settings['order_mail_template'] : '';

        $shop_user = UserShop::with(['user'])->where('shop_id',$shop_id)->first();
        $contact_emails = (isset($shop_user->user['contact_emails']) && !empty($shop_user->user['contact_emails'])) ? unserialize($shop_user->user['contact_emails']) : '';

        if($payment_method == 'cash')
        {
            if($checkout_type == 'takeaway')
            {
                // New Order
                $order = new Order();
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->email = $request->email;
                $order->phone = $request->phone;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->is_new = $is_new;
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }
            elseif($checkout_type == 'table_service')
            {
                // New Order
                $order = new Order();
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->table = $request->table;
                $order->is_new = $is_new;
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }
            elseif($checkout_type == 'room_delivery')
            {
                // New Order
                $order = new Order();
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->is_new = $is_new;
                $order->room = $request->room;
                $order->delivery_time = (isset($request->delivery_time)) ? $request->delivery_time : '';
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }
            elseif($checkout_type == 'delivery')
            {

                if(!empty($min_amount_for_delivery) && ($total_cart_amount < $min_amount_for_delivery))
                {
                    return redirect()->route('shop.cart',$shop_slug);
                }

                $latitude = isset($request->latitude) ? $request->latitude : '';
                $longitude = isset($request->longitude) ? $request->longitude : '';
                $address = isset($request->address) ? $request->address : '';
                $floor = isset($request->floor) ? $request->floor : '';
                $door_bell = isset($request->door_bell) ? $request->door_bell : '';
                $instructions = isset($request->instructions) ? $request->instructions : '';

                $delivey_avaialbility = checkDeliveryAvilability($shop_id,$latitude,$longitude);

                if($delivey_avaialbility == 1)
                {
                    // New Order
                    $order = new Order();
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
                    $order->instructions = $instructions;
                    $order->checkout_type = $checkout_type;
                    $order->payment_method = $payment_method;
                    $order->order_status = $order_status;
                    $order->is_new = $is_new;
                    $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                    $order->save();
                }
                else
                {
                    $validator = Validator::make([], []);
                    $validator->getMessageBag()->add('address', 'Sorry your address is out of our delivery range.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $from_email = (isset($request->email)) ? $request->email : 'no-reply@gmail.com';

            // Insert Order Items
            if($order->id)
            {
                foreach($cart as $cart_val)
                {
                    $otpions_arr = [];

                    // Item Details
                    $item_details = Items::where('id',$cart_val['item_id'])->first();
                    $item_name = (isset($item_details[$name_key])) ? $item_details[$name_key] : '';

                    //Price Details
                    $price_detail = ItemPrice::where('id',$cart_val['option_id'])->first();
                    $price_label = (isset($price_detail[$label_key])) ? $price_detail[$label_key] : '';
                    $item_price = (isset($price_detail['price'])) ? $price_detail['price'] : '';

                    if(!empty($price_label))
                    {
                        $otpions_arr[] = $price_label;
                    }


                    $total_amount = $cart_val['total_amount'];
                    $total_amount_text = $cart_val['total_amount_text'];
                    $categories_data = (isset($cart_val['categories_data']) && !empty($cart_val['categories_data'])) ? $cart_val['categories_data'] : [];

                    $final_amount += $total_amount;
                    $total_qty += $cart_val['quantity'];

                    if(count($categories_data) > 0)
                    {
                        foreach($categories_data as $option_id)
                        {
                            $my_opt = $option_id;

                            if(is_array($my_opt))
                            {
                                if(count($my_opt) > 0)
                                {
                                    foreach ($my_opt as $optid)
                                    {
                                        $opt_price_dt = OptionPrice::where('id',$optid)->first();$opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                        $otpions_arr[] = $opt_price_name;
                                    }
                                }
                            }
                            else
                            {
                                $opt_price_dt = OptionPrice::where('id',$my_opt)->first();
                                $opt_price_name = (isset($opt_price_dt[$name_key])) ? $opt_price_dt[$name_key] : '';
                                $otpions_arr[] = $opt_price_name;
                            }
                        }
                    }

                    // Order Items
                    $order_items = new OrderItems();
                    $order_items->shop_id = $shop_id;
                    $order_items->order_id = $order->id;
                    $order_items->item_id = $cart_val['item_id'];
                    $order_items->item_name = $item_name;
                    $order_items->item_price = $item_price;
                    $order_items->item_price_label = $price_label;
                    $order_items->item_qty = $cart_val['quantity'];
                    $order_items->sub_total = $total_amount;
                    $order_items->sub_total_text = $total_amount_text;
                    $order_items->item_price_label = $price_label;
                    $order_items->options = serialize($otpions_arr);
                    $order_items->save();
                }

                $update_order = Order::find($order->id);
                if($discount_per > 0)
                {
                    $discount_amount = ($final_amount * $discount_per) / 100;
                    $update_order->discount_per = $discount_per;
                    $update_order->discount_value = $final_amount - $discount_amount;
                }
                $update_order->order_total = $final_amount;
                $update_order->order_total_text = Currency::currency($currency)->format($final_amount);
                $update_order->total_qty = $total_qty;
                $update_order->update();

                if(count($contact_emails) > 0)
                {
                    foreach($contact_emails as $mail)
                    {
                        $to = $mail;
                        $subject = "New Order";
                        $fname = (isset($request->firstname)) ? $request->firstname : '';
                        $lname = (isset($request->lastname)) ? $request->lastname : '';

                        $message = $order_mail_template;
                        $message = str_replace('{shop_name}',$shop_name,$message);
                        $message = str_replace('{firstname}',$fname,$message);
                        $message = str_replace('{lastname}',$lname,$message);
                        $message = str_replace('{order_id}',$order->id,$message);
                        $message = str_replace('{order_type}',$checkout_type,$message);

                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                        // More headers
                        $headers .= 'From: <'.$from_email.'>' . "\r\n";

                        mail($to,$subject,$message,$headers);

                    }
                }
            }


            session()->forget('cart');
            session()->forget('checkout_type');
            session()->forget('discount_per');
            session()->forget('cust_lat');
            session()->forget('cust_long');
            session()->forget('cust_address');
            session()->save();

            return redirect()->route('shop.checkout.success',[$shop_slug,encrypt($order->id)]);
        }
        elseif($payment_method == 'paypal')
        {
            if($checkout_type == 'delivery')
            {
                if(!empty($min_amount_for_delivery) && ($total_cart_amount < $min_amount_for_delivery))
                {
                    return redirect()->route('shop.cart',$shop_slug);
                }

                $latitude = isset($request->latitude) ? $request->latitude : '';
                $longitude = isset($request->longitude) ? $request->longitude : '';
                $delivey_avaialbility = checkDeliveryAvilability($shop_id,$latitude,$longitude);

                if($delivey_avaialbility == 0)
                {
                    $validator = Validator::make([], []);
                    $validator->getMessageBag()->add('address', 'Sorry your address is out of our delivery range.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }

            }

            session()->put('order_details',$request->all());
            session()->save();
            return redirect()->route('paypal.payment',$shop_slug);
        }
        elseif($payment_method == 'every_pay')
        {
            if($checkout_type == 'delivery')
            {
                if(!empty($min_amount_for_delivery) && ($total_cart_amount < $min_amount_for_delivery))
                {
                    return redirect()->route('shop.cart',$shop_slug);
                }

                $latitude = isset($request->latitude) ? $request->latitude : '';
                $longitude = isset($request->longitude) ? $request->longitude : '';
                $delivey_avaialbility = checkDeliveryAvilability($shop_id,$latitude,$longitude);

                if($delivey_avaialbility == 0)
                {
                    $validator = Validator::make([], []);
                    $validator->getMessageBag()->add('address', 'Sorry your address is out of our delivery range.');
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            session()->put('order_details',$request->all());
            session()->save();
            return redirect()->route('everypay.checkout.view',$shop_slug);
        }
    }


    // Function for redirect Checkout Success
    public function checkoutSuccess($shop_slug, $orderID)
    {
        try
        {
            $order_id = decrypt($orderID);

            $data['order_details'] = Order::where('id',$order_id)->first();

            if(empty($data['order_details']))
            {
                return redirect()->route('restaurant',$shop_slug);
            }

            // Shop Details
            $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

            // Shop ID
            $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

            // Primary Language Details
            $language_setting = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_setting['primary_language']) ? $language_setting['primary_language'] : '';
            $data['primary_language_details'] = getLangDetails($primary_lang_id);

            // Get all Additional Language of Shop
            $data['additional_languages'] = AdditionalLanguage::with(['language'])->where('shop_id',$shop_id)->where('published',1)->get();

            // Current Languge Code
            $data['current_lang_code'] = (session()->has('locale')) ? session()->get('locale') : 'en';

            return view('shop.checkout_success',$data);
        }
        catch (\Throwable $th)
        {
           return redirect()->route('restaurant',$shop_slug)->with('error','Internal Server Error!');
        }
    }


    // Function for Check Order Status
    public function checkOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $order = Order::where('id',$order_id)->first();
        $order_status = (isset($order['order_status'])) ? $order['order_status'] : '';
        return response()->json([
            'success' => 1,
            'status' => $order_status,
        ]);
    }


    // Function for Send Item Review
    public function sendItemReview(Request $request)
    {

        $rules = [
            'item_review' => 'required',
        ];

        if(!empty($request->email_id))
        {
            $rules += [
                'email_id' => 'email',
            ];
        }

       $request->validate($rules);

        try
        {

            $item_id = (isset($request->item_id)) ? $request->item_id : '';
            $comment = (isset($request->item_review)) ? $request->item_review : '';
            $rating = (isset($request->rating)) ? $request->rating : '';
            $email = (isset($request->email_id)) ? $request->email_id : '';

            // Item Details
            $item = Items::where('id',$item_id)->first();
            $cat_id = (isset($item['category_id'])) ? $item['category_id'] : '';
            $shop_id = (isset($item['shop_id'])) ? $item['shop_id'] : '';
            $user_ip = $request->ip();

            if($item->id)
            {
                $item_review = new ItemReview();
                $item_review->shop_id = $shop_id;
                $item_review->category_id = $cat_id;
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
            }
            else
            {
                return response()->json([
                    'success' => 0,
                    'message' => 'Internal Server Error!',
                ]);
            }

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
