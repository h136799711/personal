<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-17
 * Time: 8:57
 */
namespace app\src\user\action;


use app\src\base\action\BaseAction;
use app\src\base\facade\AccountFacade;
use app\src\user\facade\DefaultUserFacade;

class RegisterAction extends BaseAction
{
    protected  $facade;
    
    public function getUserFacade(){
        $this->facade = new AccountFacade(new DefaultUserFacade());
        return $this->facade;
    }

    public function register($entity){
        
        $result = $this->getUserFacade()->register($entity);
        return $this->result($result);
    }

}