<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Auth;
use App\Mail\ClientSupport;
use Mail;


class ContactController extends Controller
{
    public function index()
    {
        return view('client.contact.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
        ]);

        // Get Client Details
        $user_details = Auth::user();
        $shop_name = (isset($user_details->hasOneShop->shop['name'])) ? $user_details->hasOneShop->shop['name'] : '';
        $shop_url = (isset($user_details->hasOneShop->shop['shop_slug'])) ? $user_details->hasOneShop->shop['shop_slug'] : '';
        $shop_id = (isset($user_details->hasOneShop->shop['id'])) ? $user_details->hasOneShop->shop['id'] : '';
        $shop_url = asset($shop_url);
        $shop_name = '<a href="'.$shop_url.'">'.$shop_name.'</a>';

        $shop_settings = getClientSettings($shop_id);

        $shop_theme_id = isset($shop_settings['shop_active_theme']) ? $shop_settings['shop_active_theme'] : '';
        $theme_settings = themeSettings($shop_theme_id);      

        $layout = isset($theme_settings['desk_layout']) ? $theme_settings['desk_layout'] : ''; 

        if($layout == 'layout_1'){
            // Shop Logo
            $shop_logo = (isset($shop_settings['logo_layout_1']) && !empty($shop_settings['logo_layout_1'])) ? $shop_settings['logo_layout_1'] : '';
        }elseif($layout == 'layout_2'){
            // Shop Logo
            $shop_logo = (isset($shop_settings['logo_layout_2']) && !empty($shop_settings['logo_layout_2'])) ? $shop_settings['logo_layout_2'] : '';
        }elseif($layout == 'layout_3'){
            // Shop Logo
            $shop_logo = (isset($shop_settings['logo_layout_3']) && !empty($shop_settings['logo_layout_3'])) ? $shop_settings['logo_layout_3'] : '';
        }else{
            $shop_logo = "";
        }

        if(!empty($shop_logo)){
            $shop_logo = '<img src="'.$shop_logo.'" width="200">';
        }else{
            $shop_logo = '<img src="'.asset('public/client_images/not-found/your_logo_1.png'). '" width="200">';
        }

        // Get To Mails & Subject
        $admin_settings = getAdminSettings();
        $contact_us_mail_template = (isset($admin_settings['contact_us_mail_template'])) ? $admin_settings['contact_us_mail_template'] : '';

        // Client Message
        $contact_message = $request->message;

        // To Mails
        $email_array =  (isset($admin_settings['contact_us_email']) && !empty($admin_settings['contact_us_email'])) ? unserialize($admin_settings['contact_us_email']) : [];

        // If found to Mails then sent Mail
        if(count($email_array) > 0 && !empty($contact_us_mail_template))
        {
            foreach($email_array as $email)
            {
                $to = $email;
                $subject = $request->title;

                $message = $contact_us_mail_template;
                $message = str_replace('{subject}',$subject,$message);
                $message = str_replace('{shop_logo}',$shop_logo,$message);
                $message = str_replace('{message}',$contact_message,$message);
                $message = str_replace('{shop_name}',$shop_name,$message);

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <'.$user_details['email'].'>' . "\r\n";

                mail($to,$subject,$message,$headers);
            }
        }
        else
        {
            return redirect()->route('contact')->with('error','Internal Server Error!');
        }
        return redirect()->route('contact')->with('success','Email has been Sent SuccessFully....');
    }
}
