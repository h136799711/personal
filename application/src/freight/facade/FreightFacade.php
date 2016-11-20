<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 14:23
 */

namespace app\src\freight\facade;


class FreightFacade
{

    /**
     * 按商品重量来计算
     * @param $items
     * @param $receive_addr
     * @return array
     */
    public function calculateFreightTypeWeight($items,$receive_addr){
        $country_id = $receive_addr['country_id'];
        $province_id = $receive_addr['province_id'];
        $city_id = $receive_addr['city_id'];

        $freight_total = [];
        foreach ($items as $key=>$item){
            $freight = 0;
            $address_id = $item['address_id'];
            //国家、省、市，都判断

            if(empty($address_id)
                //检查国家编码是否存在于模板中
                || (!empty($country_id) && strpos($address_id,$country_id.',') > -1)
                //检查省份编码是否存在于模板中
                || (!empty($province_id) && strpos($address_id,$province_id.',') > -1)
                //检查市区编码是否存在于模板中
                || (!empty($city_id) && strpos($address_id,$city_id.',') > -1 )){
                
                $weight = floatval($item['weight']);
                $replenishmoney = $item['replenishmoney'];
                $replenishpiece = $item['replenishpiece'];
                $firstmoney = $item['firstmoney'];
                $firstpiece =$item['firstpiece'];
                if($weight < $firstpiece){
                    $freight += $firstmoney;
                }else{
                    $weight =  $weight - $firstpiece;
                    $replenish_cnt = $weight / $replenishpiece;

                    $freight += $firstmoney +  $replenish_cnt * $replenishmoney;
                }
            }
            array_push($freight_total,$freight);
        }

        return $freight_total;
    }


    /**
     * 按件数来计算运费
     * 运费模板id
     * @param $items
     * @param $receive_addr
     * @return array
     */
    public function calculateFreightTypeCount($items,$receive_addr){

        $country_id = $receive_addr['country_id'];
        $province_id = $receive_addr['province_id'];
        $city_id = $receive_addr['city_id'];
        $freight_total = [];

        foreach ($items as $key=>$item){
            $freight = 0;
            $address_id = $item['address_id'];

            if(empty($address_id)
                //检查国家编码是否存在于模板中
                || (!empty($country_id) && strpos($address_id,$country_id.',') > -1)
                //检查省份编码是否存在于模板中
                || (!empty($province_id) && strpos($address_id,$province_id.',') > -1)
                //检查市区编码是否存在于模板中
                || (!empty($city_id) && strpos($address_id,$city_id.',') > -1 )){

                $buy_count = $item['count'];
                $replenishmoney = $item['replenishmoney'];
                $replenishpiece = $item['replenishpiece'];
                $firstmoney = $item['firstmoney'];
                $firstpiece =$item['firstpiece'];
                if($buy_count < $firstpiece){
                    $freight += $firstmoney;
                }else{

                    $buy_count =  $buy_count - $firstpiece;

                    $replenish_cnt = $replenishpiece <= 0 ? 0 : ceil($buy_count / $replenishpiece);
                    
                    $freight += $firstmoney +  $replenish_cnt * $replenishmoney;
                }
            }
            array_push($freight_total,$freight);
        }

        return $freight_total;
    }

}