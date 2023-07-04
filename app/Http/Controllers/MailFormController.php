<?php

namespace App\Http\Controllers;

use App\Models\AdditionalLanguage;
use App\Models\Languages;
use App\Models\MailForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailFormController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $shop_id = (isset(Auth::user()->hasOneShop->shop['id'])) ? Auth::user()->hasOneShop->shop['id'] : '';

        $data['mail_forms'] = MailForm::where('shop_id',$shop_id)->get();
        return view('client.design.mail_forms',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    // Show the form for editing the specified resource.
    public function edit(Request $request)
    {
        $mail_form_id = $request->id;
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try
        {
            // Mail Form Details
            $mail_form_details = MailForm::where('id',$mail_form_id)->first();

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
                        $html .= '<form id="editMailFormsForm" enctype="multipart/form-data">';

                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                            $html .= '<input type="hidden" name="mail_form_id" id="mail_form_id" value="'.$mail_form_details['id'].'">';

                            $html .= '<div class="row mb-3">';
                                $html .= '<div class="col-md-12">';
                                    if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                                    {
                                        $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Owner') .'</label>';
                                    }
                                    elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                                    {
                                        $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Customer') .'</label>';
                                    }
                                    elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                                    {
                                        $html .= '<label for="mail_form_text" class="form-label">'. __('Check In Mail Form') .'</label>';
                                    }
                                    $html .= '<textarea name="mail_form_text" id="mail_form_text" class="form-control">'.$mail_form_details[$primary_lang_code."_form"].'</textarea>';
                                    if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                                    {
                                        $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {payment_method}, {items}, {total})</code>';
                                    }
                                    elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                                    {
                                        $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {order_status}, {payment_method}, {items}, {total}, {estimated_time})</code>';
                                    }
                                    elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                                    {
                                        $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {phone}, {passport_no}, {room_no}, {nationality}, {age}, {address}, {arrival_date}, {departure_date}, {message})</code>';
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
                        $html .= '<form id="editMailFormsForm" enctype="multipart/form-data">';

                            $html .= csrf_field();
                            $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                            $html .= '<input type="hidden" name="option_id" id="option_id" value="'.$mail_form_details['id'].'">';

                            $html .= '<div class="row mb-3">';
                                $html .= '<div class="col-md-12">';
                                    if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                                    {
                                        $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Owner') .'</label>';
                                    }
                                    elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                                    {
                                        $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Customer') .'</label>';
                                    }
                                    elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                                    {
                                        $html .= '<label for="mail_form_text" class="form-label">'. __('Check In Mail Form') .'</label>';
                                    }
                                    $html .= '<textarea name="mail_form_text" id="mail_form_text" class="form-control">'.$mail_form_details[$primary_lang_code."_form"].'</textarea>';
                                    if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                                    {
                                        $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {payment_method}, {items}, {total})</code>';
                                    }
                                    elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                                    {
                                        $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {order_status}, {payment_method}, {items}, {total}, {estimated_time})</code>';
                                    }
                                    elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                                    {
                                        $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {phone}, {passport_no}, {room_no}, {nationality}, {age}, {address}, {arrival_date}, {departure_date}, {message})</code>';
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



    // Update Mail Form Data When Change Tab
    public function updateByLangCode(Request $request)
    {
        // Shop ID
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $mail_form_id = $request->mail_form_id;
        $active_lang_code = $request->active_lang_code;
        $next_lang_code = $request->next_lang_code;

        try
        {
            $update_form_key = $active_lang_code."_form";

            $mail_form = MailForm::find($mail_form_id);
            $mail_form->form = $request->mail_form_text;
            $mail_form->$update_form_key = $request->mail_form_text;
            $mail_form->update();

            $html_data = $this->getEditMailFormData($next_lang_code,$mail_form_id);

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



    // Get Mail Form Data By Language Code & Mail Form ID
    public function getEditMailFormData($current_lang_code,$mail_form_id)
    {
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        // Mail Form Details
        $mail_form_details = MailForm::where('id',$mail_form_id)->first();

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
                    $html .= '<form id="editMailFormsForm" enctype="multipart/form-data">';

                        $html .= csrf_field();
                        $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$current_lang_code.'">';
                        $html .= '<input type="hidden" name="mail_form_id" id="mail_form_id" value="'.$mail_form_details['id'].'">';

                        $html .= '<div class="row mb-3">';
                            $html .= '<div class="col-md-12">';
                                if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                                {
                                    $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Owner') .'</label>';
                                }
                                elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                                {
                                    $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Customer') .'</label>';
                                }
                                elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                                {
                                    $html .= '<label for="mail_form_text" class="form-label">'. __('Check In Mail Form') .'</label>';
                                }
                                $html .= '<textarea name="mail_form_text" id="mail_form_text" class="form-control">'.$mail_form_details[$current_lang_code."_form"].'</textarea>';
                                if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                                {
                                    $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {payment_method}, {items}, {total})</code>';
                                }
                                elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                                {
                                    $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {order_status}, {payment_method}, {items}, {total}, {estimated_time})</code>';
                                }
                                elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                                {
                                    $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {phone}, {passport_no}, {room_no}, {nationality}, {age}, {address}, {arrival_date}, {departure_date}, {message})</code>';
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
                    $html .= '<form id="editMailFormsForm" enctype="multipart/form-data">';

                        $html .= csrf_field();
                        $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                        $html .= '<input type="hidden" name="option_id" id="option_id" value="'.$mail_form_details['id'].'">';

                        $html .= '<div class="row mb-3">';
                        $html .= '<div class="col-md-12">';
                            if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                            {
                                $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Owner') .'</label>';
                            }
                            elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                            {
                                $html .= '<label for="mail_form_text" class="form-label">'. __('Orders Mail Form Customer') .'</label>';
                            }
                            elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                            {
                                $html .= '<label for="mail_form_text" class="form-label">'. __('Check In Mail Form') .'</label>';
                            }
                            $html .= '<textarea name="mail_form_text" id="mail_form_text" class="form-control">'.$mail_form_details[$primary_lang_code."_form"].'</textarea>';
                            if($mail_form_details['mail_form_key'] == 'orders_mail_form_client')
                            {
                                $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {payment_method}, {items}, {total})</code>';
                            }
                            elseif($mail_form_details['mail_form_key'] == 'orders_mail_form_customer')
                            {
                                $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {order_status}, {payment_method}, {items}, {total}, {estimated_time})</code>';
                            }
                            elseif ($mail_form_details['mail_form_key'] == 'check_in_mail_form')
                            {
                                $html .= '<code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {phone}, {passport_no}, {room_no}, {nationality}, {age}, {address}, {arrival_date}, {departure_date}, {message})</code>';
                            }
                        $html .= '</div>';
                    $html .= '</div>';

                    $html .= '</form>';
                $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }


     // Update the specified resource in storage.
     public function update(Request $request)
     {
        // Shop ID
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $mail_form_id = $request->mail_form_id;
        $active_lang_code = $request->active_lang_code;

        try
        {
            $update_form_key = $active_lang_code."_form";

            $mail_form = MailForm::find($mail_form_id);
            $mail_form->form = $request->mail_form_text;
            $mail_form->$update_form_key = $request->mail_form_text;
            $mail_form->update();

            return response()->json([
                'success' => 1,
                'message' => 'Mail Form has been Updated SuccessFully...',
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
