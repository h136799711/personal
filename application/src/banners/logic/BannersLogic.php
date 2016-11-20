<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-04
 * Time: 9:40
 */

namespace app\src\banners\logic;


use app\src\banners\model\Banners;
use app\src\base\logic\BaseLogic;

class BannersLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new Banners());
    }
}