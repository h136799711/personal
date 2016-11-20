<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-15
 * Time: 17:05
 */

namespace app\src\user\action;


use app\src\base\action\BaseAction;
use app\src\base\facade\AccountFacade;
use app\src\user\facade\DefaultUserFacade;

class LoginAction extends BaseAction
{
    protected  $facade;
    
    public function getUserFacade(){
        $this->facade = new AccountFacade(new DefaultUserFacade());
        return $this->facade;
    }

    public function loginByCode($mobile,$code,$country){
        $result = $this->getUserFacade()->loginByCode($mobile,$code,$country);
        return $this->result($result);
    }
    
    public function login($username,$password,$country){
        $result = $this->getUserFacade()->login($username,$password,"",$country);
        return $this->result($result);
    }
}