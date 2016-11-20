<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 16:11
 */

namespace app\src\order\model;


use think\Model;

class OrdersItem extends Model
{
    protected $name;
    protected $img;
    protected $price;
    protected $oriPrice;
    protected $skuId;
    protected $pskuId;
    protected $skuDesc;
    protected $count;
    protected $orderCode;
    protected $createtime;
    protected $pId;
    protected $dtGoodsUnit;
    protected $dtOriginCountry;
    protected $weight;
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
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
    public function getOriPrice()
    {
        return $this->oriPrice;
    }

    /**
     * @param mixed $oriPrice
     */
    public function setOriPrice($oriPrice)
    {
        $this->oriPrice = $oriPrice;
    }

    /**
     * @return mixed
     */
    public function getSkuId()
    {
        return $this->skuId;
    }

    /**
     * @param mixed $skuId
     */
    public function setSkuId($skuId)
    {
        $this->skuId = $skuId;
    }

    /**
     * @return mixed
     */
    public function getPskuId()
    {
        return $this->pskuId;
    }

    /**
     * @param mixed $pskuId
     */
    public function setPskuId($pskuId)
    {
        $this->pskuId = $pskuId;
    }

    /**
     * @return mixed
     */
    public function getSkuDesc()
    {
        return $this->skuDesc;
    }

    /**
     * @param mixed $skuDesc
     */
    public function setSkuDesc($skuDesc)
    {
        $this->skuDesc = $skuDesc;
    }

    /**
     * @return mixed
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param mixed $count
     */
    public function setCount($count)
    {
        $this->count = $count;
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
    public function getCreatetime()
    {
        return $this->createtime;
    }

    /**
     * @param mixed $createtime
     */
    public function setCreatetime($createtime)
    {
        $this->createtime = $createtime;
    }

    /**
     * @return mixed
     */
    public function getPId()
    {
        return $this->pId;
    }

    /**
     * @param mixed $pId
     */
    public function setPId($pId)
    {
        $this->pId = $pId;
    }

    /**
     * @return mixed
     */
    public function getDtGoodsUnit()
    {
        return $this->dtGoodsUnit;
    }

    /**
     * @param mixed $dtGoodsUnit
     */
    public function setDtGoodsUnit($dtGoodsUnit)
    {
        $this->dtGoodsUnit = $dtGoodsUnit;
    }

    /**
     * @return mixed
     */
    public function getDtOriginCountry()
    {
        return $this->dtOriginCountry;
    }

    /**
     * @param mixed $dtOriginCountry
     */
    public function setDtOriginCountry($dtOriginCountry)
    {
        $this->dtOriginCountry = $dtOriginCountry;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @author hebidu <email:346551990@qq.com>
     */
    public function getModelArray(){
        return [
            'name'=>$this->getName(),
            'img'=>$this->getImg(),
            'price'=>$this->getPrice(),
            'ori_price'=>$this->getOriPrice(),
            'sku_id'=>$this->getSkuId(),
            'psku_id'=>$this->getPskuId(),
            'sku_desc'=>$this->getSkuDesc(),
            'count'=>$this->getCount(),
            'order_code'=>$this->getOrderCode(),
            'createtime'=>$this->getCreatetime(),
            'p_id'=>$this->getPId(),
            'dt_goods_unit'=>$this->getDtGoodsUnit(),
            'dt_origin_country'=>$this->getDtOriginCountry(),
            'weight'=>$this->getWeight()
        ];
    }
}