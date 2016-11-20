<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 15:07
 */

namespace app\src\shoppingCart\model;


use think\Model;

class ShoppingCart extends Model
{
    const  CART_STATUS_NORMAL = "1";
    const  CART_STATUS_SHELF_OFF = "2";
    const  CART_STATUS_INVALID = "3";
    const  CART_STATUS_INVENTORY_LACK = "4";
}