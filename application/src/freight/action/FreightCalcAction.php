<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 22:02
 */

namespace app\src\freight\action;


use app\src\address\logic\AddressLogic;
use app\src\base\action\BaseAction;
use app\src\base\exception\BusinessException;
use app\src\freight\facade\FreightFacade;
use app\src\freight\model\FreightTemplate;
use app\src\goods\logic\ProductLogic;
use app\src\goods\model\Product;
use app\src\shoppingCart\logic\ShoppingCartLogic;
use think\Exception;

class FreightCalcAction extends BaseAction
{


    private $address;//收货地址信息
    private $skuItem;//商品详细数据,方便查找，使用了商品规格id作为主键
    private $items;//商品详细数据，对应购物车
    private $processItem;//分类后的商品项

    private $freightArr;//运费

    /**
     * 立即计算运费
     * @param $uid
     * @param $count_arr
     * @param $sku_ids
     * @param $address_id
     * @return array
     */
    public function calcBuyNow($uid,$count_arr,$sku_ids,$address_id){
        try{

            //0. 查询出商品数据、收货地址
            $buy_items = $this->getBuyItemFromPost($count_arr,$sku_ids);

            $this->getData($uid,$buy_items,$address_id);

            //1. 检查商品的有效性
            $this->checkProduct();

            //2. 对商品进行分类（同店铺、同币种、同国家）
            $this->classifiedGoods();

            //3. 计算生成订单信息
            //3.1 一个订单的运费
            $this->calcOrderInfo();

            $ret = [];
            foreach($this->freightArr as $vo){
                $ret[] = ['store_id'=>$vo['store_id'],'freight'=>$vo['order_freight']];
            }

            return $this->success($ret);
        }catch (Exception $ex){
            return $this->error($ex->getMessage());
        }
    }

    /**
     * 计算运费
     * @author hebidu <email:346551990@qq.com>
     * @param $uid integer 用户id
     * @param $cartIds string 购物车项目里的id
     * @param $address_id integer 收货地址id
     * @return array
     * @throws BusinessException
     * @internal param string $ids 商品的规格id
     */
    public function calc($uid,$cartIds,$address_id){
        
        //0. 查询出商品数据、收货地址
        $buy_items = $this->getBuyItemFromShoppingCart($uid,$cartIds);
        $this->getData($uid,$buy_items,$address_id);

        //1. 检查商品的有效性
        $this->checkProduct();

        //2. 对商品进行分类（同店铺、同币种、同国家）
        $this->classifiedGoods();

        //3. 计算生成订单信息
        // 3.1 一个订单的运费
        $this->calcOrderInfo();

        $ret = [];
        foreach($this->freightArr as $vo){
            $ret[] = ['store_id'=>$vo['store_id'],'freight'=>$vo['order_freight']];
        }

        return $this->success($ret);
    }

    /**
     * 计算生成订单的信息
     * @author hebidu <email:346551990@qq.com>
     */
    private function calcOrderInfo(){

        //收货地址id
        $receive_addr = $this->getReceiveAddressId();
        $this->freightArr = [];
        foreach ($this->processItem as $key=>&$item){

            $freight = $this->calculateFreightOneOrder($key,$item,$receive_addr);

            if(!isset($this->freightArr[$key])){
                $this->freightArr[$key] = [
                    'store_id'=>$item[0]['store_id'],
                    'order_freight'=>0
                ];
            }

            $this->freightArr[$key]['order_freight'] = $freight;

        }

    }

    /**
     * 计算一个订单的运费
     * @param $store_id
     * @param $list
     * @param $receive_addr
     * @return int|mixed
     */
    private function calculateFreightOneOrder($store_id,$list,$receive_addr){

        $freight_items = [];

        foreach ($list as $key=>$item){

            $oneDimensional = 'T_'.$item['freight_type'];

            if(!isset($freight_items[$oneDimensional])){
                $freight_items[$oneDimensional] = [];
            }
            $tplKey = 'T'.$item['template_id'];
            //按运费模板类型，运费模板id来分类
            if(!isset($freight_items[$oneDimensional][$tplKey])){

                //同一运费模板id的 首件、首件价格、续件、续件价格都是一样的
                $freight_items[$oneDimensional][$tplKey] = [
                    'replenishmoney'=>$item['replenishmoney'],
                    'replenishpiece'=>$item['replenishpiece'],
                    'firstmoney'=>$item['firstmoney'],
                    'firstpiece'=>$item['firstpiece'],
                    'count'=>0,
                    'weight'=>0,
                    'address_id'=> empty($item['addressids'])?"":$item['addressids']
                ];
            }

            $count = $item['_cart_item']['count'];

            //同一运费模板id的 数量、重量进行合并
            $freight_items[$oneDimensional][$tplKey]['count']  += $count;
            $freight_items[$oneDimensional][$tplKey]['weight'] += ($count * $item['weight']);

        }
        $freight_total = 0;
        $freight_arr = [];
        if(isset($freight_items['T_'.FreightTemplate::TYPE_COUNT])){
            $freight_arr = (new FreightFacade())->calculateFreightTypeCount($freight_items['T_'.FreightTemplate::TYPE_COUNT],$receive_addr);
        }

        if(isset($freight_items['T_'.FreightTemplate::TYPE_WEIGHT])){
            $result = (new FreightFacade())->calculateFreightTypeWeight($freight_items['T_'.FreightTemplate::TYPE_WEIGHT],$receive_addr);
            array_merge($freight_arr,$result);
        }

        foreach ($freight_arr as $item){
            $freight_total += $item;
        }

        return $freight_total;
    }

    /**
     * 获取收货地址id
     * @author hebidu <email:346551990@qq.com>
     */
    private function getReceiveAddressId(){
        $ret = [
            'country_id'=>'',
            'province_id'=>'',
            'city_id'=>''
        ];

        if(isset($this->address['cityid']) && !empty($this->address['cityid'])){
            $ret['city_id'] = $this->address['cityid'];
        }

        if(isset($this->address['provinceid']) && !empty($this->address['provinceid'])){
            $ret['city_id'] = $this->address['provinceid'];
        }

        if(isset($this->address['country_id']) && !empty($this->address['country_id'])){
            $ret['city_id'] = $this->address['country_id'];
        }

        return $ret;
    }

    /**
     * 对商品进行分类
     * @author hebidu <email:346551990@qq.com>
     */
    private function classifiedGoods(){
        $this->processItem = [];
        $this->skuItem = [];
        foreach ($this->items as $item){

            $store_id = $item['store_id'];
            $key = md5($store_id);

            $this->skuItem[$item['sku_pkid']] = $item;

            if(!isset($this->processItem[$key])) {
                $this->processItem[$key] = [];
            }

            array_push($this->processItem[$key],$item);
        }
    }

    /**
     * 检查商品数据的合法性
     * @author hebidu <email:346551990@qq.com>
     * @throws BusinessException
     */
    private function checkProduct(){

        $logic = new ProductLogic();
        foreach ($this->items as $vo){

            $status = $logic->getBusinessStatus($vo);

            //1. 判定商品业务状态是否有效
            if($status != Product::BUSINESS_STATUS_NORMAL){
                throw new BusinessException($logic->getBusinessStatusDesc($status));
            }

            //2. 判定购物车的购买数量 是否 小于商品的库存数量
            $item = $vo['_cart_item'];
            if(intval($vo['quantity']) < intval($item['count'])){
                throw  new BusinessException(lang("err_quantity_lack",['name'=>$vo['name']]));
            }

        }

    }

    /**
     * 获取相应的数据,并赋值
     * @param $uid
     * @param $buy_items
     * @param $address_id
     * @throws BusinessException
     */
    private function getData($uid,$buy_items,$address_id){
        //1. 拼接商品规格id
        $ids = "";
        foreach ($buy_items as $item){
            $ids .= $item['psku_id'].',';
        }

        //2. 获取收货地址信息
        $logic = new AddressLogic();
        $result = $logic->getInfo(['uid'=>$uid,'id'=>$address_id]);
        if(!$result['status'] || empty($result['info'])){
            throw  new BusinessException(lang('err_address_id'));
        }

        $this->address = $result['info'];

        $logic = new ProductLogic();

        $result = $logic->queryWithSkuIds($ids);

        if(!$result['status'] || empty($result['info'])){
            throw  new BusinessException(lang('err_sku_ids'));
        }

        $this->items = $result['info'];

        foreach ($this->items as &$vo){
            foreach ($buy_items as $item) {
                if ($vo['sku_pkid'] == $item['psku_id']) {
                    $vo['_cart_item'] = $item;
                }
            }
        }
    }

    /**
     * 根据请求传输过来的数据构造购买项目
     * @param $count_arr
     * @param $sku_ids
     * @return array
     */
    private function getBuyItemFromPost($count_arr,$sku_ids){
        $ret = [];
        for ( $i = 0 ; $i < count($count_arr) ; $i++) {
            array_push($ret,[
                'count'=>$count_arr[$i],
                'psku_id'=>$sku_ids[$i]
            ]);
        }

        return $ret;
    }

    /**
     * 从购物车表中获取购买的商品项
     * @author hebidu <email:346551990@qq.com>
     */
    private function getBuyItemFromShoppingCart($uid,$cartIds){
        $logic = new ShoppingCartLogic();

        $result = $logic->queryNoPaging(['uid'=>$uid,'id'=>['in',$cartIds]]);

        if(!$result['status'] || empty($result['info'])){
            throw  new BusinessException(lang('err_cart_id'));
        }

        return $result['info'];
    }

}