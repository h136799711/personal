<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-13
 * Time: 16:01
 */

namespace app\web\controller;


use app\src\paypal\facade\PaypalFacade;
class Paypal
{
    
    public function index(){
        $facade = new PaypalFacade();
        $facade->saveCreditCardInfoToVault();
    }
}