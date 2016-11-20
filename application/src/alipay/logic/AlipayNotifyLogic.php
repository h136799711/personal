<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 22:15
 */

namespace app\src\alipay\logic;


use app\src\alipay\model\AlipayNotify;
use app\src\base\logic\BaseLogic;

class AlipayNotifyLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new AlipayNotify());
    }
}