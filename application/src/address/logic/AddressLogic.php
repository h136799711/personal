<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-18
 * Time: 18:09
 */

namespace app\src\address\logic;


use app\src\address\model\Address;
use app\src\base\logic\BaseLogic;


/**
 * Class AddressLogic
 * @author hebidu <email:346551990@qq.com>
 * @package app\src\system\logic
 */
class AddressLogic extends BaseLogic
{
    /**
     * @return mixed
     */
    protected function _init()
    {
        $this->setModel(new Address());
    }

    
}