<?php
/**
 * Created by PhpStorm
 * User: ProgrammerHasan
 * Date: 04-08-2021
 * Time: 06:00 AM
 */
namespace App\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;

class EasypaywayService
{

    public static function config(): array
    {
        return [
            'sandboxcURL' => 'https://sandbox.easypayway.com/payment/request.php',
            'securepaycURL' => 'https://securepay.easypayway.com/payment/request.php',
            'sandboxPostMethodURL' => 'https://sandbox.easypayway.com/payment/index.php',
            'securepayPostMethodURL' => 'https://securepay.easypayway.com/payment/index.php',
        ];
    }

    public function rand_string( $length ): string
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen( $chars );
        $str = null;
        for( $i = 0; $i < $length; $i++) { $str .= $chars[ rand( 0, $size - 1 ) ]; }
        return $str;
    }

    public function initiatePayment($request)
    {
        if(!$request->all()){return 'input required';}
        $fields_string = null;
        $cur_random_value= $this->rand_string(10);
        $url = self::config()['securepaycURL'];
        $fields = array(
            //Merchant Details
            'store_id' => env('EASYPAYWAY_STORE_ID'),
            'signature_key' => env('EASYPAYWAY_SIGNATURE_KEY'),
            'tran_id' => $cur_random_value,
            'success_url' => route('easypayway.success'),
            'fail_url' => route('easypayway.fail'),
            'cancel_url' => route('easypayway.cancel'),
            'ipn_url' => route('easypayway.ipn'),
            'amount' => $request->get('amount'),
            'currency' => 'BDT',
            'desc' => $request->get('desc'),
            'cus_name' => $request->get('name'),
            'cus_email' => 'abc@abc.com',
            'cus_add1' => 'House B-158, Road 22',
            'cus_add2' => 'Mohakhali DOHS',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1206',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $request->get('phone'),);
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        $fields_string = rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $responseJSON = json_decode($result, true);
        return redirect(url($responseJSON));
    }

}
