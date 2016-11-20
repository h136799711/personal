<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-22
 * Time: 10:08
 */

namespace app\src\favorites\model;


use think\Model;

class Favorites extends Model
{
    /**
     * 置顶
     */
    const FAV_STICK = "1";
    /**
     * 未置顶
     */
    const FAV_UNSTICK = "0";

    /**
     * 商品类型商品
     */
    const FAV_TYPE_PRODUCT = "1";

    /**
     * 商品类型类目
     */
    const FAV_TYPE_CATEGORY = "3";

    /**
     * 检查类型参数是否合法
     * @author hebidu <email:346551990@qq.com>
     * @param $type
     * @return bool
     */
    public static function isLegalType($type){
        switch ($type){
            case Favorites::FAV_TYPE_PRODUCT:
                return true;
                break;
            case Favorites::FAV_TYPE_CATEGORY:
                return true;
                break;
            default:
                return false;
                break;
        }
    }
}