<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-13
 * Time: 17:43
 */

namespace app\index\controller;

use app\src\paysystem\logic\PaySuccessHook;
use think\Controller;

/**
 * Paypal
 * Class Paypal
 * @author hebidu <email:346551990@qq.com>
 * @package app\index\controller
 */
class Paypal extends Controller{

    private $webhookId = "7LS42255C93172233";

    public function index(){
        $input = file_get_contents("php://input");

        $obj = json_decode($input,JSON_OBJECT_AS_ARRAY);
        $event_type = isset($obj['event_type']) ? $obj['event_type']:"";
        $resource_type = isset($obj['resource_type']) ? $obj["resource_type"] : "";
        $resource = isset($obj['resource']) ? $obj["resource"] : "";

        $amount = isset($resource['amount']) ? $resource["amount"] : "";
        $out_trade_no =  isset($resource['id']) ? $resource["id"] : "";

        $total = isset($amount['total']) ? $amount["total"] : "";
        $currency = isset($amount['currency']) ? $amount["currency"] : "";

        addLog("Paypal_notify",$obj,$resource,"Paypal异步通知");
        if($resource_type == "sale" && $event_type == "PAYMENT.SALE.COMPLETED"){

            addLog("Paypal_notify".$total.$currency,$obj,$resource,"Paypal交易完成异步通知");

            $result = (new PaySuccessHook())->success("",$total,$out_trade_no,$currency);

        }



//        {
//            "id": "WH-2WR32451HC0233532-67976317FL4543714",
//	"create_time": "2014-10-23T17:23:52Z",
//	"resource_type": "sale",
//	"event_type": "PAYMENT.SALE.COMPLETED",
//	"summary": "A successful sale payment was made for $ 0.48 USD",
//	"resource": {
//            "amount": {
//                  "total": "0.48",
//			        "currency": "USD"
//		        },
//		        "id": "80021663DE681814L",
//		"parent_payment": "PAY-1PA12106FU478450MKRETS4A",
//		"update_time": "2014-10-23T17:23:04Z",
//		"clearing_time": "2014-10-30T07:00:00Z",
//		"state": "completed",
//		"payment_mode": "ECHECK",
//		"create_time": "2014-10-23T17:22:56Z",
//		"links": [
//			{
//                "href": "https://api.paypal.com/v1/payments/sale/80021663DE681814L",
//				"rel": "self",
//				"method": "GET"
//			},
//			{
//                "href": "https://api.paypal.com/v1/payments/sale/80021663DE681814L/refund",
//				"rel": "refund",
//				"method": "POST"
//			},
//			{
//                "href": "https://api.paypal.com/v1/payments/payment/PAY-1PA12106FU478450MKRETS4A",
//				"rel": "parent_payment",
//				"method": "GET"
//			}
//		],
//		"protection_eligibility_type": "ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE",
//		"protection_eligibility": "ELIGIBLE"
//	},
//	"links": [
//		{
//            "href": "https://api.paypal.com/v1/notifications/webhooks-events/WH-2WR32451HC0233532-67976317FL4543714",
//			"rel": "self",
//			"method": "GET",
//			"encType": "application/json"
//		},
//		{
//            "href": "https://api.paypal.com/v1/notifications/webhooks-events/WH-2WR32451HC0233532-67976317FL4543714/resend",
//			"rel": "resend",
//			"method": "POST",
//			"encType": "application/json"
//		}
//	],
//	"event_version": "1.0"
//}

        echo "ok";
    }

}