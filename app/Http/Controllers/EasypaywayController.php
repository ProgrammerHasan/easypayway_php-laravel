<?php
namespace App\Http\Controllers;
use App\Order;
use App\Services\EasypaywayService;
use Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\Http\Controllers;

class EasypaywayController extends Controller
{

    public function index(Request $request)
    {
        $order = Order::findOrFail($request->session()->get('order_id'));
        $post_data = array();
        $post_data['total_amount'] = $order->grand_total; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $orderId = "orderId_".$order->id;

        $info = [
            'amount'          => $post_data['total_amount'],
        ];

        return EasypaywayService::initiatePayment($info);
    }

    public function success(Request $request)
    {

    }

    public function fail(Request $request)
    {

    }

    public function cancel(Request $request)
    {

    }


}
