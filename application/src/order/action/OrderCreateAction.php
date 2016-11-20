<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-08
 * Time: 19:51
 */

namespace app\src\order\action;


use app\src\address\logic\AddressLogic;
use app\src\base\action\BaseAction;
use app\src\base\exception\BusinessException;
use app\src\base\helper\ExceptionHelper;
use app\src\base\utils\CodeGenerateUtils;
use app\src\freight\facade\FreightFacade;
use app\src\freight\model\FreightTemplate;
use app\src\goods\logic\ProductLogic;
use app\src\goods\model\Product;
use app\src\order\enum\PayCurrency;
use app\src\order\logic\OrdersLogic;
use app\src\order\logic\OrdersPaycodeLogic;
use app\src\order\model\Orders;
use app\src\order\model\OrdersContactinfo;
use app\src\order\model\OrdersItem;
use app\src\shoppingCart\logic\ShoppingCartLogic;

/**
 * 创建订单
 * Class OrderCreateAction
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\order\action
 */
class OrderCreateAction extends BaseAction
{

    private $address;//收货地址信息
    private $skuItem;//商品详细数据,方便查找，使用了商品规格id作为主键
    private $items;//商品详细数据，对应购物车
    private $processItem;//分类后的商品项

    private $freightArr;//运费

    /**
     *
     * 立即购买
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @param $count_arr
     * @param $sku_ids
     * @param $address_id
     * @param $note
     * @return array
     */
    public function createNow($uid,$count_arr,$sku_ids,$address_id,$note){
        try{

            //0. 查询出商品数据、收货地址
            $buyItems = $this->getBuyItemFromPost($count_arr,$sku_ids);

            $this->getData($uid,$buyItems,$address_id);

            //1. 检查商品的有效性
            $this->checkProduct();

            //2. 对商品进行分类（同店铺、同币种、同国家）
            $this->classifiedGoods();

            //3. 计算生成订单信息
            // 3.1 一个订单的运费
            // 3.2 一个订单的总价
            // 3.3 一个订单的优惠信息
            $this->calcOrderInfo();

            //4. 生成订单记录
            return $this->addOrder($uid,$note);
        }catch (BusinessException $ex){
            return $this->error($ex);
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
     * 创建订单
     * @author hebidu <email:346551990@qq.com>
     * @param $uid integer 用户id
     * @param $cartIds string 购物车项目里的id
     * @param $address_id integer 收货地址id
     * @param $note
     * @return array
     * @throws BusinessException
     * @internal param string $ids 商品的规格id
     */
    public function create($uid,$cartIds,$address_id,$note){

        try{

            //0. 查询出商品数据、收货地址
            $buyItems = $this->getBuyItemFromShoppingCart($uid,$cartIds);

            $this->getData($uid,$buyItems,$address_id);

            //1. 检查商品的有效性
            $this->checkProduct();

            //2. 对商品进行分类（同店铺、同币种、同国家）
            $this->classifiedGoods();

            //3. 计算生成订单信息
            // 3.1 一个订单的运费
            // 3.2 一个订单的总价
            // 3.3 一个订单的优惠信息
            $this->calcOrderInfo();

            //4. 生成订单记录
            return $this->addOrder($uid,$note);
        }catch (BusinessException $ex){
            return $this->error($ex);
        }
    }

    /**
     * 插入订单记录
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @param $note
     * @return array
     */
    private function addOrder($uid,$note){
        $now = time();
        $codeUtils = new CodeGenerateUtils();
        $orders_items = [];
        $orderLogic = new OrdersLogic();
        $orderCodes = [];//订单编号
        $total_price = 0;//总金额

        foreach ($this->freightArr as $key=>$item){

            $order_code = $codeUtils->getOrderCode($uid);
            $orders = new Orders();
            $orders->setCsStatus(Orders::CS_DEFAULT);
            $orders->setDiscountMoney(0);
            $orders->setUid($uid);
            $orders->setPrice($item['order_price']);
            $orders->setPostPrice($item['order_freight']);
            $orders->setOrderCode($order_code);
            $orders->setFrom(Orders::COME_FROM_OTHER);
            $orders->setNote($note);
            $orders->setStatus(1);
            $orders->setPayStatus(Orders::ORDER_TOBE_PAID);
            $orders->setOrderStatus(Orders::ORDER_TOBE_CONFIRMED);
            $orders->setCreateTime($now);
            $orders->setUpdateTime($now);
            $orders->setCommentStatus(Orders::ORDER_TOBE_EVALUATE);
            $orders->setStoreid($item['store_id']);
            $orders->setGoodsAmount($item['order_price']);
            $orders->setPayType(0);
            $orders->setPayCode("");
            $orders->setPayBalance(0);
            $total_price += $item['order_price'];

            $items =  $this->processItem[$key];

            //订单项
            foreach($items as $vo){

                $cart_item = $vo['_cart_item'];
                $ordersItem = new OrdersItem();
                $ordersItem->setOrderCode($order_code);

                $ordersItem->setName($vo['name']);
                $ordersItem->setCreatetime($now);
                $ordersItem->setImg($vo['icon_url']);
                $ordersItem->setPrice($vo['_price']);
                $ordersItem->setOriPrice($vo['ori_price']);
                $ordersItem->setSkuId($vo['sku_id']);
                $ordersItem->setDtGoodsUnit($vo['dt_goods_unit']);
                $ordersItem->setDtOriginCountry($vo['dt_origin_country']);
                $ordersItem->setPId($vo['id']);

                $ordersItem->setSkuDesc($vo['sku_desc']);
                $ordersItem->setWeight($vo['weight']);

                $ordersItem->setPskuId($cart_item['psku_id']);
                $ordersItem->setCount($cart_item['count']);

                array_push($orders_items,$ordersItem);
            }

            //3. 设置订单收货人信息
            $ordersContactInfo = $this->getContactInfo();

            $ordersContactInfo->setOrderCode($order_code);
            $ordersContactInfo->setUid($uid);
            $ordersContactInfo->setNotes($note);

            //1. 添加订单
            $result = $orderLogic->addOrder($orders_items,$orders,$ordersContactInfo);
            if($result['status']){
                array_push($orderCodes,$order_code);
            }
        }

        $result  = (new OrdersPaycodeLogic())->getPayInfo($uid,$orderCodes,$total_price,PayCurrency::RMB);

        return $this->result($result);
    }


    /**
     * 获取订单联系人信息
     * @author hebidu <email:346551990@qq.com>
     * @return OrdersContactinfo
     */
    private function getContactInfo(){
        $ordersContactInfo = new OrdersContactinfo();
        $ordersContactInfo->setContactName($this->address['contactname']);
        $ordersContactInfo->setCountry($this->address['country']);
        $ordersContactInfo->setProvince($this->address['province']);
        $ordersContactInfo->setCity($this->address['city']);
        $ordersContactInfo->setArea($this->address['area']);
        $ordersContactInfo->setDetailInfo($this->address['detailinfo']);
        $ordersContactInfo->setMobile($this->address['mobile']);
        $ordersContactInfo->setWxno($this->address['wxno']);
        $ordersContactInfo->setIdCard($this->address['id_card']);
        $ordersContactInfo->setPostalCode($this->address['postal_code']);

        return $ordersContactInfo;
    }

    /**
     * 计算订单的价格
     * @param $list
     * @return int
     * @throws BusinessException
     */
    private function calculatePriceOneOrder(&$list){
        if(count($list) <=  0) return 0;
        
        $order_price = 0;
        $cnt1   = -1;
        $cnt2   = -1;
        $cnt3   = -1;
        $price1 = -1;
        $price2 = -1;
        $price3 = -1;
        $total_buy_cnt = 0;
        $price  = 0;
        foreach ($list as $key=>&$item){
            //商品信息
            $cart_item = $item['_cart_item'];
            $buy_cnt =  $cart_item['count'];
            if($cnt1 == -1){
                $cnt1 = $item['cnt1'];
                $cnt2 = $item['cnt2'];
                $cnt3 = $item['cnt3'];
                $price1 = $item['price'];
                $price3 = $item['price3'];
                $price2 = $item['price2'];
            }

            $total_buy_cnt += $buy_cnt;
        }

        if($total_buy_cnt < $cnt1){
            throw  new BusinessException(lang("err_cart_item_count_invalid",['name'=>'','min'=>$cnt1]));
        }

        if($total_buy_cnt > $cnt3){
            $price = $price3;
        }elseif ($total_buy_cnt > $cnt2){
            $price = $price2;
        }elseif($total_buy_cnt > $cnt1){
            $price = $price1;
        }

        foreach ($list as $key=>&$item){
            $cart_item = $item['_cart_item'];
            $buy_cnt =  $cart_item['count'];
            $item['_price'] = $price;
            $order_price += ($price * $buy_cnt);
        }

        return $order_price;
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
     * 计算生成订单的信息
     * @author hebidu <email:346551990@qq.com>
     */
    private function calcOrderInfo(){

        //收货地址id
        $receive_addr = $this->getReceiveAddressId();
        $this->freightArr = [];
        foreach ($this->processItem as $key=>&$item){

            $freight = $this->calculateFreightOneOrder($key,$item,$receive_addr);
            $price   = $this->calculatePriceOneOrder($item);

            if(!isset($this->freightArr[$key])){
                $this->freightArr[$key] = [
                    'store_id'=>$item[0]['store_id'],
                    'order_freight'=>0,
                    'order_price'=>0
                ];
            }

            $this->freightArr[$key]['order_freight'] = $freight;
            $this->freightArr[$key]['order_price'] = $price;

        }

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
     * 从购物车表中获取购买的商品项
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @param $cartIds
     * @return
     * @throws BusinessException
     */
    private function getBuyItemFromShoppingCart($uid,$cartIds){
        $logic = new ShoppingCartLogic();

        $result = $logic->queryNoPaging(['uid'=>$uid,'id'=>['in',$cartIds]]);

        if(!$result['status'] || empty($result['info'])){
            throw  new BusinessException(lang('err_cart_id'));
        }

        return $result['info'];
    }

    /**
     * 获取相应的数据,并赋值
     * @param $uid
     * @param $buyItems
     * @param $address_id
     * @throws BusinessException
     * @internal param $cartIds
     */
    private function getData($uid,$buyItems,$address_id){

        //2. 拼接商品规格id
        $ids = "";
        foreach ($buyItems as $item){
            $ids .= $item['psku_id'].',';
        }

        $ids = rtrim($ids,",");

        //3. 获取收货地址信息
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
            foreach ($buyItems as $item) {
                if ($vo['sku_pkid'] == $item['psku_id']) {
                    $vo['_cart_item'] = $item;
                }
            }
        }
    }
}