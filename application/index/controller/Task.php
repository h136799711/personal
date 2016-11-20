<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-11
 * Time: 14:54
 */

namespace app\index\controller;


use app\src\base\helper\ValidateHelper;
use app\src\order\config\OrdersConfig;
use app\src\order\logic\OrdersLogic;
use app\src\order\model\Orders;
use think\Controller;

class Task extends Controller
{
    private $now;
    public function index(){
        set_time_limit(0);
        $from = $this->request->get('from');
        $last_call_time = cache('last_call_time');
        if($from == 'crontab'){
            $this->now = time();
            //自动关闭订单的功能
            $this->closeOrders();
            //1. TODO 自动收货功能
            //2. TODO 自动完成功能

            cache('last_call_time',time());
        }
        $str  = '上一次调用时间: '.date("Y-m-d H:i:s",$last_call_time);
        $str .= '<br/>下一次调用时间:'.date("Y-m-d H:i:s",$last_call_time+300);
        echo $str;
    }

    /**
     * 关闭订单
     * @author hebidu <email:346551990@qq.com>
     */
    private function closeOrders(){
        //每次处理10个订单
        //1. 查找符合条件的10个订单进行处理
        //距离上次操作订单大于3天，则自动关闭订单
        $elapseFromLastUpdateTime = OrdersConfig::getAutoCloseTimeInterval();
        
        $logic = new OrdersLogic();
        $time = intval($this->now - $elapseFromLastUpdateTime);
        if($time < 0){
            \think\Log::log('自动关闭订单时间错误'.$time);
        }

        $map = [
            'order_status'=>Orders::ORDER_TOBE_CONFIRMED,
            'pay_status'=>Orders::ORDER_TOBE_PAID
        ];

        $map['updatetime'] = ['lt',$time];

        $result = $logic->query($map,['curpage'=>1,'size'=>20]);
        if(ValidateHelper::legalArrayResult($result) && $result['info']['count'] > 0){
            $orders = $result['info']['list'];
            foreach ($orders as $item){
                $logic->autoCloseOrder($item);
            }
        }
    }

}