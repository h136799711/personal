<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-08
 * Time: 18:52
 */

namespace app\domain;
use app\src\base\helper\ValidateHelper;
use app\src\order\action\OrderConfirmAction;
use app\src\order\action\OrderCreateAction;
use app\src\order\action\OrderDetailAction;
use app\src\order\action\OrderQueryAction;
use app\src\order\action\OrderReceiveGoodsAction;
use app\src\order\action\OrderShippedAction;
use app\src\order\enum\PayCurrency;
use app\src\order\helper\OrdersBusinessStatusHelper;
use app\src\order\logic\OrdersLogic;
use app\src\order\logic\OrdersPaycodeLogic;
use app\src\order\model\Orders;
use app\src\order\model\OrdersExpress;
use app\src\shoppingCart\logic\ShoppingCartLogic;


/**
 * 订单业务操作
 * Class OrderDomain
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\domain
 */
class OrderDomain extends BaseDomain
{

    /**
     * 订单确认操作
     * @author hebidu <email:346551990@qq.com>
     */
    public function confirm(){
        $order_code = $this->_post("order_code","",lang("lack_parameter",['param'=>'order_code']));
        $uid        = $this->_post('uid',"",lang("uid_need"));

        $orders = new Orders();
        $orders->setUid($uid);
        $orders->setOrderCode($order_code);

        $result = (new OrderConfirmAction())->confirm($orders);
        
        $this->returnResult($result);
    }

    /**
     * 订单发货操作
     * @author hebidu <email:346551990@qq.com>
     */
    public function shipped(){
        $order_code = $this->_post("order_code","",lang("lack_parameter",['param'=>'order_code']));
        $uid        = $this->_post('uid',"",lang("uid_need"));
        $express_no = $this->_post("express_no","",lang("lack_parameter",['param'=>'express_no']));
        $express_code = $this->_post("express_code","",lang("lack_parameter",['param'=>'express_code']));
        $express_name = $this->_post("express_name","",lang("lack_parameter",['param'=>'express_name']));


        $orders = new Orders();
        $orders->setUid($uid);
        $orders->setOrderCode($order_code);

        $ordersExpress = new OrdersExpress();
        $ordersExpress->setUid($uid);
        $ordersExpress->setOrderCode($order_code);
        $ordersExpress->setExpresscode($express_code);
        $ordersExpress->setExpressno($express_no);
        $ordersExpress->setExpressname($express_name);

        $result = (new OrderShippedAction())->shipped($orders,$ordersExpress);

        $this->exitWhenError($result,true);
    }
    
    /**
     * 确认收货
     * @author hebidu <email:346551990@qq.com>
     */
    public function receiveGoods(){
        $order_code = $this->_post("order_code","",lang("lack_parameter",['param'=>'order_code']));
        $uid        = $this->_post('uid',"",lang("uid_need"));
        
        $orders = new Orders();
        $orders->setUid($uid);
        $orders->setOrderCode($order_code);
        
        $result = (new OrderReceiveGoodsAction())->receiveGoods($orders);

        $this->exitWhenError($result,true);
    }
    
    
    /**
     * 立即购买
     * @author hebidu <email:346551990@qq.com>
     */
    public function createNow(){

        $uid = $this->_post('uid',"",lang("uid_need")); 
        $count = $this->_post('count',"",lang("lack_parameter",["param"=>"count"]));
        $sku_pkid = $this->_post('sku_pkid',"",lang("lack_parameter",["param"=>"sku_pkid"]));
        $address_id = $this->_post('address_id','',lang('address_id_need'));
        $note = $this->_post("note",'');

        $count_arr = explode(",",$count);
        $sku_pkid_arr = explode(",",$sku_pkid);
        
        if(empty($count_arr) || empty($sku_pkid_arr) || count($sku_pkid_arr) != count($count_arr)){
            $this->apiReturnErr(lang("err_param"));
        }
        
        //批量立即购买
        $action = new OrderCreateAction();

        $result = $action->createNow($uid,$count_arr,$sku_pkid_arr,$address_id,$note);

        $this->exitWhenError($result,true);
    }
    

    /**
     * 订单详情页
     * @author hebidu <email:346551990@qq.com>
     */
    public function detail(){
        
        $uid        = $this->_post('uid',"",lang("uid_need"));
        $order_code = $this->_post("order_code","",lang("lack_parameter",['param'=>'order_code']));

        $result = (new OrderDetailAction())->detail($uid,$order_code);

        $this->exitWhenError($result,true);
    }

    /**
     *
     * 查询订单
     * @author hebidu <email:346551990@qq.com>
     */
    public function query(){
        $uid          = $this->_post('uid',"",lang("uid_need"));
        $query_status = $this->_post('query_status',"");
        $keyword = $this->_post('keyword',"");

        //对查询状态进行分析
        //[0=>全部,1=>待付款,2=>待发货,3=>待收货,4=>已收货,5=>退款/售后,6=>待评价,7=>已完成]

        $status = OrdersBusinessStatusHelper::analysisQueryStatus($query_status);

        $orders = new Orders();
        $orders->setOrderStatus($status['order_status']);
        $orders->setPayStatus($status['pay_status']);
        $orders->setUid($uid);

        $result = (new OrderQueryAction())->query($keyword,$orders,$this->getPageParams());

        if(ValidateHelper::legalArrayResult($result)){
            foreach ($result['info']['list'] as &$item){
                $item['query_status'] = OrdersBusinessStatusHelper::convertQueryStatus($item);
            }
        }
        $this->exitWhenError($result,true);
    }

    /**
     * 取消订单
     * @author hebidu <email:346551990@qq.com>
     */
    public function cancel(){
        $order_code = $this->_post("order_code","",lang("lack_parameter",['param'=>'order_code']));
        $uid        = $this->_post('uid',"",lang("uid_need"));

        $orders = new Orders();
        $orders->setUid($uid);
        $orders->setOrderCode($order_code);
        
        $result = (new OrdersLogic())->cancel($orders);

        $this->exitWhenError($result,true);
    }

    /**
     * 创建订单
     * @author hebidu <email:346551990@qq.com>
     */
    public function create(){
        $ids = $this->_post("ids",'',lang('id_need'));
        $uid = $this->_post("uid",'',lang('uid_need'));
        $address_id = $this->_post('address_id','',lang('address_id_need'));
        $note = $this->_post("note",'');
        $del = $this->_post("del",0);

        $action = new OrderCreateAction();

        $result = $action->create($uid,$ids,$address_id,$note);
        
        //调用成功后，删除购物车项
        if($result['status'] && $del == 1){
            $this->deleteShoppingCartItem($uid,$ids);
        }

        $this->exitWhenError($result,true);

    }

    /**
     * 删除购物车中的项目
     * @author hebidu <email:346551990@qq.com>
     * @param $uid
     * @param $ids
     */
    private function deleteShoppingCartItem($uid,$ids){
        $map = [
            'uid'=>$uid,
            'id'=>['in',$ids]
        ];

        (new ShoppingCartLogic())->delete($map);
    }

    /**
     * 订单重新支付
     * @author hebidu <email:346551990@qq.com>
     */
    public function repay(){

        $uid = $this->_post('uid','',lang('uid_need'));
        $order_code = $this->_post('order_code','',lang('order_code_need'));
        $result = (new OrdersLogic())->getInfo(['uid'=>$uid,'order_code'=>$order_code]);

        if(!ValidateHelper::legalArrayResult($result)){
            $this->apiReturnErr(lang('err_repay_param'));
        }
        
        $payMoney = $result['info']['price'];
        
        $result = (new OrdersPaycodeLogic())->getPayInfo($uid,[$order_code],$payMoney,PayCurrency::RMB);
        
        $this->exitWhenError($result,true);
    }

}