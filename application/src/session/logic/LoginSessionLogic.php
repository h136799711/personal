<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-01
 * Time: 10:47
 */

namespace app\src\session\logic;


use app\src\base\logic\BaseLogic;
use app\src\session\model\LoginSession;

class LoginSessionLogic extends BaseLogic
{
    /**
     * @return mixed
     */
    protected function _init()
    {
        $this->setModel(new LoginSession());
    }
    
}