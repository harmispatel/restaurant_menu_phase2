<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLanguage;
use App\Models\Languages;
use App\Models\ShopBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopBannerController extends Controller
{

    public function index()
    {
        // Subscrption ID
        $subscription_id = Auth::user()->hasOneSubscription['subscription_id'];

        // Get Package Permissions
        $package_permissions = getPackagePermission($subscription_id);

        if(!isset($package_permissions['banner']) || empty($package_permissions['banner']) || $package_permissions['banner'] == 0)
        {
            return redirect()->route('client.dashboard')->with('error',"You have not access this Menu");
        }

        $clientID = Auth::user()->id;
        $key = "shop_banner";

        $data['shop_id'] = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        // Banner Settings
        $data['banner_setting'] = ShopBanner::where('shop_id',$data['shop_id'])->where('key',$key)->first();

        // Get Language Settings
        $language_settings = clientLanguageSettings($data['shop_id']);
        $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

        // Primary Language Details
        $data['primary_language_detail'] = Languages::where('id',$primary_lang_id)->first();

        // Additional Languages
        $data['additional_languages'] = AdditionalLanguage::where('shop_id',$data['shop_id'])->get();

        return view('client.design.banner',$data);
    }

    // Function for Insert & Update Banners
    public function update(Request $request)
    {
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
        $shop_id = $request->shop_id;
        $lang_code = $request->lang_code;
        $banner_text = $request->banner_text;
        $key = "shop_banner";

        $title_key = $lang_code."_title";
        $image_key = $lang_code."_image";

        $get_setting = ShopBanner::where('shop_id',$shop_id)->where('key',$key)->first();
        $setting_id = isset($get_setting['id']) ? $get_setting['id'] : '';

        if(!empty($setting_id) || $setting_id != '')
        {
            $banner = ShopBanner::find($setting_id);
            $banner->title = $banner_text;
            $banner->$title_key = $banner_text;

            if($request->hasFile('banner'))
            {
                $old_image = isset($banner-> $image_key) ? $banner-> $image_key : "";
                if(!empty($old_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$old_image))
                {
                    unlink('public/client_uploads/shops/'.$shop_slug.'/banners/'.$old_image);
                }

                // Insert new Image
                $imgname = "banner_".time().".". $request->file('banner')->getClientOriginalExtension();
                $request->file('banner')->move(public_path('client_uploads/shops/'.$shop_slug.'/banners/'), $imgname);
                $banner->image = $imgname;
                $banner->$image_key = $imgname;

            }

            $banner->update();
        }
        else
        {
            $banner = new ShopBanner();
            $banner->shop_id = $shop_id;
            $banner->key = $key;
            $banner->title = $banner_text;
            $banner->$title_key = $banner_text;

            if($request->hasFile('banner'))
            {
                // Insert new Image
                $imgname = "banner_".time().".". $request->file('banner')->getClientOriginalExtension();
                $request->file('banner')->move(public_path('client_uploads/shops/'.$shop_slug.'/banners/'), $imgname);
                $banner->image = $imgname;
                $banner->$image_key = $imgname;
            }

            $banner->save();
        }

        return redirect()->route('design.banner')->with('success','Settings has been Updated SuccessFully..');

    }

    public function deleteBanner($key)
    {
        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';
        $image_key = $key."_image";

        $get_banner = ShopBanner::where('shop_id',$shop_id)->where('key','shop_banner')->first();
        $setting_id = isset($get_banner->id) ? $get_banner->id : '';
        $lang_bannner = isset($get_banner[$image_key]) ? $get_banner[$image_key] : '';

        if(!empty($lang_bannner) && file_exists('public/client_uploads/shops/'.$shop_slug.'/banners/'.$lang_bannner))
        {
            unlink('public/client_uploads/shops/'.$shop_slug.'/banners/'.$lang_bannner);
        }

        if(!empty($setting_id))
        {
            $banner = ShopBanner::find($setting_id);
            $banner->$image_key = "";
            $banner->update();
        }

        return redirect()->route('design.banner')->with('success','Banner has been Removed SuccessFully...');

    }
}
