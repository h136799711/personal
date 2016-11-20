<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 16:13
 */

namespace app\src\order\model;


use think\Model;

class OrdersContactinfo extends Model
{
    private $contactName;
    private $uid;
    private $country;
    private $province;
    private $detailInfo;
    private $area;
    private $mobile;
    private $notes;
    private $wxno;
    private $orderCode;
    private $city;
    private $idCard;
    private $postalCode;

    /**
     * @return mixed
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param mixed $contactName
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
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

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getDetailInfo()
    {
        return $this->detailInfo;
    }

    /**
     * @param mixed $detailInfo
     */
    public function setDetailInfo($detailInfo)
    {
        $this->detailInfo = $detailInfo;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getWxno()
    {
        return $this->wxno;
    }

    /**
     * @param mixed $wxno
     */
    public function setWxno($wxno)
    {
        $this->wxno = $wxno;
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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getIdCard()
    {
        return $this->idCard;
    }

    /**
     * @param mixed $idCard
     */
    public function setIdCard($idCard)
    {
        $this->idCard = $idCard;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
    }

    /**
     * 获取模型数据数组，便于调用
     * @return array
     */
    public function getModelArray()
    {
        return [
            'contactname'=>$this->getContactName(),
            'uid'=>$this->getUid(),
            'country'=>$this->getCountry(),
            'province'=>$this->getProvince(),
            'detailinfo'=>$this->getDetailInfo(),
            'area'=>$this->getArea(),
            'mobile'=>$this->getMobile(),
            'notes'=>$this->getNotes(),
            'wxno'=>$this->getWxno(),
            'order_code'=>$this->getOrderCode(),
            'city'=>$this->getCity(),
            'id_card'=>$this->getIdCard(),
            'postal_code'=>$this->getPostalCode()
        ];
    }
}