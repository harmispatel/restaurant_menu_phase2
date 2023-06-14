<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\{Amount, Details, Item,ItemList,Payer,Payment,PaymentExecution,RedirectUrls,Transaction};
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Models\{Items,Shop,AdditionalLanguage,ItemPrice, OptionPrice, Order, OrderItems, UserShop};
use Exception;
use Magarrent\LaravelCurrencyFormatter\Facades\Currency;

class PaypalController extends Controller
{
    private $_api_context;

    public function payWithpaypal($shop_slug)
    {
        $checkout_type = session()->get('checkout_type');

        if(empty($checkout_type))
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','UnAuthorized Request!');
        }

        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';

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

        $discount_per = session()->get('discount_per');
        $discount_type = session()->get('discount_type');

        // Keys
        $name_key = $current_lang_code."_name";
        $label_key = $current_lang_code."_label";

        $final_amount = 0;

        $paypal_config = getPayPalConfig($shop_slug);
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_config['client_id'],
            $paypal_config['secret'])
        );
        $this->_api_context->setConfig($paypal_config['settings']);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Get Cart Details
        $cart = session()->get('cart', []);

        if(count($cart) == 0)
        {
            return redirect()->route('restaurant',$shop_slug);
        }

        // Add Items
        foreach($cart as $cart_val)
        {
            $otpions_arr = [];
            $item_price = 0.00;
            $total_amount = $cart_val['total_amount'];
            $total_amount_text = $cart_val['total_amount_text'];
            $categories_data = (isset($cart_val['categories_data']) && !empty($cart_val['categories_data'])) ? $cart_val['categories_data'] : [];

            if(count($categories_data) > 0)
            {
                foreach ($categories_data as $option_id)
                {
                    $my_opt = $option_id;
                    if(is_array($my_opt))
                    {
                        if(count($my_opt) > 0)
                        {
                            foreach ($my_opt as $optid)
                            {
                                $opt_price_dt = OptionPrice::where('id',$optid)->first();
                                $opt_price = (isset($opt_price_dt['price'])) ? $opt_price_dt['price'] : 0.00;
                                $item_price += $opt_price;
                            }
                        }
                    }
                    else
                    {
                        $opt_price_dt = OptionPrice::where('id',$my_opt)->first();
                        $opt_price = (isset($opt_price_dt['price'])) ? $opt_price_dt['price'] : 0.00;
                        $item_price += $opt_price;
                    }
                }
            }

            // Item Details
            $item_details = Items::where('id',$cart_val['item_id'])->first();
            $item_name = (isset($item_details[$name_key])) ? $item_details[$name_key] : '';

            //Price Details
            $price_detail = ItemPrice::where('id',$cart_val['option_id'])->first();
            $price_label = (isset($price_detail[$label_key])) ? $price_detail[$label_key] : '';
            $item_qty = $cart_val['quantity'];
            if(isset($price_detail['price']))
            {
                $item_price += $price_detail['price'];
            }

            if(!empty($price_label))
            {
                $otpions_arr[] = $price_label;
            }

            $final_amount += $total_amount;

            $item = new Item();
            $item->setName($item_name);
            $item->setCurrency($currency);
            $item->setQuantity($item_qty);
            $item->setPrice($item_price);
            $all_item[] = $item;
        }
        $item_list = new ItemList();
        $item_list->setItems($all_item);

        $amount = new Amount();
        $amount->setCurrency($currency);

        $final_amount = number_format($final_amount,2);

        if($discount_per > 0)
        {
            if($discount_type == 'fixed')
            {
                $discount_amount = $discount_per;
            }
            else
            {
                $discount_amount = ($final_amount * $discount_per) / 100;
            }
            $total = number_format($final_amount - $discount_amount,2);

            $amount->setTotal($total);
            $amount->setDetails( new Details([
                'subtotal' => $final_amount,
                'discount' => number_format($discount_amount,2),
                'currency' => $currency,
            ]));
        }
        else
        {
            $amount->setTotal($final_amount);
        }

        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Your transaction description')->setInvoiceNumber(uniqid());

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('paypal.payment.status',$shop_slug))->setCancelUrl(route('paypal.payment.status',$shop_slug));

        $payment = new Payment();
        $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array($transaction));

        try
        {
            $payment->create($this->_api_context);
        }
        catch (Exception $ex)
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','Payment Failed!');
        }

        foreach($payment->getLinks() as $link)
        {
            if($link->getRel() == 'approval_url')
            {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        session()->put('paypal_payment_id', $payment->getId());
        session()->save();

        if(isset($redirect_url))
        {
            // redirect to paypal
            return redirect($redirect_url);
        }
    }


    public function paymentCancel($shop_slug)
    {
       return redirect()->route('restaurant',$shop_slug)->with('error','Payment Cancel!');
    }


    public function getPaymentStatus($shop_slug, Request $request)
    {
        $cart = session()->get('cart', []);
        $discount_per = session()->get('discount_per');
        $discount_type = session()->get('discount_type');

        // Shop Details
        $data['shop_details'] = Shop::where('shop_slug',$shop_slug)->first();

        // Shop ID
        $shop_id = isset($data['shop_details']->id) ? $data['shop_details']->id : '';
        $shop_name = isset($data['shop_details']->name) ? $data['shop_details']->name : '';
        $shop_url = (isset($data['shop_details']->shop_slug)) ? $data['shop_details']->shop_slug : '';
        $shop_url = asset($shop_url);
        $shop_name = '<a href="'.$shop_url.'">'.$shop_name.'</a>';
        $shop_logo = (isset($data['shop_details']->logo)) ? $data['shop_details']->logo : '';
        $shop_logo = '<img src="'.$shop_logo.'" width="100">';

        $shop_user = UserShop::with(['user'])->where('shop_id',$shop_id)->first();
        $contact_emails = (isset($shop_user->user['contact_emails']) && !empty($shop_user->user['contact_emails'])) ? unserialize($shop_user->user['contact_emails']) : [];

        $shop_settings = getClientSettings($shop_id);

        // Order Mail Template
        $orders_mail_form_client = (isset($shop_settings['orders_mail_form_client'])) ? $shop_settings['orders_mail_form_client'] : '';
        $orders_mail_form_customer = (isset($shop_settings['orders_mail_form_customer'])) ? $shop_settings['orders_mail_form_customer'] : '';

        // Ip Address
        $user_ip = $request->ip();

        $final_amount = 0;
        $total_qty = 0;

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

        // Order Settings
        $order_settings = getOrderSettings($shop_id);

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

        // Keys
        $name_key = $current_lang_code."_name";
        $label_key = $current_lang_code."_label";

        $order_details = session()->get('order_details');

        $paypal_config = getPayPalConfig($shop_slug);
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_config['client_id'],
            $paypal_config['secret'])
        );
        $this->_api_context->setConfig($paypal_config['settings']);

        // Get the payment ID before session clear
        $payment_id = session()->get('paypal_payment_id');

        if(empty($request->PayerID) || empty($request->token))
        {
            return redirect()->route('restaurant',$shop_slug)->with('error', 'Payment failed!');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);

        //Execute the payment
        try
        {
            $result = $payment->execute($execution, $this->_api_context);
        }
        catch (\Throwable $th)
        {
            return redirect()->route('restaurant',$shop_slug)->with('error','Payment Failed!');
        }

        if($result->getState() == 'approved') // payment made
        {
            $checkout_type = $order_details['checkout_type'];
            $payment_method = $order_details['payment_method'];
            if($checkout_type == 'takeaway')
            {
                // New Order
                $order = new Order();
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname =  $order_details['firstname'];
                $order->lastname =  $order_details['lastname'];
                $order->email =  $order_details['email'];
                $order->phone =  $order_details['phone'];
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
                $order->is_new = $is_new;
                $order->table = $order_details['table'];
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }
            elseif($checkout_type == 'room_delivery')
            {
                // New Order
                $order = new Order();
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $order_details['firstname'];
                $order->lastname = $order_details['lastname'];
                $order->checkout_type = $checkout_type;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->is_new = $is_new;
                $order->room = $order_details['room'];
                $order->delivery_time = (isset($order_details['delivery_time'])) ? $order_details['delivery_time'] : '';
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }
            elseif($checkout_type == 'delivery')
            {
                // New Order
                $order = new Order();
                $order->shop_id = $shop_id;
                $order->ip_address = $user_ip;
                $order->firstname = $order_details['firstname'];
                $order->lastname = $order_details['lastname'];
                $order->email = $order_details['email'];
                $order->phone = $order_details['phone'];
                $order->address = $order_details['address'];
                $order->latitude = $order_details['latitude'];
                $order->longitude = $order_details['longitude'];
                $order->floor = $order_details['floor'];
                $order->door_bell = $order_details['door_bell'];
                $order->instructions = $order_details['instructions'];
                $order->checkout_type = $checkout_type;
                $order->is_new = $is_new;
                $order->payment_method = $payment_method;
                $order->order_status = $order_status;
                $order->estimated_time = (isset($order_settings['order_arrival_minutes']) && !empty($order_settings['order_arrival_minutes'])) ? $order_settings['order_arrival_minutes'] : '30';
                $order->save();
            }

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
                    if($discount_type == 'fixed')
                    {
                        $discount_amount = $discount_per;
                    }
                    else
                    {
                        $discount_amount = ($final_amount * $discount_per) / 100;
                    }
                    $update_order->discount_per = $discount_per;
                    $update_order->discount_type = $discount_type;
                    $update_order->discount_value = $final_amount - $discount_amount;
                }
                $update_order->order_total = $final_amount;
                $update_order->order_total_text = Currency::currency($currency)->format($final_amount);
                $update_order->total_qty = $total_qty;
                $update_order->update();

                $from_email = (isset($order_details['email'])) ? $order_details['email'] : '';

                if($checkout_type == 'takeaway' || $checkout_type == 'delivery')
                {
                    $order_dt = Order::with(['order_items'])->where('id',$order->id)->first();
                    $order_items = (isset($order_dt->order_items) && count($order_dt->order_items) > 0) ? $order_dt->order_items : [];

                    // Sent Mail to Shop Owner
                    if(count($contact_emails) > 0 && !empty($orders_mail_form_client))
                    {
                        foreach($contact_emails as $mail)
                        {
                            $to = $mail;
                            $subject = "New Order";
                            $fname = (isset($order_details['firstname'])) ? $order_details['firstname'] : '';
                            $lname = (isset($order_details['lastname'])) ? $order_details['lastname'] : '';

                            $message = $orders_mail_form_client;
                            $message = str_replace('{shop_logo}',$shop_logo,$message);
                            $message = str_replace('{shop_name}',$shop_name,$message);
                            $message = str_replace('{firstname}',$fname,$message);
                            $message = str_replace('{lastname}',$lname,$message);
                            $message = str_replace('{order_id}',$order->id,$message);
                            $message = str_replace('{order_type}',$checkout_type,$message);
                            $message = str_replace('{payment_method}',$payment_method,$message);

                            // Order Items
                            $order_html  = "";
                            $order_html .= '<div>';
                                $order_html .= '<table style="width:100%; border:1px solid gray;border-collapse: collapse;">';
                                    $order_html .= '<thead style="background:lightgray; color:white">';
                                        $order_html .= '<tr style="text-transform: uppercase!important;    font-weight: 700!important;">';
                                            $order_html .= '<th style="text-align: left!important;width: 60%;padding:10px">Item</th>';
                                            $order_html .= '<th style="text-align: center!important;padding:10px">Qty.</th>';
                                            $order_html .= '<th style="text-align: right!important;padding:10px">Item Total</th>';
                                        $order_html .= '</tr>';
                                    $order_html .= '</thead>';
                                    $order_html .= '<tbody style="font-weight: 600!important;">';

                                        if(count($order_items) > 0)
                                        {
                                            foreach($order_items as $order_item)
                                            {
                                                $item_dt = itemDetails($order_item['item_id']);
                                                $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                                                $options_array = (isset($order_item['options']) && !empty($order_item['options'])) ? unserialize($order_item['options']) : '';
                                                if(count($options_array) > 0)
                                                {
                                                    $options_array = implode(', ',$options_array);
                                                }

                                                $order_html .= '<tr>';

                                                    $order_html .= '<td style="text-align: left!important;padding:10px; border-bottom:1px solid gray;">';
                                                        $order_html .= '<div style="align-items: center!important;display: flex!important;">';
                                                            $order_html .= '<a style="display: inline-block;
                                                            flex-shrink: 0;position: relative;border-radius: 0.75rem;">';
                                                                $order_html .= '<span style="width: 50px;
                                                                height: 50px;display: flex;
                                                                align-items: center;
                                                                justify-content: center;
                                                                font-weight: 500;background-repeat: no-repeat;
                                                                background-position: center center;
                                                                background-size: cover;
                                                                border-radius: 0.75rem; background-image:url('.$item_image.')"></span>';
                                                            $order_html .= '</a>';
                                                            $order_html .= '<div style="display: block;    margin-left: 3rem!important;">';
                                                                $order_html .= '<a style="font-weight: 700!important;color: #7e8299;
                                                                ">'.$order_item->item_name.'</a>';

                                                                if(!empty($options_array))
                                                                {
                                                                    $order_html .= '<div style="color: #a19e9e;display: block;">'.$options_array.'</div>';
                                                                }
                                                                else
                                                                {
                                                                    $order_html .= '<div style="color: #a19e9e;display: block;"></div>';
                                                                }

                                                            $order_html .= '</div>';
                                                        $order_html .= '</div>';
                                                    $order_html .= '</td>';

                                                    $order_html .= '<td style="text-align: center!important;padding:10px; border-bottom:1px solid gray;">';
                                                        $order_html .= $order_item['item_qty'];
                                                    $order_html .= '</td>';

                                                    $order_html .= '<td style="text-align: right!important;padding:10px; border-bottom:1px solid gray;">';
                                                        $order_html .= $order_item['sub_total_text'];
                                                    $order_html .= '</td>';

                                                $order_html .= '</tr>';
                                            }
                                        }

                                    $order_html .= '</tbody>';
                                $order_html .= '</table>';
                            $order_html .= '</div>';
                            $message = str_replace('{items}',$order_html,$message);

                            // Order Total
                            $order_total_html = "";
                            $order_total_html .= '<div>';
                                $order_total_html .= '<table style="width:50%; border:1px solid gray;border-collapse: collapse;">';
                                    $order_total_html .= '<tbody style="font-weight: 700!important;">';
                                        $order_total_html .= '<tr>';
                                            $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Sub Total : </td>';
                                            $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">'.$order_dt->order_total_text.'</td>';
                                        $order_total_html .= '</tr>';

                                        if($order_dt->discount_per > 0)
                                        {
                                            $order_total_html .= '<tr>';
                                                $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Discount : </td>';
                                                if($discount_type == 'fixed')
                                                {
                                                    $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">- '.Currency::currency($currency)->format($order_dt->discount_per).'</td>';
                                                }
                                                else
                                                {
                                                    $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">- '.$order_dt->discount_per.'%</td>';
                                                }
                                            $order_total_html .= '</tr>';

                                            $order_total_html .= '<tr>';
                                                $order_total_html .= '<td style="padding:10px;">Total : </td>';
                                                $order_total_html .= '<td style="padding:10px;">';
                                                    $order_total_html .= Currency::currency($currency)->format($order_dt->discount_value);
                                                $order_total_html .= '</td>';
                                            $order_total_html .= '</tr>';
                                        }

                                    $order_total_html .= '</tbody>';
                                $order_total_html .= '</table>';
                            $order_total_html .= '</div>';
                            $message = str_replace('{total}',$order_total_html,$message);

                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                            // More headers
                            $headers .= 'From: <'.$from_email.'>' . "\r\n";

                            mail($to,$subject,$message,$headers);

                        }
                    }

                    // // Sent Mail to Customer
                    // if(!empty($from_email) && count($contact_emails) > 0 && !empty($orders_mail_form_customer))
                    // {
                    //     $to = $from_email;
                    //     $from = $contact_emails[0];
                    //     $subject = "Order Placed";
                    //     $fname = (isset($order_details['firstname'])) ? $order_details['firstname'] : '';
                    //     $lname = (isset($order_details['lastname'])) ? $order_details['lastname'] : '';

                    //     $message = $orders_mail_form_customer;
                    //     $message = str_replace('{shop_logo}',$shop_logo,$message);
                    //     $message = str_replace('{shop_name}',$shop_name,$message);
                    //     $message = str_replace('{firstname}',$fname,$message);
                    //     $message = str_replace('{lastname}',$lname,$message);
                    //     $message = str_replace('{order_id}',$order->id,$message);
                    //     $message = str_replace('{order_type}',$checkout_type,$message);
                    //     $message = str_replace('{payment_method}',$payment_method,$message);
                    //     $message = str_replace('{order_status}',$order_status,$message);

                    //     // Order Items
                    //     $order_html  = "";
                    //     $order_html .= '<div>';
                    //         $order_html .= '<table style="width:100%; border:1px solid gray;border-collapse: collapse;">';
                    //             $order_html .= '<thead style="background:lightgray; color:white">';
                    //                 $order_html .= '<tr style="text-transform: uppercase!important;    font-weight: 700!important;">';
                    //                     $order_html .= '<th style="text-align: left!important;width: 60%;padding:10px">Item</th>';
                    //                     $order_html .= '<th style="text-align: center!important;padding:10px">Qty.</th>';
                    //                     $order_html .= '<th style="text-align: right!important;padding:10px">Item Total</th>';
                    //                 $order_html .= '</tr>';
                    //             $order_html .= '</thead>';
                    //             $order_html .= '<tbody style="font-weight: 600!important;">';

                    //                 if(count($order_items) > 0)
                    //                 {
                    //                     foreach($order_items as $order_item)
                    //                     {
                    //                         $item_dt = itemDetails($order_item['item_id']);
                    //                         $item_image = (isset($item_dt['image']) && !empty($item_dt['image']) && file_exists('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image'])) ? asset('public/client_uploads/shops/'.$shop_slug.'/items/'.$item_dt['image']) : asset('public/client_images/not-found/no_image_1.jpg');
                    //                         $options_array = (isset($order_item['options']) && !empty($order_item['options'])) ? unserialize($order_item['options']) : '';
                    //                         if(count($options_array) > 0)
                    //                         {
                    //                             $options_array = implode(', ',$options_array);
                    //                         }

                    //                         $order_html .= '<tr>';

                    //                             $order_html .= '<td style="text-align: left!important;padding:10px; border-bottom:1px solid gray;">';
                    //                                 $order_html .= '<div style="align-items: center!important;display: flex!important;">';
                    //                                     $order_html .= '<a style="display: inline-block;
                    //                                     flex-shrink: 0;position: relative;border-radius: 0.75rem;">';
                    //                                         $order_html .= '<span style="width: 50px;
                    //                                         height: 50px;display: flex;
                    //                                         align-items: center;
                    //                                         justify-content: center;
                    //                                         font-weight: 500;background-repeat: no-repeat;
                    //                                         background-position: center center;
                    //                                         background-size: cover;
                    //                                         border-radius: 0.75rem; background-image:url('.$item_image.')"></span>';
                    //                                     $order_html .= '</a>';
                    //                                     $order_html .= '<div style="display: block;    margin-left: 3rem!important;">';
                    //                                         $order_html .= '<a style="font-weight: 700!important;color: #7e8299;
                    //                                         ">'.$order_item->item_name.'</a>';

                    //                                         if(!empty($options_array))
                    //                                         {
                    //                                             $order_html .= '<div style="color: #a19e9e;display: block;">'.$options_array.'</div>';
                    //                                         }
                    //                                         else
                    //                                         {
                    //                                             $order_html .= '<div style="color: #a19e9e;display: block;"></div>';
                    //                                         }

                    //                                     $order_html .= '</div>';
                    //                                 $order_html .= '</div>';
                    //                             $order_html .= '</td>';

                    //                             $order_html .= '<td style="text-align: center!important;padding:10px; border-bottom:1px solid gray;">';
                    //                                 $order_html .= $order_item['item_qty'];
                    //                             $order_html .= '</td>';

                    //                             $order_html .= '<td style="text-align: right!important;padding:10px; border-bottom:1px solid gray;">';
                    //                                 $order_html .= $order_item['sub_total_text'];
                    //                             $order_html .= '</td>';

                    //                         $order_html .= '</tr>';
                    //                     }
                    //                 }

                    //             $order_html .= '</tbody>';
                    //         $order_html .= '</table>';
                    //     $order_html .= '</div>';
                    //     $message = str_replace('{items}',$order_html,$message);

                    //     // Order Total
                    //     $order_total_html = "";
                    //     $order_total_html .= '<div>';
                    //         $order_total_html .= '<table style="width:50%; border:1px solid gray;border-collapse: collapse;">';
                    //             $order_total_html .= '<tbody style="font-weight: 700!important;">';
                    //                 $order_total_html .= '<tr>';
                    //                     $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Sub Total : </td>';
                    //                     $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">'.$order_dt->order_total_text.'</td>';
                    //                 $order_total_html .= '</tr>';

                    //                 if($order_dt->discount_per > 0)
                    //                 {
                    //                     $order_total_html .= '<tr>';
                    //                         $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">Discount : </td>';
                    //                         $order_total_html .= '<td style="padding:10px; border-bottom:1px solid gray">- '.$order_dt->discount_per.'%</td>';
                    //                     $order_total_html .= '</tr>';

                    //                     $order_total_html .= '<tr>';
                    //                         $order_total_html .= '<td style="padding:10px;">Total : </td>';
                    //                         $order_total_html .= '<td style="padding:10px;">';
                    //                             $order_total_html .= Currency::currency($currency)->format($order_dt->discount_value);
                    //                         $order_total_html .= '</td>';
                    //                     $order_total_html .= '</tr>';
                    //                 }

                    //             $order_total_html .= '</tbody>';
                    //         $order_total_html .= '</table>';
                    //     $order_total_html .= '</div>';
                    //     $message = str_replace('{total}',$order_total_html,$message);

                    //     $headers = "MIME-Version: 1.0" . "\r\n";
                    //     $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    //     // More headers
                    //     $headers .= 'From: <'.$from.'>' . "\r\n";

                    //     mail($to,$subject,$message,$headers);
                    // }

                }

            }


            session()->forget('cart');
            session()->forget('checkout_type');
            session()->forget('order_details');
            session()->forget('paypal_payment_id');
            session()->forget('discount_per');
            session()->forget('discount_type');
            session()->forget('cust_lat');
            session()->forget('cust_long');
            session()->forget('cust_address');
            session()->save();

            return redirect()->route('shop.checkout.success',[$shop_slug,encrypt($order->id)]);
        }

        return redirect()->route('paypal.payment.cancel',$shop_slug);
    }
}
