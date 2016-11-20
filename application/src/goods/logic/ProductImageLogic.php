<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-20
 * Time: 9:29
 */

namespace app\src\goods\logic;


use app\src\base\logic\BaseLogic;
use app\src\goods\model\ProductImage;

class ProductImageLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new ProductImage());
    }
}