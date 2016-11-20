<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-11
 * Time: 9:25
 */

namespace app\src\order\model;


use think\Model;

class OrdersExpress extends Model
{

    public function getPoArray(){
        return [
            'expresscode'=>$this->getExpresscode(),
            'expressname'=>$this->getExpressname(),
            'expressno'=>$this->getExpressno(),
            'note'=>$this->getNote(),
            'order_code'=>$this->getOrderCode(),
            'uid'=>$this->getUid(),
            'createtime'=>time(),
            'updatetime'=>time()
        ];
    }

    protected $expresscode;
    protected $expressname;
    protected $expressno;
    protected $note;
    protected $order_code;
    protected $uid;

    /**
     * @return mixed
     */
    public function getExpresscode()
    {
        return $this->expresscode;
    }

    /**
     * @param mixed $expresscode
     */
    public function setExpresscode($expresscode)
    {
        $this->expresscode = $expresscode;
    }

    /**
     * @return mixed
     */
    public function getExpressname()
    {
        return $this->expressname;
    }

    /**
     * @param mixed $expressname
     */
    public function setExpressname($expressname)
    {
        $this->expressname = $expressname;
    }

    /**
     * @return mixed
     */
    public function getExpressno()
    {
        return $this->expressno;
    }

    /**
     * @param mixed $expressno
     */
    public function setExpressno($expressno)
    {
        $this->expressno = $expressno;
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
    public function getOrderCode()
    {
        return $this->order_code;
    }

    /**
     * @param mixed $order_code
     */
    public function setOrderCode($order_code)
    {
        $this->order_code = $order_code;
    }

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



}