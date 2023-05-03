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

        // Keys
        $keys = ([
            'contact_us_email',
            'contact_us_subject',
        ]);

        // Get To Mails & Subject
        $settings = [];
        foreach($keys as $key)
        {
            $query = AdminSettings::select('value')->where('key',$key)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }

        // Get Subject from Site
        $subject_content = (isset($settings['contact_us_subject'])) ? $settings['contact_us_subject'] : 'Smart QR Support |';

        // Client Message
        $contact_message = $request->message;

        // To Mails
        $email_array =  (isset($settings['contact_us_email']) && !empty($settings['contact_us_email'])) ? unserialize($settings['contact_us_email']) : [];

        // If found to Mails then sent Mail
        if(count($email_array) > 0)
        {
            foreach($email_array as $email)
            {
                $subject = $subject_content." ".$request->title;

                $data = [
                    'message' => $contact_message,
                    'subject' => $subject,
                    'client_details' => $user_details,
                ];

                Mail::to($email)->send(new ClientSupport($data));

                // mail($mail,$data['subject'],$data['description']);
            }
        }
        else
        {
            return redirect()->route('contact')->with('error','Internal Server Error!');
        }

        return redirect()->route('contact')->with('success','Email has been Sent SuccessFully....');

    }


}
