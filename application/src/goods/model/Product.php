<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 9:26
 */

namespace app\src\goods\model;


use think\Model;

class Product extends Model
{
    /*
     * 上架
     */
    const SHELF_ON  = "1";

    /**
     * 下架
     */
    const SHELF_OFF = "0";
    
    /**
     * 商品正常
     * @author hebidu <email:346551990@qq.com>
     */
    const BUSINESS_STATUS_NORMAL = 0;

    /**
     * 商品已下架
     * @author hebidu <email:346551990@qq.com>
     */
    const BUSINESS_STATUS_SHELF_OFF = 2;

    /**
     * 商品已过期
     * @author hebidu <email:346551990@qq.com>
     */
    const BUSINESS_STATUS_EXPIRED = 4;


    /**
     * 商品没有库存，库存为0
     * @author hebidu <email:346551990@qq.com>
     */
    const BUSINESS_STATUS_OUTSOLD = 8;

    /**
     * 商品被删除
     * @author hebidu <email:346551990@qq.com>
     */
    const BUSINESS_STATUS_DELETE = 16;



}