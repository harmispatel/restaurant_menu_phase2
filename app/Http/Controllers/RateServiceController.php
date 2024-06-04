<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ShopRateServies,ClientSettings,Languages, AdditionalLanguage};
use Illuminate\Support\Facades\Auth;


class RateServiceController extends Controller
{
    //
    public function index()
    {
        $rateServices = ShopRateServies::where('shop_id', Auth::user()->hasOneShop->shop['id'])->get();
        return view('client.rate_services.rate_services', compact('rateServices'));
    }

    public function insert()
    {
        return view('client.rate_services.new_rate_services');
    }

    public function store(Request $request)
    {

        // Shop Id
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        // Language Settings
        $language_settings = clientLanguageSettings($shop_id);
        $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

        // Language Details
        $language_detail = Languages::where('id',$primary_lang_id)->first();
        $lang_code = isset($language_detail->code) ? $language_detail->code : '';

        $rate_name_key = $lang_code."_name";

        $request->validate([
            'name' => 'required',
        ]);

        try {

            $rate = new ShopRateServies();
            $rate->name = $request->name;
            $rate->shop_id = $shop_id;
            $rate->$rate_name_key = $request->name;
            $rate->status = 1;
            $rate->save();
            return response()->json([
                'success' => 1,
                'message' => "Rate Service has been Inserted SuccessFully...",
            ]);

            // $data = $request->except('_token');
            // // $RateServies = ShopRateServies::create($data);
            // return redirect()->route('rate.services')->with('success', 'Rate Service has been Inserted SuccessFully....');
        } catch (\Throwable $th) {
            // return redirect()->route('rate.services')->with('error', 'Internal Server Error');
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }

    public function changeStatus(Request $request)
    {
        // Service ID & Status

        $servicesId = $request->id;
        $status = $request->status;

        try {
            $RateServies = ShopRateServies::find($servicesId);
            $RateServies->status = $status;
            $RateServies->save();


            return response()->json([
                'success' => 1,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => 0,
            ]);
        }
    }

    public function edit(Request $request)
    {
        $rate_service_id = $request->id;
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        try {
            // Get Language Settings
            $language_settings = clientLanguageSettings($shop_id);
            $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

            // Primary Language Details
            $primary_language_detail = Languages::where('id',$primary_lang_id)->first();
            $primary_lang_code = isset($primary_language_detail->code) ? $primary_language_detail->code : '';
            $primary_lang_name = isset($primary_language_detail->name) ? $primary_language_detail->name : '';
            $rate_name_key = $primary_lang_code."_name";

            // Additional Languages
            $additional_languages = AdditionalLanguage::where('shop_id',$shop_id)->get();

            // Tag Details
            $rate_service_details = ShopRateServies::where('id',$rate_service_id)->first();
            $rate_name = (isset($rate_service_details[$rate_name_key])) ? $rate_service_details[$rate_name_key] : '';

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
                         $html .= '<form id="editRateServiceForm" enctype="multipart/form-data">';

                             $html .= csrf_field();
                             $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                             $html .= '<input type="hidden" name="rate_id" id="rate_id" value="'.$rate_service_details['id'].'">';

                             $html .= '<div class="row mb-3">';
                                 $html .= '<div class="col-md-3">';
                                     $html .= '<label for="name" class="form-label">'. __('Name') .'</label>';
                                 $html .= '</div>';
                                 $html .= '<div class="col-md-9">';
                                     $html .= '<input type="text" name="name" id="name" class="form-control" value="'.$rate_name.'">';
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
                         $html .= '<form id="editRateServiceForm" enctype="multipart/form-data">';

                             $html .= csrf_field();
                             $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                             $html .= '<input type="hidden" name="rate_id" id="rate_id" value="'.$rate_service_details['id'].'">';

                             $html .= '<div class="row mb-3">';
                                 $html .= '<div class="col-md-3">';
                                     $html .= '<label for="name" class="form-label">'. __('Name') .'</label>';
                                 $html .= '</div>';
                                 $html .= '<div class="col-md-9">';
                                     $html .= '<input type="text" name="name" id="name" class="form-control" value="'.$rate_name.'">';
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

        } catch (\Throwable $th) {

            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }

    }

    public function update(Request $request)
    {
        // Shop ID
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $rate_id = $request->rate_id;
        $name = $request->name;
        $active_lang_code = $request->active_lang_code;
        $act_lang_name_key = $active_lang_code."_name";


        $request->validate([
            'name' => 'required',
        ]);
        try {
             // Update Shop Serivce
             $rate_service = ShopRateServies::find($rate_id);
             $rate_service->name = $name;
             $rate_service->$act_lang_name_key = $name;
             $rate_service->update();
            // $data = $request->except('_token', 'id');
            // $rateService = ShopRateServies::find($request->id);
            // $rateService->update($data);

            return response()->json([
                'success' => 1,
                'message' => 'Rate Service has been Updated SuccessFully...',
            ]);
            // return redirect()->route('rate.services')->with('success', 'Rate Service has been Updated SuccessFully....');
        } catch (\Throwable $th) {

            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
            // return redirect()->route('rate.services')->with('error', 'Internal Server Error');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        try {
            ShopRateServies::where('id', $id)->delete();

            return response()->json([
                'success' => 1,
                'message' => "Rate Service has been Removed SuccessFully..",
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => "Internal Server Error!",
            ]);
        }
    }

    public function emailRate(Request $request)
    {

        $clientID = isset(Auth::user()->id) ? Auth::user()->id : '';
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';


        try {

            if(!empty($clientID) && !empty($shop_id)) {
                $email_req = ClientSettings::where('client_id', $clientID)->where('shop_id', $shop_id)->where('key','grading-email-required')->first();
                $email_id = isset($email_req->id) ? $email_req->id : '';

                if(!empty($email_id) || $email_id != ''){
                    $email = ClientSettings::find($email_id);
                    $email->value = $request->status;
                    $email->update();
                }else{
                    $email = new ClientSettings();
                    $email->client_id = $clientID;
                    $email->shop_id = $shop_id;
                    $email->key = 'grading-email-required';
                    $email->value = $request->status;
                    $email->save();
                }
                return response()->json([
                    'success' => 1,
                    'message' => "Duration has been Updated SuccessFully...",
                ]);
            }else{
                return response()->json([
                    'success' => 0,
                    'message' => 'Oops, Something Went Wrong !',
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error !',
            ]);
        }
    }


    public function updateByLangCode(Request $request)
    {
        // Shop Id
        $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';

        $rate_id = $request->rate_id;
        $name = $request->name;
        $active_lang_code = $request->active_lang_code;
        $next_lang_code = $request->next_lang_code;
        $act_lang_name_key = $active_lang_code."_name";


        $request->validate([
            'name' => 'required',
        ]);
        try {

             // Update Tag
             $rate = ShopRateServies::find($rate_id);
             $rate->name = $name;
             $rate->$act_lang_name_key = $name;
             $rate->update();

             // Get HTML Data
             $html_data = $this->getEditRateServiceData($next_lang_code,$rate_id);

             return response()->json([
                'success' => 1,
                'message' => 'Data has been Updated SuccessFully...',
                'data' => $html_data,
            ]);


        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => 0,
                'message' => 'Internal Server Error!',
            ]);
        }
    }

     // Function for Get Tag Data
     public function getEditRateServiceData($current_lang_code,$rate_id)
     {
         $shop_id = isset(Auth::user()->hasOneShop->shop['id']) ? Auth::user()->hasOneShop->shop['id'] : '';
         $shop_slug = isset(Auth::user()->hasOneShop->shop['shop_slug']) ? Auth::user()->hasOneShop->shop['shop_slug'] : '';

         // Get Language Settings
         $language_settings = clientLanguageSettings($shop_id);
         $primary_lang_id = isset($language_settings['primary_language']) ? $language_settings['primary_language'] : '';

         // Primary Language Details
         $primary_language_detail = Languages::where('id',$primary_lang_id)->first();
         $primary_lang_code = isset($primary_language_detail->code) ? $primary_language_detail->code : '';
         $primary_lang_name = isset($primary_language_detail->name) ? $primary_language_detail->name : '';

         // Additional Languages
         $additional_languages = AdditionalLanguage::where('shop_id',$shop_id)->get();
         if(count($additional_languages) > 0)
         {
             $rate_name_key = $current_lang_code."_name";
         }
         else
         {
             $rate_name_key = $primary_lang_code."_name";
         }

         // Tag Details
         $rate_service_details = ShopRateServies::where('id',$rate_id)->first();
         $rate_name = isset($rate_service_details[$rate_name_key]) ? $rate_service_details[$rate_name_key] : '';

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
                         $html .= '<form id="editRateServiceForm" enctype="multipart/form-data">';

                             $html .= csrf_field();
                             $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$current_lang_code.'">';
                             $html .= '<input type="hidden" name="rate_id" id="rate_id" value="'.$rate_service_details['id'].'">';

                             $html .= '<div class="row mb-3">';
                                 $html .= '<div class="col-md-3">';
                                     $html .= '<label for="name" class="form-label">'. __('Name') .'</label>';
                                 $html .= '</div>';
                                 $html .= '<div class="col-md-9">';
                                     $html .= '<input type="text" name="name" id="name" class="form-control" value="'.$rate_name.'">';
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
                         $html .= '<form id="editRateServiceForm" enctype="multipart/form-data">';

                             $html .= csrf_field();
                             $html .= '<input type="hidden" name="active_lang_code" id="active_lang_code" value="'.$primary_lang_code.'">';
                             $html .= '<input type="hidden" name="rate_id" id="rate_id" value="'.$rate_service_details['id'].'">';

                             $html .= '<div class="row mb-3">';
                                 $html .= '<div class="col-md-3">';
                                     $html .= '<label for="name" class="form-label">'. __('Name') .'</label>';
                                 $html .= '</div>';
                                 $html .= '<div class="col-md-9">';
                                     $html .= '<input type="text" name="name" id="name" class="form-control" value="'.$rate_name.'">';
                                 $html .= '</div>';
                             $html .= '</div>';

                         $html .= '</form>';
                     $html .= '</div>';
                 $html .= '</div>';
         }

         return $html;

     }

}
