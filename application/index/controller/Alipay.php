<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-05
 * Time: 16:37
 */
namespace app\index\controller;

use app\src\alipay\action\AlipayNotifyAction;
use app\src\alipay\po\AlipayNotfiyPo;
use think\Controller;

/**
 * 支付宝
 * Class Alipay
 * @author hebidu <email:346551990@qq.com>
 * @package app\index\controller
 */
class Alipay extends Controller{


    public function notify(){

        addLog("Alipay_notify",$_GET,$_POST,"支付宝异步通知");

        $alipayNotfiyPo = new AlipayNotfiyPo();

        $action = new AlipayNotifyAction($alipayNotfiyPo);

        $arr = $_POST;
        
        $alipayNotfiyPo->init($arr);
        
        $result =  $action->notify();

        echo $result['info'];
    }


}