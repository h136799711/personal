<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-12
 * Time: 11:47
 */

namespace app\src\oauth2\logic;


use app\src\base\logic\BaseLogic;
use app\src\oauth2\model\OauthClients;

class OauthClientsLogic extends BaseLogic
{

    /**
     * @return mixed
     */
    protected function _init()
    {
        $this->setModel(new OauthClients());
    }

    

}