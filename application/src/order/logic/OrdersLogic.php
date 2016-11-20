<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-09
 * Time: 20:54
 */

namespace app\src\order\logic;


use app\src\base\helper\ValidateHelper;
use app\src\base\logic\BaseLogic;
use app\src\order\model\Orders;
use app\src\order\model\OrdersContactinfo;
use app\src\order\model\OrdersExpress;
use app\src\order\model\OrdersItem;
use think\Db;
use think\exception\DbException;

class OrdersLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new Orders());
    }

    /**
     * 订单确认操作
     * @param Orders $orders
     * @return \app\src\base\logic\status|array|bool
     */
    public function confirm(Orders $orders){

        $result = $this->getInfo(['order_code'=>$orders->getOrderCode()]);

        if(!ValidateHelper::legalArrayResult($result)){
            return $this->apiReturnErr(lang("err_order_code"));
        }

        $order_info = $result['info'];

        //不是已发货
        if($order_info['order_status'] != Orders::ORDER_TOBE_CONFIRMED){
            return $this->apiReturnErr(lang("err_order_status"));
        }

        //不是已支付
        if($order_info['pay_status'] != Orders::ORDER_PAID){
            return $this->apiReturnErr(lang("err_pay_status"));
        }


        $map    = ['uid'=>$orders->getUid(),'order_code'=>$orders->getOrderCode()];
        $update = ['order_status'=>Orders::ORDER_TOBE_SHIPPED,'updatetime'=>time()];

        $result = $this->save($map,$update);

        return $result;

    }

    /**
     * 订单发货操作
     * @author hebidu <email:346551990@qq.com>
     * @param Orders $orders
     * @param OrdersExpress $ordersExpress
     * @return \app\src\base\logic\status|array|bool
     */
    public function shipped(Orders $orders,OrdersExpress $ordersExpress){

        $result = $this->getInfo(['order_code'=>$orders->getOrderCode()]);

        if(!ValidateHelper::legalArrayResult($result)){
            return $this->apiReturnErr(lang("err_order_code"));
        }

        $order_info = $result['info'];

        //不是已发货
        if($order_info['order_status'] != Orders::ORDER_TOBE_SHIPPED){
            return $this->apiReturnErr(lang("err_order_status"));
        }

        //不是已支付
        if($order_info['pay_status'] != Orders::ORDER_PAID){
            return $this->apiReturnErr(lang("err_pay_status"));
        }

        //1. 开启事务
        Db::startTrans();
        $hasError  = false;
        $error = "";
        $map    = ['uid'=>$orders->getUid(),'order_code'=>$orders->getOrderCode()];
        $update = ['order_status'=>Orders::ORDER_SHIPPED,'updatetime'=>time()];

        $result = $this->save($map,$update);

        if(!$result['status']){
            $hasError = true;
            $error = $result['info'];
        }else{

            $entity = $ordersExpress->getPoArray();
            $result = (new OrdersExpressLogic())->add($entity);

            if(!$result['status']) {
                $hasError = true;
                $error = $result['info'];
            }
        }

        if($hasError){
            Db::rollback();
            return ['status'=>false,'info'=>$error];
        }else{
            Db::commit();
            return ['status'=>true,'info'=>lang('success')];
        }

    }

    /**
     * 确认收货订单
     * @author hebidu <email:346551990@qq.com>
     * @param Orders $orders
     * @return \app\src\base\logic\status|array|bool
     */
    public function receiveGoods(Orders $orders){

        $result = $this->getInfo(['order_code'=>$orders->getOrderCode()]);

        if(!ValidateHelper::legalArrayResult($result)){
            return $this->apiReturnErr(lang("err_order_code"));
        }

        $order_info = $result['info'];

        //不是已发货
        if($order_info['order_status'] != Orders::ORDER_SHIPPED){
            return $this->apiReturnErr(lang("err_order_status"));
        }

        //不是已支付
        if($order_info['pay_status'] != Orders::ORDER_PAID){
            return $this->apiReturnErr(lang("err_pay_status"));
        }

        $map    = ['uid'=>$orders->getUid(),'order_code'=>$orders->getOrderCode()];
        $update = ['order_status'=>Orders::ORDER_RECEIPT_OF_GOODS,'updatetime'=>time()];
        
        $result = $this->save($map,$update);

        return $result;
    }

    /**
     * 订单支付成功
     * @param $order_info
     * @param $pay_info
     */
    public function paySuccess($order_info,$pay_info){
        //1. 订单已支付，则返回
        if($order_info['pay_status'] == Orders::ORDER_PAID){
            return $this->apiReturnErr('payed');
        }

        $update = [
            'pay_status'=>Orders::ORDER_PAID,
            'pay_type'=>$pay_info['pay_type'],
            'pay_code'=>$pay_info['pay_code'],
            'pay_balance'=>$pay_info['pay_balance'],
            'updatetime'=>time()
        ];

        $map = [
            'uid'=>$order_info['uid'],
            'order_code'=>$order_info['order_code']
        ];

        return $this->save($map,$update);

    }

    /**
     * 自动关闭订单
     * @param Orders $orders
     * @return \app\src\base\logic\status|array|bool
     */
    public function autoCloseOrder($orders){

        if(!isset($orders['uid']) || !isset($orders['order_code'])){
            return false;
        }

        if($orders['order_status'] != Orders::ORDER_TOBE_CONFIRMED){
            return $this->apiReturnErr(lang("err_order_status"));
        }

        if($orders['pay_status'] != Orders::ORDER_TOBE_PAID){
            return $this->apiReturnErr(lang("err_pay_status"));
        }

        $result = $this->save(['uid'=>$orders['uid'],'order_code'=>$orders['order_code']],['order_status'=>Orders::ORDER_CANCEL,'updatetime'=>time()]);

        return $result;
    }

    /**
     * 取消订单
     * @author hebidu <email:346551990@qq.com>
     * @param Orders $orders
     * @return \app\src\base\logic\status|array|bool
     */
    public function cancel(Orders $orders){

        $result = $this->getInfo(['order_code'=>$orders->getOrderCode()]);

        if(!ValidateHelper::legalArrayResult($result)){
            $this->apiReturnErr(lang("err_order_code"));
        }
        
        $order_info = $result['info'];
        
        if($order_info['order_status'] != Orders::ORDER_TOBE_CONFIRMED){
            return $this->apiReturnErr(lang("err_order_status"));
        }

        if($order_info['pay_status'] != Orders::ORDER_TOBE_PAID){
            return $this->apiReturnErr(lang("err_pay_status"));
        }

        $result = $this->save(['uid'=>$orders->getUid(),'order_code'=>$orders->getOrderCode()],['order_status'=>Orders::ORDER_CANCEL,'updatetime'=>time()]);

        return $result;
    }


    /**
     * 添加订单信息
     * @author hebidu <email:346551990@qq.com>
     * @param $items
     * @param Orders $orders
     * @param OrdersContactinfo $contactInfo
     * @return array
     * @throws \Exception
     */
    public function addOrder($items,Orders $orders,OrdersContactinfo $contactInfo){

        Db::startTrans();
        $flag = true;
        $info = "";
        $result = $this->add($orders->getModelArray());
        
        if(!$result['status']){
            $flag = false;
            $info = empty($result['info'])? lang('err_add_order_info_fail'):$result['info'];
        }
        $info = $result['info'];

        $result = $contactInfo -> data($contactInfo->getModelArray()) ->isUpdate(false) -> save();

        if ($result === false) {
            $flag = false;
            $info = $contactInfo -> getError();
        }

        $order_items = [];
        foreach ($items as $item){
            if($item instanceof OrdersItem){
                array_push($order_items ,  $item->getModelArray() );
            }
        }

        $ordersItemModel = new OrdersItem();

        $result = $ordersItemModel->saveAll($order_items,true);

        if ($result === false) {
            $flag = false;
            $info = $ordersItemModel -> getError();
        }



        if($flag){
            Db::commit();
            return $this->apiReturnSuc($info);
        }else{
            Db::rollback();
            return $this->apiReturnErr($info);
        }
    }

    /**
     * 获取订单信息包含订单拥有者昵称
     * @param $map
     * @return array
     */
    public function getInfoWithPublisherName($map){

        try{

            $result = Db::table("itboye_orders")->alias("orders")
                ->field("oc.city,oc.postal_code,oc.id_card,oc.detailinfo,oc.mobile,oc.area,oc.province,oc.contactname,oc.country,orders.*,m.nickname as publisher_name")
                ->join("itboye_orders_contactinfo as oc","oc.order_code = orders.order_code","LEFT")
            ->join("itboye_store as store","store.id = orders.storeid","LEFT")
            ->join(["member as m","common_"],"m.uid = store.uid","LEFT")
                ->where($map)
            ->find();

            return $this->apiReturnSuc($result);
        }catch (DbException $ex){
            return $this->apiReturnErr($ex->getMessage());
        }

    }

}