<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-14
 * Time: 9:48
 */

namespace app\src\shoppingCart\action;


use app\src\base\action\BaseAction;

class ShoppingCartBulkAddAction extends BaseAction
{
    /**
     *
     * @param $uid
     * @param $pid
     * @param $count_arr
     * @param $sku_id_arr
     * @author hebidu <email:346551990@qq.com>
     * @return array
     */
    public function bulkAdd($uid,$pid,$count_arr,$sku_id_arr){

        $action = new ShoppingCartAddAction();
        $error = "";
        for( $i = 0 ; $i < count($count_arr) ; $i ++ ){
           $result = $action->add(['uid'=>$uid,'id'=>$pid,'count'=>$count_arr[$i],'sku_pkid'=>$sku_id_arr[$i]]);
            if(!$result['status']){
                $error .= $result['info'];
            }
        }

        if(!empty($error)){
            return $this->error($error);
        }
        return $this->success(lang('success'));
    }
}