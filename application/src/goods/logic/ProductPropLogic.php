<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-05
 * Time: 17:12
 */

namespace app\src\goods\logic;


use app\src\base\logic\BaseLogic;
use app\src\goods\model\ProductProp;
use think\Db;

class ProductPropLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new ProductProp());
    }

    /**
     * 获取属性列表
     * @author hebidu <email:346551990@qq.com>
     * @param $id
     * @return array
     */
    public function queryPropList($id){

        $result = Db::table("itboye_product_prop")->alias("prop")
            ->field("prop.*,dt1.propname as prop_name,dt2.valuename as value_name")
            ->join("itboye_category_prop as dt1","dt1.id = prop.prop_id","LEFT")
            ->join("itboye_category_propvalue as dt2","dt2.id = prop.value_id","LEFT")
            ->where("prop.pid",$id)
            ->select();

        $tmp = [];

        foreach ($result as $vo){
            $key = md5($vo['prop_id']);
            if(!isset($tmp[$key])){
                $tmp[$key] = [
                    'prop_id'   => $vo['prop_id'],
                    'prop_name' => $vo['prop_name'],
                    'prop_value_list'=>[]
                ];
            }

            $val = [
                'value_id'=>$vo['value_id'],
                'value_name'=>$vo['value_name']
            ];

            array_push($tmp[$key]['prop_value_list'],$val);

        }

        $ret = [];

        foreach($tmp as &$item){
            array_push($ret,$item);
        }

        return $ret;
    }

}