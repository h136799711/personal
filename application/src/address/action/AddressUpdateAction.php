<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 18:02
 */

namespace app\src\service\Address;


use app\src\service\BaseAction;

class AddressUpdateAction extends BaseAction
{
    public function update($map,$entity){
        $logic = new AddressLogic();

        
        return $this->result($result);
    }
}