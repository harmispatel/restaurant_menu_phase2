<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSettings;
use Illuminate\Support\Facades\Auth;
use Mail;


class ContactController extends Controller
{
    public function index()
    {
        return view('client.contact.contact');
    }

    public function send(Request $request)
    {
        $from_mail = Auth::user()->email;

        // Keys
        $keys = ([
            'contact_us_email',
            'contact_us_subject',
        ]);

        $settings = [];

        foreach($keys as $key)
        {
            $query = AdminSettings::select('value')->where('key',$key)->first();
            $settings[$key] = isset($query->value) ? $query->value : '';
        }
        $title = $settings['contact_us_subject'].'-'.$request->title.' Email:'.$from_mail;

        $contact_message = $request->message;
        $email_array =  unserialize($settings['contact_us_email']);
        $data = [$title,$contact_message];

        if(count($email_array) > 0)
        {
            foreach($email_array as $to_mail)
            {

                $to = $to_mail;
                $subject = $title;
                $txt = $contact_message;
                $headers = "From: ".$from_mail. "\r\n";

                mail($to,$subject,$txt,$headers);

               /*  Mail::send([],$data,function ($message) use ($to_mail,$from_mail,$title,$contact_message)
                {
                        $message->from($from_mail, "Contact");
                        $message->to($to_mail);
                        $message->subject($title);
                        $message->setbody($contact_message);
                }); */
            }
        }
        else
        {
            // Mail::send([],$data,function ($message) use ($to_mail,$from_mail,$title,$contact_message)
            //     {
            //             $message->from($from_mail, "Contact");
            //             $message->to($to_mail);
            //             $message->subject($title);
            //             $message->setbody($contact_message);
            //     });
        }


        return redirect()->route('contact')->with('success','Email has been Sent SuccessFully....');

    }


}
