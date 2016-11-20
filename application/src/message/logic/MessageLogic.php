<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-03
 * Time: 15:06
 */

namespace app\src\message\logic;


use app\src\base\logic\BaseLogic;
use app\src\message\model\Message;

class MessageLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new Message());
    }
}