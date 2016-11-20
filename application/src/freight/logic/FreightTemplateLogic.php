<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-10
 * Time: 14:22
 */

namespace app\src\freight\logic;


use app\src\base\logic\BaseLogic;
use app\src\freight\model\FreightTemplate;

class FreightTemplateLogic extends BaseLogic
{
    public function _init()
    {
        $this->setModel(new FreightTemplate());
    }
}