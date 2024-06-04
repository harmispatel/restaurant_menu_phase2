<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLanguage;
use App\Models\Languages;
use App\Models\OtherSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherSettingController extends Controller
{
    // Show the form for editing the specified resource.
    public function edit(Request $request)
    {
        $setting_id = $request->id;
        $setting_key = $request->setting_key;
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        try
        {
            // Setting Details
            $setting_details = OtherSetting::where('id',$setting_id)->first();

            // Get Language Settings
            $language_settings = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

            // Primary Language Details
            $primary_language_detail = Languages::where('id',$primary_lang_id)->first();
            $primary_lang_code = isset($primary_language_detail->code) ? $primary_language_detail->code : '';
            $primary_lang_name = isset($primary_language_detail->name) ? $primary_language_detail->name : '';

            // Additional Languages
            $additional_languages = AdditionalLanguage::where('shop_id',$shop_id)->get();

            // Dynamic Language Bar
            if(count($additional_languages) > 0)
            {
                $html = '';
                $html .= '<div class="lang-tab">';
                    // Primary Language
                    $html .= '<a class="active text-uppercase" onclick="updateByCode(\''.$primary_lang_code.'\')">'.$primary_lang_code.'</a>';

                    // Additional Language
                    foreach($additional_languages as $value)
                    {
                        // Additional Language Details
                        $add_lang_detail = Languages::where('id',$value->language_id)->first();
                        $add_lang_code = isset($add_lang_detail->code) ? $add_lang_detail->code : '';
                        $add_lang_name = isset($add_lang_detail->name) ? $add_lang_detail->name : '';

                        $html .= '<a class="text-uppercase" onclick="updateByCode(\''.$add_lang_code.'\')">'.$add_lang_code.'</a>';
                    }
                $html .= '</div>';

                $html .= '<hr>';

                $html .= '<div class="row">';
                    $html .= '<div class="col-md-12">';
                        $html .= '<form id="otherSettingsForm" enctype="multipart/form-data">';

                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                            $html .= '<input type="hidden" name="setting_id" id="setting_id" value="'.$setting_details['id'].'">';
                            $html .= '<input type="hidden" name="setting_key" id="setting_key" value="'.$setting_details['key'].'">';

                            $html .= '<div class="row mb-3">';
                                $html .= '<div class="col-md-12">';

                                    if($setting_details['key'] == 'read_more_link_label')
                                    {
                                        $html .= '<label for="read_more_link_label" class="form-label">'. __('TITLE: For "Read More"') .'</label>';
                                        $html .= '<input type="text" name="read_more_link_label" id="read_more_link_label" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    }
                                    elseif($setting_details['key'] == 'delivery_message')
                                    {
                                        $html .= '<label for="delivery_message" class="form-label">'. __('ALERT: Address Outside Delivery Zone') .'</label>';
                                        $html .= '<textarea name="delivery_message" id="delivery_message" class="form-control">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'today_special_icon')
                                    {
                                        $html .= '<label for="today_special_icon" class="form-label">'. __('IMAGE: For "Today\'s Special"') .'</label>';
                                        $html .= '<input type="file" name="today_special_icon" id="today_special_icon" class="form-control">';

                                        $today_special_icon = (isset($setting_details[$primary_lang_code."_value"])) ? $setting_details[$primary_lang_code."_value"] : '';

                                        if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                        {
                                            $today_special_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                            $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                        }
                                        else
                                        {
                                            $today_special_icon = asset('public/client_images/not-found/no_image_1.jpg');
                                            $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                        }
                                    }
                                    elseif($setting_details['key'] == 'distance_message')
                                    {
                                        $html .= '<label for="distance_message" class="form-label">'. __('NOTICE: Minimum Order Per Distance') .'</label>';
                                        $html .= '<textarea name="distance_message" id="distance_message" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                        $html .= '<code>Tags : ({from}, {to}, {amount}).</code>';
                                    }
                                    elseif($setting_details['key'] == 'distance_alert_message')
                                    {
                                        $html .= '<label for="distance_alert_message" class="form-label">'.  __('ALERT: Minimum Order Per Distance') .'</label>';
                                        $html .= '<input type="text" name="distance_alert_message" id="distance_alert_message" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    }
                                    elseif($setting_details['key'] == 'homepage_intro')
                                    {
                                        $html .= '<label for="homepage_intro" class="form-label">'. __('TEXT: Footer Text HTML') .'</label>';
                                        $html .= '<textarea name="homepage_intro" id="homepage_intro" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'seo_message')
                                    {
                                        $html .= '<label for="seo_message" class="form-label">'. __('TITLE: SEO Message') .'</label>';
                                        $html .= '<textarea name="seo_message" id="seo_message" class="form-control">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'service_closed_message')
                                    {
                                        $html .= '<label for="service_closed_message" class="form-label">'. __('NOTICE: Service Is Closed') .'</label>';
                                        $html .= '<textarea name="service_closed_message" id="service_closed_message" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'header_text_1'){
                                        $html .= '<label for="header_text_1" class="form-label">'.  __('HEADER 1: Working Hours') .'</label>';
                                        $html .= '<input type="text" name="header_text_1" id="header_text_1" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    }
                                    elseif($setting_details['key'] == 'header_text_2'){
                                        $html .= '<label for="header_text_2" class="form-label">'.  __('HEADER 2: Sub Title') .'</label>';
                                        // $html .= '<input type="text" name="header_text_2" id="header_text_2" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                        $html .= '<textarea name="header_text_2" id="header_text_2" class="form-control header_text_2" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';

                                    }

                                $html .= '</div>';
                            $html .= '</div>';

                        $html .= '</form>';
                    $html .= '</div>';
                $html .= '</div>';
            }
            else
            {
                $html = '';
                $html .= '<div class="lang-tab">';
                    // Primary Language
                    $html .= '<a class="active text-uppercase" onclick="updateByCode(\''.$primary_lang_code.'\')">'.$primary_lang_code.'</a>';
                $html .= '</div>';

                $html .= '<hr>';

                $html .= '<div class="row">';
                    $html .= '<div class="col-md-12">';
                        $html .= '<form id="otherSettingsForm" enctype="multipart/form-data">';

                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                            $html .= '<input type="hidden" name="setting_id" id="setting_id" value="'.$setting_details['id'].'">';
                            $html .= '<input type="hidden" name="setting_key" id="setting_key" value="'.$setting_details['key'].'">';

                            $html .= '<div class="row mb-3">';
                                $html .= '<div class="col-md-12">';

                                    if($setting_details['key'] == 'read_more_link_label')
                                    {
                                        $html .= '<label for="read_more_link_label" class="form-label">'. __('TITLE: For "Read More"') .'</label>';
                                        $html .= '<input type="text" name="read_more_link_label" id="read_more_link_label" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    }
                                    elseif($setting_details['key'] == 'delivery_message')
                                    {
                                        $html .= '<label for="delivery_message" class="form-label">'. __('ALERT: Address Outside Delivery Zone') .'</label>';
                                        $html .= '<textarea name="delivery_message" id="delivery_message" class="form-control">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'today_special_icon')
                                    {
                                        $html .= '<label for="today_special_icon" class="form-label">'. __('IMAGE: For "Today\'s Special"') .'</label>';
                                        $html .= '<input type="file" name="today_special_icon" id="today_special_icon" class="form-control">';

                                        $today_special_icon = (isset($setting_details[$primary_lang_code."_value"])) ? $setting_details[$primary_lang_code."_value"] : '';

                                        if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                        {
                                            $today_special_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                            $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                        }
                                        else
                                        {
                                            $today_special_icon = asset('public/client_images/not-found/no_image_1.jpg');
                                            $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                        }
                                    }
                                    elseif($setting_details['key'] == 'distance_message')
                                    {
                                        $html .= '<label for="distance_message" class="form-label">'. __('NOTICE: Minimum Order Per Distance') .'</label>';
                                        $html .= '<textarea name="distance_message" id="distance_message" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                        $html .= '<code>Tags : ({from}, {to}, {amount}).</code>';
                                    }
                                    elseif($setting_details['key'] == 'distance_alert_message')
                                    {
                                        $html .= '<label for="distance_alert_message" class="form-label">'.  __('ALERT: Minimum Order Per Distance') .'</label>';
                                        $html .= '<input type="text" name="distance_alert_message" id="distance_alert_message" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    }
                                    elseif($setting_details['key'] == 'homepage_intro')
                                    {
                                        $html .= '<label for="homepage_intro" class="form-label">'. __('TEXT: Footer Text HTML') .'</label>';
                                        $html .= '<textarea name="homepage_intro" id="homepage_intro" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'seo_message')
                                    {
                                        $html .= '<label for="seo_message" class="form-label">'. __('TITLE: SEO Message') .'</label>';
                                        $html .= '<textarea name="seo_message" id="seo_message" class="form-control">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'service_closed_message')
                                    {
                                        $html .= '<label for="service_closed_message" class="form-label">'. __('NOTICE: Service Is Closed') .'</label>';
                                        $html .= '<textarea name="service_closed_message" id="service_closed_message" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    }
                                    elseif($setting_details['key'] == 'header_text_1'){
                                        $html .= '<label for="header_text_1" class="form-label">'.  __('HEADER 1: Working Hours') .'</label>';
                                        $html .= '<input type="text" name="header_text_1" id="header_text_1" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    }
                                    elseif($setting_details['key'] == 'header_text_2'){
                                        $html .= '<label for="header_text_2" class="form-label">'.  __('HEADER 2: Sub Title') .'</label>';
                                        // $html .= '<input type="text" name="header_text_2" id="header_text_2" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                        $html .= '<textarea name="header_text_2" id="header_text_2" class="form-control header_text_2" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';

                                    }

                                $html .= '</div>';
                            $html .= '</div>';

                        $html .= '</form>';
                    $html .= '</div>';
                $html .= '</div>';
            }

            return response()->json([
                'success' => 1,
                'message' => 'Data has been Fetched SuccessFully..',
                'data' => $html,
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


    // Update Other Settings When Change Tab
    public function updateByLangCode(Request $request)
    {
        // Shop ID & Slug
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $setting_id = $request->setting_id;
        $setting_key = $request->setting_key;
        $active_lang_code = $request->active_lang_code;
        $next_lang_code = $request->next_lang_code;

        if($setting_key == 'today_special_icon')
        {
            $request->validate([
                'today_special_icon' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF',
            ]);
        }

        try
        {
            $value_key = $active_lang_code."_value";
            $other_setting = OtherSetting::find($setting_id);

            if($setting_key == 'today_special_icon')
            {
                if($request->hasFile('today_special_icon'))
                {

                    // Delete Old Image
                    $old_image = (isset($other_setting->$value_key)) ? $other_setting->$value_key : '';
                    if(!empty($old_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$old_image))
                    {
                        unlink('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$old_image);
                    }

                    // Upload New Image
                    $imgname = $active_lang_code."_today_special_icon_".time().".". $request->file('today_special_icon')->getClientOriginalExtension();
                    $request->file('today_special_icon')->move(public_path('client_uploads/shops/'.$shop_slug.'/today_special_icon/'), $imgname);
                    $other_setting->$value_key = $imgname;
                    $other_setting->update();
                }
            }
            elseif($setting_key == 'read_more_link_label')
            {
                $other_setting->$value_key = $request->read_more_link_label;
                $other_setting->update();
            }
            elseif($setting_key == 'delivery_message')
            {
                $other_setting->$value_key = $request->delivery_message;
                $other_setting->update();
            }
            elseif($setting_key == 'distance_message')
            {
                $other_setting->$value_key = $request->distance_message;
                $other_setting->update();
            }
            elseif($setting_key == 'distance_alert_message')
            {
                $other_setting->$value_key = $request->distance_alert_message;
                $other_setting->update();
            }
            elseif($setting_key == 'homepage_intro')
            {
                $other_setting->$value_key = $request->homepage_intro;
                $other_setting->update();
            }
            elseif($setting_key == 'seo_message')
            {
                $other_setting->$value_key = $request->seo_message;
                $other_setting->update();
            }
            elseif($setting_key == 'service_closed_message')
            {
                $other_setting->$value_key = $request->service_closed_message;
                $other_setting->update();
            }
            elseif($setting_key == 'header_text_1')
            {
                $other_setting->$value_key = $request->header_text_1;
                $other_setting->update();
            }
            elseif($setting_key == 'header_text_2')
            {
                $other_setting->$value_key = $request->header_text_2;
                $other_setting->update();
            }

            $html_data = $this->getEditOtherSettingData($next_lang_code,$setting_id);

            return response()->json([
                'success' => 1,
                'message' => 'Data has been Updated SuccessFully...',
                'data' => $html_data,
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



    // Update the specified resource in storage.
    public function update(Request $request)
    {
        // Shop ID & Slug
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        $setting_id = $request->setting_id;
        $setting_key = $request->setting_key;
        $active_lang_code = $request->active_lang_code;

        if($setting_key == 'today_special_icon')
        {
            $request->validate([
                'today_special_icon' => 'mimes:png,jpg,svg,gif,jpeg,PNG,SVG,JPG,JPEG,GIF',
            ]);
        }

        try
        {
            $value_key = $active_lang_code."_value";

            $other_setting = OtherSetting::find($setting_id);

            if($setting_key == 'today_special_icon')
            {
                if($request->hasFile('today_special_icon'))
                {

                    // Delete Old Image
                    $old_image = (isset($other_setting->$value_key)) ? $other_setting->$value_key : '';
                    if(!empty($old_image) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$old_image))
                    {
                        unlink('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$old_image);
                    }

                    // Upload New Image
                    $imgname = $active_lang_code."_today_special_icon_".time().".". $request->file('today_special_icon')->getClientOriginalExtension();
                    $request->file('today_special_icon')->move(public_path('client_uploads/shops/'.$shop_slug.'/today_special_icon/'), $imgname);
                    $other_setting->$value_key = $imgname;
                    $other_setting->update();
                }
            }
            elseif($setting_key == 'read_more_link_label')
            {
                $other_setting->$value_key = $request->read_more_link_label;
                $other_setting->update();
            }
            elseif($setting_key == 'delivery_message')
            {
                $other_setting->$value_key = $request->delivery_message;
                $other_setting->update();
            }
            elseif($setting_key == 'distance_message')
            {
                $other_setting->$value_key = $request->distance_message;
                $other_setting->update();
            }
            elseif($setting_key == 'distance_alert_message')
            {
                $other_setting->$value_key = $request->distance_alert_message;
                $other_setting->update();
            }
            elseif($setting_key == 'homepage_intro')
            {
                $other_setting->$value_key = $request->homepage_intro;
                $other_setting->update();
            }
            elseif($setting_key == 'seo_message')
            {
                $other_setting->$value_key = $request->seo_message;
                $other_setting->update();
            }
            elseif($setting_key == 'service_closed_message')
            {
                $other_setting->$value_key = $request->service_closed_message;
                $other_setting->update();
            }
            elseif($setting_key == 'header_text_1')
            {
                $other_setting->$value_key = $request->header_text_1;
                $other_setting->update();
            }
            elseif($setting_key == 'header_text_2')
            {
                $other_setting->$value_key = $request->header_text_2;
                $other_setting->update();
            }

            return response()->json([
                'success' => 1,
                'message' => 'More Translations has been Updated SuccessFully...',
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


    // Get Mail Form Data By Language Code & Mail Form ID
    public function getEditOtherSettingData($current_lang_code,$setting_id)
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
        $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

        // Setting Details
        $setting_details = OtherSetting::where('id',$setting_id)->first();

        // Get Language Settings
        $language_settings = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

        // Primary Language Details
        $primary_language_detail = Languages::where('id',$primary_lang_id)->first();
        $primary_lang_code = isset($primary_language_detail->code) ? $primary_language_detail->code : '';
        $primary_lang_name = isset($primary_language_detail->name) ? $primary_language_detail->name : '';

        // Additional Languages
        $additional_languages = AdditionalLanguage::where('shop_id',$shop_id)->get();

        // Primary Active Tab
        $primary_active_tab = ($primary_lang_code == $current_lang_code) ? 'active' : '';

        // Dynamic Language Bar
        if(count($additional_languages) > 0)
        {
            $html = '';
            $html .= '<div class="lang-tab">';
                // Primary Language
                $html .= '<a class="'.$primary_active_tab.' text-uppercase" onclick="updateByCode(\''.$primary_lang_code.'\')">'.$primary_lang_code.'</a>';

                // Additional Language
                foreach($additional_languages as $value)
                {
                    // Additional Language Details
                    $add_lang_detail = Languages::where('id',$value->language_id)->first();
                    $add_lang_code = isset($add_lang_detail->code) ? $add_lang_detail->code : '';
                    $add_lang_name = isset($add_lang_detail->name) ? $add_lang_detail->name : '';

                    // Additional Active Tab
                    $additional_active_tab = ($add_lang_code == $current_lang_code) ? 'active' : '';

                    $html .= '<a class="'.$additional_active_tab.' text-uppercase" onclick="updateByCode(\''.$add_lang_code.'\')">'.$add_lang_code.'</a>';
                }
            $html .= '</div>';

            $html .= '<hr>';

            $html .= '<div class="row">';
                $html .= '<div class="col-md-12">';
                    $html .= '<form id="otherSettingsForm" enctype="multipart/form-data">';

                        $html .= csrf_field();
                        $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$current_lang_code.'">';
                        $html .= '<input type="hidden" name="setting_id" id="setting_id" value="'.$setting_details['id'].'">';
                        $html .= '<input type="hidden" name="setting_key" id="setting_key" value="'.$setting_details['key'].'">';

                        $html .= '<div class="row mb-3">';
                            $html .= '<div class="col-md-12">';

                                if($setting_details['key'] == 'read_more_link_label')
                                {
                                    $html .= '<label for="read_more_link_label" class="form-label">'. __('TITLE: For "Read More"') .'</label>';
                                    $html .= '<input type="text" name="read_more_link_label" id="read_more_link_label" value="'.$setting_details[$current_lang_code."_value"].'" class="form-control">';
                                }
                                elseif($setting_details['key'] == 'delivery_message')
                                {
                                    $html .= '<label for="delivery_message" class="form-label">'. __('ALERT: Address Outside Delivery Zone') .'</label>';
                                    $html .= '<textarea name="delivery_message" id="delivery_message" class="form-control">'.$setting_details[$current_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'today_special_icon')
                                {
                                    $html .= '<label for="today_special_icon" class="form-label">'. __('IMAGE: For "Today\'s Special"') .'</label>';
                                    $html .= '<input type="file" name="today_special_icon" id="today_special_icon" class="form-control">';

                                    $today_special_icon = (isset($setting_details[$current_lang_code."_value"])) ? $setting_details[$current_lang_code."_value"] : '';

                                    if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                    {
                                        $today_special_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                        $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                    }
                                    else
                                    {
                                        $today_special_icon = asset('public/client_images/not-found/no_image_1.jpg');
                                        $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                    }
                                }
                                elseif($setting_details['key'] == 'distance_message')
                                {
                                    $html .= '<label for="distance_message" class="form-label">'. __('NOTICE: Minimum Order Per Distance') .'</label>';
                                    $html .= '<textarea name="distance_message" id="distance_message" class="form-control" rows="5">'.$setting_details[$current_lang_code."_value"].'</textarea>';
                                    $html .= '<code>Tags : ({from}, {to}, {amount}).</code>';
                                }
                                elseif($setting_details['key'] == 'distance_alert_message')
                                {
                                    $html .= '<label for="distance_alert_message" class="form-label">'.  __('ALERT: Minimum Order Per Distance') .'</label>';
                                    $html .= '<input type="text" name="distance_alert_message" id="distance_alert_message" value="'.$setting_details[$current_lang_code."_value"].'" class="form-control">';
                                }
                                elseif($setting_details['key'] == 'homepage_intro')
                                {
                                    $html .= '<label for="homepage_intro" class="form-label">'. __('TEXT: Footer Text HTML') .'</label>';
                                    $html .= '<textarea name="homepage_intro" id="homepage_intro" class="form-control" rows="5">'.$setting_details[$current_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'seo_message')
                                {
                                    $html .= '<label for="seo_message" class="form-label">'. __('TITLE: SEO Message') .'</label>';
                                    $html .= '<textarea name="seo_message" id="seo_message" class="form-control">'.$setting_details[$current_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'service_closed_message')
                                {
                                    $html .= '<label for="service_closed_message" class="form-label">'. __('NOTICE: Service Is Closed') .'</label>';
                                    $html .= '<textarea name="service_closed_message" id="service_closed_message" class="form-control" rows="5">'.$setting_details[$current_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'header_text_1'){
                                    $html .= '<label for="header_text_1" class="form-label">'.  __('HEADER 1: Working Hours') .'</label>';
                                    $html .= '<input type="text" name="header_text_1" id="header_text_1" value="'.$setting_details[$current_lang_code."_value"].'" class="form-control">';
                                }
                                elseif($setting_details['key'] == 'header_text_2'){
                                    $html .= '<label for="header_text_2" class="form-label">'.  __('HEADER 2: Sub Title') .'</label>';
                                    // $html .= '<input type="text" name="header_text_2" id="header_text_2" value="'.$setting_details[$current_lang_code."_value"].'" class="form-control">';
                                    $html .= '<textarea name="header_text_2" id="header_text_2" class="form-control header_text_2" rows="5">'.$setting_details[$current_lang_code."_value"].'</textarea>';

                                }

                            $html .= '</div>';
                        $html .= '</div>';

                    $html .= '</form>';
                $html .= '</div>';
            $html .= '</div>';
        }
        else
        {
            $html = '';
            $html .= '<div class="lang-tab">';
                // Primary Language
                $html .= '<a class="active text-uppercase" onclick="updateByCode(\''.$primary_lang_code.'\')">'.$primary_lang_code.'</a>';
            $html .= '</div>';

            $html .= '<hr>';

            $html .= '<div class="row">';
                $html .= '<div class="col-md-12">';
                    $html .= '<form id="otherSettingsForm" enctype="multipart/form-data">';

                        $html .= csrf_field();
                        $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                        $html .= '<input type="hidden" name="setting_id" id="setting_id" value="'.$setting_details['id'].'">';
                        $html .= '<input type="hidden" name="setting_key" id="setting_key" value="'.$setting_details['key'].'">';

                        $html .= '<div class="row mb-3">';
                            $html .= '<div class="col-md-12">';

                                if($setting_details['key'] == 'read_more_link_label')
                                {
                                    $html .= '<label for="read_more_link_label" class="form-label">'. __('TITLE: For "Read More"') .'</label>';
                                    $html .= '<input type="text" name="read_more_link_label" id="read_more_link_label" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                }
                                elseif($setting_details['key'] == 'delivery_message')
                                {
                                    $html .= '<label for="delivery_message" class="form-label">'. __('ALERT: Address Outside Delivery Zone') .'</label>';
                                    $html .= '<textarea name="delivery_message" id="delivery_message" class="form-control">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'today_special_icon')
                                {
                                    $html .= '<label for="today_special_icon" class="form-label">'. __('IMAGE: For "Today\'s Special"') .'</label>';
                                    $html .= '<input type="file" name="today_special_icon" id="today_special_icon" class="form-control">';

                                    $today_special_icon = (isset($setting_details[$primary_lang_code."_value"])) ? $setting_details[$primary_lang_code."_value"] : '';

                                    if(!empty($today_special_icon) && file_exists('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon))
                                    {
                                        $today_special_icon = asset('public/client_uploads/shops/'.$shop_slug.'/today_special_icon/'.$today_special_icon);
                                        $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                    }
                                    else
                                    {
                                        $today_special_icon = asset('public/client_images/not-found/no_image_1.jpg');
                                        $html .= '<img class="mt-3" src="'.$today_special_icon.'" width="100">';
                                    }
                                }
                                elseif($setting_details['key'] == 'distance_message')
                                {
                                    $html .= '<label for="distance_message" class="form-label">'. __('NOTICE: Minimum Order Per Distance') .'</label>';
                                    $html .= '<textarea name="distance_message" id="distance_message" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                    $html .= '<code>Tags : ({from}, {to}, {amount}).</code>';
                                }
                                elseif($setting_details['key'] == 'distance_alert_message')
                                {
                                    $html .= '<label for="distance_alert_message" class="form-label">'.  __('ALERT: Minimum Order Per Distance') .'</label>';
                                    $html .= '<input type="text" name="distance_alert_message" id="distance_alert_message" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                }
                                elseif($setting_details['key'] == 'homepage_intro')
                                {
                                    $html .= '<label for="homepage_intro" class="form-label">'. __('TEXT: Footer Text HTML') .'</label>';
                                    $html .= '<textarea name="homepage_intro" id="homepage_intro" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'seo_message')
                                {
                                    $html .= '<label for="seo_message" class="form-label">'. __('TITLE: SEO Message') .'</label>';
                                    $html .= '<textarea name="seo_message" id="seo_message" class="form-control">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'service_closed_message')
                                {
                                    $html .= '<label for="service_closed_message" class="form-label">'. __('NOTICE: Service Is Closed') .'</label>';
                                    $html .= '<textarea name="service_closed_message" id="service_closed_message" class="form-control" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';
                                }
                                elseif($setting_details['key'] == 'header_text_1'){
                                    $html .= '<label for="header_text_1" class="form-label">'.  __('HEADER 1: Working Hours') .'</label>';
                                    $html .= '<input type="text" name="header_text_1" id="header_text_1" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                }
                                elseif($setting_details['key'] == 'header_text_2'){
                                    $html .= '<label for="header_text_2" class="form-label">'.  __('HEADER 2: Sub Title') .'</label>';
                                    // $html .= '<input type="text" name="header_text_2" id="header_text_2" value="'.$setting_details[$primary_lang_code."_value"].'" class="form-control">';
                                    $html .= '<textarea name="header_text_2" id="header_text_2" class="form-control header_text_2" rows="5">'.$setting_details[$primary_lang_code."_value"].'</textarea>';

                                }

                            $html .= '</div>';
                        $html .= '</div>';

                    $html .= '</form>';
                $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }
}
