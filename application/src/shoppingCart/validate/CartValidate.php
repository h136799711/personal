<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 15:54
 */

namespace app\src\shoppingCart\validate;

use think\Validate;

/**
 * Class CartValidate
 * 购物车验证类
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\shoppingCart\validate
 */
class CartValidate extends Validate
{
    
    protected $rule = [
        'count'  =>  'require|number|gt:0',
        'uid'    => 'require',
        'id'    => 'require',
        'sku_pkid'    => 'require'
    ];

    protected $msg = [
        'count.require'=> "购买数量 必须",
        'count.egt'=> "购买数量 必须大于0",
        'count.number'=> "购买数量 必须为数字",
        'uid.require'=> "uid 必须",
        'id.require'=> "id 必须",
        'sku_pkid.require'=> "sku_pkid 必须"
    ];


}