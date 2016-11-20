<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-09
 * Time: 20:53
 */

namespace app\src\order\model;


use think\Model;

class Orders extends Model
{

    protected $uid;
    protected $orderCode;
    protected $price;
    protected $postPrice;
    protected $note;
    protected $status;
    protected $payStatus;
    protected $orderStatus;
    protected $csStatus;
    protected $createTime;
    protected $updateTime;
    protected $statusNote;
    protected $commentStatus;
    protected $from;
    protected $discountMoney;
    protected $storeid;
    protected $goodsAmount;
    protected $payType;
    protected $payCode;
    protected $payBalance;


    /**
     * 订单退回
     */
    const ORDER_BACK = 12;
    /**
     * 待确认
     */
    const ORDER_TOBE_CONFIRMED = 2;
    /**
     * 待发货
     */
    const ORDER_TOBE_SHIPPED = 3;
    /**
     * 已发货
     */
    const ORDER_SHIPPED = 4;
    /**
     * 已收货
     */
    const ORDER_RECEIPT_OF_GOODS = 5;
    /**
     * 已退货
     */
    const ORDER_RETURNED = 6;
    /**
     * 已完成
     */
    const ORDER_COMPLETED = 7;
    /**
     * 取消或交易关闭
     */
    const ORDER_CANCEL = 8;
    /**
     * 正在退款
     */
    const ORDER_RESENDS = 9;

    //订单出库状态


    //订单支付状态
    /**
     * 待支付
     */
    const ORDER_TOBE_PAID = 0;
    /**
     * 支付中
     */
    const ORDER_PAY_ING = 3;
    /**
     * 货到付款
     */
    const ORDER_CASH_ON_DELIVERY = 5;
    /**
     * 已支付
     */
    const ORDER_PAID = 1;
    /**
     * 已退款
     */
    const ORDER_REFUND = 2;

    //订单评论状态


    /**
     * 待评论
     */
    const ORDER_TOBE_EVALUATE = 0;
    /**
     * 已评论
     */
    const ORDER_HUMAN_EVALUATED = 1;
    /**
     * 超时、系统自动评论
     */
    const ORDER_SYSTEM_EVALUATED = 2;

    //**************订单来源*****************
    /**
     * 来源PC网站
     */
    const COME_FROM_PC = 1;

    /**
     * 来源Android
     */
    const COME_FROM_ANDROID = 2;

    /**
     * 来源IOS
     */
    const COME_FROM_IOS = 3;

    /**
     * 其它
     */
    const COME_FROM_OTHER = 4;

    //**************订单支付类型*****************

    /**
     * 支付宝
     */
    const PAY_TYPE_ALIPAY = 1;

    //**************订单售后状态*****************

    /**
     * 初始状态
     */
    const CS_DEFAULT = 0;

    /**
     * 待处理
     */
    const CS_PENDING = 2;

    /**
     * 已处理
     */
    const CS_PROCESSED = 3;

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @return mixed
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * @param mixed $orderCode
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPostPrice()
    {
        return $this->postPrice;
    }

    /**
     * @param mixed $postPrice
     */
    public function setPostPrice($postPrice)
    {
        $this->postPrice = $postPrice;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPayStatus()
    {
        return $this->payStatus;
    }

    /**
     * @param mixed $payStatus
     */
    public function setPayStatus($payStatus)
    {
        $this->payStatus = $payStatus;
    }

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param mixed $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    /**
     * @return mixed
     */
    public function getCsStatus()
    {
        return $this->csStatus;
    }

    /**
     * @param mixed $csStatus
     */
    public function setCsStatus($csStatus)
    {
        $this->csStatus = $csStatus;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    /**
     * @return mixed
     */
    public function getStatusNote()
    {
        return $this->statusNote;
    }

    /**
     * @param mixed $statusNote
     */
    public function setStatusNote($statusNote)
    {
        $this->statusNote = $statusNote;
    }

    /**
     * @return mixed
     */
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * @param mixed $commentStatus
     */
    public function setCommentStatus($commentStatus)
    {
        $this->commentStatus = $commentStatus;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getDiscountMoney()
    {
        return $this->discountMoney;
    }

    /**
     * @param mixed $discountMoney
     */
    public function setDiscountMoney($discountMoney)
    {
        $this->discountMoney = $discountMoney;
    }

    /**
     * @return mixed
     */
    public function getStoreid()
    {
        return $this->storeid;
    }

    /**
     * @param mixed $storeid
     */
    public function setStoreid($storeid)
    {
        $this->storeid = $storeid;
    }

    /**
     * @return mixed
     */
    public function getGoodsAmount()
    {
        return $this->goodsAmount;
    }

    /**
     * @param mixed $goodsAmount
     */
    public function setGoodsAmount($goodsAmount)
    {
        $this->goodsAmount = $goodsAmount;
    }

    /**
     * @return mixed
     */
    public function getPayType()
    {
        return $this->payType;
    }

    /**
     * @param mixed $payType
     */
    public function setPayType($payType)
    {
        $this->payType = $payType;
    }

    /**
     * @return mixed
     */
    public function getPayCode()
    {
        return $this->payCode;
    }

    /**
     * @param mixed $payCode
     */
    public function setPayCode($payCode)
    {
        $this->payCode = $payCode;
    }

    /**
     * @return mixed
     */
    public function getPayBalance()
    {
        return $this->payBalance;
    }

    /**
     * @param mixed $payBalance
     */
    public function setPayBalance($payBalance)
    {
        $this->payBalance = $payBalance;
    }


    /**
     * 获取模型数组
     * @author hebidu <email:346551990@qq.com>
     */
    public function getModelArray(){
        return [
            'uid'=>$this->getUid(),
            'order_code'=>$this->getOrderCode(),
            'price'=>$this->getPrice(),
            'post_price'=>$this->getPostPrice(),
            'note'=>$this->getNote(),
            'status'=>$this->getStatus(),
            'pay_status'=>$this->getPayStatus(),
            'order_status'=>$this->getOrderStatus(),
            'cs_status'=>$this->getCsStatus(),
            'createtime'=>$this->getCreateTime(),
            'updatetime'=>$this->getUpdateTime(),
            'comment_status'=>$this->getCommentStatus(),
            'from'=>$this->getFrom(),
            'discount_money'=>$this->getDiscountMoney(),
            'storeid'=>$this->getStoreid(),
            'goods_amount'=>$this->getGoodsAmount(),
            'pay_type'=>$this->getPayType(),
            'pay_code'=>$this->getPayCode(),
            'pay_balance'=>$this->getPayBalance()
        ];
    }
}