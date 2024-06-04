<?php

namespace App\Http\Controllers;

use App\Models\ClientSettings;
use App\Models\Theme;
use App\Models\ThemeSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $data['shop_id'] = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $data['themes'] = Theme::where('shop_id',$data['shop_id'])->get();
        return view('client.design.theme',$data);
    }


    // Display a listing of the resource.
    public function themePrview($id)
    {

        // Subscrption ID
        $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        if(!isset($package_permissions['add_edit_clone_theme']) || empty($package_permissions['add_edit_clone_theme']) || $package_permissions['add_edit_clone_theme'] == 0)
        {
            return redirect()->route('client.dashboard')->with('error',"You have not access this Menu");
        }

        // Theme Details
        $theme = Theme::where('id',$id)->first();

        // Keys
        $keys = ([
            'desk_layout',
            'slider_effect',
            'header_color',
            'sticky_header',
            'language_bar_position',
            'logo_position',
            'search_box_position',
            'banner_position',
            'banner_type',
            'banner_slide_button',
            'banner_delay_time',
            'category_image_sider',
            'category_image_slider_delay_time',
            'background_color',
            'font_color',
            'label_color',
            'category_side',
            'social_media_icon_color',
            'category_slider_effect',
            'categories_bar_color',
            'menu_bar_font_color',
            'category_title_and_description_color',
            'price_color',
            'cart_icon_color',
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
            'theme_preview_image',
            'search_box_icon_color',
            'read_more_link_color',
            'banner_height',
            'label_color_transparency',
            'item_box_background_color',
            'item_title_color',
            'item_description_color',
            'icon_bg_color',
            'special_discount_backgound_color',
            'header_image',
            'category_view',
            'special_discount_text_color',
            'cart_animation_color',
            'modal_item_title_color',
            'modal_item_des_color',
            'modal_item_price_color',
            'modal_close_icon_color',
            'modal_close_bg_color',
            'modal_add_btn_color',
            'modal_add_btn_text_color',
            'modal_quantity_icon_color',
            'modal_quantity_bg_color',
            'modal_price_label_color',
            'modal_igradient_type_color',
            'modal_body_bg_color',
            'special_day_effect_box',
            'special_day_effect_color',
            'category_box_shadow',
            'category_box_title_icon_color',
            'category_box_text_color',
            'category_box_background',
            'category_box_title_background',
            'label_font_size',
            'modal_item_title_font_size',
            'back_arrow_bg_color',
            'back_arrow_icon_color',
            'category_box_act_txt_bg',
            'header_bg_color_opc',
            'header_effect_bg_color',
            'rating_service_name_color',
            'bar_icon_color',
            'bar_icon_bg_color',
            'cover_link_icon_color',
            'cover_link_bg_color',
            'bg_image',
            'rating_star_icon_color'
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = ThemeSettings::select('value')->where('key',$key)->where('theme_id',$id)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        return view('client.design.theme_preview',compact(['settings','theme']));
    }



    // Show the form for creating a new resource.
    public function create()
    {
        // Subscrption ID
        $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        if(!isset($package_permissions['add_edit_clone_theme']) || empty($package_permissions['add_edit_clone_theme']) || $package_permissions['add_edit_clone_theme'] == 0)
        {
            return redirect()->route('client.dashboard')->with('error',"You have not access this Menu");
        }

        return view('client.design.new-theme');
    }


    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'theme_name' => 'required',
            'theme_preview_image' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
            'header_image' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
            'bg_image' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
        ]);

        // Shop ID
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $theme_name = $request->theme_name;

        // Insert New Theme
        $theme = new Theme();
        $theme->shop_id = $shop_id;
        $theme->name = $theme_name;
        $theme->is_default = false;
        $theme->save();

        $setting_keys = [
            'desk_layout' => isset($request->desk_layout) ? $request->desk_layout : "layout_1",
            'slider_effect'=> $request->slider_effect,
            'header_color' => $request->header_color,
            'sticky_header' => isset($request->sticky_header) ? $request->sticky_header : 0,
            'language_bar_position' => $request->language_bar_position,
            'logo_position' => $request->logo_position,
            'search_box_position' => $request->search_box_position,
            'banner_position' => $request->banner_position,
            'banner_type' => $request->banner_type,
            'banner_slide_button' => isset($request->banner_slide_button) ? $request->banner_slide_button : 0,
            'banner_delay_time' => $request->banner_delay_time,
            'category_image_sider' => $request->category_image_sider,
            'category_image_slider_delay_time' => isset($request->category_image_slider_delay_time) ? $request->category_image_slider_delay_time : 1200 ,
            'background_color' => $request->background_color,
            'category_side' => $request->category_side,
            'font_color' => $request->font_color,
            'label_color' => $request->label_color,
            'social_media_icon_color' => $request->social_media_icon_color,
            'category_slider_effect' => $request->category_slider_effect,
            'categories_bar_color' => $request->categories_bar_color,
            'menu_bar_font_color' => $request->menu_bar_font_color,
            'category_title_and_description_color' => $request->category_title_and_description_color,
            'price_color' => $request->price_color,
            'cart_icon_color' => $request->cart_icon_color,
            'item_box_shadow' => isset($request->item_box_shadow) ? $request->item_box_shadow : 0,
            'item_box_shadow_color' => $request->item_box_shadow_color,
            'item_box_shadow_thickness' => $request->item_box_shadow_thickness,
            'item_divider' => $request->item_divider,
            'item_divider_color' => $request->item_divider_color,
            'item_divider_thickness' => $request->item_divider_thickness,
            'item_divider_type' => $request->item_divider_type,
            'item_divider_position' => $request->item_divider_position,
            'item_divider_font_color' => $request->item_divider_font_color,
            'tag_font_color' => $request->tag_font_color,
            'tag_label_color' => $request->tag_label_color,
            'category_bar_type' => $request->category_bar_type,
            'search_box_icon_color' => $request->search_box_icon_color,
            'read_more_link_color' => $request->read_more_link_color,
            'banner_height' => $request->banner_height,
            'label_color_transparency' => $request->label_color_transparency,
            'item_box_background_color' => $request->item_box_background_color,
            'item_title_color' => $request->item_title_color,
            'item_description_color' => $request->item_description_color,
            'special_discount_backgound_color' => $request->special_discount_backgound_color,
            'icon_bg_color' => $request->icon_bg_color,
            'category_view' => $request->category_view,
            'special_discount_text_color' => $request->special_discount_text_color,
            'cart_animation_color' => $request->cart_animation_color,
            'modal_item_title_color' => $request->modal_item_title_color,
            'modal_item_des_color' => $request->modal_item_des_color,
            'modal_item_price_color' => $request->modal_item_price_color,
            'modal_close_icon_color' => $request->modal_close_icon_color,
            'modal_close_bg_color' => $request->modal_close_bg_color,
            'modal_add_btn_color' => $request->modal_add_btn_color,
            'modal_add_btn_text_color' => $request->modal_add_btn_text_color,
            'modal_quantity_icon_color' => $request->modal_quantity_icon_color,
            'modal_quantity_bg_color' => $request->modal_quantity_bg_color,
            'modal_price_label_color' => $request->modal_price_label_color,
            'modal_igradient_type_color' => $request->modal_igradient_type_color,
            'modal_body_bg_color' => $request->modal_body_bg_color,
            'special_day_effect_box' => $request->special_day_effect_box,
            'special_day_effect_color' => $request->special_day_effect_color,
            'category_box_shadow' => $request->category_box_shadow,
            'category_box_title_icon_color' => $request->category_box_title_icon_color,
            'category_box_text_color' => $request->category_box_text_color,
            'category_box_background' => $request->category_box_background,
            'category_box_title_background' => $request->category_box_title_background,
            'label_font_size'=>$request->label_font_size,
            'modal_item_title_font_size'=> $request->modal_item_title_font_size,
            'back_arrow_bg_color'=>$request->back_arrow_bg_color,
            'back_arrow_icon_color'=>$request->back_arrow_icon_color,
            'category_box_act_txt_bg'=>$request->category_box_act_txt_bg,
            'header_bg_color_opc'=>$request->header_bg_color_opc,
            'header_effect_bg_color'=>$request->header_effect_bg_color,
            'rating_service_name_color'=>$request->rating_service_name_color,
            'bar_icon_color'=>$request->bar_icon_color,
            'bar_icon_bg_color'=>$request->bar_icon_bg_color,
            'cover_link_icon_color'=>$request->cover_link_icon_color,
            'cover_link_bg_color'=>$request->cover_link_bg_color,
            'rating_star_icon_color'=>$request->rating_star_icon_color,
        ];

        if($request->hasFile('theme_preview_image'))
        {
            $imgname = "theme_preview_image_".time().".". $request->file('theme_preview_image')->getClientOriginalExtension();
            $request->file('theme_preview_image')->move(public_path('client_uploads/shops/'.$shop_slug.'/theme_preview_image/'), $imgname);
            $setting_keys['theme_preview_image'] = $imgname;
        }

        if($request->hasFile('bg_image'))
        {
            $imgname = "bg_image".time().".". $request->file('bg_image')->getClientOriginalExtension();
            $request->file('bg_image')->move(public_path('client_uploads/shops/'.$shop_slug.'/background_image/'), $imgname);
            $setting_keys['bg_image'] = $imgname;
        }


        if($request->hasFile('header_image'))
        {
            $imgname = "header_image_".time().".". $request->file('header_image')->getClientOriginalExtension();
            $request->file('header_image')->move(public_path('client_uploads/shops/'.$shop_slug.'/header_image/'), $imgname);
            $setting_keys['header_image'] = $imgname;
        }

        if($theme->id)
        {
            foreach($setting_keys as $key => $val)
            {
                $theme_setting = new ThemeSettings();
                $theme_setting->theme_id = $theme->id;
                $theme_setting->key = $key;
                $theme_setting->value = $val;
                $theme_setting->save();
            }
        }

        return redirect()->route('design.theme')->with('success','New Theme has been Inserted SuccessFully...');

    }



    // Change Current Theme
    public function changeTheme(Request $request)
    {
        $client_id = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $theme_id = $request->theme_id;

        $query = ClientSettings::where('shop_id',$shop_id)->where('client_id',$client_id)->where('key','shop_active_theme')->first();
        $setting_id = isset($query->id) ? $query->id : '';

        if(!empty($setting_id))
        {
            // Client's Active Theme
            $active_theme = ClientSettings::find($setting_id);
            $active_theme->value = $theme_id;
            $active_theme->update();
        }
        else
        {
            $active_theme = new ClientSettings();
            $active_theme->client_id = $client_id;
            $active_theme->shop_id = $shop_id;
            $active_theme->key = 'shop_active_theme';
            $active_theme->value = $theme_id;
            $active_theme->save();
        }


        return response()->json([
            'success' => 1,
            'message' => 'Theme has been Activated SuccessFully...',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\Response
     */
    public function edit(Theme $theme)
    {
        //
    }


    // Update the specified resource in storage.
    public function update(Request $request)
    {

        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $request->validate([
            'theme_name' => 'required',
            'theme_preview_image' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
            'header_image' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
            'bg_image' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
        ]);

        // Theme ID
        $theme_id = $request->theme_id;

        // Update Theme Name
        $theme = Theme::find($theme_id);
        $theme->name = $request->theme_name;
        $theme->update();

        $setting_keys = [
            'desk_layout' => isset($request->desk_layout) ? $request->desk_layout : "layout_1",
            'slider_effect'=> $request->slider_effect,
            'header_color' => $request->header_color,
            'sticky_header' => isset($request->sticky_header) ? $request->sticky_header : 0,
            'language_bar_position' => $request->language_bar_position,
            'logo_position' => $request->logo_position,
            'search_box_position' => $request->search_box_position,
            'banner_position' => $request->banner_position,
            'banner_type' => $request->banner_type,
            'banner_slide_button' => isset($request->banner_slide_button) ? $request->banner_slide_button : 0,
            'banner_delay_time' => $request->banner_delay_time,
            'category_image_sider' => $request->category_image_sider,
            'category_image_slider_delay_time' => isset($request->category_image_slider_delay_time) ? $request->category_image_slider_delay_time : 1200 ,
            'background_color' => $request->background_color,
            'category_side' => $request->category_side,
            'font_color' => $request->font_color,
            'label_color' => $request->label_color,
            'social_media_icon_color' => $request->social_media_icon_color,
            'category_slider_effect' => $request->category_slider_effect,
            'categories_bar_color' => $request->categories_bar_color,
            'menu_bar_font_color' => $request->menu_bar_font_color,
            'category_title_and_description_color' => $request->category_title_and_description_color,
            'price_color' => $request->price_color,
            'cart_icon_color' => $request->cart_icon_color,
            'item_box_shadow' => isset($request->item_box_shadow) ? $request->item_box_shadow : 0,
            'item_box_shadow_color' => $request->item_box_shadow_color,
            'item_box_shadow_thickness' => $request->item_box_shadow_thickness,
            'item_divider' => $request->item_divider,
            'item_divider_color' => $request->item_divider_color,
            'item_divider_thickness' => $request->item_divider_thickness,
            'item_divider_type' => $request->item_divider_type,
            'item_divider_position' => $request->item_divider_position,
            'item_divider_font_color' => $request->item_divider_font_color,
            'tag_font_color' => $request->tag_font_color,
            'tag_label_color' => $request->tag_label_color,
            'category_bar_type' => $request->category_bar_type,
            'search_box_icon_color' => $request->search_box_icon_color,
            'read_more_link_color' => $request->read_more_link_color,
            'banner_height' => $request->banner_height,
            'label_color_transparency' => $request->label_color_transparency,
            'item_box_background_color' => $request->item_box_background_color,
            'item_title_color' => $request->item_title_color,
            'item_description_color' => $request->item_description_color,
            'icon_bg_color' => $request->icon_bg_color,
            'special_discount_backgound_color' => $request->special_discount_backgound_color,
            'category_view' => $request->category_view,
            'special_discount_text_color' => $request->special_discount_text_color,
            'cart_animation_color' => $request->cart_animation_color,
            'modal_item_title_color' => $request->modal_item_title_color,
            'modal_item_des_color' => $request->modal_item_des_color,
            'modal_item_price_color' => $request->modal_item_price_color,
            'modal_close_icon_color' => $request->modal_close_icon_color,
            'modal_close_bg_color' => $request->modal_close_bg_color,
            'modal_add_btn_color' => $request->modal_add_btn_color,
            'modal_add_btn_text_color' => $request->modal_add_btn_text_color,
            'modal_quantity_icon_color' => $request->modal_quantity_icon_color,
            'modal_quantity_bg_color' => $request->modal_quantity_bg_color,
            'modal_price_label_color' => $request->modal_price_label_color,
            'modal_igradient_type_color' => $request->modal_igradient_type_color,
            'modal_body_bg_color' => $request->modal_body_bg_color,
            'special_day_effect_box' => $request->special_day_effect_box,
            'special_day_effect_color' => $request->special_day_effect_color,
            'category_box_shadow' => $request->category_box_shadow,
            'category_box_title_icon_color' => $request->category_box_title_icon_color,
            'category_box_text_color' => $request->category_box_text_color,
            'category_box_background' => $request->category_box_background,
            'category_box_title_background' => $request->category_box_title_background,
            'label_font_size'=>$request->label_font_size,
            'modal_item_title_font_size'=> $request->modal_item_title_font_size,
            'back_arrow_bg_color'=>$request->back_arrow_bg_color,
            'back_arrow_icon_color'=>$request->back_arrow_icon_color,
            'category_box_act_txt_bg'=>$request->category_box_act_txt_bg,
            'header_bg_color_opc'=>$request->header_bg_color_opc,
            'header_effect_bg_color'=>$request->header_effect_bg_color,
            'rating_service_name_color'=>$request->rating_service_name_color,
            'bar_icon_color'=>$request->bar_icon_color,
            'bar_icon_bg_color'=>$request->bar_icon_bg_color,
            'cover_link_icon_color'=>$request->cover_link_icon_color,
            'cover_link_bg_color'=>$request->cover_link_bg_color,
            'rating_star_icon_color'=>$request->rating_star_icon_color,
        ];

        if($request->hasFile('theme_preview_image'))
        {
            $imgname = "theme_preview_image_".time().".". $request->file('theme_preview_image')->getClientOriginalExtension();
            $request->file('theme_preview_image')->move(public_path('client_uploads/shops/'.$shop_slug.'/theme_preview_image/'), $imgname);
            $setting_keys['theme_preview_image'] = $imgname;
        }

        if($request->hasFile('bg_image'))
        {
            $imgname = "bg_image".time().".". $request->file('bg_image')->getClientOriginalExtension();
            $request->file('bg_image')->move(public_path('client_uploads/shops/'.$shop_slug.'/background_image/'), $imgname);
            $setting_keys['bg_image'] = $imgname;
        }

        if($request->hasFile('header_image'))
        {
            $imgname = "header_image_".time().".". $request->file('header_image')->getClientOriginalExtension();
            $request->file('header_image')->move(public_path('client_uploads/shops/'.$shop_slug.'/header_image/'), $imgname);
            $setting_keys['header_image'] = $imgname;
        }

        // Update Theme Settings
        foreach($setting_keys as $key => $value)
        {
            $query = ThemeSettings::where('key',$key)->where('theme_id',$theme_id)->first();
            $setting_id = isset($query->id) ? $query->id : '';

            // Update
            if(!empty($setting_id) || $setting_id != '')
            {
                $settings = ThemeSettings::find($setting_id);
                $settings->value = $value;
                $settings->update();
            }
            else
            {
                $settings = new ThemeSettings();
                $settings->theme_id = $theme_id;
                $settings->key = $key;
                $settings->value = $value;
                $settings->save();
            }
        }

        return redirect()->back()->with('success', 'Theme Settings has been Changed SuccessFully...');
    }



    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // Delete Theme Settings
        ThemeSettings::where('theme_id',$id)->delete();

        // Delete Theme
        Theme::where('id',$id)->delete();

        return redirect()->route('design.theme')->with('success','Theme has been Removed SuccessFully..');
    }


    // Clone Theme View
    public function cloneView($id)
    {
        // Subscrption ID
        $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        if(!isset($package_permissions['add_edit_clone_theme']) || empty($package_permissions['add_edit_clone_theme']) || $package_permissions['add_edit_clone_theme'] == 0)
        {
            return redirect()->route('client.dashboard')->with('error',"You have not access this Menu");
        }

        // Theme Details
        $theme = Theme::where('id',$id)->first();

        // Keys
        $keys = ([
            'header_color',
            'sticky_header',
            'language_bar_position',
            'logo_position',
            'search_box_position',
            'banner_position',
            'banner_type',
            'banner_slide_button',
            'banner_delay_time',
            'background_color',
            'font_color',
            'label_color',
            'social_media_icon_color',
            'category_slider_effect',
            'categories_bar_color',
            'menu_bar_font_color',
            'category_title_and_description_color',
            'price_color',
            'cart_icon_color',
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
            'theme_preview_image',
            'search_box_icon_color',
            'read_more_link_color',
            'banner_height',
            'label_color_transparency',
            'item_box_background_color',
            'item_title_color',
            'item_description_color',
            'modal_item_title_color',
            'modal_item_des_color',
            'modal_item_price_color',
            'modal_close_icon_color',
            'modal_close_bg_color',
            'modal_add_btn_color',
            'modal_add_btn_text_color',
            'modal_quantity_icon_color',
            'modal_quantity_bg_color',
            'modal_price_label_color',
            'modal_igradient_type_color',
            'modal_body_bg_color',
            'modal_item_title_font_size',
            'rating_service_name_color',
            'rating_star_icon_color',
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = ThemeSettings::select('value')->where('key',$key)->where('theme_id',$id)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        return view('client.design.theme.clone',compact(['theme','settings']));
    }

    public function deleteHeaderImg($id)
    {

        // Delete Theme Settings
         $header_img = ThemeSettings::where('theme_id',$id)->where('key','header_image')->first();
         $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

         if($header_img)
         {
            $filePath = public_path('client_uploads/shops/' . $shop_slug . '/header_image/' . $header_img->value);
            if (file_exists($filePath)) {
                // Delete the file
                unlink($filePath);
            }
            $header_img->delete();
            return redirect()->back()->with('success','Header Image has been Removed SuccessFully..');
         }
    }

    public function deleteBgImg($id)
    {
          // Delete Theme Settings
          $bg_img = ThemeSettings::where('theme_id',$id)->where('key','bg_image')->first();
          $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

          if($bg_img)
          {
             $filePath = public_path('client_uploads/shops/' . $shop_slug . '/background_image/' . $bg_img->value);
             if (file_exists($filePath)) {
                 // Delete the file
                 unlink($filePath);
             }
             $bg_img->delete();
             return redirect()->back()->with('success','Background  Image has been Removed SuccessFully..');
          }
    }
}
