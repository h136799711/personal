<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 18:02
 */

namespace app\src\address\action;


use app\src\address\logic\AddressLogic;
use app\src\base\action\BaseAction;

class AddressAddAction extends BaseAction
{
    public function add($entity){
        $logic = new AddressLogic();
        if(!isset($entity['uid'])) return $this->error(lang('uid_need'));

        $uid = $entity['uid'];

        $result = $logic->count(['uid'=>$uid]);

        if(!$result['status']) return $this->error(lang('fail'));

        $count = intval($result['info']);
        
        if($count >= 10){
            return $this->error(lang("exceed_limit",['param'=>10]));
        }
        
        $result = $logic->add($entity,"id");

        return $this->result($result);
    }
}