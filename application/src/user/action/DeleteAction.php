<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 11:11
 */
namespace app\src\user\action;

use app\src\base\action\BaseAction;
use app\src\base\facade\AccountFacade;
use app\src\user\facade\DefaultUserFacade;

class DeleteAction extends BaseAction
{

    protected  $facade;

    public function getUserFacade(){
        $this->facade = new AccountFacade(new DefaultUserFacade());
        return $this->facade;
    }

    public function delete($mobile){
        $result = $this->getUserFacade()->delete(['mobile'=>$mobile]);
        return $this->result($result);
    }

}