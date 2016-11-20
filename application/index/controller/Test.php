<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-02
 * Time: 16:08
 */

namespace app\index\controller;


use app\src\alibaichuan\service\OpenIMUserService;
use app\src\rfpay\po\RfNoCardBalanceReq;
use app\src\rfpay\po\RfNoCardOrderCreateReq;
use app\src\rfpay\service\RfNoCardOrderService;
use app\src\rfpay\utils\RsaUtils;
use app\src\user\logic\MemberConfigLogic;
use think\Controller;

class Test extends Controller
{

    /**
     *
     */
    public function testOpenIMUser(){
        $id = $_GET['id'];
        $result = (new MemberConfigLogic())->getInfo(['uid'=>$id]);

        if(!$result['status'] || empty($result['info']) ){
            dump("用户id错误");
            exit;
        }
        $openid = $result['info']['alibaichuan_id'];
        var_dump($openid);
        $service = new OpenIMUserService();
        $result = $service->get($openid);
        dump($result);
    }

    /**
     * @author hebidu <email:346551990@qq.com>
     *
     */
    public function balance(){
        $req = new RfNoCardBalanceReq();
        $req->setLinkId("T".time());
        $service = new RfNoCardOrderService();
        $resp = $service->balance($req);
        if($resp->isSuccess()){
            dump("success");
        }

        dump($resp);
    }

    public function sendSms(){

//        $req = new RfNoCardOrderSmsReq();
//        $req->setOrderNo("100000");
//        $service = new RfNoCardOrderSmsService();
//        $result = $service->send($req);
//        dump($result);
    }

    public function request(){

        $req = new RfNoCardOrderCreateReq();
        $req->setTestData();
        $req->setLinkId($req->getRandomLinkId());
        $req->setAmount("1000");
        $service = new RfNoCardOrderService();
        $result = $service->create($req);
        dump($result);
    }

    public function index(){
        RsaUtils::test();
        
//        $key = "12456ds98d123456";
//        $data = "123456";
//
//
//        $encrypt = AesUtils::encrypt($data,$key);
//
//        echo $data." <br/>";
//        $hexEncrypt = AesUtils::toHexStr($encrypt);
//        echo "==encrypt=<br/>";
//        echo $hexEncrypt."<br/>";
//
//        $decrypt = AesUtils::decrypt(AesUtils::toBinStr($hexEncrypt),$key);
//        echo "<br/>==decrypt=<br/>";
//        echo $decrypt;

    }
}