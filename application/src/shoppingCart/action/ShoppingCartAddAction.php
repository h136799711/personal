<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 15:27
 */

namespace app\src\shoppingCart\action;

use app\src\base\helper\ValidateHelper;
use app\src\goods\logic\ProductLogic;
use app\src\goods\logic\ProductSkuLogic;
use app\src\base\action\BaseAction;
use app\src\shoppingCart\logic\ShoppingCartLogic;

class ShoppingCartAddAction extends BaseAction
{
    /**
     * 1. 库存是否足够
     * 2. 最小起订量是否满足
     * 3. 最大限购数量是否满足
     *
     * @param $detail
     * @param $sku
     * @param $count
     * @return bool|mixed
     */
    private function check($detail,$sku,$count){

        //TODO: 判断起订量

        if($count > $sku['quantity']){
            return lang('err_cart_quantity',['quantity'=>$sku['quantity']]);
        }

        return false;
    }

    /**
     * 加入购物车
     * @param $entity
     * @return array
     */
    public function add($entity){
        $uid = $entity['uid'];
        $id = $entity['id'];

        $count = $entity['count'];
        $sku_pkid = $entity['sku_pkid'];

        //TODO: uid,id是否合法的验证

        $logic = new ProductSkuLogic();
        $productLogic = new ProductLogic();
        $result = $productLogic->detail($id);
        
        if(!$result['status']){
            return $this->error($result['info']);
        }

        if(empty($result['info'])){
            return $this->error(lang('err_cart_invalid_sku_pkid'));
        }

        $detail = $result['info'];
        $cur_sku = [];
        foreach ($detail['sku_list'] as $sku){
            if($sku['sku_pkid'] == $sku_pkid){
                $cur_sku = $sku;
                break;
            }
        }

        if(count($cur_sku) == 0){
            return $this->error(lang('err_cart_invalid_sku_pkid'));
        }

        $check_result = $this->check($detail,$cur_sku,$count);
        if($check_result !== false){
            return $this->error($check_result);
        }

        $now_time = time();
        $item = [
            'uid'=>$uid,
            'create_time'=>$now_time,
            'update_time'=>$now_time,
            'store_id'=>$detail['store_id'],
            'p_id'=>$id,
//            'product_code'=>$detail['product_code'],
            'sku_id'=>$cur_sku['sku_id'],
            'sku_desc'=>$cur_sku['sku_desc'],
            'icon_url'=>$cur_sku['icon_url'],
            'count'=>$count,
            'name'=>$detail['name'],
            'express'=>0,
            'template_id'=>$detail['template_id'],
            'price'=>$cur_sku['price'],
            'ori_price'=>$cur_sku['ori_price'],
            'psku_id'=>$cur_sku['sku_pkid'],
            'weight'=>$detail['weight'],
            'tax_rate'=>0,
            'group_id'=>0,
            'package_id'=>0
        ];

        $logic  = new ShoppingCartLogic();

        $result = $logic->getInfo(['p_id'=>$id,'uid'=>$uid,'psku_id'=>$cur_sku['sku_pkid']]);

        if(ValidateHelper::legalArrayResult($result)){
            //更新
            $info = $result['info'];
            $item['count'] = intval($item['count']) + intval($info['count']);
            unset($item['create_time']);
            $result = $logic->save(['id'=>$info['id']],$item);
        }else{
            //增加
            $result = $logic->add($item);
        }
        return $this->result($result);
    }
}