<?php

namespace App\Http\Controllers;

use App\Models\ClientSettings;
use App\Models\Shop;
use App\Models\TimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignController extends Controller
{
    // Frontend Topbar Logo View
    public function logo()
    {
        return view('client.design.logo');
    }



    // Upload new Frontend Topbar Logo
    public function logoUpload(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $request->validate([
            'shop_view_header_logo' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
        ]);

        try
        {
            if(!empty($clientID) && !empty($shop_id))
            {
                if($request->hasFile('shop_view_header_logo'))
                {
                    $get_logo_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','shop_view_header_logo')->first();
                    $setting_id = isset($get_logo_setting->id) ? $get_logo_setting->id : '';

                    if(!empty($setting_id) || $setting_id != '')
                    {
                        // Delete old Logo
                        $logo = isset($get_logo_setting->value) ? $get_logo_setting->value : '';
                        if(!empty($logo) && file_exists('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo))
                        {
                            unlink('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo);
                        }

                        // Insert new Logo
                        $logo_name = "top_logo_".time().".". $request->file('shop_view_header_logo')->getClientOriginalExtension();
                        $request->file('shop_view_header_logo')->move(public_path('client_uploads/shops/'.$shop_slug.'/top_logos/'), $logo_name);
                        $new_logo = $logo_name;

                        $logo_setting = ClientSettings::find($setting_id);
                        $logo_setting->value = $new_logo;
                        $logo_setting->update();

                    }
                    else
                    {
                        // Insert new Logo
                        $logo_name = "top_logo_".time().".". $request->file('shop_view_header_logo')->getClientOriginalExtension();
                        $request->file('shop_view_header_logo')->move(public_path('client_uploads/shops/'.$shop_slug.'/top_logos/'), $logo_name);
                        $new_logo = $logo_name;

                        $logo_setting = new ClientSettings();
                        $logo_setting->client_id = $clientID;
                        $logo_setting->shop_id = $shop_id;
                        $logo_setting->key = 'shop_view_header_logo';
                        $logo_setting->value = $new_logo;
                        $logo_setting->save();
                    }

                }

                return response()->json([
                    'success' => 1,
                    'message' => 'Logo has been Uploaded SuccessFully....',
                ]);
            }
            else
            {
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong !',
                ]);
            }
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error !',
            ]);
        }

    }


    // Delete Logo
    public function deleteLogo()
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $get_logo_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','shop_view_header_logo')->first();
        $setting_id = isset($get_logo_setting->id) ? $get_logo_setting->id : '';
        $logo = isset($get_logo_setting->value) ? $get_logo_setting->value : '';

        if(!empty($logo) && file_exists('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo))
        {
            unlink('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo);
        }

        if(!empty($setting_id))
        {
            $logo_setting = ClientSettings::find($setting_id);
            $logo_setting->value = "";
            $logo_setting->update();
        }

        return redirect()->route('design.general-info')->with('success','Logo has been Removed SuccessFully...');

    }


    // Change Intro Status
    public function introStatus(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try
        {
            if(!empty($clientID) && !empty($shop_id))
            {
                $get_intro_status_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','intro_icon_status')->first();
                $setting_id = isset($get_intro_status_setting->id) ? $get_intro_status_setting->id : '';

                if($request->status == 1)
                {
                    $get_cube_status_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','shop_intro_icon_is_cube')->first();

                    $cube_id = isset($get_cube_status_setting->id) ? $get_cube_status_setting->id : '';
                    if(!empty($cube_id) || $cube_id != '')
                    {
                        $cube_status = ClientSettings::find($cube_id);
                        $cube_status->value = '0';
                        $cube_status->update();
                    }
                }

                if(!empty($setting_id) || $setting_id != '')
                {
                    $intro_status = ClientSettings::find($setting_id);
                    $intro_status->value = $request->status;
                    $intro_status->update();
                }
                else
                {
                    $intro_status = new ClientSettings();
                    $intro_status->client_id = $clientID;
                    $intro_status->shop_id = $shop_id;
                    $intro_status->key = 'intro_icon_status';
                    $intro_status->value = $request->status;
                    $intro_status->save();
                }

                if($request->status == 1)
                {
                    $message = "Intro has been Enabled SuccessFully....";
                }
                else
                {
                    $message = "Intro has been Disabled SuccessFully....";
                }

                return response()->json([
                    'success' => 1,
                    'message' => $message,
                ]);
            }
            else
            {
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong !',
                ]);
            }
        }
        catch (\Throwable $th)
        {
            dd($th);
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error !',
            ]);
        }
    }


    // Change Intro Cube Status
    public function introCube(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try
        {
            if(!empty($clientID) && !empty($shop_id)){
                $get_intro_icon_is_cube_setting = ClientSettings::where('client_id', $clientID)->where('shop_id', $shop_id)->where('key', 'shop_intro_icon_is_cube')->first();
                $setting_id = isset($get_intro_icon_is_cube_setting->id) ? $get_intro_icon_is_cube_setting->id : '';

                if($request->status == 1)
                {
                    $get_intro_icon_status = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','intro_icon_status')->first();

                    $intro_id = isset($get_intro_icon_status->id) ? $get_intro_icon_status->id : '';
                    if(!empty($intro_id) || $intro_id != '')
                    {
                        $cube_status = ClientSettings::find($intro_id);
                        $cube_status->value = '0';
                        $cube_status->update();
                    }
                }
                    if(!empty($setting_id) || $setting_id != ''){
                        $intro_status = ClientSettings::find($setting_id);
                        $intro_status->value = $request->status;
                        $intro_status->update();
                    }else{
                        $intro_status = new ClientSettings();
                        $intro_status->client_id = $clientID;
                        $intro_status->shop_id = $shop_id;
                        $intro_status->key = 'shop_intro_icon_is_cube';
                        $intro_status->value = $request->status;
                        $intro_status->save();
                    }


                    if($request->status == 1)
                {
                    $message = "Intro Cube has been Enabled SuccessFully....";
                }
                else
                {
                    $message = "Intro Cube has been Disabled SuccessFully....";
                }
                return response()->json([
                    'success' => 1,
                    'message' => $message,
                ]);
            }else{
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong !',
                ]);
            }
        }catch (\Throwable $th){
            dd($th);
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

    // Change Intro Duration
    public function introDuration(Request $request)
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try
        {
            if(!empty($clientID) && !empty($shop_id))
            {
                $get_intro_duration_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','intro_icon_duration')->first();
                $setting_id = isset($get_intro_duration_setting->id) ? $get_intro_duration_setting->id : '';

                if(!empty($setting_id) || $setting_id != '')
                {
                    $intro_duration = ClientSettings::find($setting_id);
                    $intro_duration->value = $request->duration;
                    $intro_duration->update();
                }
                else
                {
                    $intro_duration = new ClientSettings();
                    $intro_duration->client_id = $clientID;
                    $intro_duration->shop_id = $shop_id;
                    $intro_duration->key = 'intro_icon_duration';
                    $intro_duration->value = $request->duration;
                    $intro_duration->save();
                }

                return response()->json([
                    'success' => 1,
                    'message' => "Duration has been Updated SuccessFully...",
                ]);
            }
            else
            {
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong !',
                ]);
            }
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error !',
            ]);
        }
    }

    // Change Intro Link
    public function introLink(Request $request)
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try
        {
            if(!empty($clientID) && !empty($shop_id)){
                $get_link_setting = ClientSettings::where('client_id', $clientID)->where('shop_id', $shop_id)->where('key', $request->key)->first();
                $setting_id = isset($get_link_setting->id) ? $get_link_setting->id : '';

                if(!empty($setting_id) || $setting_id != ''){
                    $intro_duration = ClientSettings::find($setting_id);
                    $intro_duration->value = $request->link;
                    $intro_duration->update();
                }else{
                    $intro_duration = new ClientSettings();
                    $intro_duration->client_id = $clientID;
                    $intro_duration->shop_id = $shop_id;
                    $intro_duration->key = $request->key;
                    $intro_duration->value = $request->link;
                    $intro_duration->save();
                }

                return response()->json([
                    'success' => 1,
                    'message' => "Link has been Updated SuccessFully...",
                ]);
            }else{
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong!',
                ]);
            }
        }catch (\Throwable $th){
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error !',
            ]);
        }
    }


    // Upload Intro Icon
    public function introIconUpload(Request $request)
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $request->validate([
            'shop_intro_icon' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF,mp4,mov|max:2000',
            'shop_intro_icon_1' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF,mp4,mov|max:2000',
            'shop_intro_icon_2' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF,mp4,mov|max:2000',
            'shop_intro_icon_3' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF,mp4,mov|max:2000',
            'shop_intro_icon_4' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF,mp4,mov|max:2000',
        ]);

        try
        {
            if(!empty($clientID) && !empty($shop_id)){
                if($request->hasFile('shop_intro_icon')){
                    $this->uploadAllIntroIcon($clientID, $shop_id, $shop_slug, 'shop_intro_icon', $request->file('shop_intro_icon'));
                }elseif($request->hasFile('shop_intro_icon_1')){
                    $this->uploadAllIntroIcon($clientID, $shop_id, $shop_slug, 'shop_intro_icon_1', $request->file('shop_intro_icon_1'));
                }elseif($request->hasFile('shop_intro_icon_2')){
                    $this->uploadAllIntroIcon($clientID, $shop_id, $shop_slug, 'shop_intro_icon_2', $request->file('shop_intro_icon_2'));
                }elseif($request->hasFile('shop_intro_icon_3')){
                    $this->uploadAllIntroIcon($clientID, $shop_id, $shop_slug, 'shop_intro_icon_3', $request->file('shop_intro_icon_3'));
                }elseif($request->hasFile('shop_intro_icon_4')){
                    $this->uploadAllIntroIcon($clientID, $shop_id, $shop_slug, 'shop_intro_icon_4', $request->file('shop_intro_icon_4'));
                }

                return response()->json([
                    'success' => 1,
                    'message' => 'Icon has been Uploaded SuccessFully....',
                ]);
            }else{
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong !',
                ]);
            }
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error !',
            ]);
        }
    }


    public function uploadAllIntroIcon($clientID, $shopID, $shopSlug, $key, $file)
    {
        $get_intro_icon_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shopID)->where('key',$key)->first();
        $setting_id = isset($get_intro_icon_setting->id) ? $get_intro_icon_setting->id : '';

        if(!empty($setting_id) || $setting_id != ''){
            // Delete old Icon
            $icon = isset($get_intro_icon_setting->value) ? $get_intro_icon_setting->value : '';
            if(!empty($icon) && file_exists('public/client_uploads/shops/'.$shopSlug.'/intro_icons/'.$icon)){
                unlink('public/client_uploads/shops/'.$shopSlug.'/intro_icons/'.$icon);
            }

            // Insert new Icon
            $icon_name = "intro_icon_".time().".". $file->getClientOriginalExtension();
            $file->move(public_path('client_uploads/shops/'.$shopSlug.'/intro_icons/'), $icon_name);

            $icon_setting = ClientSettings::find($setting_id);
            $icon_setting->value = $icon_name;
            $icon_setting->update();

        }else{
            // Insert new Logo
            $icon_name = "intro_icon_".time().".". $file->getClientOriginalExtension();
            $file->move(public_path('client_uploads/shops/'.$shopSlug.'/intro_icons/'), $icon_name);

            $icon_setting = new ClientSettings();
            $icon_setting->client_id = $clientID;
            $icon_setting->shop_id = $shopID;
            $icon_setting->key = $key;
            $icon_setting->value = $icon_name;
            $icon_setting->save();
        }
    }


    // Function for Cover
    public function cover()
    {
        return view('client.design.cover');
    }


    // Delete Cover
    public function deleteCover($key)
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $get_intro_icon = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key',$key)->first();
        $setting_id = isset($get_intro_icon->id) ? $get_intro_icon->id : '';
        $shop_intro_icon = isset($get_intro_icon->value) ? $get_intro_icon->value : '';

        if(!empty($shop_intro_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon))
        {
            unlink('public/client_uploads/shops/'.$shop_slug.'/intro_icons/'.$shop_intro_icon);
        }

        if(!empty($setting_id))
        {
            $logo_setting = ClientSettings::find($setting_id);
            $logo_setting->value = "";
            $logo_setting->update();
        }

        return redirect()->route('design.cover')->with('success','Cover has been Removed SuccessFully...');

    }



    // Generel Info View
    public function generalInfo()
    {
        $timezones = TimeZone::all();
        return view('client.design.general_info', compact(['timezones']));
    }



    // Update General General Info Settings
    public function generalInfoUpdate(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';


        $request->validate([
            'business_name' => 'required',
            'default_currency' => 'required',
            'shop_view_header_logo' => 'mimes:png,jpg,svg,jpeg,PNG,SVG,JPG,JPEG',
            'shop_loader' => 'mimes:gif',
        ]);

        $all_data['business_name'] = $request->business_name;
        $all_data['default_currency'] = $request->default_currency;
        $all_data['business_telephone'] = $request->business_telephone;
        // $all_data['business_subtitle'] = $request->business_subtitle;
        $all_data['is_sub_title'] = $request->is_sub_title;
        $all_data['instagram_link'] = $request->instagram_link;
        $all_data['twitter_link'] = $request->twitter_link;
        $all_data['facebook_link'] = $request->facebook_link;
        $all_data['foursquare_link'] = $request->foursquare_link;
        $all_data['tripadvisor_link'] = $request->tripadvisor_link;
        $all_data['map_url'] = $request->map_url;
        $all_data['website_url'] = $request->website_url;
        $all_data['pinterest_link'] = $request->pinterest_link;
        $all_data['is_loader'] = isset($request->is_loader) ? $request->is_loader : 0;
        $all_data['shop_start_time'] = $request->shop_start_time;
        $all_data['shop_end_time'] = $request->shop_end_time;
        $all_data['default_timezone'] = $request->default_timezone;
        if($request->hasFile('shop_loader'))
        {
            $all_data['shop_loader'] = $request->shop_loader;
        }

        if($request->hasFile('shop_loader'))
        {
            $loader_name = "shop_loader_".time().".". $request->file('shop_loader')->getClientOriginalExtension();
            $request->file('shop_loader')->move(public_path('client_uploads/shops/'.$shop_slug.'/loader/'), $loader_name);
            $all_data['shop_loader'] = $loader_name;
        }


        // Update Shop Name
        $shop = Shop::find($shop_id);
        $shop->name = $request->business_name;
        $shop->update();

        // Insert or Update Settings
        foreach($all_data as $key => $value)
        {
            $query = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key',$key)->first();
            $setting_id = isset($query->id) ? $query->id : '';

            if (!empty($setting_id) || $setting_id != '')  // Update
            {
                $settings = ClientSettings::find($setting_id);
                $settings->value = $value;
                $settings->update();
            }
            else // Insert
            {
                $settings = new ClientSettings();
                $settings->client_id = $clientID;
                $settings->shop_id = $shop_id;
                $settings->key = $key;
                $settings->value = $value;
                $settings->save();
            }
        }

             if($request->hasFile('shop_view_header_logo'))
                {
                    $get_logo_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','shop_view_header_logo')->first();
                    $setting_id = isset($get_logo_setting->id) ? $get_logo_setting->id : '';

                    if(!empty($setting_id) || $setting_id != '')
                    {
                        // Delete old Logo
                        $logo = isset($get_logo_setting->value) ? $get_logo_setting->value : '';
                        if(!empty($logo) && file_exists('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo))
                        {
                            unlink('public/client_uploads/shops/'.$shop_slug.'/top_logos/'.$logo);
                        }

                        // Insert new Logo
                        $logo_name = "top_logo_".time().".". $request->file('shop_view_header_logo')->getClientOriginalExtension();
                        $request->file('shop_view_header_logo')->move(public_path('client_uploads/shops/'.$shop_slug.'/top_logos/'), $logo_name);
                        $new_logo = $logo_name;

                        $logo_setting = ClientSettings::find($setting_id);
                        $logo_setting->value = $new_logo;
                        $logo_setting->update();

                    }
                    else
                    {
                        // Insert new Logo
                        $logo_name = "top_logo_".time().".". $request->file('shop_view_header_logo')->getClientOriginalExtension();
                        $request->file('shop_view_header_logo')->move(public_path('client_uploads/shops/'.$shop_slug.'/top_logos/'), $logo_name);
                        $new_logo = $logo_name;

                        $logo_setting = new ClientSettings();
                        $logo_setting->client_id = $clientID;
                        $logo_setting->shop_id = $shop_id;
                        $logo_setting->key = 'shop_view_header_logo';
                        $logo_setting->value = $new_logo;
                        $logo_setting->save();
                    }

                }
        return redirect()->route('design.general-info')->with('success','General Information has been Updated SuccessFully..');
    }


    // Update Mail Form Settings
    public function mailFormUpdate(Request $request)
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $all_data['orders_mail_form_client'] = $request->orders_mail_form_client;
        $all_data['orders_mail_form_customer'] = $request->orders_mail_form_customer;
        $all_data['check_in_mail_form'] = $request->check_in_mail_form;

        // Insert or Update Settings
        foreach($all_data as $key => $value)
        {
            $query = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key',$key)->first();
            $setting_id = isset($query->id) ? $query->id : '';

            if (!empty($setting_id) || $setting_id != '')  // Update
            {
                $settings = ClientSettings::find($setting_id);
                $settings->value = $value;
                $settings->update();
            }
            else // Insert
            {
                $settings = new ClientSettings();
                $settings->client_id = $clientID;
                $settings->shop_id = $shop_id;
                $settings->key = $key;
                $settings->value = $value;
                $settings->save();
            }
        }
        return redirect()->route('design.mail.forms')->with('success','Mail Forms has been Updated SuccessFully..');
    }


    public function loaderDelete()
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $get_loader_setting = ClientSettings::where('client_id',$clientID)->where('shop_id',$shop_id)->where('key','shop_loader')->first();

        $setting_id = isset($get_loader_setting->id) ? $get_loader_setting->id : '';
        $loader = isset($get_loader_setting->value) ? $get_loader_setting->value : '';

        if(!empty($loader) && file_exists('public/client_uploads/shops/'.$shop_slug.'/loader/'.$loader))
        {
            unlink('public/client_uploads/shops/'.$shop_slug.'/loader/'.$loader);
        }

        if(!empty($setting_id))
        {
            $logo_setting = ClientSettings::find($setting_id);
            $logo_setting->value = "";
            $logo_setting->update();
        }

        return redirect()->route('design.general-info')->with('success','Logo has been Removed SuccessFully...');

    }
}
