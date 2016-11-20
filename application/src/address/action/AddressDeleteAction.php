<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 10:14
 */

namespace app\src\address\action;


use app\src\address\logic\AddressLogic;
use app\src\base\action\BaseAction;
use app\src\user\logic\MemberConfigLogic;

class AddressDeleteAction extends BaseAction
{
    public function delete($map){

        $uid = $map['uid'];
        $id  = $map['id'];

        $logic = new AddressLogic();

        $result = $logic->delete($map);

        if($result['status']){

            $result = $logic->getInfo(['uid'=>$uid]);
            $address_id = 0;

            if($result['status'] && is_array($result['info']) && !empty($result['info'])){
                $address = $result['info'];
                $address_id = $address['id'];
            }

            $logic = new MemberConfigLogic();
            //
            $logic->save(['uid'=>$uid,'default_address'=>$id],['default_address'=>$address_id]);

            return $this->success(1);
        }

        return $this->error($result['info']);

    }
}